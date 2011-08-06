<?php $this->title($title);?>

<h1>Select Place</h1>

<div class="notification" style="font-size:1.1em;">
    <p>First, you have to choose the place where you found the new dish or promo.</p>
</div>
<div class="headerBorderBottom">
<!--    <div class="advanceSearch">-->
<!--        <div class="filterBox">-->
            <h3>Search places by criteria</h3>
            <form id="filterSearch" action="<?=$this->url("places/selectPlace/$redirect")?>">
                <label style="display: inline-block;">Insert your keyword</label>
                <input name="keyword" placeholder="keyword" type="text" class="inputBox" style="display: inline-block;"/>
                <label style="display: inline-block;">near</label>
                <input name="location" placeholder="city" type="text" class="inputBox" style="display: inline-block;" />
                <input type="submit" name="submit" value="search" class="button"/>
            </form>
<!--        </div>-->
<!--    </div>-->
</div>
<div class="searchResults">
    <h2>Places found</h2>
    <?php if($criteria != null): ?>
        <p>Search criteria : <b><?=$criteria['keyword']?></b> near <b><?=$criteria['location']?></b></p>
    <?php endif; ?>

    <div id="resultContainer">
        <?php $counter = 0; ?>
        <?php foreach($places as $place): ?>
            <?php $counter++;?>
            <div class="placeItem">
                <div class="briefInfo">
                    <a href="<?=$this->url("places/view/$place->_id");?>"><h3><?=$place->name;?></h3></a>
                    <div class="thumbnail placeThumb">
                        <a href="<?=$this->url("places/view/$place->_id");?>">
                            <img width="70px" height="60px" title="<?=$place->name;?>"
                                 src="../dishes">
                        </a>
                    </div>
                    <div class="description marginLeftInfo narrowLineHeight">
                        <div class="rightFloatBox">
                            <a href="<?=$this->url("$redirect/add/$place->_id");?>"
                               style="text-align: center;" class="button">Select</a>
                        </div>
                        <div class="addressInfo">
                            <p><?=$place->address->street;?></p>
                            <p><?=$place->address->city;?></p>
                        </div>
                        <p style="font-style:italic;"><?=$place->description;?></p>
                    </div>
                    <div class="linkPanel marginLeftInfo">
                        <span title="Place rating" class="rating" style="display: inline-block;">
                            <!-- Promo Rating -->
                            <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$promo->rating , 'identifier'=>$counter )
                            );?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <div class="notification" style="font-size:1.1em;">
        <p>Dont find your desired places? Submit your new place <?=$this->html->link('here','places/add');?></p>
    </div>
</div>