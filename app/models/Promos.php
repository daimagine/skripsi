<?php
/**
 * Promos class file.
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

class Promos extends \lithium\data\Model {
    protected $_meta = array('source' => 'promos');

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
        'title' => array('notEmpty', 'message' => 'Please insert promo title'),
    );

    public function save($entity, $data = null, array $options = array()) {
        //handle boolean attribute
//        $data['nonhalal'] = ($data['nonhalal'])?true:false;
//        $data['isFavoriteDish'] = ($data['isFavoriteDish'])?true:false;

        if ($data) {
			$entity->set($data);
		}

        $countExist = Promos::count(array('name'=>$entity->title));
        if($countExist>0) {
            $entity->id = Inflector::slug($entity->title." ".$countExist);
        }else{
            $entity->id = Inflector::slug($entity->title);
        }

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
        $promos = Promos::find('first', array(
                     'conditions' => array(
                         '_id' => new MongoId($id)
                     )
                   ));
        //step three
        //sub-one
        if(isset($promos->rating)) {
            $oldrating = $promos->rating;
            $points += $oldrating->points;
            $voters = $oldrating->voters + 1;
        }
        //sub-two
        $rating->points = $points;
        $rating->voters = $voters;
        $rating->value  = round(($points / $voters), 2);
        //step four
        $promos->rating = $rating;
        $promos->save();
        //debugging dude.
//        print '<pre>'.
//                print_r($data, true).
//                print_r($newPoints, true).
//                print_r($oldrating, true).
//                print_r($rating, true)
//              .'</pre>';
//        die();
        return $promos;
    }
     
}
