<?php
    use \app\models\Users;
    use lithium\storage\Session;
//    use li3_facebook\extensions\FacebookProxy;


    $this->title("Message");
?>

<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/validationEngine.jquery.css');?>" type="text/css"/>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/template.css');?>" type="text/css"/>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js');?>" type="text/javascript" charset="utf-8"></script>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/jquery.validationEngine.js');?>" type="text/javascript" charset="utf-8"></script>


<?php echo $this->view()->render(array('element'=>'users/miniPanel'));?>

<div id="userMessages">
    <?php if(!isset($detail)) :?>
        <?php foreach($messages->conversation as $conversation) :?>
            <?php
                //TODO:THANKS FOR MY STUPIDITY ON LEARNING MONGODB. I CANT USE $PULL CORRECTLY
                // THERE IS A HOLE IN ARRAY THAT MAKES IT NULL. DAMN........
                if($conversation==null)
                    continue;
            ?>
            <div class="messageItem">
                <div class="briefInfo">
                    <div class="thumbnail peopleThumb">
                        <a href="<?=$this->url("users/messageDetail/$conversation->friend_id")?>">
                            <img width="70px" height="60px" src="">
                        </a>
                    </div>
                    <div class="description marginLeftInfo narrowLineHeight">
                        <h3 style="padding-top: 10px;"><a href="<?=$this->url("users/messageDetail/$conversation->friend_id")?>">
                                <?=Users::getUserById($conversation->friend_id)->getName();?>
                        </a></h3>
                        <?php $lastIdx = $conversation->messages->count()-1; ?>
                        <div class="messageLastContent">
                            <p title="Last message in conversation"><?=$conversation->messages[$lastIdx]->content;?></p>
                            <datetime class="whenHappening"><?=date('l, d F Y', $conversation->messages[$lastIdx]->time->sec);?></datetime>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="hrGray">
        <?php endforeach;?>
    <?php else : ?>
        <?php foreach($messages->conversation as $conversation) :?>
            <?php if($conversation->friend_id == $detail): ?>
                <div class="messageWrapper">
                    <?php
                        $friends = Users::getUserById($detail);
                    ?>
                    <?php foreach($conversation->messages as $message) :?>
                        <div id="<?= $message->id;?>">
                            <div class="thumbnail peopleThumb">
                                <a href="">
                                    <img width="70px" height="60px" src="">
                                </a>
                            </div>
                            <div class="description marginLeftInfo narrowLineHeight">
                                <h4 style=""><a href="">
                                    <?=($message->from == $friends->_id)? $friends->getName() : $user->getName();?>
                                </a></h4>
                                <div class="messageLastContent">
                                    <p title="Last message in conversation"><?=$message->content;?></p>
                                    <datetime class="whenHappening"><?=date('l, d F Y', $message->time->sec);?></datetime>
                                    <div class="messageRemover" style="font-size: 0.85em;">
                                        <a href="<?=$this->url(array("controller"=>"users","action"=>"removeMessage",
                                                "args"=>array(
                                                    "id"=>$messages->_id,
                                                    "messageId"=>$message->id,
                                                    "friendId"=>$conversation->friend_id
                                                )
                                           ));?>"
                                           onclick="removeMsg('<?=$message->id;?>')" id="removerButton">remove</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="hrGray">
                    <?php endforeach;?>
                </div>
                <?php break;?>
            <?php else: ?>
                <?php continue;?>
            <?php endif;?>
        <?php endforeach;?>
        <br>
        <div id="replyMessage">
            <fieldset>
                <script type="text/javascript">
                    function removeMsg(id){
                        id = '#'+id;
                        $(id).remove();
                    }

                    $(document).ready(function(){
                        jQuery("#ReplyMessageForm").validationEngine({
                            promptPosition: 'centerRight'
                        });
                    });
                </script>
                <form action="<?=$this->url("/users/sendMessage/$detail");?>" method="post" id="ReplyMessageForm">
    <!--                <input type="hidden" value="--><?//=$detail;?><!--" name="id">-->
                    <input type="hidden" value="/users/messageDetail/<?=$detail;?>" name="callback">
                    <label for="messageContent">Reply Message</label>
                    <textarea type="text" name="message" id="messageContent" style="width:70%;" rows=5
                              class="validate[required] inputBox mediumInput"></textarea>
                    <input type="submit" name="submit" value="Send" class="button">
                </form>
            </fieldset>
        </div>
    <?php endif;?>
</div>


<pre>
<!--    --><?php //print_r($messages->conversation->data());?>
<!--    --><?//=$detail;?>
</pre>