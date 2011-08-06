<h4 class="regular"><span class="contact"></span>
<span class="h4title">Tell us about this situation.</span></h4>
<!-- BEGIN SEARCH FORM - EDIT YOUR DOMAIN BELOW -->
<?=$this->form->create(null, array('url'=>'pages/report'));?>
    <div>
        <label>Insert your name :</label>
        <input class="inputBox" name="submitter">
        <label>Your messages to us :</label>
        <textarea class="inputBox" name="message" cols="35" rows="3" maxlength="256"></textarea>
        <input type="hidden" name="type" value="error">
        <!--[if IE 6]><input type="submit" value="Go"><![endif]-->
        <!--[if !IE 6]><!--><input type="submit" class="button" value="Submit"><!--<![endif]-->
    </div>
</form>