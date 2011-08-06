<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use lithium\core\ErrorHandler;
use lithium\action\Response;
use lithium\net\http\Media;
use app\models\Reports;

ErrorHandler::apply('lithium\action\Dispatcher::run', array(), function($info, $params) {
    try{
    //print '<pre>'.print_r($info['exception'],true).'</pre>';
        if(Reports::count(array('conditions'=>array('message'=>json_encode($info['exception']->getMessage()))))==0) {
	        $report = Reports::create();
	        $dataReport['type'] = 'error';
            $exc['file'] = json_encode($info['exception']->getFile());
	        $exc['code'] = $info['exception']->getCode();
	        $dataReport['exception'] = $exc;
	        $dataReport['message'] = json_encode($info['exception']->getMessage());
	        $dataReport['request'] = json_encode($params['request']);
        print '<pre>'.print_r($dataReport,true).'</pre>';
	        $report->save($dataReport);
        }
	} catch(Exception $e) {

	}

	$response = new Response(array(
		'request' => $params['request'],
		'status' => $info['exception']->getCode()
	));

	Media::render($response, compact('info', 'params'), array(
		'controller' => '_errors',
		'template' => 'development',
		'layout' => 'blank',
//        'template' => '404/error',
		'request' => $params['request']
	));
	return $response;
});

?>

