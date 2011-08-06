<?php $this->title($title);?>

<h1>Places</h1>
<div class="headerBorderBottom">
    <fieldset>
        <div class="filterBox">
            <div class="advanceSearch">
                <div id="searchCounter">
                    <h3>Places spotted so far</h3>
                    <p class="counterResult"><?=$places->count()?></p>
                </div>
            </div>
            <div style="display:inline-block;">
                <h3 style="margin-top:0;">Search places by criteria</h3>
                <form id="filterSearch" action="<?=$this->url('places/find')?>">
                    <div style="display:inline-block; float: left; margin-right: 8px;">
                        <label>Insert your keyword</label>
                        <input name="keyword" placeholder="keyword" type="text" class="inputBox" />
                    </div>
                    <div style="display:inline-block;">
                        <label>near</label>
                        <input name="location" placeholder="city" type="text" class="inputBox" />
                    </div>
                    <?=$this->form->field('category', array(
                           'class'=>'inputBox',
                           'type'=>'select',
                           'list'=>array('all'=>'All')+\app\models\Places::$categories,
                      ));?>
                    <input type="submit" name="submit" value="search" class="button"/>
                </form>
            </div>
        </div>
    </fieldset>
</div>
<div class="searchResults">
    <h2>Places found</h2>
    <?php if(isset($criteria['keyword']) && isset($criteria['location'])): ?>
        <p>Search criteria : <b><?=$criteria['keyword']?></b> near <b><?=$criteria['location']?></b></p>
    <?php endif; ?>
    <?php if(isset($criteria['category'])): ?>
        <p>Search category : <b><?=(@\app\models\Places::$categories[$criteria['category']])?:'All';?></b></p>
    <?php endif; ?>

    <div class="notification" style="font-size:1.1em;">
        <p>Dont find your desired places? Submit your new place <?=$this->html->link('here','places/add');?></p>
    </div>
    <div id="resultContainer">
        <?php $counter = 0; ?>
        <?php foreach($places as $place): ?>
            <?php if($counter++ > 10) break;?>
            <div class="placeItem">
                <div class="briefInfo">
                    <div class="rightFloatBox">
                        <div href="<?=$this->url("places/view/$place->_id", array('absolute'=>true));?>"
                             class="g-plusone" data-size="medium" data-count="true"></div>
                        <div><fb:like send="false" layout="button_count" width="70" show_faces="false"
                             href="<?=$this->url("places/view/$place->_id", array('absolute'=>true));?>"></fb:like></div>
                    </div>
                    <div>
                        <h4><?=@\app\models\Places::$categories[$place->category];?></h4>
                    </div>
                    <div class="thumbnail placeThumb">
                        <a href="<?=$this->url("places/view/$place->_id");?>">
                            <img width="70px" height="60px" title="<?=$place->name;?>"
                                 src="<?php
                                        if(isset($place->mainPicture) && $place->mainPicture!==''){
                                            echo $this->daiHtml->getImgUploadUrl($place->mainPicture);
                                         }
                                     ?>">
                        </a>
                    </div>
                    <div class="description marginLeftInfo narrowLineHeight">
                        <a href="<?=$this->url("places/view/$place->_id");?>"><h3><?=$place->name;?></h3></a>
                        <div class="addressInfo">
                            <p><?=$place->address->street;?></p>
                            <p><?=$place->address->city;?></p>
                        </div>
                        <p style="font-style:italic;"><?=$place->description;?></p>
                    </div>
                    <div class="linkPanel marginLeftInfo">
                        <span title="Place rating" class="rating" style="display: inline-block;">
                            <!-- Place Rating -->
                            <?php echo $this->view()->render(
                                array( 'element' => 'singleRating' ),
                                array( 'rating' => @$place->rating , 'identifier'=>$counter )
                            );?>
                        </span>
                        <a href="<?=$this->url("places/view/$place->_id#reviewForm");?>">write review</a>
                    </div>
                </div>
            </div>
        <?php endforeach;?>
    </div>
    <?php if($places->count() > 0): ?>
        <div class="notification" style="font-size:1.1em;">
            <p>Dont find your desired places? Submit your new place <?=$this->html->link('here','places/add');?></p>
        </div>
    <?php endif; ?>
</div>
