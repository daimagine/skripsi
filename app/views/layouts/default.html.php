<?php
    use app\models\Users;
    use lithium\storage\Session;
    use li3_facebook\extensions\FacebookProxy;
    use lithium\util\Inflector;
?>

<!DOCTYPE HTML>
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<html>
  <head>
      <title><?php echo $this->title(); ?> | SkripsiApp</title>
      <?php echo $this->html->charset();?>
      <?php echo $this->html->style(array('html', 'megamenu', 'style', 'login')); ?>
      <!--  load jquery and jquery ui  -->
      <script src="<?=$this->path('js/jquery/jquery-1.6.1.js');?>"></script>
      <script src="<?=$this->path('js/jquery/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js');?>"></script>
      <link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/jquery-ui-1.8.13.custom/css/ui-lightness/jquery-ui-1.8.14.custom.css')?>" />
	  <?php echo $this->scripts(); ?>
	  <?php echo $this->html->link('Icon', null, array('type' => 'icon')); ?>
      <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

      <script src="<?=$this->path('js/jquery/jquery.pnotify.min.js');?>"></script>
      <link rel="stylesheet" type="text/css" href="<?=$this->path('css/jquery/jquery.pnotify.default.icons.css')?>" />
      <link rel="stylesheet" type="text/css" href="<?=$this->path('css/jquery/jquery.pnotify.default.css')?>" />

      <script src="<?=$this->path('js/jquery/jquery.tipsy.js');?>"></script>
      <link rel="stylesheet" type="text/css" href="<?=$this->path('css/jquery/tipsy.css')?>" />

      <script type="text/javascript" src="<?=$this->path('js/jquery/jquery.ui.stars/jquery.ui.stars.js');?>"></script>
	  <link rel="stylesheet" type="text/css" href="<?=$this->path('js/jquery/jquery.ui.stars/jquery.ui.stars.css');?>"/>

      <link rel="stylesheet" type="text/css" media="screen" href="<?=$this->path('js/jquery/dunfy-allRating/css/allRating.css');?>">
      <script type="text/javascript" src="<?=$this->path('js/jquery/dunfy-allRating/js/jquery.allRating.js');?>"></script>

      <link rel="stylesheet" type="text/css" media="screen" href="<?=$this->path('js/jquery/facebox/src/facebox.css');?>">
      <script type="text/javascript" src="<?=$this->path('js/jquery/facebox/src/facebox.js');?>"></script>

      <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&region=id"></script>

      <script type="text/javascript">
        var baseUrl = 'http://localhost/skripsi-dev/';
        $(document).ready(function() {
            $("#personalLink").click(function(e) {
                e.preventDefault();
                $("#signInMenu").toggle();
                $("#topPersonal").toggleClass("signInMenuActive");
                $("#topPersonalBox").toggleClass("signInMenuBoxActive");
                $("#personalLink").toggleClass("personalLinkActive");
                $("#username").focus();
            });
            $("#signInMenu").mouseup(function() {
                return false
            });
            $(document).mouseup(function(e) {
                if($(e.target).parent("#personalLink").length==0) {
                    $("#personalLink").removeClass("personalLinkActive");
                    $("#topPersonal").removeClass("signInMenuActive");
                    $("#topPersonalBox").removeClass("signInMenuBoxActive");
                    $("#signInMenu").hide();
                }
            });

            //trigger all tipsy
            $(".daiTipsy").tipsy({
                gravity:'s',
                fade: true
//                title: 'tips'
            });
            //for input only
            $(".daiTipsyInput").tipsy({
                trigger: 'focus',
                gravity:'w',
                fade: true,
                title: 'placeholder'
            });
        });

        function showWindow(type) {
            var url = '';
            switch(type) {
                case 'contact' : url = "pages/contact"; break;
                case 'share' : url = 'pages/share'; break;
                default : url = type; break;
            }
            if(url!='')
                $.facebox({ ajax : baseUrl + url });
        }
      </script>
  </head>
  <body>
      <?=$this->daiHtml->flash(); ?>
      <!-- header -->
      <div id="header">
          <div id="barContent">
              <!--<div id="logo">
                  <a id="linkLogo" href="#">
                      <img src="" width="100" height="40">
                  </a>
              </div>-->
              <div id="topPanel">
                  <div id="topMenu">
                      <ul id="menu">
                          <li class="iconTopMenu firstIconTopMenu" title="home">
                              <a href="/skripsi-dev"><span class="homeIconActive topIcon"></span>HOME</a>
                          </li>
                          <li class="iconTopMenu" title="places">
                              <a href="/skripsi-dev/places"><span class="placesIconActive topIcon"></span>PLACES</a>
                              <div class="dropdown_3columns">
                                <div class="col_3">
                                    <h3>Explore all places spotted by user</h3>
                                </div>
                                <div class="col_1">
                                    <p class="black_box">Choose one of place categories to see all place within.</p>
                                </div>
                                <div class="col_1">
                                    <ul class="greybox">
                                        <?php $categories = array_chunk(\app\models\Places::$categories,
                                                    count(\app\models\Places::$categories)/2,true);?>
                                        <?php foreach($categories[0] as $key => $cat): ?>
                                            <a href="<?=$this->url('places/find');?>?category=<?=$key;?>">
                                                <li><?=$cat;?></li></a>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                                <div class="col_1">
                                    <ul class="greybox">
                                        <?php foreach($categories[1] as $key => $cat): ?>
                                            <a href="<?=$this->url('places/find');?>?category=<?=$key;?>">
                                                <li><?=$cat;?></li></a>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                          </div><!-- End 4 columns container -->
                          </li>
                          <li class="iconTopMenu" title="dishes">
                              <a href="/skripsi-dev/dishes"><span class="dishesIconActive topIcon"></span>DISHES</a>
                          </li>
                          <li class="iconTopMenu" title="promos">
                              <a href="/skripsi-dev/promos"><span class="promosIconActive topIcon"></span>PROMOS</a>
                          </li>
                          <li class="iconTopMenu" title="site">
                              <a href="#site"><span class="siteIconActive topIcon"></span>SITE</a>
                              <div class="dropdown_2columns">
                                <div class="col_1">
                                    <p class="black_box">Feel free to contact us or just wanted to know who we are.</p>
                                </div>
                                <div class="col_1">
                                    <ul class="greybox">
                                        <li><a href="#contact" onclick="showWindow('contact');">Contact Us</a></li>
                                        <li><a href="#contact">About Us</a></li>
                                    </ul>
                                </div>
                          </div><!-- End 4 columns container -->
                          </li>
                      </ul>
                  </div>
                  <div id="topSearch">
<!--                      <form id="topSearchForm" action="--><?//=$this->url('pages/find');?><!--" method="get">-->
                      <?=$this->form->create(null,array('id'=>'topSearchForm', 'method'=>'get',
                                               'url'=>'pages/find'));?>
                        <input id="subjectSearchBox" class="inputSearchBox"
                               type="text" name="keyword" maxlength="250"
                               autocomplete="off" placeholder="Find Place or dish">
                        <span id="topSearchIn">in</span>
                        <input id="locationSearchBox" class="inputSearchBox"
                               type="text" name="location" maxlength="100"
                               autocomplete="off" placeholder="City">
                        <button name="searchSubmit" id="submitSearchTop" onclick="form.submit()">
                            <span class="iconSearch"></span>
                        </button>
                      <?=$this->form->end();?>
                  </div>
                  <div id="topPersonal">
                      <div id="topPersonalBox">
                          <a href="#" id="personalLink">
                              <?php if($web_user): ?>
                                  <span id="personalLinkId">
                                      <img style="padding-bottom: 0; vertical-align: middle; margin-right: 5px;"
                                       width="16" height="16" src="<?=$this->path('img/icon/16/user.png');?>">
                                      <?=(isset($web_user['name']))?$web_user['name']:$web_user['username'];?>
                                  </span>
                                  <span class="tinyDownArrow"></span>
                              <?php else: ?>
                                  <span id="personalLinkId">
                                      <img style="padding-bottom: 0; vertical-align: middle; margin-right: 5px;"
                                       width="16" height="16" src="<?=$this->path('img/icon/16/key.png');?>">
                                      Join <span style="font-size:0.80em;color:#363636;">or</span> Sign in
                                  </span>
                                  <span class="tinyDownArrow"></span>
                              <?php endif; ?>
                          </a>
                      </div>
                      <div id="signInMenu" class="<?=($web_user)?'signInMenuSmall':'';?>">
                          <?php if($web_user): ?>
                              <div>
                                  <ul>
                                      <li><a href="<?=$this->url("/users/dashboard");?>" class="daiTipsy"
                                            title="Here you can see your profile page.">
                                            Profile</a></li>
                                      <li><a href="<?=$this->url("/users/messages");?>" class="daiTipsy"
                                            title="All your conversation with your friend stored here.">
                                            Messages</a></li>
                                      <li><a href="<?=$this->url("/places/add");?>" class="daiTipsy"
                                            title="Find new places? lets share with us here.">
                                            Submit new Place</a></li>
                                      <li><a href="<?=$this->url("/dishes/add");?>" class="daiTipsy"
                                            title="Find new dishes? lets share with us here.">
                                            Submit new Dish</a></li>
                                      <li><a href="<?=$this->url("/promos/add");?>" class="daiTipsy"
                                            title="Find new promotions? lets share with us here.">
                                            Submit new Promo</a></li>
                                      <hr style="border: 1px dashed #EBEBEB;">
                                      <li><a href="<?=(Session::read('fb_logout_url'))?:$this->url('users/logout');?>"
                                            class="daiTipsy"
                                            title="Leave alredy? Well, see ya.">
                                            Logout</a></li>
                                  </ul>
                              </div>
                          <?php else: ?>
                              <form method="post" id="signInMenuForm" action="<?=$this->url('users/login');?>">
                                  <div id="signInMenuLeft">
                                      <p>Dont have an account?</p>
                                      <a href="<?=$this->url('users/signup');?>" class="button">Signup Here</a>
                                      <p>or</p>
                                      <p>
<!--                                          <fb:login-button perms="email,offline_access,publish_stream,user_checkins"-->
<!--                                              onlogin="window.location='--><?//=$this->url("users/login/fb", array("absolute"=>true));?><!--'">-->
<!--                                              Connect</fb:login-button>-->
                                          <a href="<?=\li3_facebook\extensions\FacebookProxy::getLoginUrl(array('req_perms'=>'email,publish_stream,user_checkins', "next"=>"http://localhost/skripsi-dev/users/login/fb/"));?>">
                                              <img src="<?=$this->path('img/facebook-login-button.png')?>">
                                          </a>
                                      </p>
                                  </div>
                                  <div id="signInMenuRight">
                                      <p>
                                          <label for="email">Email</label>
                                          <input id="email" name="email" placeholder="Insert your email" value="" title="username" type="text">
                                      </p>
                                      <p>
                                          <label for="password">Password</label>
                                          <input id="password" name="password" value="" placeholder="Insert your password" title="password" type="password">
                                      </p>
                                      <p class="forgot">
<!--                                          <a href="#" id="resendPasswordLink">Forgot your password?</a>-->
                                          <input id="signInSubmit" value="Login" type="submit">
                                      </p>
                                  </div>
                              </form>
                          <?php endif; ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <!-- buat wrapper utama, bagi jadi 3 bagian -->
      <div id="content">
          <?php if(isset($isHome)) :?>
              <script src="<?=$this->path('js/mainMapHome.js');?>"></script>
              <div id="contentHeader">
                  <!--<div id="banner"><h2>Find, Taste, and Share</h2></div>
                  <div class="clear"></div>-->
                  <div id="mainMap"></div>
                  <h1>Places Map</h1>
              </div>
          <?php endif;?>
          <div id="insideContent">
                <div id="leftContent">
                    <?php echo $this->content(); ?>
                </div>
                <div id="rightContent">
                    <div id="exploreSideNav">
                        <h1 class="sideHeader">Explore</h1>
                        <nav id="sideNav">
                            <ul>
                                <li>
                                    <a id="submitSideNav" href="#" class="daiTipsy"
                                            title="If you find something new, then lets share with us."
                                            onclick="showWindow('share');">
                                        <div class="home_icon"></div>
                                        <h3>Share</h3>
                                        <p>Find new Place, Dish, or Promotion? Share with us here.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="placeSideNav" href="<?=$this->url('places/find')?>" class="daiTipsy"
                                            title="Here you can find all places spotted by you and another user.">
                                        <div class="home_icon"></div>
                                        <h3>Places</h3>
                                        <p>List of places spotted by foodies around you.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="dishSideNav" href="<?=$this->url('dishes/find')?>" class="daiTipsy"
                                            title="Here you can find all dishes submitted by you and another user.">
                                        <div class="home_icon"></div>
                                        <h3>Dishes</h3>
                                        <p>List of delicious dishes on respective places.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="promosSideNav" href="<?=$this->url('promos/find')?>" class="daiTipsy"
                                            title="Here you can find all promotions submitted by you and another user.">
                                        <div class="home_icon"></div>
                                        <h3>Promos</h3>
                                        <p>List of promos founded by foodies on some places.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                            </ul>
                        </nav>
                    </div>
<!--                    <div id="newReviews">-->
<!--                        <h1 class="sideHeader">New Reviews</h1>-->
<!--                        <div class="listItem">-->
<!--                            <div class="comment">-->
<!--                                <blockquote>Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit</blockquote>-->
<!--                            </div>-->
<!--                            <div class="meta">-->
<!--                                <h3 title="People name">People name <span>on <span>Place</span></span></h3>-->
<!--                                <time datetime="2011-07-10">Just now</time>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div id="topUser">-->
<!--                        <h1 class="sideHeader">Top Reviewers</h1>-->
<!--                        <div id="first">-->
<!--                            <div class="thumbnail peopleThumb">-->
<!--                                <a href="#">-->
<!--                                    <img width="70px" height="60px" title="People thumbnail"-->
<!--                                         src="">-->
<!--                                </a>-->
<!--                            </div>-->
<!--                            <div id="info">-->
<!--                                <h3>User name</h3>-->
<!--                                <span class="thumbPoin">10</span>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="otherList">-->
<!--                            <ul class="clearPadding">-->
<!--                                <li>-->
<!--                                    <div class="smallThumbnail peopleThumbSmall">-->
<!--                                        <a href="#">-->
<!--                                            <img width="32px" height="32px" title="People thumbnail"-->
<!--                                                 src="">-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="info">-->
<!--                                        <h3>User name</h3>-->
<!--                                        <span class="thumbPoin">10</span>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <div class="smallThumbnail peopleThumbSmall">-->
<!--                                        <a href="#">-->
<!--                                            <img width="32px" height="32px" title="People thumbnail"-->
<!--                                                 src="">-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="info">-->
<!--                                        <h3>User name</h3>-->
<!--                                        <span class="thumbPoin">10</span>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <div class="smallThumbnail peopleThumbSmall">-->
<!--                                        <a href="#">-->
<!--                                            <img width="32px" height="32px" title="People thumbnail"-->
<!--                                                 src="">-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="info">-->
<!--                                        <h3>User name</h3>-->
<!--                                        <span class="thumbPoin">10</span>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <div class="smallThumbnail peopleThumbSmall">-->
<!--                                        <a href="#">-->
<!--                                            <img width="32px" height="32px" title="People thumbnail"-->
<!--                                                 src="">-->
<!--                                        </a>-->
<!--                                    </div>-->
<!--                                    <div class="info">-->
<!--                                        <h3>User name</h3>-->
<!--                                        <span class="thumbPoin">10</span>-->
<!--                                    </div>-->
<!--                                </li>-->
<!--                            </ul>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
          </div>
      </div>
      <div class="clear"></div>
      <!-- footer nya -->
      <footer>
          <div id="beforeEnd"></div>
          <div id="ending">
            &copy;2011 Daimagine -
            <a href="#">Terms</a>
            - <a href="#">Content Policy</a>
            - <a href="#">Privacy</a>
          </div>
      </footer>
<!--      <div id="fb-root"></div>-->
      <script type="text/javascript">
          /*window.fbAsyncInit = function() {
            FB.init({
              appId   : '103181459767347',
              status  : true, // check login status
              cookie  : true, // enable cookies to allow the server to access the session
              xfbml   : true // parse XFBML
            });

            // whenever the user logs in, we tell our login service
            FB.Event.subscribe('auth.login', function() {
              window.location = "<?//=$this->url('/users/login/fb', array('absolute'=>true));?>";
            });
          };

          (function() {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
          }());*/
        </script>
  <?=$this->facebook->facebook_init(); ?>
  </body>
</html>

