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
  var marker;
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

      //set marker
      marker = new google.maps.Marker({
          position: locdefault,
          map: map,
          draggable: true,
          icon: 'http://dl.dropbox.com/u/25935007/map-icon/restaurant-map.png',
      });

      contentWindow  = '<div style="font-size:0.85em;font-style:italic;"><p>';
      contentWindow += 'You can drag this marker to the location <br>where you spotted the place.';
      contentWindow += '<br>If you think this location is valid, <br>then feel free to submit the form.';
      contentWindow += '</p></div>';
      infowindow.setContent(contentWindow);
      //infowindow.setPosition(marker.getPosition());
      infowindow.open(map, marker);

      // Update current position info.
      if(document.getElementById("latitude") && document.getElementById("longitude")) {
          //geocodePosition(locdefault);
      } else {
          //geocode();
      }

      // Add dragging event listeners.
      google.maps.event.addListener(marker, 'dragstart', function() {
        //updateMarkerAddress('Dragging...');
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
                  marker.setPosition(locdefault);
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

