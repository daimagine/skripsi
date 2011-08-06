<?=$this->form->create(null, array('type'=>'file'));?>
    <?=$this->form->field('image', array(
         'type'=>'file'
       ));?>
    <?=$this->form->submit('submit');?>
<?=$this->form->end();?>


<pre>
    <?php @print_r($data);?>
</pre>
<br><br><hr>

<h3>Tes kodingan stackoverflow</h3>
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





<br><br><hr>
<!------ gagal ----------->
<h3>Tes valums uploader</h3>

<div id="preview">
    <img id="thumb" width="100px" height="100px" src=''>
</div>
<div id="file-uploader-demo1">
    <noscript>
        <p>Please enable JavaScript to use file uploader.</p>
        <!-- or put a simple form for upload here -->
    </noscript>
</div>

<script src="<?=$this->path('js/jquery/valums-file-uploader/client/fileuploader.js');?>"></script>
<link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/valums-file-uploader/client/fileuploader.css')?>" />

<script>
    var baseUrl = 'http://localhost/skripsi-dev/';
    var thumb = $('img#thumb');
//    function createUploader(){
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader-demo1'),
            name: 'valumsimage',
            action: baseUrl+'places/tesDoUpload',
            debug: true,
            onSubmit: function(file, extension) {
                $('div.preview').addClass('loading');
            },
            onComplete: function(file, fileName, response) {
                thumb.load(function(){
                    $('div.preview').removeClass('loading');
                    thumb.unbind();
                });
                thumb.attr('src', fileName);
                console.debug(file, fileName, response);
            },
            // additional data to send, name-value pairs
            //params: {},
            // validation
            // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
            // each file size limit in bytes
            // this option isn't supported in all browsers
            sizeLimit: 5242880, // max size 5MB
            //minSizeLimit: 0, // min size
            maxNumberOfFiles: 1,
        });
//    }
    // in your app create uploader as soon as the DOM is ready
    // don't wait for the window to load
    //window.onload = createUploader;
</script>

<style type="text/css">
    div.preview.loading { background: url('http://www.zurb.com/images/loading.gif') no-repeat 39px 40px; }
    div.preview.loading img {display: none; }
</style>