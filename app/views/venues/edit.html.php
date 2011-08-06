<?php
//    echo $this->view()->render(
//        array('element' => 'menu'),
//        array('var1' => 'var1', 'var2' => 'var2')
//    );

    $this->title($title);

    $this->set(array(
        'header' => '<h1>Skripsi Gan</h1>',
        'subHeader' => $subHeader,
        'navbar' => array('element' => 'navbar'),
        'footer' => '',
    ));

?>

<?=$this->form->create($venue, array('type'=>'file'));?>
    <?=$this->form->field('name', array(
        'label'=>'Insert venue name',
      ));?>
    <?=$this->form->field('address', array(
        'label'=>'Insert venue address',
      ));?>
    <?=$this->form->field('city', array(
        'label'=>'Insert venue city',
      ));?>
    <?=$this->form->field('telp');?>
    <?=$this->form->field('website');?>
    
    <?=$this->form->field('lowestPrice');?>
    <?=$this->form->field('highestPrice');?>
    <div>
        <?=$this->form->label('VenueDescription','Gives some short description');?>
        <?=$this->form->textarea('description');?>
        <?=$this->form->error('description');?>
    </div>
    <?=$this->form->field('tags', array('label' => 'Add tags separated by commas')); ?>
    <?=$this->form->submit('Save')?>
<?=$this->form->end();?>


<pre>
<?//=print_r($venue);?>
</pre>