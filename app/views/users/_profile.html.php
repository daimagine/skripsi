
<?=$this->form->create($user, array('type'=>'file'));?>
    <?=$this->form->field('profilePicture');?>
    <?=$this->form->field('name');?>
    <?=$this->form->field('permalink');//custom url masking?>
    <?=$this->form->field('gender');//should be combobox?>
    <?=$this->form->field('birthday');?>
    <?=$this->form->field('hometown');?>
    <?=$this->form->field('location');//use for geo tagging?>
    <?=$this->form->field('phone');?>
    <?=$this->form->field('website');?>
    <?=$this->form->field('aboutMe');?>
    <?=$this->form->field('favoriteFoods');//should be auto complete tagging in array?>
    <?=$this->form->submit('Save')?>
<?=$this->form->end();?>


<pre>
<?//=print_r($user);?>
</pre>