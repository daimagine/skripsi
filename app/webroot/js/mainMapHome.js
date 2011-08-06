  var baseUrl = 'http://localhost/skripsi-dev/';
  var initialLocation;
  var locdefault = new google.maps.LatLng(-6.3918409, 106.80603880000001);
  var bounds = new google.maps.LatLngBounds();
  var browserSupportFlag =  new Boolean();
  var map;
  var infoWindow;
  var marker = new Array();
  var contentString;
  var contentArray = new Array();
  var iterator = 0;

  function initGmap() {
      var myOptions = {
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      };
      map = new google.maps.Map(document.getElementById("mainMap"),
          myOptions);
      // Try W3C Geolocation method (Preferred)
//                      if(navigator.geolocation) {
//                        browserSupportFlag = true;
//                        navigator.geolocation.getCurrentPosition(function(position) {
//                          initialLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
//                          //contentString = "Location found using W3C standard : "+position.coords.latitude+","+position.coords.longitude;
//                          map.setCenter(initialLocation);
//                          //infowindow.setContent(contentString);
//                          //infowindow.setPosition(initialLocation);
//                          //infowindow.open(map);
//                        }, function() {
//                          handleNoGeolocation(browserSupportFlag);
//                        });
//                      } else {
//                        // Browser doesn't support Geolocation
//                        browserSupportFlag = false;
        handleNoGeolocation(browserSupportFlag);
//                      }

      //close infowindow
      google.maps.event.addListener(map, 'click', function() {
          infoWindow.close();
        });
      //bounds.extend(locdefault);
      //set places marker
      setPlacesMarker();
      //fit to bound
      //map.fitBounds(bounds);
  }
  function handleNoGeolocation(errorFlag) {
      if (errorFlag == true) {
        initialLocation = locdefault;
        //contentString = "Error: The Geolocation service failed.";
      } else {
        initialLocation = locdefault;
        //contentString = "Error: Your browser doesn't support geolocation. Are you in Siberia?";
      }
      map.setCenter(initialLocation);
      //infowindow.setContent(contentString);
      //infowindow.setPosition(initialLocation);
      //infowindow.open(map);
  }
  //TODO get places and dishes data from php
  //TODO create marker and attach listener
  //data must contains :
  //   position[lat,lang] : array of latitude and longitude
  //   place id : for obtain information to be placed in info window
  function setPlacesMarker() {
      var url = baseUrl + 'places/getAll';
      $.getJSON(url, function(response) {
          places = response.data;
          //console.debug(places);
          $.each(places, function(i, place) {
              addMarker(place);
              //console.debug(place);
          });​
      });
  }
  function addMarker(data) {
      // Adding a marker to the map
      point = new google.maps.LatLng(data.address.location.lat, data.address.location.lng);
      marker[iterator] = new google.maps.Marker({
          position: point,
          map: map,
          icon: 'http://dl.dropbox.com/u/25935007/map-icon/restaurant-map.png',
          shadow: 'http://dl.dropbox.com/u/25935007/map-icon/shadow.png'
      });
      //extend bounds
      bounds.extend(point);
      map.fitBounds(bounds);
      //console.debug(marker);
      contentString = '<a href="' + baseUrl + 'places/view/' + data._id + '"><h3>'+ data.name +'</h3></a>' +
          '<p>'+ data.address.street + ', ' + data.address.city + '</p>';
          '<p>'+ data.address.location.lat + ', ' + data.address.location.lng + '</p>';
      //init info window
      infoWindow = new google.maps.InfoWindow();
      //bind all
      bindInfoW(marker[iterator], contentString, infoWindow);
      //iterate
      iterator++;
  }

  function bindInfoW(marker, contentString, infowindow) {
        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(contentString);
            infowindow.open(map, this);
        });
  }

  //init the map
  google.maps.event.addDomListener(window, 'load', initGmap);