<?php
    $this->title($title);
?>
<h1>Submit New Place</h1>
<?=$this->form->create($place, array('type'=>'file'));?>
    <fieldset>
        <?=$this->form->field('name', array(
            'label'=>'Insert place name',
            'class'=>'inputBox longInput',
            'placeholder'=>'fill your spotted place name',
            'style'=>'font-size:12px;'
          ));?>
        <?=$this->form->field('description', array(
            'label'=>'Insert place description',
            'class'=>'inputBox longInput',
            'type'=>'textarea',
            'placeholder'=>'fill some description about this place'
          ));?>
        <?=$this->form->field('address.street', array(
            'label'=>'Insert place address',
            'class'=>'inputBox longInput',
            'type'=>'textarea',
            'placeholder'=>'fill address where you spotted this place'
          ));?>
        <?=$this->form->field('address.city', array(
            'label'=>'Insert place city',
            'class'=>'inputBox longInput',
            'placeholder'=>'fill the city name where you spotted this place'
          ));?>
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
    </fieldset>
    <fieldset>
        <legend>Price Range</legend>
        <?=$this->form->field('lowestPrice', array(
            'label'=>'Insert place lowest dishes price',
            'class'=>'inputBox mediumInput',
            'placeholder'=>"fill place's lowest dishes price if you know"
          ));?>
        <?=$this->form->field('highestPrice', array(
            'label'=>'Insert place highest dishes price',
            'class'=>'inputBox mediumInput',
            'placeholder'=>"fill place's highest dishes price if you know"
          ));?>
    </fieldset>

<!--    --><?//=$this->form->field('tags', array('label' => 'Add tags separated by commas')); ?>
    <fieldset>
        <?=$this->form->field('contributor.id', array(
            'type'=>'hidden',
            'value'=>$web_user['_id']
          ));?>
        <?=$this->form->field('contributor.name', array(
            'type'=>'hidden',
            'value'=>(isset($web_user['name']))?$web_user['name']:$web_user['username']
          ));?>
        <?=$this->form->submit('Save',array(
             'class'=>'button clearFloat',
          ))?>
    </fieldset>
<?=$this->form->end();?>


<pre>
<?//=print_r($place);?>
</pre>