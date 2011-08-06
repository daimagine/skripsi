base_url = 'http://jakarta.urbanesia.com/';
image_url = '/images/v1/';
base_url = 'http://jakarta.urbanesia.com/';
image_url = '/images/v1/';
var biz_id = '99445';
$(document).ready(function () {
    $(".cal-day-notempty > .date-text-default > .date-text").fadeTo("slow", 0.7);
    $('.cal-day').hover(function () {
        $(this).addClass('date-hover');
        $(this).click(function () {
            $(this).removeClass('date-hover')
        })
    }, function () {
        $(this).removeClass('date-hover')
    });
    $('.cal-day').click(function () {
        $('.event_landing_single_container').remove();
        $('.cal-list-header').show();
        $('.calendar').find('.additional_mo').show();
        $(this).find('.additional_mo').hide();
        $('.calendar').find('.date-text').css({
            background: ''
        });
        $('.calendar').find('.date-selected').removeClass('date-selected');
        $('.calendar').find('.date-text-default').removeClass('date-text-selected');
        $('.calendar').find('.hidden-mo-text').hide();
        $('.today').find('.hidden-mo-text').show();
        $('.calendar').find('.rect-img').removeClass('rect-img');
        $(this).addClass('date-selected');
        $(this).find('.date-text-default').addClass('date-text-selected');
        $(this).find('.hidden-mo-text').show();
        var dateString = $(this).find('.formated-date').text(),
            dateString2 = $(this).attr('id'),
            eventCreateLink = base_url + 'member/events/add';
        $('.event-cal-list > .event-create-link').attr('href', eventCreateLink);
        $('.event-cal-list > h2').text(dateString);
        $('.event-cal-list').show()
    });
    $('.ajax-event-cal-loader').ajaxStart(function () {
        $(this).show()
    }).ajaxStop(function () {
        $(this).hide()
    });
    $('.cal-day-notempty').click(function () {
        var dateString = $(this).find('.formated-date').text(),
            dateString2 = $(this).attr('id'),
            eventCreateLink = base_url + 'member/events/add';
        $('.calendar').find('.rect-img').removeClass('rect-img');
        $('.calendar').find('.date-text').css({
            background: ''
        });
        $(this).find('.date-text').css({
            opacity: '1'
        });
        $(this).addClass('date-selected');
        $('.calendar').find('.date-selected').removeClass('date-selected');
        $('.calendar').find('.date-text-default').removeClass('date-text-selected');
        $('.calendar').find('.hidden-mo-text').hide();
        $(this).addClass('date-selected');
        $(this).find('img').addClass('rect-img');
        $('.event_landing_box_wrapper').slideUp().remove();
        $('.event-cal-list > .event-create-link').attr('href', eventCreateLink);
        $('.event-cal-list > h2').text(dateString);
        $.ajax({
            type: "POST",
            url: base_url + "ajax/ajax_event_list_for_biz",
            data: "biz_id=" + biz_id + "&date=" + dateString2,
            success: function (data) {
                $(data).insertBefore('.event-create-link');
                $('.event_landing_box_wrapper').slideDown(600)
            },
            error: function () {
                alert('event listing process failed')
            }
        })
    })
});
if (GBrowserIsCompatible()) {
    var gmarkers = [],
        htmls = [],
        i = 1,
        baseIcon = new GIcon();
    baseIcon.shadow = "http://static-10.urbanesia.com/images/urban_maps_shadow.png";
    baseIcon.shadowSize = new GSize(23, 14);
    baseIcon.iconAnchor = new GPoint(0, 0);
    baseIcon.infoWindowAnchor = new GPoint(9, 2);
    var directionsPanel, directions, geo = new GClientGeocoder(),
        reasons = [];
    reasons[G_GEO_SUCCESS] = "Success";
    reasons[G_GEO_MISSING_ADDRESS] = "alamat hilang; Alamat antara hilang atau kosong";
    reasons[G_GEO_UNKNOWN_ADDRESS] = "alamat tidak dikenal. Coba tuliskan alamat yang lebih luas. Misal: 'Sudirman, Jakarta' atau 'Sudirman, Indonesia'.";
    reasons[G_GEO_UNAVAILABLE_ADDRESS] = "area yang anda berikan sedang ada masalah legal.";
    reasons[G_GEO_BAD_KEY] = "bad Key: The API key is either invalid or does not match the domain for which it was given";
    reasons[G_GEO_TOO_MANY_QUERIES] = "quota server kami sudah melebihi batas. Coba lagi besok.";
    reasons[G_GEO_SERVER_ERROR] = "server Google Maps sedang tidak dapat memproses data anda.";
    var newMarkers = [],
        latLngs = [],
        icons = [],
        lastmarker, iwform = 'Apakah anda yakin titik ini <br />adalah lokasi Wartel Balap Sepeda?<br /><form action="#" onsubmit="processinsertmap(this); return false" action="#">  <input type="submit" value="Ya! Saya yakin." /><\/form><br /><br /><font size="1">Note: Anda masih dapat meng-click drag <br />marker hijau ini sampai posisinya tepat</font>';

    function createMarkerNormal(point, text) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function () {
            marker.openInfoWindow(document.createTextNode(text))
        });
        map.addOverlay(marker);
        return marker
    };

    function place(lat, lng) {
        var point = new GLatLng(lat, lng);
        map.setCenter(point, 16)
    };
    $(document).ready(function () {
        showGeoAddress()
    });

    function showGeoAddress() {
        var search = document.getElementById("search").value;
        if ((search == '' || search == 'Lokasi saya sekarang') && navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                if (position.coords.latitude < 6.0 && position.coords.latitude > -11.2 && position.coords.longitude > 94.0 && position.coords.longitude < 141.1) if (position.coords.accuracy < 1000) {
                    document.getElementById("search").value = 'Lokasi saya sekarang';
                    place(position.coords.latitude, position.coords.longitude)
                } else {
                    areatoowide = '<div class="yellow_box" style="width: 640px">kami tidak dapat menemukan posisi anda. Tingkat akurasi data ' + Math.round(position.coords.accuracy / 100) / 10 + ' Km.</div>';
                    $("#message").html(areatoowide).animate({
                        height: 'show',
                        opacity: 'show'
                    }, 1000)
                }
            });
            return
        };
        if (search == '') return true;
        geo.getLocations(search, function (result) {
            map.clearOverlays();
            if (result.Status.code == G_GEO_SUCCESS) {
                if (result.Placemark.length > 1) {
                    didyoumean = "Apakah yang anda maksud:";
                    for (var i = 0; i < result.Placemark.length; i++) {
                        var p = result.Placemark[i].Point.coordinates;
                        didyoumean += "<br>" + (i + 1) + ". <a href='javascript:place(" + p[1] + "," + p[0] + ")'>" + result.Placemark[i].address + "<\/a>"
                    };
                    $("#message").html(didyoumean).animate({
                        height: 'show',
                        opacity: 'show'
                    }, 1000)
                } else {
                    $("#message").animate({
                        height: 'hide',
                        opacity: 'hide'
                    }, 1000).html('');
                    var p = result.Placemark[0].Point.coordinates;
                    place(p[1], p[0])
                }
            } else {
                var reason = "Code " + result.Status.code;
                if (reasons[result.Status.code]) reason = reasons[result.Status.code];
                $("#message").html('<div class="yellow_box" style="width:640px">Tidak dapat menemukan "' + search + '" dikarenakan ' + reason + '</div>').animate({
                    height: 'show',
                    opacity: 'show'
                }, 1000)
            }
        })
    };
    var map = new GMap2(document.getElementById("map")),
        bounds = new GLatLngBounds();
    map.enableDoubleClickZoom();
    map.enableScrollWheelZoom();
    map.enableContinuousZoom();
    map.addMapType(G_SATELLITE_3D_MAP);
    map.addControl(new GSmallMapControl());
    map.addControl(new GHierarchicalMapTypeControl());
    map.setCenter(new GLatLng(-6.18938335032070000000, 106.88909312851000000000), 16);
    var Icon = new GIcon();
    Icon.image = "http://jakarta.urbanesia.com/images/assets/gmaps/xhair.png";
    Icon.iconSize = new GSize(21, 21);
    Icon.shadowSize = new GSize(0, 0);
    Icon.iconAnchor = new GPoint(11, 11);
    Icon.infoWindowAnchor = new GPoint(11, 11);
    Icon.infoShadowAnchor = new GPoint(11, 11);
    var minimap = new GMap2(document.getElementById("minimap"));
    minimap.setCenter(new GLatLng(-6.18938335032070000000, 106.88909312851000000000), 11);
    var xhair = new GMarker(minimap.getCenter(), Icon);
    minimap.addOverlay(xhair);
    var map_moving = 0,
        minimap_moving = 0;

    function Move() {
        minimap_moving = true;
        if (map_moving == false) {
            minimap.setCenter(map.getCenter());
            xhair.setPoint(map.getCenter());
            xhair.redraw(true)
        };
        minimap_moving = false
    };

    function MMove() {
        map_moving = true;
        if (minimap_moving == false) {
            map.setCenter(minimap.getCenter());
            xhair.setPoint(minimap.getCenter());
            xhair.redraw(true)
        };
        map_moving = false
    };
    GEvent.addListener(map, 'move', Move);
    GEvent.addListener(minimap, 'moveend', MMove);

    function createInputMarker(point) {
        map.clearOverlays();
        var marker = new GMarker(point, {
            draggable: true,
            icon: G_START_ICON
        });
        GEvent.addListener(marker, "click", function () {
            lastmarker = marker;
            marker.openInfoWindowHtml(iwform)
        });
        map.addOverlay(marker);
        marker.openInfoWindowHtml(iwform);
        lastmarker = marker;
        return marker
    };

    function processinsertmap(form) {
        var lat = lastmarker.getPoint().lat(),
            lng = lastmarker.getPoint().lng(),
            url = "http://jakarta.urbanesia.com/ajax/addmark/business/99445/latitude/" + lat + "/longitude/" + lng + "";
        GDownloadUrl(url, function (doc) {});
        map.closeInfoWindow();
        var details = "Terima kasih. Community manager akan segera memprosesnya dalam waktu 7x24 jam.",
            marker = createMarkerNormal(lastmarker.getPoint(), details);
        GEvent.trigger(marker, "click");
        GEvent.removeListener(clickMarkerListener)
    };
    var clickMarkerListener = GEvent.addListener(map, "click", function (overlay, point) {
        if (!overlay) createInputMarker(point)
    })
};
var point = new GLatLng(-6.18938335032070000000, 106.88909312851000000000);
map.addOverlay(new GMarker(point))