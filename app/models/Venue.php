<?php
/**
 * Venue class file.
 * @author dai
 * @date 6/14/11
 * @time: 2:43 PM
 */

namespace app\models;

use MongoDate;
use MongoId;
use app\extensions\Set;
use \lithium\util\Inflector;

class Venue extends \lithium\data\Model {

    protected $_meta = array('source' => 'venues');

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
        'name' => array('notEmpty', 'message' => 'Please insert venue name'),
    );


    public function save($entity, $data = null, array $options = array()) {

        //set for pricing average
        $data['pricing']  = ($data['lowestPrice'])?:'n/a';
        $data['pricing'] .= "-";
        $data['pricing'] .= ($data['highestPrice'])?:'n/a';
        unset($data['lowestPrice']);
        unset($data['highestPrice']);

		if ($data) {
			$entity->set($data);
		}

		if (!$entity->exists() && isset($entity->file->tmp_name)) {
			$entity->location = Geocoder::exifCoords(exif_read_data($entity->file->tmp_name));
		}

        //handle the tags
        if($entity->tags == '') {
             $entity->tags = array();
        } elseif ($entity->tags && !is_array($entity->tags)) {
			$entity->tags = array_map('trim', explode(',', $entity->tags));
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

    public static function addDish($venueId, $data){
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
            '_id' => $venueId,
            'dishes.id' => array(
                '$ne' => $dishGenId
            )
        );
        //finally, execute the query
        $result = Venue::update($query, $conditions, array('atomic'=>false));
        return $result;
    }

    public static function addFavoriteDish($venueId, $dishId) {
        $dishId = Inflector::slug($dishId, '-');
        $query = array(
            '$addToSet' => array(
                'favoriteDishes' => $dishId,
            )
        );
        $conditions = array('_id' => $venueId);
        $result = Venue::update($query, $conditions, array('atomic'=>false));
        return $result;
    }

    public function isFavoriteDish($record, $dishId) {
        $favoriteDish = $record->favoriteDishes;
        if(array_search($dishId, $favoriteDish)){
            return true;
        }
        return false;
    }

    public static function addReview($venueId, $data){
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