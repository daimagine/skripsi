<?php
    use app\models\Users;
    use lithium\storage\Session;
    use li3_facebook\extensions\FacebookProxy;
    use lithium\util\Inflector;

    $this->title($user->getName());
?>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/validationEngine.jquery.css');?>" type="text/css"/>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/template.css');?>" type="text/css"/>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js');?>" type="text/javascript" charset="utf-8"></script>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/jquery.validationEngine.js');?>" type="text/javascript" charset="utf-8"></script>
<script>
    // This method is called right before the ajax form validation request
    // it is typically used to setup some visuals ("Please wait...");
    // you may return a false to stop the request
    function beforeCall(form, options){
        if (window.console)
            console.log("Right before the AJAX form validation call");
        return true;
    }

    // Called once the server replies to the ajax form validation request
    function ajaxValidationCallback(status, form, json, options){
        if (window.console)
            console.log(status);

        if (status === true) {
//            alert("the form is valid!");
            // uncomment these lines to submit the form to form.action
            // form.validationEngine('detach');
            // form.submit();
            // or you may use AJAX again to submit the data
            var data = {
                '_id' : '<?=$user->_id;?>',
                'password' : $('#Password').val()
            };
            $.post('changePassword', data, function(response) {
                var message = '';
                if (response.result != 'success') {
                    // this might occur if someone sends an update at the same time...
//                    return anologue.log(response);
//                    $('#ChangePassword').fadeOut();
                    message = 'Password cannot be updated. Please try again or contact the administrator';
                    notif = 'error';
                } else {
//                    $('.footer .message').val('');
                    message = 'Your new password have been saved. Now you can sign in with your email and password';
                    notif = 'success';
                }
//                alert(message);
                $("#set-first-password").dialog("close").fadeOut();
                jQuery.pnotify({
                    pnotify_text: message,
                    pnotify_hide: false,
                    pnotify_delay: 10000,
                    pnotify_type: notif
                });
            }, "json");
        }
    }

    $(document).ready(function(){
        initValidation();
        initModalPassword();

        //if user hasnt been set any password
        <?php if($user->isSelf() && $user->password==null):?>
            $("#set-first-password").dialog("open");
        <?php endif; ?>
    });
    function initValidation(){
        jQuery("#ChangePassword").validationEngine({
            ajaxFormValidation: true,
            onAjaxFormComplete: ajaxValidationCallback,
            promptPosition: 'centerRight'
        });
    }
    function letsChangePassword(){
        $("#set-first-password").dialog('open');
    }
    function initModalPassword() {
        $("#set-first-password").dialog({
            autoOpen: false,
            beforeShow: initValidation(),
            width: 600,
            height: 250,
            title: 'Set your password',
            modal: true,
            closeOnEscape: false,
            beforeClose: function(){
                $('#ChangePassword').validationEngine('hideAll');
                $('#ChangePassword').validationEngine('detach');
                $('#ChangePassword :input[type=text]').val('');
            }
        });
    }
</script>

<?php if($user->isSelf()):?>
    <div id="set-first-password">
        <p style="font-style: italic;">Please tell us your password. It will be use for you to sign in up with your email rather than using your facebook account.</p>
        <div id="form-password">
            <?=$this->form->create(null, array('id'=>'ChangePassword', 'action'=>'changePassword', 'method'=>'post'));?>
            <?=$this->form->field('password', array(
                'label' => 'Please fill your desired password.',
                'type' => 'password',
                'class' => 'validate[required,custom[onlyLetterNumber],minSize[6]] inputBox mediumInput',
                'placeholder'=>'use something tricky to strengthen your password',
            ));?>
            <?=$this->form->field('repeat_password', array(
                'label' => 'Repeat your password.',
                'type' => 'password',
                'class' => 'validate[required,equals[Password]] inputBox mediumInput',
                'placeholder'=>'please repeat your password here',
            ));?>
            <?=$this->form->submit('Save', array('class'=>'button'));?>
            <?=$this->form->end();?>
        </div>
        <style>
            .ui-dialog { font-size: 0.9em; }
            .formError { font-size: 0.85em !important; }
        </style>
    </div>
<?php endif;?>

<div id="user-profile">

    <?php
        if($user->isSelf()){
            echo $this->view()->render(array('element'=>'users/miniPanel'));
        }
    ?>

    <div id="brief-info">
        <div id="generalInfo">
            <div class="insideInfo">
                <div class="bigThumbnail borderedBox peopleThumb">
                    <?php if($user->getProfilePicture()):?>
                        <img width="120px" height="120px" title="People thumbnail"
                             src="<?=$user->getProfilePicture();?>">
                    <?php elseif(isset($user->fbid)):?>
                        <fb:profile-pic uid="<?=$user->fbid;?>" facebook-logo="false"
                            linked="true" width="120px" height="120px"></fb:profile-pic>
                    <?php else: ?>
                        <img width="120px" height="120px" title="People thumbnail"
                             src="">
                    <?php endif; ?>
                </div>
                <div class="bigRightBriefInfo floatLeft">
                    <h1 class="noMarginTop"><?=$user->getName();?></h1>
                    <div class="detailInfo">
                        <?php if($user->isSelf()): ?>
                            <p><?=@$user->email;?></p>
                        <?php endif; ?>
                        <p class="placeDescription"><?=@$user->about;?></p>
                        <p><?=@$user->telp;?></p>
                        <p><?=@$user->website;?></p>
                     </div>
                </div>
            </div>
            <div class="clear"></div>
            <div class="miniPanel">
                <div class="otherInfo">
                    <p class="iconLeft timeIcon">Join since <time datetime="<?=$user->created->sec;?>"
                            title="<?=date('l, d-M-Y h:i:s', $user->created->sec)?>" class="daiTipsy">
                        <?=$this->daiHtml->timeAgo($user->created->sec);?></time></p>
                    <?php if(!$user->isSelf()): ?>
                        <span id="addAsFriend">
                            <?php $isFriend = $user->isFriend($web_user['_id']);?>
<!--                            --><?php //var_dump($isFriend);?>
                            <?php if(!$user->isSelf() && $isFriend['code'] == Users::$friendRequestApproved): ?>
                                <p class="iconLeft messageIcon">
                                    <a href="<?=$this->url("/users/sendMessage/$user->_id");?>" class="daiTipsy"
                                            title="Send a message to this user">Send Message</a>
                                </p>
                            <?php elseif($isFriend['code'] == Users::$friendRequestUnApproved): ?>
                                <?php if($isFriend['isRequester'] != false && !$user->isSelf()): ?>
                                    <p class="iconLeft userAddIcon">
                                        <a href="<?=$this->url("/users/approveRequest/$user->_id");?>" class="daiTipsy"
                                                title="This user has been send a friend request to you.">Approve friend request</a>
                                    </p>
                                <?php elseif(!$user->isSelf()):?>
                                    <p class="iconLeft userSiluetIcon">Your request is not approved yet.</p>
                                    <?php //else :?>
                                    <!-- <a href="--><?//=$this->url('/users/approveRequest/'.$friend['friend_id']);?><!--">Approve Request</a>-->
                                <?php endif;?>
                            <?php elseif($isFriend['code'] == false) : ?>
                                <p class="iconLeft userAddIcon">
                                    <a href="<?=$this->url("/users/addFriend/$user->_id");?>" class="daiTipsy"
                                            title="Send friend request to this user">Add as Friend</a>
                                </p>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div id="portlet">
        <?php if($user->friendlist): ?>
            <fieldset>
                <div id="friendlist">
                    <h3>Friendlist</h3>
                    <ul style="list-style: square;">
                        <?php foreach($user->getFriends() as $friend): ?>
                            <li style="margin-bottom: 15px;">
                                <a href="<?=$this->url('/users/view/'.$friend['friend_id']);?>" style="font-size:1.1em;">
                                    <?=Users::getuserById($friend['friend_id'])->getName();?>
                                </a>
<!--                                <a href="--><?//=$this->url('/users/removeFriend/'.$friend['friend_id']);?><!--" class="smallAprroveButton">-->
<!--                                        Unfriend</a>-->
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php if($user->isSelf()):?>
                        <h3>Pending Requests</h3>
                        <ul>
                            <?php foreach($user->getPendingRequests() as $friend): ?>
                                <li style="margin-bottom: 10px;">
                                    <a href="<?=$this->url('/users/view/'.$friend['friend_id']);?>" style="font-size:1.1em;">
                                        <?=Users::getuserById($friend['friend_id'])->getName();?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <h3>Friend Requests</h3>
                        <ul>
                            <?php foreach($user->getFriendRequests() as $friend): ?>
                                <li>
                                    <a href="<?=$this->url('/users/view/'.$friend['friend_id']);?>" style="font-size:1.1em;">
                                        <?=Users::getuserById($friend['friend_id'])->getName();?>
                                    </a>
                                    <a href="<?=$this->url('/users/approveRequest/'.$friend['friend_id']);?>" class="smallAprroveButton">
                                        Approve Request</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif;?>
                </div>
            </fieldset>
        <?php endif; ?>
        <?php if($user->isSelf()): ?>
            <div id="messages">

            </div>
        <?php endif; ?>
    </div>
</div>

<pre>
<!--    --><?php //$facebook = FacebookProxy::getApiInstance();?>
<!--    --><?//=var_dump($facebook->getLogoutUrl());?>
<!--    --><?php //var_dump(Session::read('web_user')); ?>
<!--    --><?php //var_dump($user->getFriends());?>
</pre>
    