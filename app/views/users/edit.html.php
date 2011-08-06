<h2>Change Profile Information</h2>
<div id="Response" class="notification">
    <p>Fill some field to update information about yourself.</p>
</div>
<?=$this->form->create($user, array('id'=>'ProfileForm','type'=>'file'));?>
    <?=$this->form->field('username', array(
        'readonly'=>'readonly',
        'disabled'=>'disabled',
        'title'=>'This field is not editable.',
        'class'=>'inputBox mediumInput daiTipsy',
        ));?>
    <?=$this->form->field('email', array(
        'readonly'=>'readonly',
        'disabled'=>'disabled',
        'title'=>'This field is not editable.',
        'class'=>'inputBox mediumInput daiTipsy',
        ));?>
    <?=$this->form->field('name', array(
        'required'=>'required',
        'class'=>'inputBox mediumInput daiTipsyInput',
        ));?>
    <?=$this->form->field('about', array(
        'type'=>'textarea',
        'class'=>'inputBox mediumInput daiTipsyInput',
        ));?>
    <?=$this->form->field('website', array(
        'class'=>'inputBox mediumInput daiTipsyInput',
        ));?>
    <?=$this->form->field('favoriteDish', array(
        'class'=>'inputBox mediumInput daiTipsyInput',
        'placeholder'=>'Input your kind of favorite dish separate by commas'
        ));?>
    <?=$this->form->submit('Save', array('class'=>'button'));?>
<?=$this->form->end();?>


<script type="text/javascript">
    $('#ProfileForm').submit(function(){
        $.post(
            this.action,
            $("#ProfileForm").serialize(),
            function(response){
                if(response.status == false) {
                    console.debug(status);
                    $('#Response').html('');
                    $('#Response').removeClass();
                    $('#Response').addClass('errorNotif');
                    $('#Response').html('\<p\>Update failed. Please try again.\<\/p\>').hide().fadeIn(1000);
                } else {
                    $('#Response').html('');
                    $('#Response').removeClass();
                    $('#Response').addClass('successNotif');
                    $('#Response').html('\<p\>Your information has been saved. This page will be refreshed in 5 seconds\<\/p\>').hide().fadeIn(1000);
                    setTimeout(function() {
                      window.location.href = baseUrl+"/users/dashboard";
                    }, 5000);
                }
                console.debug(response);
            },
            "json"
        );
        return false;
    });
</script>