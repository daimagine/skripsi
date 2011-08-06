<?php
    $this->title($dish->name);
?>

<div id="overviewInfoPlace">
    <div id="generalInfo">
        <div class="insideInfo">
            <div class="hugeThumbnail borderedBox dishThumb">
                <img title="Dish thumbnail"
                     src="">
            </div>
            <div class="rightBriefInfo floatLeft">
                <h1 class="noMarginTop"><?=$dish->name;?></h1>
                <div class="dishInfo">
                    <p class="placeDescription"><?=$dish->description;?></p>
                    <div class="bigPrice">
                        <p>Price : <span>Rp. <?=$dish->price;?></span></p>
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
                <div class="dishTagTop">
                    <h3>Food Categories</h3>
                    <?php if($dish->checkAttrib('tags')):?>
                        <ul class="niceTag">
                            <?php foreach ($dish->tags as $tag): ?>
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
                 <!-- Dish Rating -->
                <?php echo $this->view()->render(
                    array( 'element' => 'singleRating' ),
                    array( 'rating' => @$dish->rating )
                );?>
            </div>
            <div class="otherInfo">
                <p>First submitted by <a href="<?=$this->url("users/view/".$dish->contributor->id)?>"><?=$dish->contributor->name?></a>
                    on <time datetime="<?=$dish->created->sec;?>"><?=date('d F Y', $dish->created->sec);?></time></p>
            </div>
            <div class="floatRight">
                <a href="<?=$this->url("dishes/place/$place->_id");?>">Another Dish in Place</a>
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
                <!-- REVIEW ITEM -->
                <?php echo $this->view()->render(
                    array(
                        'element' => 'review'
                    ),
                    array(
                        'place' => $place,
                        'dish' => $dish,
                        'review' => $review,
                        'reviewFor' => 'dishes',
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
            'dish' => $dish,
            'reviews' => $reviews,
            'web_user' => $web_user,
            'ratingFor' => 'dishes'
        )
    );?>
    <div class="clear"></div>
</div>

    <?//=$this->html->image("/dishes/view/{$dish->_id}.jpg", array('alt' => $dish->name)); ?>

<!--    <div class="lithium-stack-trace">-->
<!--        <pre>-->
<!--        --><?//=print_r($dish);?>
<!--        </pre>-->
<!--    <div class="lithium-stack-trace">-->
