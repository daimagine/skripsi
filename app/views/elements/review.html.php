<?php
$reviewFor = 'places';
$obj = $review->placeId;
if(isset($review->promoId)) {
    $reviewFor = 'promos';
    $obj = $review->promoId;
} elseif(isset($review->dishId)) {
    $reviewFor = 'dishes';
    $obj = $review->dishId;
}
?>

<div class="placeItem" id="review-<?=$review->reviewNum;?>">
    <div class="briefInfo">
        <div class="rightFloatBox">
            <div class="g-plusone" data-size="medium" data-count="true"
                 href="<?=$this->url("$reviewFor/view/$obj#review-$review->reviewNum", array('absolute'=>true));?>"></div>
            <div><fb:like href="<?=$this->url("$reviewFor/view/$obj#review-$review->reviewNum", array('absolute'=>true));?>"
                 send="false" layout="button_count" width="70" show_faces="false" font=""></fb:like></div>
        </div>
        <a href="<?=$this->url("users/view/$review->userId");?>"><h3><?=$review->userFullname;?></h3></a>
        <div class="thumbnail peopleThumb">
            <a href="#">
                <img width="70px" height="60px" title="People thumbnail"
                     src="">
            </a>
        </div>
        <div class="description marginLeftInfo narrowLineHeight justify">
            <?php if(isset($reviewFor)):?>
                <div class="reviewStat">
                    <span class="ratingItem">
                        <span class="ratingValue isOverall"><?=$review->rating->overall;?></span>
                        <span class="ratingTitle">Overall</span>
                    </span>
                <?php if($reviewFor != 'dishes' && $reviewFor != 'promos'): ?>
                    <span class="ratingItem">
                        <span class="ratingValue"><?=$review->rating->food;?></span>
                        <span class="ratingTitle">Food</span>
                    </span>
                    <span class="ratingItem">
                        <span class="ratingValue"><?=$review->rating->service;?></span>
                        <span class="ratingTitle">Service</span>
                    </span>
                <?php endif;?>
                <?php if($reviewFor != 'promos'): ?>
                    <span class="ratingItem">
                        <span class="ratingValue"><?=$review->rating->price;?></span>
                        <span class="ratingTitle">Price</span>
                    </span>
                <?php endif;?>
                <?php if($reviewFor == 'dishes'): ?>
                    <span class="ratingItem">
                        <span class="ratingValue"><?=$review->rating->taste;?></span>
                        <span class="ratingTitle">Taste</span>
                    </span>
                <?php endif;?>
                </div>
            <?php endif;?>
            <div class="whenHappening">Posted on <time datetime="<?=$review->created->sec;?>"
                    title="<?=date('l, d-M-Y h:i:s', $review->created->sec)?>" class="daiTipsy">
                <?=$this->daiHtml->timeAgo($review->created->sec);?></time>
                <?php if(isset($review->promoId)): ?>
                     at <a href="<?=$this->url("promos/view/$review->promoId");?>">
                        <?=$this->daiHtml->getPromo($review->promoId)->title;?></a>
                <?php elseif(isset($review->dishId)): ?>
                     at <a href="<?=$this->url("dishes/view/$review->dishId");?>">
                        <?=$this->daiHtml->getDish($review->dishId)->name;?></a>
                <?php endif; ?>
            </div>
            <p><?=$review->content;?></p>
        </div>
        <div class="linkPanel marginLeftInfo">
<!--                            <span title="Place rating" class="rating">-->
<!--                                <img alt="Rating for place" src="http://static-10.urbanesia.com//images/s_rate4.png">-->
<!--                            </span>-->
<!--                            <a href="#">write review</a>-->
        </div>
    </div>
</div>