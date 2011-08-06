<?php
/**
 * Places class file.
 * @author dai
 * @date 7/14/11
 * @time: 7:19 PM
 */

namespace app\controllers;

use MongoId;
use MongoRegex;
use app\models\Places;
use app\models\Dishes;
use app\models\Promos;
use app\models\Reviews;

use \lithium\data\collection\DocumentArray;
use app\extensions\Set;
use \li3_flash_message\extensions\storage\FlashMessage;

use \li3_facebook\extensions\FacebookProxy;
use \li3_facebook\extensions\helper\Facebook;

use lithium\security\Auth;
use lithium\storage\Session;
use \lithium\util\Inflector;


class PlacesController extends \lithium\action\Controller {

    public function index() {
        return $this->redirect('places/find');
    }
    
    public function find() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        //set first
        $keyword = ''; $location = ''; $keywordRegex='.'; $locationRegex = '.'; $category = '';
        //check for submitted search
        if (isset($this->request->query['keyword']) && $this->request->query['keyword']!='') {
            $keyword = $this->request->query['keyword'];
            $keywordRegex = '.*'.$keyword.'.*';
        }
        if (isset($this->request->query['location']) && $this->request->query['location']!='') {
            $location = $this->request->query['location'];
            $locationRegex = '.*'.$location.'.*';
        }
        if (isset($this->request->query['category'])){
            $category = $this->request->query['category'];
        }
        //make regex
        $keywordRegex = new MongoRegex('/'.$keyword.'/i');
        $locationRegex = new MongoRegex('/'.$location.'/i');
        //save for view
        $criteria = null;
        if($keyword!='' and $location!='')
            $criteria = array('keyword'=>$keyword, 'location'=>$location);
        $criteria['category'] = $category;
        //blend the criteria
        $conditions = array(
            '$or' => array(
                array(
                    'address.city' => $locationRegex,
                    'name' => $keywordRegex,
                ),
                array(
                    'address.street' => $locationRegex,
                    'name' => $keywordRegex,
                ),
                array(
                    'address.city' => $locationRegex,
                    'description' => $keywordRegex,
                ),
                array(
                    'address.street' => $locationRegex,
                    'description' => $keywordRegex,
                ),
            ),
        );
        if($category!='' && $category!='all'){
            $conditions['category'] = $category;
        }
        $sort = array(
            'rating.value' => 'DESC',
            'created' => 'DESC',
            'address.city' => 'ASC'
        );
        //        print '<pre>'.print_r($conditions, true).'</pre>';
        //find the venues
        $places = Places::find('all', array('conditions'=>$conditions, 'limit'=>10, 'order'=>$sort));
        $title = 'Places';
        return compact('places', 'criteria', 'title', 'web_user');
    }

    public function add() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        $place = Places::create();
		if ($this->request->data) {
            //upload the main picture if its exist
            if(isset($this->request->data['mainPicture'])){
                $ext = pathinfo($this->request->data['mainPicture']['name'], PATHINFO_EXTENSION);
                $filename = Inflector::slug($this->request->data['name']." places main").".".$ext;
                $destination = DAI_UPLOAD_PATH.basename($filename);
                //var_dump($destination, $ext);
                if(move_uploaded_file($this->request->data['mainPicture']['tmp_name'],$destination)) {
                    $this->request->data['mainPicture'] = $filename;
                } else {
                    unset($this->request->data['mainPicture']);
                }
            }
            //debug first
//            print '<pre>'.print_r($this->request->data, true).'</pre>';
//                die();
            //save it
            if ($place->save($this->request->data)) {
                $this->redirect(array('Places::view', 'id' => $place->_id));
            }
		}
		$this->_render['template'] = 'editPlus';
        $isNewRecord = true;
        $title = 'New Place';
        return compact('place','isNewRecord', 'title', 'web_user');
    }

    public function view() {
        //lets retrieve session user
        $web_user = Auth::check('web_user');
//        var_dump($web_user);
        //set time sort for sorting by created time descending
        $timesort = array(
            'created' => 'DESC'
        );
        //get place information
		$place = Places::first($this->request->id);
        $dishes = Dishes::find('all', array(
                        'conditions' => array(
                            'placeId' => ($place->_id)
                        )
                    ));
        $promos = Promos::find('all', array(
                        'conditions' => array(
                            'placeId' => ($place->_id)
                        ),
                        'order' => array('period.from' => 'DESC') + $timesort
                    ));
        $reviews = Reviews::find('all', array(
                        'conditions' => array(
                            'placeId' => ($place->_id)
                        ),
                        'order' => $timesort,
                    ));
        $title = @$place->name;
        return compact('place', 'dishes', 'promos', 'reviews', 'title', 'web_user');
    }

    public function addReview(){
        if(!Auth::check('web_user')){
            FlashMessage::write('You must sign in to write review.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        $review = Reviews::create();
        if ($this->request->data) {
//            print '<pre>'.print_r($this->request->data, true).'</pre>';
//            die();
            if ($review->save($this->request->data)) {
                //TODO : make some routine to handle rating
                if($this->request->data['rating'])
                    $updatedPlace = Places::updateRating($review->placeId, $this->request->data['rating']);
                //redirect back to the place
                $this->redirect(array('Places::view', 'id' => $review->placeId));
            }
		}
    }


    // TODO : Deprecated since i made promos controller
//    public function addPromo() {
        //lets retrieve session user
//        $web_user = Auth::check('web_user');
//        if(!$this->request->id)
//            return $this->redirect('places/index');
//        //get place information
//        $place = Places::first($this->request->id);
//        $promo = Promos::create();
//		if ($this->request->data) {
//            if($promo->save($this->request->data)) {
//                $this->redirect("places/viewPromo/$promo->_id");
//            }
//		}
//        $this->_render['template'] = 'promos/edit';
//        $title = 'Add new promo';
//		return compact('dish', 'place', 'web_user', 'title');
//    }

    public function selectPlace($next) {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        //set first
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
        if($keyword!='.' and $location!='.')
            $criteria = array('keyword'=>$keyword, 'location'=>$location);
        //blend the criteria
        $conditions = array(
            '$or' => array(
                array(
                    'address.city' => $locationRegex,
                    'name' => $keywordRegex,
                ),
                array(
                    'address.street' => $locationRegex,
                    'name' => $keywordRegex,
                ),
                array(
                    'address.city' => $locationRegex,
                    'description' => $keywordRegex,
                ),
                array(
                    'address.street' => $locationRegex,
                    'description' => $keywordRegex,
                ),

            )
        );
        $sort = array(
            'created' => 'DESC',
            'address.city' => 'ASC'
        );
        //find the places
		$places = Places::find('all', array('conditions'=>$conditions, 'order'=>$sort));
        $title = 'Select Place';
        $redirect = $next ?: 'dishes';
		return compact('places', 'criteria', 'title', 'web_user', 'redirect');
    }

    public function getAll(){
        $fields = array('_id', 'name', 'address');
        $places = Places::find('all', array('fields'=>$fields));
        $data = $places->to('array');
        foreach($data as $key => $item){
            if(!isset($item['address']['location']))
            unset($data[$key]);
        }
        //print '<pre>'.print_r($data, true).'</pre>';die();
        $this->render(array('json' => compact('data')));
    }

    public function tesAddLoc() {
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        return compact('web_user');
    }

    public function tesUploadImg() {
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            //return $this->redirect('users/login');
        }
        //TODO upload picture submitted by user
        if($this->request->data){
            $data = $this->request->data['image'];
            $this->set(compact('data'));
            $destination = DAI_UPLOAD_PATH.basename($data['name']);
            //move_uploaded_file($data['tmp_name'],$destination);
        }
        return compact('web_user');
    }

    public function tesDoUpload() {
        if($this->request->is('ajax'))
            $this->render(array('json' => array(
                          'success' => true,
                          'get' => @$this->request->query,
                          'post' => @$this->request->data
                      )));
        print '<pre>'.print_r(@$this->request->data, true).'</pre>';
        die();
    }

}
