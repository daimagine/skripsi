<?php
/**
 * Dishes class file.
 * @author dai
 * @date 7/14/11
 * @time: 7:37 PM
 */

namespace app\models;

use MongoDate;
use MongoId;
use app\extensions\Set;
use \lithium\util\Inflector;
use \lithium\data\entity\Document;

class Dishes extends \lithium\data\Model {
    protected $_meta = array('source' => 'dishes');

    public static $categories = array(
        'prices' => array(
            '1' => '< 5.000',
            '2' => '5.000 - 10.000',
            '3' => '10.000 - 30.000',
            '4' => '30.000 - 50.000',
            '5' => '50.000 - 100.000',
            '6' => '> 100.000',
        )
    );

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
        'name' => array('notEmpty', 'message' => 'Please insert dish name'),
    );

    public function save($entity, $data = null, array $options = array()) {
        
        if ($data) {
			$entity->set($data);
		}

        $countExist = Dishes::count(array('name'=>$entity->name));
        if($countExist>0) {
            $entity->id = Inflector::slug($entity->name." ".$countExist);
        }else{
            $entity->id = Inflector::slug($entity->name);
        }

        if(isset($entity->price))
            $entity->price = (float) $entity->price;

        //handle placeId, check if its already an instance of MongoId
        $entity->placeId = ($entity->placeId instanceof MongoId)? $entity->placeId : new MongoId($entity->placeId);

        //handle the tags
        if($entity->tags == '') {
             $entity->tags = array();
        } elseif ($entity->tags && !is_array($entity->tags) && ($entity->tags instanceof String)) {
			$entity->tags = array_map('trim', explode(',', $entity->tags));
		}

        if(!$entity->_id) {
            $entity->created = new MongoDate();
            //handle boolean attribute
            $data['nonhalal'] = ($data['nonhalal'])?true:false;
            $data['isFavoriteDish'] = ($data['isFavoriteDish'])?true:false;
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

    public function getLogo($record) {
        return ($record->logo)? $record->logo : 'dish';
    }

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
        $dish = Dishes::find('first', array(
                     'conditions' => array(
                         '_id' => new MongoId($id)
                     )
                   ));
        //step three
        //sub-one
        if(isset($dish->rating)) {
            $oldrating = $promos->rating;
            $points += $oldrating->points;
            $voters = $oldrating->voters + 1;
        }
        //sub-two
        $rating->points = $points;
        $rating->voters = $voters;
        $rating->value  = round(($points / $voters), 2);
        //step four
        $dish->rating = $rating;
        $dish->save();
        //debugging dude.
//        print '<pre>'.
//                print_r($data, true).
//                print_r($newPoints, true).
//                print_r($oldrating, true).
//                print_r($rating, true)
//              .'</pre>';
//        die();
        return $dish;
    }

}
