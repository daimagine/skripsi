<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );
//
//    $this->title($title);
//
//    $this->set(array(
//        'header' => '<h1>Skripsi Gan</h1>',
//        'subHeader' => $subHeader,
//        'navbar' => array('element' => 'navbar'),
//        'footer' => '',
//    ));
    $this->title($title);
?>

<script src="<?=$this->path('js/jquery/tag-it/js/tag-it.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/tag-it/css/jquery.tagit.css')?>" />

<script type="text/javascript">
    $(function(){
        $('#PromoTags').tagit({
            allowSpaces: true,
            maxlengthTag:25,
            placeholder:'keyword tags separated by comma. Example: free,discount',
        });
    });
</script>
    
<h1>New Promo</h1>
    <h2>on <a href="<?=$this->url("places/view/$place->_id");?>"><?=$place->name;?></a></h2>
<?=$this->form->create(null, array('type'=>'file'));?>
    <fieldset>
        <?=$this->form->field('title', array(
            'label'=>'Insert promo title',
            'placeholder'=>'fill promo title here',
            'class'=>'inputBox mediumInput daiInputTipsy',
            'style'=>'font-size:1.1em;'
          ));?>
        <?=$this->form->field('description', array(
            'type'=>'textarea',
            'rows'=>5,
            'label'=>'Give some short description about this promo',
            'placeholder'=>'fill dish description here',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
        <?=$this->form->field('info', array(
            'type'=>'textarea',
            'rows'=>5,
            'label'=>'Are there any additional information about this promo?',
            'placeholder'=>'fill additional information here. For example, promo terms and conditions',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
        <?=$this->form->field('tags', array(
            'label'=>'Insert tags',
            'id'=>'PromoTags',
            'placeholder'=>'keyword tags separated by comma. Example: free,discount',
            'class'=>'inputBox mediumInput daiInputTipsy'
          ));?>
    </fieldset>
    <fieldset>
        <legend>Promo period</legend>
        <?=$this->form->field('period.from', array(
            'label'=>'Fill date when does this promos starting.',
            'class'=>'inputBox mediumInput',
            'placeholder'=>"Format mm/dd/yyyy. For example: 11/27/2011"
          ));?>
        <?=$this->form->field('period.to', array(
            'label'=>'Fill date when does this promos closed.',
            'class'=>'inputBox mediumInput',
            'placeholder'=>"Format mm/dd/yyyy. For example: 12/30/2011"
          ));?>
    </fieldset>
    <fieldset>
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
    </fieldset>
<?=$this->form->end();?>

