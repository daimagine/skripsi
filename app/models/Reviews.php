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

class Reviews extends \lithium\data\Model {
    protected $_meta = array('source' => 'reviews');

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
//        'title' => array('notEmpty', 'message' => 'Please insert review title'),
    );

    public function save($entity, $data = null, array $options = array()) {
        //handle boolean attribute
//        $data['nonhalal'] = ($data['nonhalal'])?true:false;
//        $data['isFavoriteDish'] = ($data['isFavoriteDish'])?true:false;

        if ($data) {
			$entity->set($data);
		}

        //handle some, check if its already an instance of MongoId
        if(isset($entity->userId)) {
            $entity->userId = ($entity->userId instanceof MongoId)? $entity->userId : new MongoId($entity->userId);
        }

        if(isset($entity->placeId)) {
            $entity->placeId = ($entity->placeId instanceof MongoId)? $entity->placeId : new MongoId($entity->placeId);
        }

        if(isset($entity->dishId)) {
            $entity->dishId = ($entity->dishId instanceof MongoId)? $entity->dishId : new MongoId($entity->dishId);
        }

        if(isset($entity->promoId)) {
            $entity->promoId = ($entity->promoId instanceof MongoId)? $entity->promoId : new MongoId($entity->promoId);
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
}
