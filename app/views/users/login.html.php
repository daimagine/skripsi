<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );
    use lithium\storage\Session;
    $this->title($title);
?>

<h1>Sign in</h1>
<?=$this->form->create(null);?>
    <?php if($scenario!='fb'):?>
        <h3>
            <label>Sign in with your Facebook Account : &nbsp;
                <a href="<?=\li3_facebook\extensions\FacebookProxy::getLoginUrl(array('req_perms'=>'email,publish_stream,user_checkins', "next"=>"http://localhost/skripsi-dev/users/login/fb/"));?>">
                    <img src="<?=$this->path('img/facebook-login-button.png')?>" style="vertical-align: middle;">
                </a>
            <br><br><label>Or with your registered account</label>
        </h3>
    <?php else:?>
<!--                <div style="margin-bottom: 10px;">Sign Up with Facebook As <b>--><?//=$user->name;?><!--</b></div>-->
<!--                --><?//=$this->form->field('name', array('type'=>'hidden'));?>
<!--                --><?//=$this->form->field('fbid', array('type'=>'hidden'));?>
        <?php
            $readonly = 'readonly';
            $labelEmail = 'Your connected email from Facebook';
            $labelUsername = 'Your Facebook username';
        ?>
    <?php endif;?>
    <?=$this->form->field('email', array(
        'label'=>'Insert your email',
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'Insert your registered email here',
        'autofocus'=>'true',
        ));?>
    <?=$this->form->field('password', array(
        'label'=>'Insert your password',
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'Insert your password here',
        'type'=>'password',
      ));?>
        <?php //if(isset($errorLogin)):?>
        <div class="error">
            <?//=$errorLogin;?>
            <?=$this->flashMessage->output();?>
        </div>
<!--            --><?php //endif;?>
    <?=$this->form->submit('Sign in', array('class'=>'button'));?>
<?=$this->form->end();?>

<hr class="hrGray">
<fieldset>
    <h3>Don't have an account yet? &nbsp; <button onclick="window.location='<?=$this->url('users/signup')?>'"
                                                  class="button">Join us</button></h3>
    Or using your Facebook account : &nbsp;
    <a href="<?=\li3_facebook\extensions\FacebookProxy::getLoginUrl(array('req_perms'=>'email,publish_stream,user_checkins', "next"=>"http://localhost/skripsi-dev/users/login/fb/"));?>">
        <img src="<?=$this->path('img/facebook-login-button.png')?>" style="vertical-align: middle;">
    </a>
</fieldset>


<pre>
    <?php
//        var_dump($facebook);
        try{
//            var_dump(Session::read('fb_logout_url'));
//            $meg = $facebook;
//            var_dump($meg);
//            var_dump(\li3_facebook\extensions\FacebookProxy::getSession());
        } catch (\Exception $err) {
            
        }
    ?>
</pre>
