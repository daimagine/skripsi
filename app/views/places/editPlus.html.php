<?php
    $this->title($title);
?>

<script src="<?=$this->path('js/addLocation.js');?>" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/validationEngine.jquery.css');?>" type="text/css"/>
<link rel="stylesheet" href="<?=$this->path('js/jquery/jQuery-Validation-Engine/css/template.css');?>" type="text/css"/>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js');?>" type="text/javascript" charset="utf-8"></script>
<script src="<?=$this->path('js/jquery/jQuery-Validation-Engine/js/jquery.validationEngine.js');?>" type="text/javascript" charset="utf-8"></script>

<script src="<?=$this->path('js/jquery/tag-it/js/tag-it.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/tag-it/css/jquery.tagit.css')?>" />

<script type="text/javascript">
    $(function(){
        $('#PlaceTags').tagit({
            allowSpaces: true,
            maxlengthTag:25,
            placeholder:'keyword tags separated by comma. Example: steak house,chinese food',
            daiSize:'long'
        });
    });

    var nextstep = true;
    $(document).ready(function(){
        $("#addPlaceForm").validationEngine({});

        $('#addPlaceForm').submit(function(){
            $(':submit', this).click(function() {
                return false;
            });
        });
    });
    function next(nextId, prevId, opt) {
        var $inputs = $(prevId+' :input');
        var valid = true;
        var error = '';
        $inputs.each(function() {
            if ($(this).hasClass('isi') && opt!='back') {
                insidevalid = $("#addPlaceForm").validationEngine('validateField', '#' + this.id);
                if(insidevalid === true) {
                    valid = false;
                }
//                alert($("#addPlaceForm").validationEngine('validateField', '#'+this.id)+'#' + this.id);
            }
        });
        if(valid) {
            $("#addPlaceForm").validationEngine('hideAll');
            if(nextId == 'submit') {
                $("#addPlaceForm").submit();
            } else {
                $(prevId).toggle();
                $(nextId).toggle();
            }
        } else {
//            message = "You have to fill these field before going to next step : "+$(error).attr('id');
//            $('.error').html(message);
        }
        return false;
    }

    function setImage(file) {
        var message;
        var fileTypes=["bmp","gif","png","jpg","jpeg"];
        var uploadedfile = file.files.item(0);
        clearImgUploader();
        if(document.all) {
            $('#prevImage').attr('src',file.value);
            //TODO i dont know why should check for document.all
            //for a while i assume we cannot do this stuff in any browser except FF
        } else {
            extvalid = false;
            ext = uploadedfile.name.substring(uploadedfile.name.lastIndexOf(".") + 1,
                    uploadedfile.name.length).toLowerCase();
            if(ext !== '') {
                for (var i=0; i<fileTypes.length; i++) {
                    if (fileTypes[i] == ext) {
                        extvalid = true;
                        break;
                    }
                }
            }
            console.debug(uploadedfile.name, ext, extvalid);
            if(extvalid == false) {
                message = 'Image is not valid. Please select an image with an extension of one of the following: '+fileTypes.join(", ");
                clearImgUploader();
                $('#myImage').val('');
                $('#errorImage').html(message);
                console.debug(message);
            }
            else if(uploadedfile.size > 5242880) {
                message = 'Your selected picture is too large. Maximum file size is 5MB';
                clearImgUploader();
                $('#myImage').val('');
                $('#errorImage').html(message);
                console.debug(message);
            } // max size 5MB
            else {
                $('#prevImage').attr('src', uploadedfile.getAsDataURL());
                console.debug('success');
                $('#prevImage').css('display','block');
            }
        }
    }
    function clearImgUploader(){
        $('#prevImage').val('src','');
        $('#prevImage').css('display','none');
        $('#errorImage').html('');
    }
</script>

<h1>Submit New Place</h1>
<?=$this->form->create($place, array('type'=>'file', 'id'=>'addPlaceForm'));?>
    <div id="BriefInfo">
        <div class="notification">
            <p>When you find some new places, you can always visit this page to share the place with us.</p>
            <p>All you have to do is just follow step by step instruction and fill the form.</p>
        </div>
        <fieldset>
            <legend>Step 1 &raquo; Brief Information About Place</legend>
            <div class="error" style="margin-bottom:10px;"></div>
            <?=$this->form->field('name', array(
                'label'=>'Place name *',
                'class'=>'validate[required] inputBox longInput isi',
                'placeholder'=>'fill your spotted place name',
                'style'=>'font-size:12px;'
              ));?>
            <?=$this->form->field('description', array(
                'label'=>'Some short description about this place',
                'class'=>'inputBox longInput',
                'type'=>'textarea',
                'placeholder'=>'fill some description about this place'
              ));?>
            <?=$this->form->field('category', array(
                'label'=>'Select place category',
                'class'=>'inputBox smallInput',
                'type'=>'select',
                'list'=>\app\models\Places::$categories,
                'placeholder'=>'fill some description about this place'
              ));?>
            <?=$this->form->field('tags', array(
                'label'=>'Insert tags',
                'id'=>'PlaceTags',
                'placeholder'=>'keyword tags separated by comma. Example: steak house,chinese food',
                'class'=>'inputBox mediumInput daiInputTipsy'
              ));?>
            <hr class="hrGray">
            <fieldset>
                <legend>Select Main Picture</legend>
                <?=$this->form->field('mainPicture', array(
                    'label' => '',
                    'id' => 'myImage',
                    'class' => '',
                    'type' => 'file',
                    'onchange'=>'setImage(this); return false;'
                 ));?>
                <fieldset>
                    <h3 style="margin-bottom:3px;margin-top:3px;">Preview :</h3>
                    <img id="prevImage" style="display:none;"/>
                    <p style="font-size:0.75em;font-style:italic;">**firefox 3.6+ only</p>
                    <div id="errorImage"></div>
                    <style type="text/css">
                        #prevImage {
                            max-width: 300px;
                            max-height: 200px;
                            border: 1px solid #FFF;
                        }
                    </style>
                </fieldset>
            </fieldset>
        </fieldset>
        <fieldset>
            <a href="#next" onclick="next('#AdditionalInfo', '#BriefInfo');" class="linkbutton floatRight">Next</a>
        </fieldset>
    </div>
    <div id="AdditionalInfo" style="display: none;">
        <fieldset>
            <legend>Step 2 &raquo; Additional Info</legend>
            <?=$this->form->field('telp', array(
                'label'=>'Insert place telephone',
                'class'=>'inputBox longInput',
                'placeholder'=>"fill place's contact number if you know"
              ));?>
            <?=$this->form->field('website', array(
                'label'=>'Insert place website',
                'class'=>'inputBox longInput',
                'placeholder'=>"fill place's website if you know"
              ));?>
            <fieldset>
                <legend>Dishes Price Range</legend>
                <?=$this->form->field('lowestPrice', array(
                      'label'=>'Insert place lowest dishes price',
                      'class'=>'validate[custom[number],min[0],lessThan[PlacesHighestPrice]] inputBox mediumInput isi',
                      'placeholder'=>"fill place's lowest dishes price if you know"
                 ));?>
                <?=$this->form->field('highestPrice', array(
                       'label'=>'Insert place highest dishes price',
                       'class'=>'validate[custom[number],min[0],biggerThan[PlacesLowestPrice]] inputBox mediumInput isi',
                       'placeholder'=>"fill place's highest dishes price if you know"
                  ));?>
            </fieldset>
        </fieldset>
        <fieldset>
            <a href="#next" onclick="next('#BriefInfo', '#AdditionalInfo', 'back');" class="linkbutton">Back</a>
            <a href="#next" onclick="next('#Locations', '#AdditionalInfo');" class="linkbutton floatRight">Next</a>
        </fieldset>
    </div>
    <div id="Locations" style="display: none;">
        <fieldset>
            <legend>Step 3 &raquo; Set Location</legend>
            <div class="notification">
                <p>Fill street and city information and then press Locate button.</p>
                <p>You will see some suggestion and a marker on the map.</p>
                <p>Drag the marker to the correct place location and system will acquire the position and full address.</p>
                <p>If you've done, press Next button to the next step.</p>
            </div>
            <?=$this->form->field('address.street', array(
                'label'=>'Insert address or street name where you spotted this place *',
                'class'=>'validate[required] inputBox longInput isi',
                'placeholder'=>'fill address where you spotted this place',
                'onkeypress'=>"if(event.keyCode == 13) { geocode(); return false; }",
              ));?>
            <?=$this->form->field('address.city', array(
                'label'=>'Insert place city *',
                'class'=>'validate[required] inputBox longInput isi',
                'placeholder'=>'fill the city name where you spotted this place',
                'onkeypress'=>"if(event.keyCode == 13) { geocode(); return false; }",
              ));?>
            <?=$this->form->field('address.additional', array(
                'label'=>'Fill some additional address informations',
                'class'=>'inputBox longInput',
                'placeholder'=>'example : Across the street, between Masjid Arridwan and School',
                'type'=>"textarea",
              ));?>
            <?=$this->form->field('address.location.lat', array(
                'type'=>'hidden',
              ));?>
            <?=$this->form->field('address.location.lng', array(
                'type'=>'hidden',
              ));?>
            <a href="#next" onclick="geocode();" class="linkbutton">
                <img width="16" height="16" src="<?=$this->path('img/icon/16/map.png');?>" style="padding-bottom:0;
                    vertical-align: middle; margin-right: 4px;">Locate
            </a>
            <div id="responseInfo">
              <div id="responseCount">
                <span id="matchCount" style="font-weight: bold;"></span>
                <div id="matches"></div>
              </div>
              <div id="message"></div>
            </div>
            <div>
                <div>
                    <p>Current closest matching address : <span id="currentaddress"></span></p>
                    <p>
                        Geometry Location :
                        <span id="latitude"></span>,&nbsp;
                        <span id="longitude"></span>
                    </p>
                </div>
            </div>
            <div class="notification">
                <p>If map below is not rendered properly, please press Refresh Map button.</p>
                <a href="#next" onclick="google.maps.event.trigger(map, 'resize');"
                   style="width:100px;" class="linkbutton">
                    <img width="16" height="16" src="<?=$this->path('img/icon/16/arrow_refresh_small.png');?>" style="padding-bottom:0;
                            vertical-align: middle; margin-right: 4px;">Refresh Map
                </a>
            </div>
            <div>
                <div id="map"  style="clear:both;width:600px;height:470px;margin:10px auto;padding:0;"></div>
            </div>
        </fieldset>
        <fieldset>
            <a href="#next" onclick="next('#AdditionalInfo', '#Locations', 'back');" class="linkbutton">Back</a>
            <a href="#next" onclick="next('#BuildingLocation', '#Locations');" class="linkbutton floatRight">Next</a>
        </fieldset>
    </div>
    <div id="BuildingLocation" style="display: none;">
        <fieldset>
            <legend>Step 4 &raquo; Set building info where you spotted this place</legend>
            <div class="notification">
                <p>If the place is not locate on a building, then you can skip this phase and submit the form.</p>
            </div>
            <div>
                <?=$this->form->field('address.building.name', array(
                    'label'=>'Is this place located on a building? Left it blank if its not.',
                    'class'=>'inputBox longInput',
                    'placeholder'=>'Building name where place located'
                  ));?>
                <?=$this->form->field('address.building.floor', array(
                    'label'=>'Which floor does this place located on the building',
                    'class'=>'inputBox longInput',
                    'placeholder'=>'Building floor number'
                  ));?>
                <?=$this->form->field('address.building.unit', array(
                    'label'=>'Unit number of place in the building',
                    'class'=>'inputBox longInput',
                    'placeholder'=>'Place unit number'
                  ));?>
            </div>
        </fieldset>
        <?//=$this->form->field('tags', array('label' => 'Add tags separated by commas')); ?>
        <fieldset>
            <?=$this->form->field('contributor.id', array(
                'type'=>'hidden',
                'value'=>$web_user['_id']
              ));?>
            <?=$this->form->field('contributor.name', array(
                'type'=>'hidden',
                'value'=>(isset($web_user['name']))?$web_user['name']:$web_user['username']
              ));?>
            <a href="#next" onclick="next('#Locations', '#BuildingLocation', 'back');" class="linkbutton">Back</a>
            <?=$this->form->submit('Save',array(
                 'class'=>'button floatRight',
                 'onclick'=>"next('submit', '#Locations');"
              ))?>
        </fieldset>
    </div>
<?=$this->form->end();?>

<pre>
<?//=print_r($place);?>
</pre>