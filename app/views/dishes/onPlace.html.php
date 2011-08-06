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
    $this->title("Dishes on $place->name");
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
    <div class="favoriteMenus">
    <h2>Dishes on <a href="<?=$this->url("places/view/$place->_id ");?>"><?=$place->name;?></a></h2>
    <div class="favoriteMenuPictures">
        <?php $counter = 0; ?>
        <?php if($dishes->count()>0): ?>
            <?php if($counter++ > 3) break; ?>
            <div class="thumbnailContainer">
                <?php foreach($dishes as $dish): ?>
                    <div class="bigThumbnail bigThumbnailWithDesc borderedBox floatLeft marginTopTen">
                        <div class="dishPictureSmall dishThumb">
                            <img width="120px" height="120px" title="Place thumbnail"
                                 src="">
                        </div>
                        <a href="<?=$this->url("dishes/view/$dish->_id");?>">
                            <p class="title"><?=$dish->name;?></p>
                            <p><?=$dish->price;?> Rupiah</p>
                        </a>
                        <p style="display: inline-block;"><!-- Dish Rating -->
                            <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$dish->rating, 'identifier'=>'-dish'.rand() )
                            );?></p>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="clear"></div>
            <div style="margin-top: 10px;">
                <p>Find any updates? <a href="<?=$this->url("dishes/add/$place->_id");?>">Submit a dish from</a> this place.</p>
            </div>
        <?php else: ?>
            <h3>No submitted dishes on this place yet.</h3>
            <p>Find any updates? <a href="<?=$this->url("dishes/add/$place->_id");?>">Submit a dish from</a> this place.</p>
        <?php endif; ?>
    </div>
</div>
</div>