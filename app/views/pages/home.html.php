<?php
    $this->title('Home');
    $this->set(array('isHome' => true,));
?>

<div id="topVenues" class="mediumBoxWrapper">
    <h2 title="Most Popular Places by User">Most Popular Places</h2>
    <?php if($places->count()>0): ?>
        <ul>
            <?php $counter = 0; ?>
            <?php foreach($places as $place): ?>
                <?php $counter++; ?>
                <li>
                    <div class="thumbnail placeThumb">
                        <a href="#">
                            <img width="70px" height="60px" title="Place thumbnail"
                                 src="">
                        </a>
                    </div>
                    <div class="description">
                        <h3><a href="<?=$this->url("places/view/$place->_id");?>"><?=$place->name;?></a></h3>
                        <div style="display: inline-block;">
                            <p class="rating" style="display: inline-block;">
                                <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$place->rating, 'identifier'=>'-topplace'.$counter )
                            );?>
                            </p>
                        </div>
                        <div class="whenHappening">Posted on <time datetime="<?=$place->created->sec;?>"
                                title="<?=date('l, d-M-Y h:i:s', $place->created->sec)?>" class="daiTipsy">
                            <?=$this->daiHtml->timeAgo($place->created->sec);?></time>
                            by <a href="<?=$this->url("users/view/".$place->contributor->id);?>">
                                <?=$place->contributor->name?></a>
                        </div>
                        <p><?=$place->description;?></p>
                        <p class="feedback"><a href="<?=$this->url("places/view/$place->_id#reviewForm");?>">write comment</a></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<div id="topDishes" class="mediumBoxWrapper">
    <h2 title="Most Popular Dishes by User">Most Popular Dishes</h2>
    <?php if($dishes->count()>0): ?>
        <ul>
            <?php $counter = 0; ?>
            <?php foreach($dishes as $dish): ?>
                <?php $counter++; ?>
                <li>
                    <div class="thumbnail dishThumb">
                        <a href="#">
                            <img width="70px" height="60px" title="Dish thumbnail"
                                 src="">
                        </a>
                    </div>
                    <div class="description">
                        <h3><a href="<?=$this->url("dishes/view/$dish->_id");?>"><?=$dish->name;?></a>
                            <span style="font-size:0.9em;">at
                                <span>
                                    <a href="<?=$this->url("places/view/$dish->placeId");?>" style="color:#666666;">
                                        <?=$dish->placeName;?></a>
                                </span>
                            </span>
                        </h3>
                        <div style="display: inline-block;">
                            <p class="rating" style="display: inline-block;">
                                <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$dish->rating, 'identifier'=>'-topdish'.$counter )
                            );?>
                            </p>
                        </div>
                        <div class="whenHappening">Posted on <time datetime="<?=$dish->created->sec;?>"
                                title="<?=date('l, d-M-Y h:i:s', $dish->created->sec)?>" class="daiTipsy">
                            <?=$this->daiHtml->timeAgo($dish->created->sec);?></time>
                            by <a href="<?=$this->url("users/view/".$dish->contributor->id);?>">
                                <?=$dish->contributor->name?></a>
                        </div>
                        <?php if(isset($dish->price)):?>
                            <p>Rp. <?=$dish->price;?></p>
                        <?php endif; ?>
                        <p><?=$dish->description;?></p>
                        <p class="feedback"><a href="<?=$this->url("dishes/view/$dish->_id#reviewForm");?>">write comment</a></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<div id="topPromos" class="mediumBoxWrapper">
    <h2 title="Most Popular Promos by User">Most Popular Promos</h2>
    <?php if($promos->count()>0): ?>
        <ul>
            <?php $counter = 0; ?>
            <?php foreach($promos as $promo): ?>
                <?php $counter++; ?>
                <li>
                    <div class="thumbnail promoThumb">
                        <a href="#">
                            <img width="70px" height="60px" title="Promo thumbnail"
                                 src="">
                        </a>
                    </div>
                    <div class="description">
                        <h3><a href="<?=$this->url("promos/view/$promo->_id");?>"><?=$promo->title;?></a>
                            <span style="font-size:0.9em;">at
                                <span>
                                    <a href="<?=$this->url("places/view/$promo->placeId");?>" style="color:#666666;">
                                        <?=$promo->placeName;?></a>
                                </span>
                            </span>
                        </h3>
                        <div style="display: inline-block;">
                            <p class="rating" style="display: inline-block;">
                                <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$promo->rating, 'identifier'=>'-toppromo'.$counter )
                            );?>
                            </p>
                        </div>
                        <div class="whenHappening">Posted on <time datetime="<?=$promo->created->sec;?>"
                                title="<?=date('l, d-M-Y h:i:s', $promo->created->sec)?>" class="daiTipsy">
                            <?=$this->daiHtml->timeAgo($promo->created->sec);?></time>
                            by <a href="<?=$this->url("users/view/".$promo->contributor->id);?>">
                                <?=$promo->contributor->name?></a>
                        </div>
                        <p><?=$promo->description;?></p>
                        <p class="feedback"><a href="<?=$this->url("promos/view/$promo->_id#reviewForm");?>">write comment</a></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<div id="topReviews" class="mediumBoxWrapper">
    <h2 title="Most Popular Reviews by User">Most Popular Reviews</h2>
    <?php if($reviews->count()>0): ?>
        <ul>
            <?php $counter = 0; ?>
            <?php foreach($reviews as $review): ?>
                <?php $counter++; ?>
                <li>
                    <div class="thumbnail peopleThumb">
                        <a href="#">
                            <img width="70px" height="60px" title="People thumbnail"
                                 src="">
                        </a>
                    </div>
                    <div class="description">
                        <h3><a href="<?=$this->url("users/view/$review->userId");?>"><?=$review->userFullname;?></a>
                            <span style="font-size:0.8em;">on
                                <span>
                                    <?php if(isset($review->promoId)): ?>
                                         <a href="<?=$this->url("promos/view/$review->promoId#review-$review->reviewNum");?>" style="color:#666666;">
                                             <?=$this->daiHtml->getPromo($review->promoId)->title;?></a>
                                    <?php elseif(isset($review->dishId)): ?>
                                         <a href="<?=$this->url("dishes/view/$review->dishId#review-$review->reviewNum");?>" style="color:#666666;">
                                             <?=$this->daiHtml->getDish($review->dishId)->name;?></a>
                                    <?php else: ?>
                                         <a href="<?=$this->url("places/view/$review->placeId#review-$review->reviewNum");?>" style="color:#666666;">
                                             <?=$this->daiHtml->getPlace($review->placeId)->name;?></a>
                                    <?php endif; ?>
                                </span>
                            </span>
                        </h3>
                        <div class="whenHappening">Posted on <time datetime="<?=$review->created->sec;?>"
                                title="<?=date('l, d-M-Y h:i:s', $review->created->sec)?>" class="daiTipsy">
                            <?=$this->daiHtml->timeAgo($review->created->sec);?></time>
                        </div>
                        <p><?=$review->content;?></p>
<!--                        <p class="feedback"><a href="#">write comment</a></p>-->
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
