<?php
/**
 * Reports class file.
 * @author dai
 * @date 7/31/11
 * @time: 12:19 AM
 */

namespace app\models;

use MongoDate;
use MongoId;

class Reports extends \lithium\data\Model {

    public static $REPORT_TYPE = array(
        'error' => 'Fatal Error',
        'bug' => 'Bug Report',
        'claim' => 'Claim on Place',
        'abuse' => 'Report abuse',
        'report' => 'General Report',
        'userReport' => 'Report user',
        'contact' => 'Contact from User'
    );

    protected $_meta = array('source' => 'reports');

    public function save($entity, $data = null, array $options = array()) {

        $entity->set($data);

        if(isset($message)){
            if(strlen($entity->message) > 256)
                $entity->message = substr($entity->message, 0, 256);
        }

        if(!$entity->_id) {
            $entity->created = new MongoDate();
        }
        $entity->updated = new MongoDate();

		return parent::save($entity, null, $options);
    }

}

