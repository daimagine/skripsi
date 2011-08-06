<?php
/**
 * DaiBase class file.
 * @author dai
 * @date 6/14/11
 * @time: 10:04 PM
 */

namespace app\models;

use MongoId;

class DaiBase extends \li3_behaviors\extensions\Model {
     /**
     * Set up default connection options and connect default finders.
     *
     * Parent override which registers:
     *
     * <ul>
     *     <li>a find filter for coverting a string id to a MongoId instance</li>
     *     <li>a 'read' finder, which enables <code>Model::read($id)</code></li>
     * </ul>
     *
     * @see lithium\data\Model
     * @param array $config
     * @return void
     */
//    public static function __init($config = array())
//    {
//        parent::__init($config);
//
//        // filter for converting a string id into a MongoId instance
//        static::applyFilter('find', function($self, $params, $chain){
//
//            $conditions = $params['options']['conditions'];
//
//            if (isset($conditions['id']) and preg_match('/^[0-9a-f]{24}$/', $conditions['id']))
//            {
//                $params['options']['conditions']['_id'] = new MongoId($conditions['id']);
//
//                unset($params['options']['conditions']['id']);
//            }
//
//            return $chain->next($self, $params, $chain);
//        });
//
//        // read finder
//        static::finder('read', function($self, $params, $chain) {
//
//            $conditions = $params['options']['conditions'];
//
//            if (isset($conditions['_id']))
//            {
//                return $self::find('first', array('conditions' => array('id' => $conditions['_id'])));
//            }
//
//            return $chain->next($self, $params, $chain);
//        });
//    }

    public static function findByPk($id) {
        return static::find('first', array('conditions' => array('_id' => new MongoId($id))));
    }

}


