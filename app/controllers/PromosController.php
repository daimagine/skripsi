<?php

namespace app\controllers;

use app\models\Places;
use app\models\Reviews;
use app\models\Promos;

use MongoId;
use MongoRegex;
use \li3_facebook\extensions\FacebookProxy;
use \li3_facebook\extensions\helper\Facebook;
use lithium\security\Auth;
use \li3_flash_message\extensions\storage\FlashMessage;

class PromosController extends \lithium\action\Controller {

	public function index() {
        return $this->redirect('promos/find');
    }

    public function find() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        //set first
        $keyword = ''; $keywordRegex='.';
        //check for submitted search
        if (isset($this->request->query['keyword']) && $this->request->query['keyword']!='') {
            $keyword = $this->request->query['keyword'];
            $keywordRegex = '.*'.$keyword.'.*';
        }
        //make regex
        $keywordRegex = new MongoRegex('/'.$keyword.'/i');
        //save for view
        $criteria = null;
        if($keyword!='')
            $criteria = array('keyword'=>$keyword);
        //blend the criteria
        $conditions = array(
            '$or' => array(
                array(
                    'title' => $keywordRegex,
                ),
                array(
                    'description' => $keywordRegex,
                ),
                array(
                    'info' => $keywordRegex,
                ),
            )
        );
        $sort = array(
            'period.from' => 'ASC',
            'created' => 'DESC',
        );
        //find the promos
		$promos = Promos::find('all', array('conditions'=>$conditions, 'limit'=>8, 'order'=>$sort));
        $title = 'Promos';
		return compact('promos', 'criteria', 'title', 'web_user');
    }

	public function view() {
        $web_user = Auth::check('web_user');
		$promo = Promos::first($this->request->id);
        $place = Places::find('first', array(
                        'conditions' => array(
                            '_id' => $promo->placeId
                        )
                 ));
        $reviews = Reviews::find('all', array(
                        'conditions' => array(
                            'promoId' => ($promo->_id)
                        )
                    ));
		return compact('promo', 'web_user', 'place', 'reviews');
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
            return $this->redirect('places/selectPlace/promos');
        $place = Places::first($this->request->id);
        $promo = Promos::create();
		if ($this->request->data) {
            if($promo->save($this->request->data)) {
                $this->redirect("promos/view/$promo->_id");
            }
		}
        $this->_render['template'] = 'edit';
        $title = 'Add new promo';
		return compact('promo', 'place', 'web_user', 'title');
	}

	public function edit() {
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in first.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        
		$promo = Promos::find($this->request->id);

		if (!$promo) {
			$this->redirect('Promos::index');
		}
		if (($this->request->data) && $promo->save($this->request->data)) {
			$this->redirect(array('Promos::view', 'args' => array($promo->_id)));
		}
        $placeId = $promo->placeId;
		return compact('promo', 'placeId');
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
			$msg = "Promos::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Promos::find($this->request->id)->delete();
		$this->redirect('/');
	}

    /**
     * @return void See all promos on specified place
     */
    public function place() {
        $web_user = Auth::check('web_user');
        if(!$this->request->id)
            return $this->redirect('promos/index');
        $place  = Places::find('first', array(
                    'conditions' => array(
                        '_id' => $this->request->id
                    )));
        $promos = Promos::find('all',array(
                    'conditions' => array(
                        'placeId' => new Mongoid($this->request->id)
                    ),
                    'sort' => array(
                        'created' => "DESC"
                    )));
        $this->_render['template'] = 'onPlace';
        return compact('web_user','place','promos');
    }

    public function addReview(){
        //get authenticate user
        $web_user = Auth::check('web_user');
        if($web_user==false){
            FlashMessage::write('You must sign in to write review.',
                array('type'=>'warn'), 'web_user');
            return $this->redirect('users/login');
        }
        $review = Reviews::create();
        if ($this->request->data) {
//            print '<pre>'.print_r($this->request->data, true).'</pre>';
//            die();
            if ($review->save($this->request->data)) {
                if($this->request->data['rating'])
                    $updatedPlace = Promos::updateRating($review->promoId, $this->request->data['rating']);
                    //also update rating to places
                    Places::updateRating($updatedPlace->placeId, $this->request->data['rating']);
                $this->redirect(array('Promos::view', 'id' => $review->promoId));
            }
		}
    }
    
}

?>