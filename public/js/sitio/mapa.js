/**
 * Created by andresvasquez on 3/19/15.
 */

var map;
var id=0;
var drawingManager;
var lstMarkers = [];
var lstCirculos = [];
var lstPoligonos = [];
var lstRectangulos = [];

var icons = [];
var iconSelect;
var selectedText;

$(document).ready(function () {

    selectedText = document.getElementById('selected-text');

    document.getElementById('my-icon-select').addEventListener('changed', function(e){
        selectedText.value = iconSelect.getSelectedValue();
    });

    iconSelect = new IconSelect("my-icon-select");


    icons.push({'iconFilePath': $("#hdnRuta").val()+'/AreaDeJuegos.png', 'iconValue':'AreaDeJuegos'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/Auditorio.png', 'iconValue':'Auditorio'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/Banio.png', 'iconValue':'Banio'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/Cadepia.png', 'iconValue':'Cadepia'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/CentroInternacionaldeNegocios.png', 'iconValue':'CentroInternacionaldeNegocios'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/PabellonAmericano.png', 'iconValue':'PabellonAmericano'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/PabellonBicentenario.png', 'iconValue':'PabellonBicentenario'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/PabellonInternacional.png', 'iconValue':'PabellonInternacional'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/PabellonUnionEuropea.png', 'iconValue':'PabellonUnionEuropea'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/Parqueo.png', 'iconValue':'Parqueo'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/PlazaDeComidas.png', 'iconValue':'PlazaDeComidas'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/Puerta.png', 'iconValue':'Puerta'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/SalonEventos.png', 'iconValue':'SalonEventos'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/SectorGanadero.png', 'iconValue':'SectorGanadero'});
    icons.push({'iconFilePath': $("#hdnRuta").val()+'/TeatroAlAireLibre.png', 'iconValue':'TeatroAlAireLibre'});

    iconSelect.refresh(icons);

    $("#txtColorPicker").colorpicker();

    var mapOptions = {
        zoom: 17,
        center: new google.maps.LatLng(-17.418905, -66.129765)
    };
    map = new google.maps.Map(document.getElementById('mapa'),
        mapOptions);

    var color = "#ffff00";
    if ($("#txtColorPicker").val() != "")
        color = $("#txtColorPicker").val();

    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.MARKER,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.MARKER,
                google.maps.drawing.OverlayType.CIRCLE,
                google.maps.drawing.OverlayType.POLYGON,
                google.maps.drawing.OverlayType.RECTANGLE
            ]
        },
        markerOptions: {
            draggable: false,
            clickable: false,
            animation: google.maps.Animation.DROP,
            icon: BuscarIcono($("#selected-text").val())
        },
        circleOptions: {
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 1,
            clickable: false,
            editable: false,
            draggable: false,
            zIndex: 1
        },
        rectangleOptions: {
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 1,
            clickable: false,
            editable: false,
            draggable: false,
            zIndex: 1
        },
        polygonOptions: {
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 1,
            clickable: true,
            editable: false,
            draggable: true,
            zIndex: 1
        }
    });

    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {

        var color = "#ffff00";
        if ($("#txtColorPicker").val() != "")
            color = $("#txtColorPicker").val();

        //Marker
        if (event.type == google.maps.drawing.OverlayType.MARKER)
        {
            if($("#txtNombre").val()=="") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else
            {
                var image = {
                    url: BuscarIcono($("#selected-text").val()),
                    size: new google.maps.Size(196, 196),
                    origin: new google.maps.Point(0,0),
                    anchor: new google.maps.Point(0, 32),
                    scaledSize: new google.maps.Size(48, 48)
                };
                event.overlay.icon=image;

                var coordenadas = [];
                var punto = event.overlay.getPosition();
                coordenadas.push(punto.lat(), punto.lng());

                id++;
                var marcador = {
                    "id":id,
                    "nombre": $("#txtNombre").val(),
                    "icono": $("#selected-text").val(),
                    "coordenadas": coordenadas
                };
                llenarCapas("marker", marcador);
                lstMarkers.push(marcador);

                var npunto = event.overlay;
                //Evento click
                google.maps.event.addListener(npunto, 'click', function (event) {
                    console.log("Click");
                    var punto = event.latLng;
                    console.log(punto.lat() + "," + punto.lng());
                });
            }
        }

        //Circulo
        if (event.type == google.maps.drawing.OverlayType.CIRCLE)
        {
            if($("#txtNombre").val()=="") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                id++;
                var circulo = {
                    "id":id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "centro": event.overlay.getCenter(),
                    "radio": event.overlay.getRadius()
                };
                llenarCapas("circulo", circulo);
                lstCirculos.push(circulo);
                console.log(JSON.stringify(lstCirculos));
            }
        }


        //Polygon
        if (event.type == google.maps.drawing.OverlayType.POLYGON)
        {
            if($("#txtNombre").val()=="") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                var coordenadas = [];
                var puntos = event.overlay.getPath();
                for (var i = 0; i < puntos.length; i++) {
                    coordenadas.push(puntos.getAt(i).lat(), puntos.getAt(i).lng());
                }

                id++;
                var poligono = {
                    "id":id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "coordenadas": coordenadas
                };
                llenarCapas("poligono", poligono);
                lstPoligonos.push(poligono);
            }
        }

        //Rectangulo
        if (event.type == google.maps.drawing.OverlayType.RECTANGLE)
        {
            if($("#txtNombre").val()=="") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                id++;
                var puntos = event.overlay.getBounds();
                var rectangulo = {
                    "id":id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "noreste": puntos.getNorthEast(),
                    "sudoeste": puntos.getSouthWest()
                };
                llenarCapas("rectangulo", rectangulo);
                lstRectangulos.push(rectangulo);
            }
        }
    });

    llenarDrawing();
    drawingManager.setMap(map);
});

BuscarIcono=function(value)
{
    for(var i=0;i<icons.length;i++)
     if(icons[i].iconValue==value)
       return icons[i].iconFilePath;
};

$("#txtColorPicker").change(function () {
    llenarDrawing();
});

$("#txtColorPicker").on("change.color", function (event, color) {
    llenarDrawing();
});


$("#btnGuardarCambios").click(function(){
    $("#loagingGuardarCambios").show();
    $("#btnGuardarCambios").addClass("disabled");
    var data=[];

    //Marcadores
    for(var i=0;i<lstMarkers.length;i++) {
        var obj = {
            "nombre": lstMarkers[i].nombre,
            "tipo": "marker",
            "color": "",
            "atributo1": lstMarkers[i].icono,
            "atributo2": "",
            "atributo3": "",
            "json": JSON.stringify(lstMarkers[i].coordenadas)
        };
        data.push(obj);
    }
    //Circulos
    for(var i=0;i<lstCirculos.length;i++) {
        var obj = {
            "nombre": lstCirculos[i].nombre,
            "tipo": "circulo",
            "color": lstCirculos[i].color,
            "atributo1": JSON.stringify(lstCirculos[i].centro),
            "atributo2": JSON.stringify(lstCirculos[i].radio),
            "atributo3": "",
            "json": ""
        };
        data.push(obj);
    }

    //Poligonos
    for(var i=0;i<lstPoligonos.length;i++) {
        var obj = {
            "nombre": lstPoligonos[i].nombre,
            "tipo": "poligono",
            "color": lstPoligonos[i].color,
            "atributo1": "",
            "atributo2": "",
            "atributo3": "",
            "json": JSON.stringify(lstPoligonos[i].coordenadas)
        };
        data.push(obj);
    }

    //Rectangulos
    for(var i=0;i<lstRectangulos.length;i++) {
        var obj = {
            "nombre": lstRectangulos[i].nombre,
            "tipo": "rectangulo",
            "color": JSON.stringify(lstRectangulos[i].color),
            "atributo1": JSON.stringify(lstRectangulos[i].noreste),
            "atributo2": JSON.stringify(lstRectangulos[i].sudoeste),
            "atributo3": "",
            "json": ""
        };
        data.push(obj);
    }

    var url = "../ws/mapa";
    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (result) {
            console.log(JSON.stringify(result));
            if (parseInt(result.intCodigo) == 1)
            {
                mensaje("ok");
                $("#loagingGuardarCambios").hide();
                $("#btnGuardarCambios").removeClass("disabled");
            }
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest + " " + textStatus);
            $("#loagingGuardarCambios").hide();
            $("#btnGuardarCambios").removeClass("disabled");
            mensaje("Error al guardar puntos en el mapa");
        }
    });
});

llenarDrawing = function () {
    var color = "#ffff00";
    if ($("#txtColorPicker").val() != "")
        color = $("#txtColorPicker").val();

    drawingManager.circleOptions.fillColor=color;
    drawingManager.rectangleOptions.fillColor=color;
    drawingManager.polygonOptions.fillColor=color;
};

llenarCapas=function(tipo,obj)
{
    $("#txtNombre").val("");
    $("#btnGuardarCambios").removeClass("disabled");

    var html='<li>';
    html+='<a href="#">';
    if(tipo=="marker")
        html+='<span class="fa fa-map-marker"></span>';
    else if(tipo=="circulo")
        html+='<span class="fa fa-circle-o"></span>';
    else if(tipo=="rectangulo")
        html+='<span class="fa fa-square-o"></span>';
    else if(tipo=="poligono")
        html+='<span class="fa fa-bookmark-o"></span>';

    html+='&nbsp; '+obj.nombre;
    html+='</a>';
    html+='</li>';
    $("#ulLstCapas").append(html);
};


mensaje = function (tipo) {
    $("#mensaje").html('');
    $("#mensaje").addClass("alert");
    var html = '';
    if (tipo == "ok") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositores importados!</strong>';
    }
    else if (tipo == "editada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositor editado!</strong>';
    }
    else if (tipo == "eliminada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Expositor eliminado!</strong>';
    }
    else {
        $("#mensaje").removeClass("alert-success");
        $("#mensaje").addClass("alert-danger");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Error!</strong> ' + tipo;
    }
    $("#mensaje").html(html);
};

