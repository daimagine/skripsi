<?php $this->title($title);?>

<h1>Promotions</h1>
<div class="headerBorderBottom">
    <fieldset>
        <div class="advanceSearch">
            <div id="searchCounter">
                <h3>Promos spotted so far</h3>
                <p class="counterResult"><?=$promos->count()?></p>
            </div>
            <div class="filterBox"></div>
        </div>
        <div>
            <h3>Search promos by criteria</h3>
            <form id="filterSearch" action="<?=$this->url('promos/find')?>">
                <label style="display:block;">Insert your keyword</label>
                <input name="keyword" placeholder="keyword" type="text" class="inputBox"
                               style="display:inline-block;"/>&nbsp;&nbsp;
                <input type="submit" name="submit" value="search" class="button"/>
            </form>
        </div>
    </fieldset>
</div>
<div class="searchResults">
    <h2>Promos found</h2>
    <?php if($criteria != null): ?>
        <p>Search criteria : <b><?=$criteria['keyword']?></b></p>
    <?php endif; ?>
    <div class="notification" style="font-size:1.1em;">
        <p>Dont find your desired promotions? Submit your new promotion <?=$this->html->link('here','promos/add');?></p>
    </div>
    <div id="resultContainer">
        <?php $counter = 0; ?>
        <?php foreach($promos as $promo): ?>
            <?php if($counter++ > 10) break;?>
            <div class="placeItem">
                <div class="briefInfo">
                    <div class="rightFloatBox">
                        <div href="<?=$this->url("promos/view/$promo->_id", array('absolute'=>true));?>"
                             class="g-plusone" data-size="medium" data-count="true"></div>
                        <div><fb:like send="false" layout="button_count" width="70" show_faces="false"
                             href="<?=$this->url("promos/view/$promo->_id", array('absolute'=>true));?>"></fb:like></div>
                    </div>
                    <a href="<?=$this->url("promos/view/$promo->_id");?>"><h3><?=$promo->title;?></h3></a>
                    <div class="thumbnail promoThumb">
                        <a href="<?=$this->url("promos/view/$promo->_id");?>">
                            <img width="70px" height="60px" title="<?=$promo->name;?>"
                                 src="">
                        </a>
                    </div>
                    <div class="description marginLeftInfo narrowLineHeight">
                        <p>@<b><a href="<?=$this->url("places/view/$promo->placeId");?>">
                            <?=$promo->placeName;?></a></b></p>
                        <p style="font-style:italic;"><?=$promo->description;?></p>
                    </div>
                    <div class="linkPanel marginLeftInfo">
                        <span title="Place rating" class="rating" style="display: inline-block;">
                            <!-- Promo Rating -->
                            <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$promo->rating , 'identifier'=>$counter )
                            );?>
                        </span>
                        <a href="<?=$this->url("promos/view/$promo->_id#reviewForm");?>">write review</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php if($promos->count() > 0): ?>
        <div class="notification" style="font-size:1.1em;">
            <p>Dont find your desired promotions? Submit your new promotion <?=$this->html->link('here','promos/add');?></p>
        </div>
    <?php endif; ?>
</div>
