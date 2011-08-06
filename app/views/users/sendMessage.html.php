<?=$this->form->create();?>
    <?=$this->form->hidden('venueId', array(
            'value' => $user->_id,
          ));?>
    <?=$this->form->field('message');?>
    <?=$this->form->submit('Send');?>
<?=$this->form->end();?>