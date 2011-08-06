<style type="text/css">
    #prevImage {
        max-width: 300px;
        max-height: 200px;
        border: 1px solid #FFF;
    }
</style>
<script type="text/javascript">
    function setImage(file) {
        var message;
        var fileTypes=["bmp","gif","png","jpg","jpeg"];
        var uploadedfile = file.files.item(0);
        clearImgUploader();
        if(document.all) {
            $('#prevImage').attr('src',file.value);
            //TODO i dont know why should check for document.all
            //for a while i assume we cannot do this stuff in any browser except FF
        } else {
            extvalid = false;
            ext = uploadedfile.name.substring(uploadedfile.name.lastIndexOf(".") + 1,
                    uploadedfile.name.length).toLowerCase();
            if(ext !== '') {
                for (var i=0; i<fileTypes.length; i++) {
                    if (fileTypes[i] == ext) {
                        extvalid = true;
                        break;
                    }
                }
            }
            console.debug(uploadedfile.name, ext, extvalid);
            if(extvalid == false) {
                message = 'Image is not valid. Please select an image with an extension of one of the following: '+fileTypes.join(", ");
                clearImgUploader();
                $('#myImage').val('');
                $('#errorImage').html(message);
                console.debug(message);
            }
            else if(uploadedfile.size > 5242880) {
                message = 'Your selected picture is too large. Maximum file size is 5MB';
                clearImgUploader();
                $('#myImage').val('');
                $('#errorImage').html(message);
                console.debug(message);
            } // max size 5MB
            else {
                $('#prevImage').attr('src', uploadedfile.getAsDataURL());
                console.debug('success');
                $('#prevImage').css('display','block');
            }
        }
    }
    function clearImgUploader(){
        $('#prevImage').val('src','');
        $('#prevImage').css('display','none');
        $('#errorImage').html('');
    }
</script>
<?=$this->form->create(null, array('type'=>'file', 'url'=>'places/tesDoUpload'));?>
    <input type="file" id="myImage" name="myImage" onchange="setImage(this);" />
    <?=$this->form->submit('submit');?>
<?=$this->form->end();?>
<div>
    <fieldset>
        <h3 style="margin-bottom:3px;margin-top:3px;">Preview :</h3>
        <img id="prevImage" style="display:none;"/>
        <p style="font-size:0.75em;font-style:italic;">**firefox 3.6+ only</p>
        <div id="errorImage"></div>
    </fieldset>
</div>
