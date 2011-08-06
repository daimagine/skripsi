<?php

namespace app\models;

use MongoDate;
use MongoId;
use app\extensions\Set;
use \lithium\util\Inflector;

class Dishes extends \lithium\data\Model {

	protected $_meta = array('source' => 'dishes');

    /**
     * @var array of validation rules
     * used to define attribute rules. for example 'title' => array('notEmpty', 'message' => 'You need a title'),
     * @see lithium\data\Model
     */
    public $validates = array(
        'name' => array('notEmpty', 'message' => 'Please insert dish name'),
    );

    public function save($entity, $data = null, array $options = array()) {
        //handle boolean attribute
        $data['nonhalal'] = ($data['nonhalal'])?true:false;
        $data['isFavoriteDish'] = ($data['isFavoriteDish'])?true:false;

        if ($data) {
			$entity->set($data);
		}

        $entity->id = Inflector::slug($entity->name, '-');
        //handle venueId, check if its already an instance of MongoId
        $entity->venueId = ($entity->venueId instanceof MongoId)?: new MongoId($entity->venueId);

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

}

?>