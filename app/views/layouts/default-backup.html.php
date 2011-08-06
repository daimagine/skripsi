<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->html->charset();?>
	<title>Application > <?php echo $this->title(); ?></title>
	<?php echo $this->html->style(array('backup/debug', 'backup/lithium', 'backup/style')); ?>
    <!--  load jquery and jquery ui  -->
    <script src="<?=$this->path('js/jquery/jquery-1.6.1.js');?>"></script>
    <script src="<?=$this->path('js/jquery/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js');?>"></script>
    <link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/jquery-ui-1.8.13.custom/css/smoothness/jquery-ui-1.8.13.custom.css')?>" />
	<?php echo $this->scripts(); ?>
	<?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
</head>
<body class="app">
	<div id="container">
<!--		<div id="header">-->
            <?php
                //@todo detect modules
                if(isset($header)) {
                    //echo '<div id="header"><header>';
                    echo '<header>';
                    if(is_array($header)) {
                        echo $this->view()->render($header);
                    }
                    else {
//                        echo $header;
                    }
                    //echo '</header></div>';
                    echo '</header>';
                }
                if(isset($navbar)) {
                    //echo '<div id="navbar"><nav>';
                    echo '<div><nav>';
                    if(is_array($navbar)) {
                        echo $this->view()->render($navbar);
                    }
                    else {
                        echo $navbar;
                    }
                    //echo '</nav></div>';
                    echo '</nav></div>';
                }
            ?>
<!--			<h1>Application</h1>-->
<!--			<h2>-->
<!--				Powered by --><?php //echo $this->html->link('Lithium', 'http://lithify.me/'); ?><!--.-->
<!--			</h2>-->
<!--		</div>-->

        <div style="clear: both;"></div>
        
		<div id="content">
            <?php if(isset($breadcrumb)) echo $this->view()->render(array('element' => 'breadcrumb'), compact('breadcrumb')); ?>
<!--            <h2>--><?php //echo @$subHeader;?><!--</h2>-->
			<?php echo $this->content(); ?>
		</div>
        <div id="footer">
            <h2>
				Powered by <?php echo $this->html->link('Lithium', 'http://lithify.me/'); ?>.
			</h2>
        </div>
	</div>
<?=$this->facebook->facebook_init(); ?>
</body>
</html>