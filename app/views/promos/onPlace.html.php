<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

//    $this->title($title);
//
//    $this->set(array(
//        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $subHeader,
//        'navbar' => array('element' => 'navbar'),
//        'footer' => '',
//    ));
    $this->title("Promotions on $place->name");
?>

<!-- PLACE GENERAL INFO -->
<?php echo $this->view()->render(
    array(
        'element' => 'places/generalInfo'
    ),
    array(
        'place' => $place,
    )
);?>

<div>
    <div class="placePromos">
        <h2>Promotions on <a href="<?=$this->url("places/view/$place->_id ");?>"><?=$place->name;?></a></h2>
        <?php if($promos->count()>0): ?>
            <?php $counter = 0; ?>
            <?php foreach($promos as $promo): ?>
                <?php if($counter++ > 10) break; ?>
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
                                    array( 'rating' => @$promo->rating, 'identifier'=>'-promo'.rand() )
                                );?>
                            </span>
                            <a href="<?=$this->url("promos/view/$promo->_id#reviewForm");?>">write review</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="clear"></div>
                <div style="margin-top: 10px;">
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