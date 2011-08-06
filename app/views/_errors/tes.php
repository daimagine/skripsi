<!DOCTYPE HTML>
<!--<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">-->
<html>
  <head>
      <title> | SkripsiApp</title>
      <meta charset="UTF-8" />      <link rel="stylesheet" type="text/css" href="/skripsi-dev/css/html.css" />
	<link rel="stylesheet" type="text/css" href="/skripsi-dev/css/megamenu.css" />
	<link rel="stylesheet" type="text/css" href="/skripsi-dev/css/style.css" />
	<link rel="stylesheet" type="text/css" href="/skripsi-dev/css/login.css" />

      <!--  load jquery and jquery ui  -->
      <script src="/skripsi-dev/js/jquery/jquery-1.6.1.js"></script>
      <script src="/skripsi-dev/js/jquery/jquery-ui-1.8.13.custom/js/jquery-ui-1.8.13.custom.min.js"></script>
      <link rel="stylesheet" type="text/css" href="/skripsi-dev/js/jquery/jquery-ui-1.8.13.custom/css/ui-lightness/jquery-ui-1.8.14.custom.css" />


	  <link href="/skripsi-dev/favicon.ico" title="Icon" type="image/x-icon" rel="icon" />
	<link href="/skripsi-dev/favicon.ico" title="Icon" type="image/x-icon" rel="shortcut icon" />      <script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>

      <script src="/skripsi-dev/js/jquery/jquery.pnotify.min.js"></script>
      <link rel="stylesheet" type="text/css" href="/skripsi-dev/css/jquery/jquery.pnotify.default.icons.css" />
      <link rel="stylesheet" type="text/css" href="/skripsi-dev/css/jquery/jquery.pnotify.default.css" />

      <script src="/skripsi-dev/js/jquery/jquery.tipsy.js"></script>
      <link rel="stylesheet" type="text/css" href="/skripsi-dev/css/jquery/tipsy.css" />

      <script type="text/javascript" src="/skripsi-dev/js/jquery/jquery.ui.stars/jquery.ui.stars.js"></script>

	  <link rel="stylesheet" type="text/css" href="/skripsi-dev/js/jquery/jquery.ui.stars/jquery.ui.stars.css"/>

      <link rel="stylesheet" type="text/css" media="screen" href="/skripsi-dev/js/jquery/dunfy-allRating/css/allRating.css">
      <script type="text/javascript" src="/skripsi-dev/js/jquery/dunfy-allRating/js/jquery.allRating.js"></script>

      <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&region=id"></script>

      <script type="text/javascript">
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
      </script>
  </head>

  <body>
            <!-- header -->
      <div id="header">
          <div id="barContent">
              <div id="logo">
                  <a id="linkLogo" href="#">
                      <img src="" width="119" height="40">
                  </a>
              </div>

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
                                                                                                                            <a href="/skripsi-dev/places/find?category=restaurant">
                                                <li>Restaurant</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=cafe">
                                                <li>Cafe</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=catering">
                                                <li>Catering</li></a>

                                                                                    <a href="/skripsi-dev/places/find?category=bakery&amp;dessert">
                                                <li>Bakery &amp; Dessert</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=coffee&amp;tea shop">
                                                <li>Coffee &amp; Tea shop</li></a>
                                                                            </ul>
                                </div>

                                <div class="col_1">
                                    <ul class="greybox">
                                                                                    <a href="/skripsi-dev/places/find?category=deli">
                                                <li>Deli</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=delivery">
                                                <li>Delivery</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=fast food">
                                                <li>Fast food</li></a>

                                                                                    <a href="/skripsi-dev/places/find?category=kiosk&amp;stall">
                                                <li>Kiosk &amp; Stall</li></a>
                                                                                    <a href="/skripsi-dev/places/find?category=lounge">
                                                <li>Lounge</li></a>
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
                      </ul>

                  </div>
                  <div id="topSearch">
                      <form id="topSearchForm" action="" method="get">
                        <input id="subjectSearchBox" class="inputSearchBox"
                               type="text" name="subject" maxlength="250"
                               autocomplete="off" placeholder="Find Place or dish">
                        <span id="topSearchIn">in</span>
                        <input id="locationSearchBox" class="inputSearchBox"
                               type="text" name="location" maxlength="100"
                               autocomplete="off" placeholder="City">
                        <button value="" name="searchSubmit" id="submitSearchTop" onclick="form.submit()">
                            <span class="iconSearch"></span>

                        </button>
                      </form>
                  </div>
                  <div id="topPersonal">
                      <div id="topPersonalBox">
                          <a href="#" id="personalLink">
                                                                <span id="personalLinkId">
                                      <img style="padding-bottom: 0; vertical-align: middle; margin-right: 5px;"
                                       width="16" height="16" src="/skripsi-dev/img/icon/16/user.png">
                                      Adi Kurniawan                                  </span>

                                  <span class="tinyDownArrow"></span>
                                                        </a>
                      </div>
                      <div id="signInMenu" class="signInMenuSmall">
                                                        <div>
                                  <ul>
                                      <li><a href="/skripsi-dev/users/dashboard" class="daiTipsy"
                                            title="Here you can see your profile page.">
                                            Profile</a></li>

                                      <li><a href="/skripsi-dev/users/messages" class="daiTipsy"
                                            title="All your conversation with your friend stored here.">
                                            Messages</a></li>
                                      <li><a href="/skripsi-dev/places/add" class="daiTipsy"
                                            title="Find new places? lets share with us here.">
                                            Submit new Place</a></li>
                                      <li><a href="/skripsi-dev/dishes/add" class="daiTipsy"
                                            title="Find new dishes? lets share with us here.">
                                            Submit new Dish</a></li>
                                      <li><a href="/skripsi-dev/promos/add" class="daiTipsy"
                                            title="Find new promotions? lets share with us here.">

                                            Submit new Promo</a></li>
                                      <hr style="border: 1px dashed #EBEBEB;">
                                      <li><a href="/skripsi-dev/users/logout"
                                            class="daiTipsy"
                                            title="Leave alredy? Well, see ya.">
                                            Logout</a></li>
                                  </ul>
                              </div>
                                                </div>
                  </div>

              </div>
          </div>
      </div>
      <!-- buat wrapper utama, bagi jadi 3 bagian -->
      <div id="content">
                    <div id="insideContent">
                <div id="leftContent">
                    <script type="text/javascript">
  var MAPFILES_URL = "http://maps.gstatic.com/intl/en_us/mapfiles/";
  var baseUrl = 'http://localhost/skripsi-dev/';
  var initialLocation;
  var locateCurrent;
  var locdefault = new google.maps.LatLng(-6.3918409, 106.80603880000001);
  var browserSupportFlag =  new Boolean();
  var map;
  var infowindow = new google.maps.InfoWindow();
  var geocoder = new google.maps.Geocoder();
  var markers = null;
  var tempMarker = null;
  var contentWindow = '';
  var tempAddress = '';

  var bounds = new google.maps.LatLngBounds();


    var GeocoderStatusDescription = {
      "OK": "The request did not encounter any errors",
      "UNKNOWN_ERROR": "A geocoding or directions request could not be successfully processed, yet the exact reason for the failure is not known",
      "OVER_QUERY_LIMIT": "The webpage has gone over the requests limit in too short a period of time",
      "REQUEST_DENIED": "The webpage is not allowed to use the geocoder for some reason",
      "INVALID_REQUEST": "This request was invalid",
      "ZERO_RESULTS": "The request did not encounter any errors but returns zero results",
      "ERROR": "There was a problem contacting the Google servers"
    };


  function initGmap() {
      var myOptions = {
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        center: locdefault
      };
      map = new google.maps.Map(document.getElementById("map"),
          myOptions);

      contentWindow  = '<div style="font-size:0.85em;font-style:italic;"><p>';
      contentWindow += 'You can drag this marker to the location <br>where you spotted the place.';
      contentWindow += '<br>If you think this location is valid, <br>then feel free to submit the form.';
      contentWindow += '</p></div>';
      infowindow.setContent(contentWindow);
      //infowindow.setPosition(marker.getPosition());
      infowindow.open(map, marker);

      // Update current position info.
      if(document.getElementById("matchCount") && document.getElementById("matchCount")) {
          //geocodePosition(locdefault);
      } else {
          //geocode();
      }

      // Add dragging event listeners.
      google.maps.event.addListener(marker, 'dragstart', function() {
        //updateMarkerAddress('Dragging...');
      });

      //set marker
      marker = new google.maps.Marker({
          position: locdefault,
          map: map,
          draggable: true,
          icon: 'https://dl-web.dropbox.com/get/skripsi/map-icon/restaurant-map.png?w=4ee1632a',
      });

      google.maps.event.addListener(marker, 'drag', function() {
        //updateMarkerPosition(marker.getPosition());
      });

      google.maps.event.addListener(marker, 'dragend', function() {
        geocodePosition(marker.getPosition());
      });

  }

  function geocodePosition(pos) {
      geocoder.geocode({
        latLng: pos
      }, function(responses) {
        if (responses && responses.length > 0) {
//          updateMarkerAddress(responses[0].formatted_address);
          updateMarkerLocation(responses[0].geometry.location);
          //do the same just like geocode()
          document.getElementById("matchCount").innerHTML = 'Match Results : ' + responses.length;
          showResults(responses);
        } else {
          updateMarkerAddress('Cannot determine address at this location.');
          updateMarkerLocation(locdefault);
        }
      });
  }

  function updateMarkerAddress(str) {
      document.getElementById('currentaddress').innerHTML = str;
      document.getElementById('address').value = str;
  }

  function updateMarkerLocation(LatLng) {
      document.getElementById('latitude').innerHTML = LatLng.lat();
      document.getElementById('longitude').innerHTML =  LatLng.lng();
  }

  //init the map
  google.maps.event.addDomListener(window, 'load', initGmap);

  function geocode(){
      clearResults();
      var addr = $("#address").val();
      var request = { 'address' : addr }
      request.country = 'ID';
      console.debug(request);
      geocoder.geocode(request, function(results, status){
         console.debug(results);
         if (!results) {
            alert("Geocoder did not return a valid response");
          } else {
            //document.getElementById("statusValue").innerHTML = status;
            //document.getElementById("statusDescription").innerHTML = GeocoderStatusDescription[status];
            //document.getElementById("responseInfo").style.display = "block";
            //document.getElementById("responseStatus").style.display = "block";

            if (status == google.maps.GeocoderStatus.OK) {
              //document.getElementById("matchCount").innerHTML = results.length;
              //document.getElementById("responseCount").style.display = "block";
              document.getElementById("matchCount").innerHTML = 'Match Results : ' + results.length;
              showResults(results);
              //set marker location
                if (results && results.length > 0) {
                  marker.setPosition(results[0].geometry.location);
                  updateMarkerAddress(results[0].formatted_address);
                  updateMarkerLocation(results[0].geometry.location);
                } else {
                  updateMarkerAddress('Cannot determine address at this location.');
                  updateMarkerLocation(locdefault);
                }
            } else if (status == google.maps.GeocoderStatus.ZERO_RESULTS) {
               noresult = 'We cannot find your given address. Map will restore to its default : Depok, Indonesia';
               document.getElementById("matches").innerHTML = noresult;
               map.setCenter(locdefault);
               map.setZoom(12);
            }
          }
      });
  }

  function showResults(results) {
      var resultsListHtml = "<ol>";
      var counter = results.length;
      //if(counter > 3) { counter = 3 }
      for (var i = 0; i < counter ; i++) {
        resultsListHtml += getResultsListItem(i, results[i]);
      }
      resultsListHtml += '</ol>';
      document.getElementById("matches").innerHTML = resultsListHtml;
  }

  function setPlace(lat, lng) {
    var point = new google.maps.LatLng(lat, lng);
    bounds.extend(point);
    map.setCenter(point);
    map.fitBounds(bounds);
    marker.setPosition(point);
    //handle if zoom level is more than 20 or less than 10
    if(map.getZoom() > 20){
        map.setZoom(15);
    }
    if(map.getZoom() < 10){
        map.setZoom(12);
    }
    //update latlng
    updateMarkerLocation(point);
  };

  function getResultsListItem(resultNum, result) {
    var html  = '<li><a onclick="updateMarkerAddress(\''+result.formatted_address+'\');';
        html += 'setPlace(' + result.geometry.location.lat() + ',' + result.geometry.location.lng() + ');">';
        html += result.formatted_address;
        html += '</a></li>';
    return html;
  }

  function clearResults(){
      document.getElementById("matches").innerHTML = '';
  }
</script>

<input type="text" id="address" name="address" class="inputBox longInput"
       onkeyup="if(event.keyCode == 13) { geocode(); }" value="depok" style="display:inline-block;">
<button onclick="geocode();" class="button" name="submit">submit</button>

<div id="responseInfo">
<!--  <div id="responseStatus">-->
<!--    <div>-->
<!--      <span style="font-weight: bold">Geocoder response: </span>-->
<!--      <span id="statusValue"></span> (<span id="statusDescription"></span>)-->
<!--    </div>-->
<!--  </div>-->
  <div id="responseCount">
    <span id="matchCount" style="font-weight: bold;"></span>
    <div id="matches"></div>

  </div>
  <div id="message"></div>
</div>
<div>
    <div>
        <p>
            Current closest matching address :
            <span id="currentaddress"></span>
        </p>
        <p>

            Geometry Location :
            <span id="latitude"></span>,&nbsp;
            <span id="longitude"></span>
        </p>
    </div>
</div>
<div id="mapcontainer">
  <div id="map" style="width:95%;height:400px;"></div>
</div>

                </div>

                <div id="rightContent">
                    <div id="exploreSideNav">
                        <h1 class="sideHeader">Explore</h1>
                        <nav id="sideNav">
                            <ul>
                                <li>
                                    <a id="submitSideNav" href="#" class="daiTipsy"
                                            title="If you find something new, then lets share with us."
                                            onclick="$('#submitNew').dialog();">
                                        <div class="home_icon"></div>

                                        <h3>Share</h3>
                                        <p>Find new Place, Dish, or Promotion? Share with us here.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="placeSideNav" href="/skripsi-dev/places/find" class="daiTipsy"
                                            title="Here you can find all places spotted by you and another user.">
                                        <div class="home_icon"></div>

                                        <h3>Places</h3>
                                        <p>List of places spotted by foodies around you.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="dishSideNav" href="/skripsi-dev/dishes/find" class="daiTipsy"
                                            title="Here you can find all dishes submitted by you and another user.">
                                        <div class="home_icon"></div>

                                        <h3>Dishes</h3>
                                        <p>List of delicious dishes on respective places.</p>
                                    </a>
                                    <div class="clear"></div>
                                </li>
                                <li>
                                    <a id="promosSideNav" href="/skripsi-dev/promos/find" class="daiTipsy"
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
              window.location = "";
            });
          };

          (function() {
            var e = document.createElement('script');
            e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
            e.async = true;
            document.getElementById('fb-root').appendChild(e);
          }());*/
        </script>
  <div id="fb-root"></div><script>window.fbAsyncInit = function() { FB.init({appId: '103181459767347', status: true, cookie: true, xfbml: true}); }; (function() { var e = document.createElement('script'); e.async = true; e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js'; document.getElementById('fb-root').appendChild(e); }());</script>  </body>
</html>