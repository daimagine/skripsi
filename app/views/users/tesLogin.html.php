<?=$this->form->create(null);?>
    <?=$this->form->field('email');?>
    <?=$this->form->field('password', array('type' => 'password'));?>
    <?=$this->form->submit('Login');?>
<?=$this->form->end();?>