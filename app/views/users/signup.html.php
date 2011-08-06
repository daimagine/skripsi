<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );
    $this->title($title);
?>
<?php
//var_dump(\li3_flash_message\extensions\storage\FlashMessage::read('web_user'));
?>
<h1>Sign Up</h1>
<?=$this->form->create($user);?>
    <?php if($scenario!='fb'):?>
        <h3>
            <label>Use your Facebook Account :
                <a href="<?=\li3_facebook\extensions\FacebookProxy::getLoginUrl(array('req_perms'=>'email,publish_stream,user_checkins', "next"=>"http://localhost/skripsi-dev/users/login/fb/"));?>">
                    <img src="<?=$this->path('img/facebook-login-button.png')?>" style="vertical-align: middle;">
                </a>
            </label>
        </h3>
        <h3>Or Manually signup with your email</h3>
    <?php else:?>
        <div style="margin-bottom: 10px;">Sign Up with Facebook As <b><?=$user->name;?></b></div>
        <?=$this->form->field('name', array('type'=>'hidden'));?>
        <?=$this->form->field('fbid', array('type'=>'hidden'));?>
        <?php
            $readonly = 'readonly';
            if(isset($user->username) and $user->username!='' and $user->username!=null)
                $readonlyUsername = 'readonly';
            $labelEmail = 'Your connected email from Facebook';
            $labelUsername = 'Your Facebook username';
        ?>
    <?php endif;?>
    <?=$this->form->field('username', array(
        'label'=>(isset($labelUsername))?$labelUsername:'Insert your desire username',
        'readonly'=>@$readonlyUsername,
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'please use only alphanumeric without space',
        'autofocus'=>'true',
        ));?>
    <?=$this->form->field('email', array(
        'label'=>(isset($labelEmail))?$labelEmail:'Insert your valid email',
        'readonly'=>@$readonly,
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'please use your valid email address'
        ));?>
    <?=$this->form->field('password', array(
        'label'=>'Insert password',
        'type'=>'password',
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'use something tricky to strengthen your password'
      ));?>
    <?=$this->form->submit('Sign Up', array('class'=>'button'));?>
<?=$this->form->end();?>

<hr class="hrGray">
<fieldset>
    <b>So you already have an account? &nbsp; <button onclick="window.location='<?=$this->url('users/login')?>'" class="button buttonMedium">Signin here</button></b>
</fieldset>

<script type="text/javascript">
//    function submitCheck() {
//	if(($('#password_input').val() != $('#password_confirm_input').val()) || ($('#password_input').val() == '')) {
//	    $('#password_confirm_input').parent().siblings('.input_help_right').hide();
//	    $('#password_confirm_error').remove();
//	    $('#password_confirm_input').parent().parent().append('<div class="input_error_right error" id="password_confirm_error">Passwords must match.</div>');
//	    return false;
//	}
//	return true;
//    }
//    $(document).ready(function() {
//	$('#password_input').val('');
//
//        $('input').blur(function() {
//			$('.input_help_right').hide();
//
//			if($('#email_input').val().length < 5) {
//				$('#email_error').remove();
//				$('#email_input').parent().parent().append('<div class="input_error_right error" id="email_error">You must provide your e-mail.</div>');
//			}
//			if($('#password_input').val().length < 6) {
//				$('#password_error').remove();
//				$('#password_input').parent().parent().append('<div class="input_error_right error" id="password_error">Password must be at least 6 characters long.</div>');
//			}
//			$('.input_help_right').hide();
//			//$(this).siblings('.error').show();
//			$(this).siblings('#email_error').show();
//			$(this).siblings('#password_error').show();
//			$(this).siblings('#password_confirm_error').show();
//        });
//
//        $('input').focus(function() {
//            $(this).parent().siblings().show();
//            $(this).parent().siblings('.error').hide();
//            $(this).parent().siblings('#email_error').hide();
//            $(this).parent().siblings('#password_error').hide();
//			$(this).parent().siblings('#password_confirm_error').hide();
//        });
//
//        $('#email_input').change(function() {
//	    $.get('/users/is_email_in_use/' + $('#email_input').val(), function(data) {
//                if(data == 'true') {
//                    $('#email_error').remove();
//                    $('#email_input').parent().parent().append('<div id="email_error">Sorry, this e-mail address is already registered.</div>');
//                } else {
//                    $('#email_error').remove();
//                }
//            });
//        });
//
//    });
</script>

<?php
// Get User ID
//$user = $facebook->getUser();
//if ($user) {
//  try {
//    // Proceed knowing you have a logged in user who's authenticated.
//    $user_profile = $facebook->api('/me');
//  } catch (FacebookApiException $e) {
//    error_log($e);
//    $user = null;
//  }
//}
//
//// Sign in or logout url will be needed depending on current user state.
//if ($user) {
//  $logoutUrl = $facebook->getLogoutUrl();
//} else {
//  $loginUrl = $facebook->getLoginUrl();
//}

//init facebook
//    echo $this->facebook->facebook_init();
//echo $this->facebook->facebook_login();
?>


<?php //if ($user): ?>
<!--<a href="--><?php //echo $logoutUrl; ?><!--">Logout</a>-->
<?php //else: ?>
<!--<div>-->
<!--Sign in using OAuth 2.0 handled by the PHP SDK:-->
<!--<a href="--><?php //echo $loginUrl; ?><!--">Sign in with Facebook</a>-->
<!--</div>-->
<?php //endif ?>

<!--<div id="fb-root"></div>-->
<script>
/*  window.fbAsyncInit = function() {
<!--    FB.init({appId: '--><?//=$facebook->getAppId();?><!--', status: true, cookie: true,-->
             xfbml: true});
  };
  (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
      '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
  }());*/
</script>

<!--<pre>-->
<?//=print_r($facebook);?>
<?//=print_r($user);?>
<!--</pre>-->


<!--<div id="content">-->
<!--   --><?//=$this->form->create($user, array('action' => 'register', 'onSubmit' => 'return submitCheck();')); ?>
<!--       <fieldset class="register">-->
<!--               <legend>Register</legend>-->
<!--               <div class="input">--><?//=$this->form->field('first_name', array('label' => 'First Name'));?><!--</div>-->
<!--               <div class="input">--><?//=$this->form->field('last_name', array('label' => 'Last Name'));?><!--</div>-->
<!--               <div class="input">--><?//=$this->form->field('email', array('label' => 'E-mail (also your username)', 'id' => 'email_input'));?><!--<div class="input_help_right">Please enter your e-mail address.</div></div>-->
<!--               <div class="input">--><?//=$this->form->field('password', array('type' => 'password', 'label' => 'Password', 'id' => 'password_input'));?><!--<div class="input_help_right">Choose a password at least 6 characters long.</div></div>-->
<!--               <div class="input">--><?//=$this->form->field('password_confirm', array('type' => 'password', 'label' => 'Confirm Password', 'id' => 'password_confirm_input'));?><!--<div class="input_help_right">Just to be sure, type your password again.</div></div>-->
<!--       --><?//=$this->form->submit('Create my account', array('class' => 'submit')); ?>
<!--           </fieldset>-->
<!--   --><?//=$this->form->end(); ?>
<!--   </div>-->
<!---->
<!--   <div class="clear"></div>-->
