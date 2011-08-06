<?php
/**
 * Places class file.
 * @author dai
 * @date 6/14/11
 * @time: 2:43 PM
 */

namespace app\models;

use MongoDate;
use MongoId;
use app\extensions\Set;
use \lithium\util\Inflector;
use \lithium\data\entity\Document;
use \lithium\data\collection\DocumentArray;

class Places extends \lithium\data\Model {

    protected $_meta = array('source' => 'places');

    public static $categories = array(
        'restaurant'=>'Restaurant',
        'cafe'=>'Cafe',
        'catering'=>'Catering',
        'bakery&dessert'=>'Bakery & Dessert',
        'coffee&tea shop'=>'Coffee & Tea shop',
        'deli'=>'Deli',
        'delivery'=>'Delivery',
        'fast food'=>'Fast food',
        'kiosk&stall'=>'Kiosk & Stall',
        'lounge'=>'Lounge',
        'pub&bar'=>'Pub & Bar',
        'other'=>'Other'
    );

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
        'name' => array('notEmpty', 'message' => 'Please insert place name'),
    );


    public function save($entity, $data = null, array $options = array()) {

		if ($data) {
            //TODO: use foreach to unset some empty data
			$entity->set($data);
		}

        //handle some field needed to become MongoId
        $entity->contributor->id = ($entity->contributor->id instanceof MongoId)?
                $entity->contributor->id : new MongoId($entity->contributor->id);

        //handle the tags
        if($entity->tags == '') {
             $entity->tags = array();
        } elseif ($entity->tags && !is_array($entity->tags) && ($entity->tags instanceof String)) {
			$entity->tags = array_map('trim', explode(',', $entity->tags));
		}

        $countExist = Places::count(array('name'=>$entity->name));
        if($countExist>0) {
            $entity->id = Inflector::slug($entity->name." ".$countExist);
        }else{
            $entity->id = Inflector::slug($entity->name);
        }

        //convert latitude andd longitude to float explicitly
        if(isset($entity->address->location)) {
            $entity->address->location->lat = (float) $entity->address->location->lat;
            $entity->address->location->lng = (float) $entity->address->location->lng;
        }

        if(!$entity->_id) {
            $entity->created = new MongoDate();
        }
        $entity->updated = new MongoDate();

		return parent::save($entity, null, $options);
	}

    public function checkAttrib($record, $attr = null){
//        var_dump($attr);
        if($attr == null)
            return false;
//        if(isset($this->$attr) && !is_string($this->$attr) && ($this->$attr==''))
        if(Set::arrayKeysSet($record, array($attr),true))
            return true;
        return false;
    }

    // TODO : Deprecated method. Dish is no longer in embedded document
    public static function addDish($placeId, $data){
        //generate id for dish
        $dishGenId = Inflector::slug($data['name'], '-');
        //just a test to use object than a simple query
        //we just setup a query
        $query = array(
            //make sure using (') single aphostrope instead of (") double ones,
            //so PHP will not notice $push as a variable
            '$push' => array(
                //then push newly dish as embedded document
                'dishes' => array(
                    'id' => $dishGenId,
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'nonhalal' => ($data['halal'])?true:false,
                )
                //test with object
//                    'dishes' => $newProd,
            ),
            //nice thing about mongo is KISS, Keep it Simple, Stupid, :p
            //use this atomic to increment dishesCount
            '$inc' => array('dishesCount' => 1),
        );
        //if there is tag
        if($data['tags'] !== '') {
            //convert tags to array
            $query['$push']['dishes']['tags'] = array_map('trim', explode(',', $data['tags']));
        }else{
            $query['$push']['dishes']['tags'] = array();
        }
        //and then build the conditions
        $conditions = array(
            '_id' => $placeId,
            'dishes.id' => array(
                '$ne' => $dishGenId
            )
        );
        //finally, execute the query
        $result = Places::update($query, $conditions, array('atomic'=>false));
        return $result;
    }

    public static function addFavoriteDish($placeId, $dishId) {
//        $dishId = Inflector::slug($dishId, '-');
        $query = array(
            '$addToSet' => array(
                'favoriteDishes' => $dishId,
            )
        );
        $conditions = array('_id' => $placeId);
        $result = Places::update($query, $conditions, array('atomic'=>false));
        return $result;
    }

    public function isFavoriteDish($record, $dishId) {
        $favoriteDish = $record->favoriteDishes;
        if(array_search($dishId, $favoriteDish)){
            return true;
        }
        return false;
    }

    // TODO : Deprecated method
    public static function addReview($placeId, $data){
        $query = array(
            '$push' => array(
                'reviews' => array(
                    'reviewer' => $data['user_id'],

                )
            ),
            '$inc' => array(

            ),
        );
    }

    /**
     * @static
     * @param $id
     * @param array $data 'rating' is review rate data passed by contains array of some rating
     * @return updated Place
     *
     * algorithm for rating :
     * #step one : Single ReviewRate by user
     * - take all rates submitted by user.
     * - count the rates and sum it, then get average point
     * - till this phase, assume we had single review's point average rate,
     * - lets call it ReviewRate.
     *   ** ReviewRate in other hand, is the Point.
     * #step two : Fetch existing Rating [in embedded doc]
     * - fetch object existing rating embedded document,
     * - if exist go to #step three-one, if its not go to #step three-two
     * #step three : Calculate the Rating
     *   ##sub one : Merging with existing
     *     - get rating fetched before
     *     - summarizing total points with ReviewRate
     *     - set points value with the new value
     *     - then call sub three-one
     *   ##sub two : Calculate the rating
     *      - set total points if its pass by parameter
     *      - formula for rating value = (total points) / (total voters)
     *        ** first need only to make simple formula
     * #step Four : save new rating [in embedded doc]
     *  - finally, save the new rating in embedded doc with schema
     *       rating {
     *           value  : object main rating value
     *           points : total point from summarizing all ReviewRate form users
     *           voters : total user who gives their rate for the object
     *       }
     *
     */
    public static function updateRating($id, $data = array()) {
        if (empty($data))
            return false;
        //init phase
//        $rating = new DocumentSet();
        $rating = new Document();
        $points = 0;
        $voters = 1;
        //step one
        $counter = 0;
        foreach($data as $key => $value) {
            //$key is rating category
            $points += $value;
            $counter++;
        }
        $points = (float) $points / $counter;
        $newPoints = $points;
        //step two
        $place = Places::find('first', array(
                     'conditions' => array(
                         '_id' => new MongoId($id)
                     )
                   ));
        //step three
        //sub-one
        if(isset($place->rating)) {
            $oldrating = $place->rating;
            $points += $oldrating->points;
            $voters = $oldrating->voters + 1;
        }
        //sub-two
        $rating->points = $points;
        $rating->voters = $voters;
        $rating->value  = round(($points / $voters), 2);
        //step four
        $place->rating = $rating;
        $place->save();
        //debugging dude.
//        print '<pre>'.
//                print_r($data, true).
//                print_r($newPoints, true).
//                print_r($oldrating, true).
//                print_r($rating, true)
//              .'</pre>';
//        die();
        return $place;
    }

}

/*
 * public function set($data = array()){
		if (isset($data) && count($data) > 1) {
			if (!isset($data['created']) && !isset($data['id']) && !isset($this->id)) {
				$data['created'] = new \DateTime(date('Y-m-d H:i:s'));
			}

			if (!isset($data['modified'])) {
				$data['modified'] = new \DateTime(date('Y-m-d H:i:s'));
			}

			foreach($data as $key => $value) {
				$this->$key = $value;
			}
		}
		return $this;
	}
 */