<h1>Contact Us</h1>
<div>
    <?=$this->form->create(null, array('id'=>'ContactForm', 'url'=>'pages/report/contact'));?>
        <div id="Response" class="notification">
            <p>Fill this form to send a message to us. We only send a valid response and will never send spam to your submitted email.</p>
        </div>
        <fieldset>
            <?=$this->form->field('name', array(
                'label'=>'Your Name',
                'class'=>'inputBox mediumInput',
                'placeholder'=>'fill your name',
                'style'=>'font-size:12px;'
              ));?>
            <?=$this->form->field('email', array(
                'label'=>'Fill your email',
                'class'=>'inputBox mediumInput',
                'placeholder'=>'fill your valid email',
              ));?>
            <?=$this->form->field('message', array(
                'label'=>'Your Message',
                'class'=>'inputBox mediumInput',
                'type'=>'textarea',
                'placeholder'=>'Fill your message. Only 256 characters allowed.',
                'maxlength'=>256
              ));?>
            <?=$this->form->field('type', array(
                'type'=>'hidden',
                'value'=>'contact',
              ));?>
            <?=$this->form->submit('Send', array(
                'class'=>'button floatRight',
              ));?>
        </fieldset>
    <?=$this->form->end();?>
</div>

<script type="text/javascript">
    $('#ContactForm').submit(function(){
        $.post(
            this.action,
            $("#ContactForm").serialize(),
            function(response){
                if(response.status == false) {
                    $('#Response').html('');
                    $('#Response').removeClass();
                    $('#Response').addClass('errorNotif');
                    $('#Response').html('\<p\>Sending failed. Please try again.\<\/p\>').hide().fadeIn(1000);
                } else {
                    $('#Response').html('');
                    $('#Response').removeClass();
                    $('#Response').addClass('successNotif');
                    $('#Response').html('\<p\>'+response.message+'\<\/p\>').hide().fadeIn(1000);
                }
                console.debug(response);
            },
            "json"
        );
        return false;
    });
</script>