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

<?=$this->form->create(null, array('type'=>'file'));?>
    <?=$this->form->field('name', array(
//        'label'=>'Insert product name'
      ));?>
    <?=$this->form->field('price', array(
//        'label'=>'Insert  price',
      ));?>
    <div>
        <?=$this->form->label('ProductDescription','Gives some short description');?>
        <?=$this->form->textarea('description');?>
        <?=$this->form->error('description');?>
    </div>
    <?=$this->form->checkbox('halal');?> non halal
    <?=$this->form->checkbox('isFavoriteDish');?> favorite menu
    <?=$this->form->field('tags', array('label' => 'Add tags separated by commas')); ?>
    <?=$this->form->hidden('venueId', array(
        'value' => $venue->_id,
      ));?>
    <?=$this->form->submit('Save')?>
<?=$this->form->end();?>


<pre>
<?//=print_r($venue);?>
</pre>