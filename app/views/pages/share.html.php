<div class="clear"></div>
<div style="padding-left: 10px; padding-right: 10px; min-width: 400px;">
    <div>
        <h3>Share something new</h3>
    </div>
    <div class="shareContainer">
        <div class="boxShare">
            <ul>
                <li class="liShare mitipsy" title="Share new place">
                    <a href="<?=$this->url("/places/add");?>" class="">
                        <div class="pictureShare"><img class="imgShare" src="<?=$this->path('img/icon/32/places.png');?>"></div>
                        <div class="titleShare">New Place</div>
                    </a>
                </li>
                <li class="liShare mitipsy" title="Share new dish">
                    <a href="<?=$this->url("/dishes/add");?>" class="">
                        <div class="pictureShare"><img class="imgShare" src="<?=$this->path('img/icon/32/apple.png');?>"></div>
                        <div class="titleShare">New Dish</div>
                    </a>
                </li>
                <li class="liShare mitipsy" title="Share new promo">
                    <a href="<?=$this->url("/promos/add");?>" class="">
                        <div class="pictureShare"><img class="imgShare" src="<?=$this->path('img/icon/32/credit.png');?>"></div>
                        <div class="titleShare">New Promo</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <div class="notification">
        <p>You need to sign in in order to share something new. <br>Visit <a href="<?=$this->url("/users/login");?>">this page</a> to sign in or join <a href="<?=$this->url("/users/signup");?>">here</a> if don't you have an account yet.</p>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".mitipsy").tipsy({
//            gravity:'s',
            fade: true
        });
    });
</script>

<style type="text/css">
    .clear {
        clear: both;
    }
    .shareContainer {
        text-align: center;
    }
    .boxShare {
        display: inline-block;
        margin: 0 auto;
    }
    .boxShare ul {
        list-style: none inside;
        display: inline-block;
        margin: 10px 0;
        padding: 0;
    }
    .liShare {
        text-align: center;
        vertical-align: middle;
        background: -moz-linear-gradient(center top , #FFFFFF, #F6F6FE) repeat scroll 0 0 transparent;
        border: 1px solid #EDEDED;
        border-radius: 5px 5px 5px 5px;
        box-shadow: 0 1px 0 #EDEDED;
        color: #666666;
        float: left;
        font-size: 13px;
        font-weight: normal;
        height: 120px;
        margin: 0 5px 0 5px;
        width: 120px;
    }
    .liShare:hover {
        background: -moz-linear-gradient(center top , #E5EEFF, #CCDEFF) repeat scroll 0 0 transparent;
        border: 1px solid #B3CDFF;
        box-shadow: 1px 1px 0 #E5EEFF;
    }
    .liShare:hover .pictureShare {
        border-bottom:1px solid #B3CDFF;
    }
    .liShare:hover .titleShare {
        border-top:1px solid #E5EEFF;
    }
    .liShare a {
        display: block;
        font-size: 1.1em;
        color: #b3c7e1;
    }
    .liShare a:hover {
        color: #5783BC !important;
        text-shadow: 0 1px 0 #E9EFF6;
    }
    .pictureShare {
        display: block;
        height: 90px;
        border-bottom:1px solid #EDEDED;
    }
    .imgShare {
        margin: 30px !important;
        opacity: 0.8;
    }
    .titleShare {
        display: block;
        height: 20px;
        padding: 5px;
        overflow: hidden;
        border-top:1px solid #FFFFFF;
    }

</style>