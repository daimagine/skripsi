<?php
/**
 * daiHtml class file.
 * @author dai
 * @date 7/16/11
 * @time: 12:11 AM
 */
namespace app\extensions\helper;

//use extensions\util\Util;
use lithium\util\Inflector;
use li3_flash_message\extensions\storage\FlashMessage;
use lithium\template\View;
use lithium\core\Libraries;

use app\models\Users;
use app\models\Promos;
use \app\models\Dishes;
use \app\models\Places;

class DaiHtml  extends \lithium\template\helper\Html {

    /**
     * fetch from MinervaHtml
	 * We want to use our own little helper so that everything is shorter to write and
	 * so we can use fancier messages with JavaScript.
	 *
	 * @param $options
	 * @return HTML String
	*/
	public function flash($options=array()) {
		$defaults = array(
			'key' => 'web_user',
			// options for the layout template, some of these options are specifically for the pnotify jquery plugin
			'options' => array(
				'type' => 'growl',
				'fade_delay' => '10000',
				'pnotify_opacity' => '1'
			)
		);
		$options += $defaults;

		$message = '';

		$flash = FlashMessage::read($options['key']);
		if (!empty($flash)) {
			$message = $flash['message'];
            if(isset($flash['atts']['type'])) {
                $options['options']['type'] = $flash['atts']['type'];
            }
            if(isset($flash['atts']['sticky'])) {
                $options['options']['hide'] = false;
            }
//            var_dump($options);
			FlashMessage::clear($options['key']);
		}

		$view = new View(array(
			'paths' => array(
				'template' => '{:library}/views/elements/{:template}.{:type}.php',
				'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
			)
		));
		return $view->render('all', array('options' => $options['options'], 'message' => $message), array(
			'template' => 'flash_message',
			'type' => 'html',
			'layout' => 'blank',
		));
	}

    public function getUser($userId) {
        $user = Users::find('first', array('conditions'=>array('_id'=>$userId)));
        return $user;
    }

    public function getPlace($userId) {
        $user = Places::find('first', array('conditions'=>array('_id'=>$userId)));
        return $user;
    }

    public function getDish($userId) {
        $user = Dishes::find('first', array('conditions'=>array('_id'=>$userId)));
        return $user;
    }

    public function getPromo($userId) {
        $user = Promos::find('first', array('conditions'=>array('_id'=>$userId)));
        return $user;
    }

    public function getObject($class, $id) {
        $obj = $class::find('first', array('conditions'=>array('_id'=>$id)));
        return $obj;
    }

    public function timeAgo($datefrom,$dateto=-1) {
        // Defaults and assume if 0 is passed in that
        // its an error rather than the epoch
        if($datefrom<=0) { return "A long time ago"; }
        if($dateto==-1) { $dateto = time(); }

        // Calculate the difference in seconds betweeen
        // the two timestamps

        $difference = $dateto - $datefrom;

        // If difference is less than 30 seconds,
        if($difference < 30)
        {
         return 'Just now';
        }

        // If difference is less than 60 seconds,
        // seconds is a good interval of choice

        if($difference < 60)
        {
        $interval = "s";
        }

        // If difference is between 60 seconds and
        // 60 minutes, minutes is a good interval
        elseif($difference >= 60 && $difference<60*60)
        {
        $interval = "n";
        }

        // If difference is between 1 hour and 24 hours
        // hours is a good interval
        elseif($difference >= 60*60 && $difference<60*60*24)
        {
        $interval = "h";
        }

        // If difference is between 1 day and 7 days
        // days is a good interval
        elseif($difference >= 60*60*24 && $difference<60*60*24*7)
        {
        $interval = "d";
        }

        // If difference is between 1 week and 30 days
        // weeks is a good interval
        elseif($difference >= 60*60*24*7 && $difference <
        60*60*24*30)
        {
        $interval = "ww";
        }

        // If difference is between 30 days and 365 days
        // months is a good interval, again, the same thing
        // applies, if the 29th February happens to exist
        // between your 2 dates, the function will return
        // the 'incorrect' value for a day
        elseif($difference >= 60*60*24*30 && $difference <
        60*60*24*365)
        {
        $interval = "m";
        }

        // If difference is greater than or equal to 365
        // days, return year. This will be incorrect if
        // for example, you call the function on the 28th April
        // 2008 passing in 29th April 2007. It will return
        // 1 year ago when in actual fact (yawn!) not quite
        // a year has gone by
        elseif($difference >= 60*60*24*365)
        {
        $interval = "y";
        }

        // Based on the interval, determine the
        // number of units between the two dates
        // From this point on, you would be hard
        // pushed telling the difference between
        // this function and DateDiff. If the $datediff
        // returned is 1, be sure to return the singular
        // of the unit, e.g. 'day' rather 'days'

        switch($interval)
        {
        case "m":
        $months_difference = floor($difference / 60 / 60 / 24 /
        29);
        while (mktime(date("H", $datefrom), date("i", $datefrom),
        date("s", $datefrom), date("n", $datefrom)+($months_difference),
        date("j", $dateto), date("Y", $datefrom)) < $dateto)
        {
        $months_difference++;
        }
        $datediff = $months_difference;

        // We need this in here because it is possible
        // to have an 'm' interval and a months
        // difference of 12 because we are using 29 days
        // in a month

        if($datediff==12)
        {
        $datediff--;
        }

        $res = ($datediff==1) ? "$datediff month ago" : "$datediff
        months ago";
        break;

        case "y":
        $datediff = floor($difference / 60 / 60 / 24 / 365);
        $res = ($datediff==1) ? "$datediff year ago" : "$datediff
        years ago";
        break;

        case "d":
        $datediff = floor($difference / 60 / 60 / 24);
        $res = ($datediff==1) ? "$datediff day ago" : "$datediff
        days ago";
        break;

        case "ww":
        $datediff = floor($difference / 60 / 60 / 24 / 7);
        $res = ($datediff==1) ? "$datediff week ago" : "$datediff
        weeks ago";
        break;

        case "h":
        $datediff = floor($difference / 60 / 60);
        $res = ($datediff==1) ? "$datediff hour ago" : "$datediff
        hours ago";
        break;

        case "n":
        $datediff = floor($difference / 60);
        $res = ($datediff==1) ? "$datediff minute ago" :
        "$datediff minutes ago";
        break;

        case "s":
        $datediff = $difference;
        $res = ($datediff==1) ? "$datediff second ago" :
        "$datediff seconds ago";
        break;
        }
        return $res;
    }

    public function getImgUploadUrl($filename) {
        $base = APP_PATH;
        if ((!empty($base)) && !preg_match('/^\//', $base)) {
            $base = '/' . $base;
        }
        $baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $base;
        return $baseurl . '/public/' . $filename;
    }

}
