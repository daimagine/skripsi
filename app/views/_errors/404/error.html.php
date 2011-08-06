<!DOCTYPE HTML>
<html>
<head>
	<title>404 Page Not Found</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	
    <link rel="stylesheet" type="text/css" href="<?=$this->path('404/css/reset.css')?>" />
    <link rel="stylesheet" type="text/css" href="<?=$this->path('404/css/styles.css')?>" />
	<!--[if LTE IE 8]>
        <link rel="stylesheet" type="text/css" href="<?=$this->path('404/css/ie.css')?>" />
	<![endif]-->
	<!--[if IE 6]>
		<script type="text/javascript" src="js/DD_belatedPNG_0.0.8a-min.js"></script>
		<script type="text/javascript">
			DD_belatedPNG.fix('#bg-top, #bg-bottom, #logo, #nav ul, #contentWrap, #content, #leftColumn');
		</script>
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?=$this->path('404/css/ajax-pages.css')?>" /> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script src="<?=$this->path('404/js/jquery.tools.min.js');?>"></script>
					<script type="text/javascript">
					$(function() {
						//$("#overlay").overlay({api:true, finish: { top: 0, left: "center" } }).load();
					});
					</script>	
</head>
<body>
	<div class="overlay" id="overlay"> 
		<div id="result" class="wrap"><div id="ajaxWrap">
	<div id="wrapper">

		<div id="bg-top"></div>
		<div id="contentWrap">
			<div id="content">
				
				<!-- BEGIN LEFT COLUMN -->
				<div id="leftColumn">
				
					<!-- YOUR LOGO GOES HERE -->
					<a href="index.html"><img id="logo" src="" alt="" width="222" height="100"/></a>
					
					<!-- BEGIN MENU -->
					<div id="nav">

						<span>Menu</span>
						<ul>
							<li class="home"><a href="<?=$this->url('/');?>">Home</a></li>
							<li class="about"><a href="#">About Us</a></li>
<!--							<li class="contact"><a href="#">Contact Us</a></li>-->
						</ul>
					</div><!-- end div #nav -->

					
					<!-- ERROR CODE HERE -->
					<h1>404<span>Not Found</span></h1>
				</div><!-- end div #leftColumn -->
				<!-- END LEFT COLUMN -->
				
				<!-- BEGIN RIGHT COLUMN -->
				<div id="rightColumn">
					<h2>Oops! Page not found...</h2>

					<p>Sorry, Evidently the document you were looking for has either been moved or no longer exists. Please use the navigational links on left side to locate additional resources and information.</p>
                    
					<h4 class="regular"><span class="contact"></span>
                        <span class="h4title">Tell us about this situation.</span></h4>
					<!-- BEGIN SEARCH FORM - EDIT YOUR DOMAIN BELOW -->
					<?=$this->form->create(null, array('url'=>'pages/report'));?>
						<div>
                            <label>Insert your name :</label>
						    <input class="inputBox" name="submitter">
                            <label>Your messages to us :</label>
							<textarea class="inputBox" name="message" cols="35" rows="3" maxlength="256"></textarea>
                            <input type="hidden" name="type" value="error">
							<!--[if IE 6]><input type="submit" value="Go"><![endif]-->
							<!--[if !IE 6]><!--><input type="submit" value="Submit"><!--<![endif]-->
						</div>
					</form>
					<!-- END SEARCH FORM -->
					
<!--					<a id="close" href="javascript:void();">close</a>-->

					
				</div><!-- end div #rightColumn -->
				<!-- END RIGHT COLUMN -->
				
				<div class="clear"></div>
			</div><!-- end div #content -->
		</div><!-- end div #contentWrap -->
		<div id="bg-bottom"></div>
	</div><!-- end div #wrapper -->
</div><!-- end div #ajaxWrap -->
<script src="<?=$this->path('404/js/jquery-pages.js');?>"></script>
</div>

	</div>
</body>

</html>

<!--<script type="text/javascript" src="/scripts/behaviour.js"></script>-->
<!--<script type="text/javascript" src="/scripts/textarea_maxlen.js"></script>-->
<!--<TEXTAREA rows="5" cols="30" maxlength="120" showremain="limitOne"></TEXTAREA>-->
<!--<br>Chars Remaining:<span id="limitOne">--</span>-->
