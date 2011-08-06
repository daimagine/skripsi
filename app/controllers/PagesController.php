<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace app\controllers;

use MongoId;
use MongoRegex;
use app\models\Places;
use app\models\Dishes;
use app\models\Promos;
use app\models\Reviews;
use app\models\Reports;

use \li3_flash_message\extensions\storage\FlashMessage;
use lithium\security\Auth;

/**
 * This controller is used for serving static pages by name, which are located in the `/views/pages`
 * folder.
 *
 * A Lithium application's default routing provides for automatically routing and rendering
 * static pages using this controller. The default route (`/`) will render the `home` template, as
 * specified in the `view()` action.
 *
 * Additionally, any other static templates in `/views/pages` can be called by name in the URL. For
 * example, browsing to `/pages/about` will render `/views/pages/about.html.php`, if it exists.
 *
 * Templates can be nested within directories as well, which will automatically be accounted for.
 * For example, browsing to `/pages/about/company` will render
 * `/views/pages/about/company.html.php`.
 */
class PagesController extends \lithium\action\Controller {

	public function view() {
        $this->_render['layout'] = 'default-backup';
        $this->_render['template'] = 'defaulthome';
//		$path = func_get_args() ?: array('home');
//		return $this->render(array('template' => join('/', $path)));
	}

    public function home() {
        //get authenticated user
        $web_user = Auth::check('web_user');
        //init limit
        $limit = 3;
        //init rating condition
        $conditions = array(
//            'rating.value' => array('$gte' => 2.5)
        );
        //set time sort for sorting by created time descending
        $sortdefault = array(
            'rating.value' => 'DESC',
        );
        //get place information
		$places = Places::find('all', array(
                        'conditions' => $conditions,
                        'order' => $sortdefault,
                        'limit' => $limit
                    ));
        $dishes = Dishes::find('all', array(
                        'conditions' => $conditions,
                        'order' => $sortdefault,
                        'limit' => $limit
                    ));
        $promos = Promos::find('all', array(
                        'conditions' => $conditions,
                        'order' => $sortdefault + array('period.from' => 'DESC'),
                        'limit' => $limit
                    ));
        $reviews = Reviews::find('all', array(
                        'conditions' => $conditions,
                        'order' => array('created' => 'DESC'),
                        'limit' => $limit
                    ));
        return compact('places', 'dishes', 'promos', 'reviews', 'title', 'web_user');
    }

    public function index() {
        return $this->redirect('/');
    }

    public function report($type='report') {
        //$web_user = Auth::check('web_user');
        $success = false;
        $message = '';
        $report = Reports::create();
        if($this->request->data && $this->request->data['message']!=''){
            switch($type) {
                case 'contact' :
                    $message = 'Your message has been saved. Thank you for your feedback.';
                    break;
                case 'report' :
                    $message = 'Your report has been submitted. Administrator will check it up for you. Thanks for your cooperation.';
            }
            if($report->save($this->request->data)) {
                $success = true;
            }
            if(!$this->request->is('ajax')) {
                FlashMessage::write($message, array('type'=>($success)?'success':'fail'), 'web_user');
            }
        }
        if($this->request->is('ajax'))
            return $this->render(array('json'=>array(
                 'status'=>$success,
                 'message'=>$message,
                 'data'=>$this->request->data
             )));
        return $this->redirect('/');
    }

    public function contact() {
        $this->_render['layout'] = 'blank';
    }

    public function share() {
        $this->_render['layout'] = 'blank';
    }

    public function find() {
        //get authenticate user
        $web_user = Auth::check('web_user');

        //get criteria parameters then make new mongoRegex
        $keyword = ''; $location = ''; $keywordRegex='.'; $locationRegex = '.';
        //check for submitted search
        if (isset($this->request->query['keyword']) && $this->request->query['keyword']!='') {
            $keyword = $this->request->query['keyword'];
            $keywordRegex = '.*'.$keyword.'.*';
        }
        if (isset($this->request->query['location']) && $this->request->query['location']!='') {
            $location = $this->request->query['location'];
            $locationRegex = '.*'.$location.'.*';
        }
        //make regex
        $keywordRegex = new MongoRegex('/'.$keyword.'/i');
        $locationRegex = new MongoRegex('/'.$location.'/i');
        //save for view
        $criteria = null;
        if($keyword!='' and $location!='')
            $criteria = array('keyword'=>$keyword, 'location'=>$location);


        /**
          * 1. find chicken in depok
          * 2. find resto in depok
          * algo :
          * - find 2 criteria to fetch all places
          * - find chicken in depok : fetch all places in depok,
          *                           fetch those place's id,
          *                           then fetch dishes by criteria :
          *                                 'chicken' and placeId $in founded place's id
          */

        /**
         * First we find the place.
         */
            //create conditions for places
            $conditions = array(
                '$or' => array(
                    array( 'address.city' => $locationRegex, 'name' => $keywordRegex ),
                    array( 'address.street' => $locationRegex, 'name' => $keywordRegex ),
                    array( 'address.city' => $locationRegex, 'description' => $keywordRegex ),
                    array( 'address.street' => $locationRegex, 'description' => $keywordRegex ),
                ),
            );
            $sort = array(
                'rating.value' => 'DESC',
                'created' => 'DESC',
                'address.city' => 'ASC'
            );
            //        print '<pre>'.print_r($conditions, true).'</pre>';
            //find the venues
            $places = Places::find('all', array('conditions'=>$conditions,'order'=>$sort));

         if($places->count() > 0) {
             /**
              * Second step, fetch all founded place's id.
              */
             $tempIdFetch = array();
             foreach($places as $item){
                 $tempIdFetch[] = $item->_id;
             }
             $dishes = Dishes::find('all',array('conditions'=>array(
                            'placeId' => array(
                                '$in' => $tempIdFetch
                            )
                         )));
//             print '<pre>'.print_r($tempIdFetch, true).'</pre>';
//             print 'found dishes : '.$dishes->count();
//             print '<pre>'.print_r($dishes, true).'</pre>';
         } else {
             /**
              * Third step, found all dishes if keyword is not a place
              * (places finder have zero result)
              */
             $tempPlacesCondition = array(
                 '$or' => array(
                     array( 'address.city' => $locationRegex ),
                     array( 'address.street' => $locationRegex ),
                 )
             );
             $tempPlaces = Places::find('all', array('conditions'=>$tempPlacesCondition,
                                                    'fields'=>array('_id')));
             $tempIdFetch = array();
             foreach($tempPlaces as $item){
                 $tempIdFetch[] = $item->_id;
             }
             $dishConditions = array(
                 'address' => array(
                     '$in' => $tempIdFetch
                 )
             );
             $dishes = Dishes::find('all', array('conditions'=>$dishConditions));
             //print '<pre>'.print_r($tempIdFetch, true).'</pre>';
//             print 'found dishes : '.$dishes->count();
//             print '<pre>'.print_r($dishes, true).'</pre>';
         }

        /**
         * alright, everything goes well i assume. then lets merge all result, places and dishes
         * merge its data into array.
         * then i wanna make it random, so i will also randomize it
         */
        $packed = array_merge($places->data(), @$dishes->data());
        $keys = array_keys($packed);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key)
            $random[$key] = $packed[$key];
        $packed = $random;
//        print '<pre>'.print_r($packed, true).'</pre>';

        $title = 'General Finder';
        return compact('places', 'criteria', 'title', 'web_user', 'packed');
    }

}

?>

