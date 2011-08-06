<?php $this->title($title); ?>

<script src="<?=$this->path('js/jquery/tag-it/js/tag-it.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/tag-it/css/jquery.tagit.css')?>" />

<script type="text/javascript">
    $(function(){
        $('#DishTags').tagit({
            allowSpaces: true,
            maxlengthTag:25
        });
    });
</script>
<h1>New dish</h1>
    <h2>on <a href="<?=$this->url("places/view/$place->_id");?>"><?=$place->name;?></a></h2>
<fieldset>
    <?=$this->form->create(null, array('type'=>'file'));?>
        <?=$this->form->field('name', array(
            'label'=>'Insert dish name',
            'placeholder'=>'fill dish name here',
            'class'=>'inputBox mediumInput daiInputTipsy',
            'style'=>'font-size:1.1em;',
            'required'=>'required'
          ));?>
        <?=$this->form->field('price', array(
            'label'=>'Insert dish price',
            'placeholder'=>'fill dish price here. Example: 50000',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
        <?=$this->form->field('description', array(
            'type'=>'textarea',
            'label'=>'Give some short description about this dish',
            'placeholder'=>'fill dish description here',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
        <p>Give more information. Check if it's true<br>
            <?=$this->form->checkbox('nonhalal');?> non halal
            <?=$this->form->checkbox('isFavoriteDish');?> favorite menu
        </p>
        <?=$this->form->field('tags', array(
            'label'=>'Insert tags',
            'id'=>'DishTags',
            'placeholder'=>'keyword tags separated by comma. Example: chicken,delicious',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
        <?=$this->form->hidden('placeId', array('value' => $place->_id));?>
        <?=$this->form->field('placeName', array(
            'type'=>'hidden',
            'value'=>$place->name
          ));?>
        <?=$this->form->field('contributor.id', array(
            'type'=>'hidden',
            'value'=>$web_user['_id']
          ));?>
        <?=$this->form->field('contributor.name', array(
            'type'=>'hidden',
            'value'=>(isset($web_user['name']))?$web_user['name']:$web_user['username']
          ));?>
        <?=$this->form->submit('Save', array('class'=>'button'))?>
    <?=$this->form->end();?>
</fieldset>

