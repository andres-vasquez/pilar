/**
 * Created by andresvasquez on 3/19/15.
 */
var map;
function initialize() {
    var mapOptions = {
        zoom: 17,
        center: new google.maps.LatLng(-17.418905, -66.129765)
    };
    map = new google.maps.Map(document.getElementById('mapa'),
        mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);