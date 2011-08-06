<?php

namespace app\controllers;

use app\models\Dishes;
use app\models\Places;
use app\models\Reviews;

use MongoId;
use MongoRegex;
use \li3_facebook\extensions\FacebookProxy;
use \li3_facebook\extensions\helper\Facebook;
use lithium\security\Auth;
use \li3_flash_message\extensions\storage\FlashMessage;

class DishesController extends \lithium\action\Controller {

	public function index() {
        return $this->redirect('dishes/find');
    }

    public function find() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        //set first
        $keyword = ''; $keywordRegex='.'; $category = '';
        //check for submitted search
        if (isset($this->request->query['keyword']) && $this->request->query['keyword']!='') {
            $keyword = $this->request->query['keyword'];
            $keywordRegex = '.*'.$keyword.'.*';
        }
        if (isset($this->request->query['category'])){
            $category = $this->request->query['category'];
        }
        //make regex
        $keywordRegex = new MongoRegex('/'.$keyword.'/i');
        //save for view
        $criteria = null;
        if($keyword!='')
            $criteria = array('keyword'=>$keyword);
        $criteria['category'] = $category;
        //blend the criteria
        $conditions = array(
            '$or' => array(
                array(
                    'name' => $keywordRegex,
                ),
                array(
                    'description' => $keywordRegex,
                ),
            )
        );
        $sort = array(
            'rating.value' => 'DESC',
            'name' => 'ASC',
            'created' => 'DESC',
        );
        if($category!='' && $category!='all'){
            if($category != '1' && $category != '6' ) {
                switch($category) {
                    case '2' : $conditions['price'] = array( '$gt' => 5000, '$lte' => 10000); break;
                    case '3' : $conditions['price'] = array( '$gt' => 10000, '$lte' => 30000); break;
                    case '4' : $conditions['price'] = array( '$gt' => 30000, '$lte' => 50000); break;
                    case '5' : $conditions['price'] = array( '$gt' => 50000, '$lte' => 100000); break;
                }
            } else {
                if($category == '1') {
                    $conditions['price'] = array( '$lt' => 5000);
                } else {
                    $conditions['price'] = array( '$gte' => 100000);
                }
            }
            //$conditions['price'] = $pricecriteria;
            $sort = array('price'=>'ASC') + $sort;
        }
        //find the dishes
		$dishes = Dishes::find('all', array('conditions'=>$conditions, 'limit'=>8, 'order'=>$sort));
        $title = 'Dishes';
		return compact('dishes', 'criteria', 'title', 'web_user');
    }

	public function view() {
        $web_user = Auth::check('web_user');
		$dish = Dishes::first($this->request->id);
        $place = Places::find('first', array(
                        'conditions' => array(
                            '_id' => $dish->placeId
                        )
                 ));
        $reviews = Reviews::find('all', array(
                        'conditions' => array(
                            'dishId' => ($dish->_id)
                        )
                    ));
		return compact('dish', 'web_user', 'place', 'reviews');
	}

	public function add() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        $placeId = $this->request->id;
        if(!$placeId)
            return $this->redirect('places/selectPlace/dishes');
        $place = Places::first($this->request->id);
        $dish = Dishes::create();
		if ($this->request->data) {
		    //debug
		    //print '<pre>'.print_r($this->request->data,true).'</pre>';die();
            if($dish->save($this->request->data)) {
                $this->redirect("dishes/view/$dish->_id");
            }
		}
        $this->_render['template'] = 'edit';
        $title = 'Add new dish';
		return compact('dish', 'place', 'web_user', 'title');
	}

	public function edit() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }

		$dish = Dishes::find($this->request->id);

		if (!$dish) {
			$this->redirect('Dishes::index');
		}
		if (($this->request->data) && $dish->save($this->request->data)) {
			$this->redirect(array('Dishes::view', 'args' => array($dish->_id)));
		}
        $placeId = $dish->placeId;
		return compact('dish', 'placeId');
	}

	public function delete() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }

		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Dishes::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Dishes::find($this->request->id)->delete();
		$this->redirect('/');
	}

    public function addReview() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign to write review.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        $review = Reviews::create();
        if ($this->request->data) {
            if ($review->save($this->request->data)) {
                if($this->request->data['rating'])
                    $updatedDish = Dishes::updateRating($review->dishId, $this->request->data['rating']);
                    //also update rating to places
                    Places::updateRating($updatedDish->placeId, $this->request->data['rating']);
                $this->redirect(array('Dishes::view', 'id' => $review->dishId));
            }
		}
    }

    /**
     * @return void See all dishes on specified place
     */
    public function place() {
        $web_user = Auth::check('web_user');
        if(!$this->request->id)
            return $this->redirect('dishes/index');
        $place  = Places::find('first', array(
                    'conditions' => array(
                        '_id' => $this->request->id
                    )));
        $dishes = Dishes::find('all',array(
                    'conditions' => array(
                        'placeId' => new Mongoid($this->request->id)
                    ),
                    'sort' => array(
                        'created' => "DESC"
                    )));
        $this->_render['template'] = 'onPlace';
        return compact('web_user','place','dishes');
    }

}

?>

