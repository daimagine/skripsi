<?php
/**
 * Venues class file.
 * @author dai
 * @date 6/14/11
 * @time: 3:22 PM
 */

namespace app\controllers;

use MongoId;
use MongoRegex;
use app\models\Venue;
use app\models\Dishes;

use \lithium\data\collection\DocumentArray;
use app\extensions\Set;

use \li3_facebook\extensions\FacebookProxy;
use \li3_facebook\extensions\helper\Facebook;

use lithium\security\Auth;
use lithium\storage\Session;
use \li3_flash_message\extensions\storage\FlashMessage;

class VenuesController extends \lithium\action\Controller {

    public function index($tags = null) {
		$conditions = $tags ? compact('tags') : array();
		$venues = Venue::all(compact('conditions'));
        $title = 'List Venues';
        $subHeader = 'List Venues';
		return compact('venues', 'title', 'subHeader');
//		return compact('venues');
	}

	public function view() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
//        var_dump($web_user);
        
		$venue = Venue::first($this->request->id);
        $dishes = Dishes::find('all', array(
                        'conditions' => array(
                            'venueId' => ($venue->_id)
                        )
                    ));
        $title = 'View Venue';
        return compact('web_user', 'venue', 'dishes', 'title');
//		return compact('venue');
	}

	public function near($place = null) {
		$this->_render['template'] = 'index';
		$coords = Geocoder::find('google', $place);

		$venues = Venue::within(array($coords, $coords), array('limit' => 1));
		return compact('venues');
	}

	public function add() {
		$venue = Venue::create();
		if ($this->request->data) {
            if ($venue->save($this->request->data)) {
                $this->redirect(array('Venues::view', 'id' => $venue->_id));
            }
		}
		$this->_render['template'] = 'edit';
        $isNewRecord = true;
        $title = 'Create Venue';
        $subHeader = 'Create New Venue';
        return compact('venue','isNewRecord', 'title', 'subHeader');
//		return compact('venue');
	}

	public function edit() {
		$venue = Venue::find($this->request->id);
        if($venue->checkAttrib('tags'))
            $venue->tags = implode(",", DocumentArray::toArray($venue->tags));

		if (!$venue) {
			$this->redirect('Venues::index');
		}
		if (($this->request->data) && $venue->save($this->request->data)) {
			$this->redirect(array('Venues::view', 'id' => $venue->_id));
		}
        $title = 'Update Venue';
        $subHeader = 'Update Venue';
        return compact('venue', 'title', 'subHeader');
//		return compact('venue');
	}

    /************************** dish management functions ***************************/

    /**
     * $venue = Venue::create();

		if (($this->request->data) && $venue->save($this->request->data)) {
		    $this->redirect(array('Venues::view', 'id' => $venue->_id));
		}
		$this->_render['template'] = 'edit';
        $isNewRecord = true;
        $title = 'Create Venue';
        $subHeader = 'Create New Venue';
        return compact('venue','isNewRecord', 'title', 'subHeader');
     */
    public function addDish(){
        //get the venue
        $venueId = $this->request->id;
        $venue = Venue::first($venueId);
        //convert tags
        if(!empty($venue->tags))
            $venue->tags = implode(",", DocumentArray::toArray($venue->tags));
        //create new dish object

        //check for request data if user has been submitted the form
        //then try to add dish and update the venue
        if(($this->request->data && $this->request->data['name'])) {
            //here in lithium, we dont create a model for an embedded document i think, awesome
            $data = &$this->request->data;
            $result = Venue::addDish($venueId, $data);
            //almost forgot dude,we use atomic as false. If we do not do this,
            //Mongo will treat our query as a raw update;
            //that $push command will become a key added to the document
            //rather than the command telling Mongo how to deal with the rest of the array.
            //
            //so, lets build a handle to see whether the result fail or not
            if($result) {
                //check if this dish is a favorite ones from venue's menu
                if($data['isFavoriteDish'])
                    Venue::addFavoriteDish($venueId, $data['name']);
                //redirect back
                return $this->redirect(array('Venues::view', 'id' => $venueId));
            }else{
                //TODO: set some flash message
                //debug dude,
                print '<pre>'.print_r($venue, true).'</pre>';
            }
        }
        $title = 'Add Product';
        $subHeader = 'Add new dish to '.$venue->name;
        $this->_render['template'] = 'dishes/add';
        return compact('venue', 'title', 'subHeader');
    }

    public function writeReview() {
        $result = false;
        if(!$this->request->id)
            return $result;
        $venueId = $this->request->id;
        $data = $this->request->data;
        $result = Venue::addReview($venueId, $data);
         if($this->request->is('ajax'))
            $this->render(array('json' => (object) compact('result')));

        return $this->redirect("venues/view/$venueId");
    }

}
