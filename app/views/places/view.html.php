<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );
    $this->title($place->name);
?>

<div id="overviewInfoPlace">
    <!-- PLACE GENERAL INFO -->
    <?php echo $this->view()->render(
        array(
            'element' => 'places/generalInfo'
        ),
        array(
            'place' => $place,
        )
    );?>
    
    <div class="additionalInfo">
        <div class="favoriteMenus">
            <h2>Favorite menus</h2>
            <div class="favoriteMenuPictures">
                <?php $counter = 0; ?>
                <?php if($dishes->count()>0): ?>
                    <?php if($counter++ > 3) break; ?>
                    <div class="thumbnailContainer">
                        <?php foreach($dishes as $dish): ?>
                            <div class="bigThumbnail bigThumbnailWithDesc borderedBox floatLeft marginTopTen">
                                <div class="dishPictureSmall dishThumb">
                                    <img width="120px" height="120px" title="Place thumbnail"
                                         src="<?php
                                                if(isset($dish->mainPicture) && $dish->mainPicture!==''){
                                                    echo $dish->mainPicture;
                                                 }
                                             ?>">
                                </div>
                                <a href="<?=$this->url("dishes/view/$dish->_id");?>">
                                    <p class="title"><?=$dish->name;?></p>
                                    <p><?=$dish->price;?> Rupiah</p>
                                </a>
                                <p style="display: inline-block;"><!-- Dish Rating -->
                                    <?php echo $this->view()->render(
                                        array( 'element' => 'singleRating' ),
                                        array( 'rating' => @$dish->rating, 'identifier'=>'-'.rand().$counter )
                                    );?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="clear"></div>
                    <div style="margin-top: 10px;">
                        <p><a href="<?=$this->url("dishes/place/$place->_id");?>">See all menu in this place</a></p>
                        <p>Find any updates? <a href="<?=$this->url("dishes/add/$place->_id");?>">Submit a dish from</a> this place.</p>
                    </div>
                <?php else: ?>
                    <h3>No Favorite Menus</h3>
                    <p>Find any updates? <a href="<?=$this->url("dishes/add/$place->_id");?>">Submit a dish from</a> this place.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="placePromos">
            <h2>Promotions</h2>
            <?php if($promos->count()>0): ?>
                <?php $counter = 0; ?>
                <?php foreach($promos as $promo): ?>
                    <?php if($counter++ > 3) break; ?>
                    <div class="placeItem">
                        <div class="briefInfo">
                            <div class="rightFloatBox">
                                <div class="g-plusone" data-size="medium" data-count="true" href="<?=$this->url("promos/view/$promo->_id", array('absolute'=>true));?>"></div>
                                <div><fb:like href="<?=$this->url("promos/view/$promo->_id", array('absolute'=>true));?>" send="false" layout="button_count" width="70" show_faces="false" font=""></fb:like></div>
                            </div>
                            <a href="<?=$this->url("promos/view/$promo->_id");?>"><h3><?=$promo->title;?></h3></a>
                            <div class="thumbnail promoThumb">
                                <a href="#">
                                    <img width="70px" height="60px" title="<?=$promo->title;?>"
                                         src="<?=$promo->logo.'png';?>">
                                </a>
                            </div>
                            <div class="description marginLeftInfo narrowLineHeight justify">
                                <div class="whenHappening">Posted on <time datetime="<?=$promo->created->sec;?>"
                                        title="<?=date('l, d-M-Y h:i:s', $promo->created->sec)?>" class="daiTipsy">
                                    <?=$this->daiHtml->timeAgo($promo->created->sec);?></time>
                                </div>
                                <p><?=$promo->description;?></p>
                            </div>
                            <div class="linkPanel marginLeftInfo">
                                <span title="Place rating" class="rating" style="display: inline-block;">
                                    <!-- Promo Rating -->
                                    <?php echo $this->view()->render(
                                        array( 'element' => 'singleRating' ),
                                        array( 'rating' => @$promo->rating, 'identifier'=>'-promo'.$counter )
                                    );?>
                                </span>
                                <a href="<?=$this->url("promos/view/$promo->_id#reviewForm");?>">write review</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="clear"></div>
                    <div style="margin-top: 10px;">
                        <p><a href="<?=$this->url("promos/place/$place->_id");?>">See all promos in this place</a></p>
                        <p>Find any updates? <a href="<?=$this->url("promos/add/$place->_id");?>">Submit a promo from</a> this place.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="favoriteMenuPictures">
                    <h3>No Promotions yet</h3>
                    <p>Find any updates? <a href="<?=$this->url("promos/add/$place->_id");?>">Submit a promo from</a> this place.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="clear"></div>
    <div class="reviewBox">
        <h2>Reviews by foodies</h2>
        <?php if($reviews->count() > 0): ?>
            <?php $counter = 0; ?>
            <?php foreach($reviews as $review): ?>
                <?php if($counter++ > 3) break; ?>
                <!-- REVIEW ITEM -->
                <?php echo $this->view()->render(
                    array(
                        'element' => 'review'
                    ),
                    array(
                        'place' => $place,
                        'review' => $review,
                        'web_user' => $web_user,
                    )
                );?>
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
            'reviews' => $reviews,
            'web_user' => $web_user,
            'ratingFor' => 'places'
        )
    );?>
    <div class="clear"></div>
</div>