<?php

namespace app\extensions;

use lithium\template\View;

class LibraryManager{
    protected static $_libraries = array();
    protected static $_data = array();
    protected static $_filters = array();

    /**
     * registers a namespaced method with a bindpoint
     *
     * $options is formatted as such:
     *
     * $options = array('bindPoint' => 'myLibrary\extensions\Foo::bar');
     * 
     * @static
     * @param null $options formatted options array
     * @return bool success
     */
    static function register($options = null){
        if(is_null($options) || !is_array($options)){
            return false;
        }

        foreach($options as $bindPoint => $bindMethod){
            list($type, $section, $subSection) = explode('_', $bindPoint);
            if(isset($type,$section,$subSection)){
                self::$_libraries[$type][$section][$subSection][] = $bindMethod;
            }
        }

        return true;
    }
    
    /**
     * While it's ideal to remove entries for old plugins, for ease of 
     * plug & play this override function exists to override already 
     * loaded library registration points. This goes without mentioning
     * that you should have your library load after the one you're trying
     * to override.
     * 
     * $options structure = array('bindPoint' => array(
     *       'overrideNamespacedMethod',
     *       'myNewMethod'
     * ));
     * 
     * where 'bindPoint' = the point where the method binds
     * 'overrideNamespacedMethod' = the method you're overriding
     * 'myNewMethod' = the namespace to your method
     * 
     * if the method you're trying to override doesn't exist this call
     * is nothing but an alias to register();
     * 
     * @static
     * @param  $options formatted options array
     * @return bool success
     */
    static function override($options){
        if(is_null($options) || !is_array($options)){
            return false;
        }

        foreach($options as $bindPoint => $bindMethods){
            list($type, $section, $subSection) = explode('_', $bindPoint);

            $override = $bindMethods[0];
            $newMethod = $bindMethods[1];

            $index = array_search($override,self::$_libraries[$type][$section][$subSection]);
            if($index === false){
                self::$_libraries[$type][$section][$subSection][] = $newMethod;
            }else{
                self::$_libraries[$type][$section][$subSection][$index] = $newMethod;
            }
        }
        return true;
    }

    static function getSection($bindPoint){
        if(is_null($bindPoint)){
            return false;
        }

        if(substr_count($bindPoint,'_') == 2){
            list($type,$section,$subSection) = explode('_', $bindPoint);
        }else{
            list($type,$section) = explode('_', $bindPoint);
        }
        if(!isset($subSection)){
            return isset(self::$_libraries[$type][$section]) ?: false;
        }else if(isset(self::$_libraries[$type][$section][$subSection])){
            return self::$_libraries[$type][$section][$subSection];
        }
        return false;
    }

    static function setData(&$data = null){
        if(is_null($data) || !is_array($data)){
            return false;
        }

        self::$_data = $data + self::$_data;

    }

    static function getData($key){
        if(!isset(self::$_data[$key])){
            return false;
        }
        return self::$_data[$key];
    }

    /**
     * Lazily applies filters set through libraries into controllers
     * Usage in a class: LibraryManager::applyControllerFilters($this, __CLASS__);
     *
     * usually run from _init();
     *
     * @static
     * @param  $controller
     * @param  $class
     * @return bool
     */
    static function applyControllerFilters(&$controller, $class){
        if(isset(self::$_filters[$class])){
            foreach(self::$_filters[$class] as $action => $actionFilters){
                foreach($actionFilters as $actionFilter){
                    $controller->applyFilter($action,$actionFilter);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Sets filters to be lazily applies to controllers actions if it comes up
     *
     * @static
     * @param $controller must be the namespaced controller name app/controllers/FooController
     * @param $action action name
     * @param $callback closure to be applied
     * @return void
     */
    static function controllerFilter($controller, $action, $callback){
        self::$_filters[$controller][$action][] = $callback;
    }
}

?>