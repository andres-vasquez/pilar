/**
 * Created by hvasquez on 12/03/2015.
 */
$(document).ready(function ()
{
    var obj = []; // Aca se guarda el punto
    var map;

    var latitud = 0;
    var longitud = 0;

    iniciarMapa = function ()
    {
        var mapOptions = {
            center: new google.maps.LatLng(-16.2837065, -63.5493965),
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("mapa"),
            mapOptions);

        //Evento click sobre el mapa
        google.maps.event.addListener(map, 'click', function (event)
        {
            //Colocamos un marcador
            placeMarker(event.latLng);
            latitud = event.latLng.lat();
            longitud = event.latLng.lng();
            //deshabili
            $("#btnSeleccionarPunto").removeAttr("disabled");
        });
    };

    placeMarker = function(location)
    {
        //Vacia los puntos existentes
        for (var i = 0; i < obj.length; i++)
            obj[i].setMap(null);

        var marker = new google.maps.Marker({
            position: location,
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP
        });
        obj.push(marker); // Agrega el punto selccionado

        //Evento cuando el mapa se mueve
        google.maps.event.addListener(marker, 'dragend', function(event) {
            latitud = event.latLng.lat();
            longitud = event.latLng.lng();
        });

        //Centra el mapa
        map.setCenter(location);
        return marker;
    };

    seleccionarPunto = function() {
        $("#lat").val(latitud);
        $("#lon").val(longitud);
    };
});
