<?php
    $this->title($promo->title);
?>

<div id="overviewInfoPlace">
    <div id="generalInfo">
        <div class="insideInfo">
            <div class="hugeThumbnail borderedBox promoThumb">
                <img title="Promo thumbnail"
                     src="">
            </div>
            <div class="rightBriefInfo floatLeft">
                <h1 class="noMarginTop"><?=$promo->title;?></h1>
                <div class="promoInfo">
                    <p class="placeDescription"><?=$promo->description;?></p>
                    <h4>Additional Info :</h4>
                    <p class="placeDescription"><?=$promo->info;?></p>
                    <div class="bigPrice">
                        <p>Promo period : <span>
                            <time datetime="<?=date('d M Y', strtotime($promo->period->from));?>"><?=date('d M Y', strtotime($promo->period->from));?></time>
                            - <time datetime="<?=date('d M Y', strtotime($promo->period->to));?>"><?=date('d M Y', strtotime($promo->period->to));?></time>
                        </span></p>
                    </div>
                    <div class="rightFloatBox">
                        <div class="g-plusone" data-size="medium" data-count="true"></div>
                        <div><fb:like send="false" layout="button_count" width="70" show_faces="false" font=""></fb:like></div>
                    </div>
                    <div class="addressInfo">
                        <a href="<?=$this->url("places/view/$place->_id")?>"><h2><?=$place->name;?></h2></a>
                        <p><?=$place->address->street;?></p>
                        <p><?=$place->address->city;?></p>
                    </div>
                 </div>
                <div class="promoTagTop">
                    <?php if($promo->checkAttrib('tags')):?>
                        <h3>Promotion Categories</h3>
                        <ul class="niceTag">
                            <?php foreach ($promo->tags as $tag): ?>
                                <li><?=$tag;?></li>
                            <?php endforeach ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="miniPanel">
            <div class="rating rightMarginTen floatLeft">
                <!-- Promos Rating -->
                <?php echo $this->view()->render(
                    array( 'element' => 'singleRating' ),
                    array( 'rating' => @$promo->rating )
                );?>
            </div>
            <div class="otherInfo">
                <p>First submitted by <a href="<?=$this->url("users/view/".$promo->contributor->id)?>"><?=$promo->contributor->name?></a>
                    on <time datetime="<?=$promo->created->sec;?>"><?=date('d F Y', $promo->created->sec);?></time></p>
            </div>
            <div class="floatRight">
                <a href="<?=$this->url("promos/place/$place->_id");?>">Another Promo in Place</a>
            </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="reviewBox">
        <h2>Reviews by foodies</h2>
        <?php if($reviews->count()>0): ?>
            <?php $counter = 0; ?>
            <?php foreach($reviews as $review): ?>
                <?php if($counter++ > 3) break; ?>
                <div class="placeItem" id="review-<?=$review->reviewNum;?>">
                    <div class="briefInfo">
                        <div class="rightFloatBox">
                            <div class="g-plusone" data-size="medium" data-count="true"
                                 href="<?=$this->url("promos/view/$promo->_id#review-$review->reviewNum", array('absolute'=>true));?>"></div>
                            <div><fb:like href="<?=$this->url("promos/view/$promo->_id#review-$review->reviewNum", array('absolute'=>true));?>"
                                 send="false" layout="button_count" width="70" show_faces="false" font=""></fb:like></div>
                        </div>
                        <a href="<?=$this->url("users/view/$review->userId");?>"><h3 title="People name"><?=$review->userFullname;?></h3></a>
                        <div class="thumbnail peopleThumb">
                            <a href="#">
                                <img width="70px" height="60px" title="People thumbnail"
                                     src="">
                            </a>
                        </div>
                        <div class="description marginLeftInfo narrowLineHeight justify">
                            <div class="reviewStat">
                                <span class="ratingItem">
                                    <span class="ratingValue isOverall"><?=$review->rating->overall;?></span>
                                    <span class="ratingTitle">Overall</span>
                                </span>
                            </div>
                            <div class="whenHappening">Posted on <time datetime="<?=$review->created->sec;?>"><?=date('d F Y', $review->created->sec);?></time></div>
                            <p><?=$review->content;?></p>
                        </div>
                        <div class="linkPanel marginLeftInfo">
<!--                            <span title="Place rating" class="rating">-->
<!--                                <img alt="Rating for place" src="http://static-10.urbanesia.com//images/s_rate4.png">-->
<!--                            </span>-->
<!--                            <a href="--><?//=$this->url("promos/view/$promo->_id#reviewForm");?><!--">write review</a>-->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="favoriteMenuPictures">
                <h3>No Review on this place yet</h3>
            </div>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
    <!-- REVIEW FORM -->
    <?php echo $this->view()->render(
        array(
            'element' => 'reviewFormBox'
        ),
        array(
            'place' => $place,
            'promo' => $promo,
            'reviews' => $reviews,
            'web_user' => $web_user,
            'ratingFor' => 'promos'
        )
    );?>
    <div class="clear"></div>
</div>

    <?//=$this->html->image("/promoes/view/{$promo->_id}.jpg", array('alt' => $promo->name)); ?>

<!--    <div class="lithium-stack-trace">-->
<!--        <pre>-->
<!--        --><?//=print_r($promo);?>
<!--        </pre>-->
<!--    <div class="lithium-stack-trace">-->
