<?php

namespace app\extensions\helper;

use app\extensions\LibraryManager;

use lithium\template\View;

class LibraryHelper extends \lithium\template\Helper {
    protected $_view = null;

    protected function _init(){
        $this->_view = new View(array(
            'paths' => array(
                'template' => '{:library}/views/{:path}/{:template}.{:type}.php'
            )
        ));
        parent::_init();
    }

    /**
     * creates a bind point within the template which other libraries can bind to.
     * 
     * @param  $bindPoint
     * @param null $classes
     * @param string $innerContent
     * @param null $iterationData
     * @return string
     */
    public function modulePoint($bindPoint, $classes = null, $innerContent = '', &$iterationData = null){
        if(empty($classes)){
            $classes = $bindPoint;
        }
        if($methods = LibraryManager::getSection($bindPoint)){
            if($methods === true){
                return sprintf('<div class="%s">%s</div>',$classes,$innerContent);
            }else{
                $output = '<div class="'.$classes.'">';
                foreach($methods as $method){
                    list($class, $method) = explode('::', $method);
                    $renderOptions = $class::$method($innerContent, $iterationData);
                    
                    $defaults = array(
                        'library' => false,
                        'template' => false,
                        'data' => array(),
                        'callback' => false
                    );

                    if(is_array($renderOptions)){
                        $renderOptions = array_merge($defaults,$renderOptions);
                    }else{
                        $renderOptions = $defaults;
                    }

                    //render the template returned by array from the called function.
                    if($renderOptions['template']){
                        if(!$renderOptions['library']){
                            list($library,$rest) = explode('\\', $class);
                            $renderOptions['library'] = $library;
                        }

                        $data = $renderOptions['data'];
                        unset($renderOptions['data']);
                        $callback = $renderOptions['callback'];
                        unset($renderOptions['callback']);

                        $element = $this->_renderElement($renderOptions,$data);

                        /**
                         * perform a callback - useful for saving reusable items.
                         * This is a function that exists within the same class
                         * as the already invoked class, so callback should
                         * only be the function name.
                         */
                        if($callback){
                            $class::$callback($element, $innerContent);
                        }else{
                            $innerContent .= $element;
                        }
                    }
                }
                $output .= $innerContent . '</div>';

                return $output;
            }
        }else{
            return $innerContent;
        }
    }

    protected function _renderElement($options = null, $data = null){
        if(isset($options)){
            $defaults = array(
                'library' => null,
                'path' => 'elements',
                'template' => null,
                'type' => 'html'
            );
            $options = $options + $defaults;
            if(isset($options['library'], $options['template'])){
                return $this->_view->render('element', $data, $options);
            }else{
                return false;
            }
        }
    }
}

?>