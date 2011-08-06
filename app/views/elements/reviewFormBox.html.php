<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/validationEngine.jquery.css');?>" type="text/css"/>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/template.css');?>" type="text/css"/>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js');?>" type="text/javascript" charset="utf-8"></script>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/jquery.validationEngine.js');?>" type="text/javascript" charset="utf-8"></script>
<script>
    $(document).ready(function(){
        initValidation();
    });
    function initValidation(){
        jQuery("#reviewForm").validationEngine({
//            promptPosition: 'centerRight'
        });
    }
</script>


<div class="reviewFormBox">
        <h3>Write your own review</h3>
        <form class="reviewForm" id="reviewForm" action="<?=$this->url("$ratingFor/addReview");?>" method="post">
            <input type="hidden" name="placeId" value="<?=$place->_id;?>">
            <?php if(isset($promo)): ?>
                <input type="hidden" name="promoId" value="<?=$promo->_id;?>">
            <?php endif; ?>
            <?php if(isset($dish)): ?>
                <input type="hidden" name="dishId" value="<?=$dish->_id;?>">
            <?php endif; ?>
            <input type="hidden" name="userId" value="<?=$web_user['_id'];?>">
            <input type="hidden" name="userFullname" value="<?php echo (isset($web_user['name']))?$web_user['name']:$web_user['username'];?>">
            <input type="hidden" name="reviewNum" value="<?=$reviews->count()+1;?>">
            <div class="briefInfo">
                <div class="thumbnail peopleThumb">
                    <a href="#">
                        <img width="70px" height="60px" title="People thumbnail"
                             src="">
                    </a>
                </div>
                <div class="description marginLeftInfo">
                    <textarea class="inputBox validate[required]" name="content" placeholder="insert your review here"
                          id="txtReview"></textarea>
                </div>
                <?php echo $this->view()->render(
                    array(
                        'element' => 'ratingForm'
                    ),
                    array(
                        'ratingFor' => $ratingFor,
                        'reviews' => $reviews,
                    )
                );?>
                <div class="floatRight">
                    <input type="submit" value="Submit" class="button">
                </div>
            </div>
        </form>
    </div>