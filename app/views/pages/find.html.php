<?php $this->title($title);?>

<h1>Search Places and Dishes</h1>
<div class="headerBorderBottom">
    <fieldset>
        <div style="display:inline-block;">
            <div class="filterBox">
                <h3>Search by criteria</h3>
                <form id="filterSearch" action="<?=$this->url('places/find')?>">
                    <div>
                        <label>Insert your keyword</label>
                        <input name="keyword" placeholder="keyword" type="text" class="inputBox" />
                    </div>
                    <div>
                        <label>near</label>
                        <input name="location" placeholder="city" type="text" class="inputBox" />
                    </div>
                    <input type="submit" name="submit" value="search" class="button"/>
                </form>
            </div>
        </div>
    </fieldset>
</div>

<div class="searchResults">
    <h2>Places and Dishes found</h2>
    <?php if(isset($criteria['keyword']) && isset($criteria['location'])): ?>
        <p>Search criteria : <b><?=$criteria['keyword']?></b> near <b><?=$criteria['location']?></b></p>
    <?php endif; ?>
    
    <div class="notification" style="font-size:1.1em;">
        <p>Submit new place <?=$this->html->link('here','places/add');?>
            or submit new dish <?=$this->html->link('here','dishes/add');?></p>
    </div>
    <div id="resultContainer">
        <?php foreach($packed as $item): ?>
            <?php if(isset($item['placeId'])): ?>
                <div class="placeItem">
                    <div class="briefInfo">
                        <div class="rightFloatBox">
                            <div href="<?=$this->url("dishes/view/".$item['_id'], array('absolute'=>true));?>"
                                 class="g-plusone" data-size="medium" data-count="true"></div>
                            <div><fb:like send="false" layout="button_count" width="70" show_faces="false"
                                 href="<?=$this->url("dishes/view/".$item['_id'], array('absolute'=>true));?>"></fb:like></div>
                        </div>
                        <a href="<?=$this->url("dishes/view/".$item['_id']);?>"><h3><?=$item['name'];?></h3></a>
                        <div class="thumbnail dishThumb">
                            <a href="<?=$this->url("dishes/view/".$item['_id']);?>">
                                <img width="70px" height="60px" title="<?=$item['name'];?>"
                                     src="">
                            </a>
                        </div>
                        <div class="description marginLeftInfo narrowLineHeight">
                            <p>@<b><a href="<?=$this->url("places/view/".$item['placeId']);?>">
                                <?=$item['placeName'];?></a></b></p>
                            <?php if(isset($item['price'])): ?>
                                <p>Rp. <?=$item['price'];?></p>
                            <?php endif; ?>
                            <p style="font-style:italic;"><?=$item['description'];?></p>
                        </div>
                        <div class="linkPanel marginLeftInfo">
                            <span title="Place rating" class="rating" style="display: inline-block;">
                                <!-- Dish Rating -->
                                <?php echo $this->view()->render(
                                    array( 'element' => 'singleRating' ),
                                    array( 'rating' => @$item['rating'] , 'identifier'=>'dish-'.rand() )
                                );?>
                            </span>
                            <a href="<?=$this->url("dishes/view/".$item['_id']."#reviewForm");?>">write review</a>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="placeItem">
                    <div class="briefInfo">
                        <div class="rightFloatBox">
                            <div href="<?=$this->url("places/view/".$item['_id'], array('absolute'=>true));?>"
                                 class="g-plusone" data-size="medium" data-count="true"></div>
                            <div><fb:like send="false" layout="button_count" width="70" show_faces="false"
                                 href="<?=$this->url("places/view/".$item['_id'], array('absolute'=>true));?>"></fb:like></div>
                        </div>
                        <div>
                            <h4><?=@\app\models\Places::$categories[$item['category']];?></h4>
                        </div>
                        <div class="thumbnail placeThumb">
                            <a href="<?=$this->url("places/view/".$item['_id']);?>">
                                <img width="70px" height="60px" title="<?=$item['name'];?>"
                                     src="<?php
                                            if(isset($item['mainPicture']) && $item['mainPicture']!==''){
                                                echo $this->daiHtml->getImgUploadUrl($item['mainPicture']);
                                             }
                                         ?>">
                            </a>
                        </div>
                        <div class="description marginLeftInfo narrowLineHeight">
                            <a href="<?=$this->url("places/view/".$item['_id']);?>"><h3><?=$item['name'];?></h3></a>
                            <div class="addressInfo">
                                <p><?=$item['address']['street'];?></p>
                                <p><?=$item['address']['city'];?></p>
                            </div>
                            <p style="font-style:italic;"><?=$item['description'];?></p>
                        </div>
                        <div class="linkPanel marginLeftInfo">
                            <span title="Place rating" class="rating" style="display: inline-block;">
                                <!-- Place Rating -->
                                <?php echo $this->view()->render(
                                    array( 'element' => 'singleRating' ),
                                    array( 'rating' => @$item['rating'] , 'identifier'=>'place-'.rand() )
                                );?>
                            </span>
                            <a href="<?=$this->url("places/view/".$item['_id']."#reviewForm");?>">write review</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;?>
    </div>
    <?php if($places->count() > 0): ?>
        <div class="notification" style="font-size:1.1em;">
            <p>Submit new place <?=$this->html->link('here','places/add');?>
            or submit new dish <?=$this->html->link('here','dishes/add');?></p>
        </div>
    <?php endif; ?>
</div>
