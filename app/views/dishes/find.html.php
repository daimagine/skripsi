<?php
    use \app\models\Dishes;
    $this->title($title);
?>

<h1>Dishes</h1>
<div class="headerBorderBottom">
    <fieldset>
        <div class="advanceSearch">
            <div id="searchCounter">
                <h3>Dishes spotted so far</h3>
                <p class="counterResult"><?=$dishes->count()?></p>
            </div>
        </div>
        <div style="display:inline-block;">
            <div class="filterBox">
                <h3>Search dish by criteria</h3>
                <form id="filterSearch" action="<?=$this->url('dishes/find')?>">
                    <label style="display:block;">Insert your keyword</label>
                    <input name="keyword" placeholder="keyword" type="text" class="inputBox"
                           style="display:inline-block;"/>&nbsp;&nbsp;
                    <?=$this->form->field('category', array(
                           'label'=>'Price Category',
                           'class'=>'inputBox',
                           'type'=>'select',
                           'list'=>array('all'=>'All')+\app\models\Dishes::$categories['prices'],
                      ));?>
                    <input type="submit" name="submit" value="search" class="button"/>
                </form>
            </div>
        </div>
    </fieldset>
</div>
<div class="searchResults">
    <h2>Dishes found</h2>
    <?php if(isset($criteria['keyword'])): ?>
        <p>Search criteria : <b><?=$criteria['keyword']?></b></p>
    <?php endif; ?>
    <?php if($criteria['category'] != '' && $criteria['category'] != 'all'): ?>
        <p>Price criteria : <b><?=Dishes::$categories['prices'][$criteria['category']]?></b></p>
    <?php endif; ?>
    <div class="notification" style="font-size:1.1em;">
        <p>Dont find your desired dishes? Submit your new dish <?=$this->html->link('here','dishes/add');?></p>
    </div>
    <div id="resultContainer">
        <?php $counter = 0; ?>
        <?php foreach($dishes as $dish): ?>
            <?php if($counter++ > 10) break;?>
            <div class="placeItem">
                <div class="briefInfo">
                    <div class="rightFloatBox">
                        <div href="<?=$this->url("dishes/view/$dish->_id", array('absolute'=>true));?>"
                             class="g-plusone" data-size="medium" data-count="true"></div>
                        <div><fb:like send="false" layout="button_count" width="70" show_faces="false"
                             href="<?=$this->url("dishes/view/$dish->_id", array('absolute'=>true));?>"></fb:like></div>
                    </div>
                    <a href="<?=$this->url("dishes/view/$dish->_id");?>"><h3><?=$dish->name;?></h3></a>
                    <div class="thumbnail dishThumb">
                        <a href="<?=$this->url("dishes/view/$dish->_id");?>">
                            <img width="70px" height="60px" title="<?=$dish->name;?>"
                                 src="">
                        </a>
                    </div>
                    <div class="description marginLeftInfo narrowLineHeight">
                        <p>@<b><a href="<?=$this->url("places/view/$dish->placeId");?>">
                            <?=$dish->placeName;?></a></b></p>
                        <?php if(isset($dish->price)): ?>
                            <p>Rp. <?=$dish->price;?></p>
                        <?php endif; ?>
                        <p style="font-style:italic;"><?=$dish->description;?></p>
                    </div>
                    <div class="linkPanel marginLeftInfo">
                        <span title="Place rating" class="rating" style="display: inline-block;">
                            <!-- Dish Rating -->
                            <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$dish->rating , 'identifier'=>$counter )
                            );?>
                        </span>
                        <a href="<?=$this->url("dishes/view/$dish->_id#reviewForm");?>">write review</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php if($dishes->count() > 0): ?>
        <div class="notification" style="font-size:1.1em;">
            <p>Dont find your desired dishes? Submit your new dish <?=$this->html->link('here','dishes/add');?></p>
        </div>
    <?php endif; ?>
</div>
