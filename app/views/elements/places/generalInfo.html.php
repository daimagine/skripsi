<div id="generalInfo">
    <div class="insideInfo">
        <div class="bigThumbnail borderedBox placeThumb">
            <img width="120px" height="120px" title="Place thumbnail"
                 src="<?php
                        if(isset($place->mainPicture) && $place->mainPicture!==''){
                            echo $this->daiHtml->getImgUploadUrl($place->mainPicture);
                         }
                     ?>">
        </div>
        <div class="bigRightBriefInfo floatLeft">
            <h1 class="noMarginTop"><a href="<?=$this->url("places/view/$place->_id ");?>"><?=$place->name;?></a></h1>
            <div class="placeInfo">
                <p class="placeDescription"><?=$place->desciption;?></p>
                <?php if($place->lowestPrice && $place->higestPrice) :?>
                    <div class="bigPrice">
                        <p>Price : <?=$place->lowestPrice;?>-<?=$place->highestPrice;?> Rupiah</p>
                    </div>
                <?php endif;?>
                <div class="rightFloatBox">
                    <div class="g-plusone" data-size="medium" data-count="true"></div>
                    <div><fb:like send="false" layout="button_count" width="70" show_faces="false" font=""></fb:like></div>
                </div>
                <div class="addressInfo">
                    <p><?=$place->address->street;?></p>
                    <p><?=$place->address->city;?></p>
                </div>
                <p><?=$place->telp;?></p>
                <p><?=$place->website;?></p>
             </div>
        </div>
    </div>
    <div class="clear"></div>
    <div class="miniPanel">
        <div class="rating rightMarginTen floatLeft">
            <!-- Place Rating -->
            <?php echo $this->view()->render(
                array( 'element' => 'singleRating' ),
                array( 'rating' => @$place->rating )
            );?>
        </div>
<!--            <div class="placeTags">-->
<!--                <ul class="niceTag">-->
<!--                    <li>tag</li>-->
<!--                    <li>tag</li>-->
<!--                </ul>-->
<!--        </div>-->
        <div class="otherInfo">
            <p>First submitted by <a href="<?=$this->url("users/view/".$place->contributor->id)?>"><?=$place->contributor->name?></a>
                on <time datetime="<?=$place->created->sec;?>"
                    title="<?=date('l, d-M-Y h:i:s', $place->created->sec)?>" class="daiTipsy">
                <?=$this->daiHtml->timeAgo($place->created->sec);?></time>
            </p>
        </div>
    </div>
</div>