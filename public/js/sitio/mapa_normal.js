/**
 * Created by andresvasquez on 3/19/15.
 */

var map;
var id = 0;
var drawingManager;
var lstMarkers = [];
var lstCirculos = [];
var lstPoligonos = [];
var lstRectangulos = [];

var icons = [];
var iconSelect;
var selectedText;

function CoordMapType(tileSize) {
    this.tileSize = tileSize;
}

CoordMapType.prototype.getTile = function (coord, zoom, ownerDocument) {
    var div = ownerDocument.createElement('div');
    //div.innerHTML = coord;
    //div.style.width = this.tileSize.width + 'px';
    //div.style.height = this.tileSize.height + 'px';
    div.style.fontSize = '10';
    div.style.borderStyle = 'solid';
    div.style.borderWidth = '1px';
    div.style.borderColor = '#AAAAAA';
    return div;
};

$(document).ready(function () {

    selectedText = document.getElementById('selected-text');

    document.getElementById('my-icon-select').addEventListener('changed', function (e) {
        selectedText.value = iconSelect.getSelectedValue();
    });

    iconSelect = new IconSelect("my-icon-select");


    icons.push({'iconFilePath': $("#hdnRuta").val() + '/AreaDeJuegos.png', 'iconValue': 'AreaDeJuegos'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Auditorio.png', 'iconValue': 'Auditorio'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Banio.png', 'iconValue': 'Banio'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Cadepia.png', 'iconValue': 'Cadepia'});
    icons.push({
        'iconFilePath': $("#hdnRuta").val() + '/CentroInternacionaldeNegocios.png',
        'iconValue': 'CentroInternacionaldeNegocios'
    });
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/PabellonAmericano.png', 'iconValue': 'PabellonAmericano'});
    icons.push({
        'iconFilePath': $("#hdnRuta").val() + '/PabellonBicentenario.png',
        'iconValue': 'PabellonBicentenario'
    });
    icons.push({
        'iconFilePath': $("#hdnRuta").val() + '/PabellonInternacional.png',
        'iconValue': 'PabellonInternacional'
    });
    icons.push({
        'iconFilePath': $("#hdnRuta").val() + '/PabellonUnionEuropea.png',
        'iconValue': 'PabellonUnionEuropea'
    });
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Parqueo.png', 'iconValue': 'Parqueo'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/PlazaDeComidas.png', 'iconValue': 'PlazaDeComidas'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Puerta.png', 'iconValue': 'Puerta'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/SalonEventos.png', 'iconValue': 'SalonEventos'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/SectorGanadero.png', 'iconValue': 'SectorGanadero'});
    icons.push({'iconFilePath': $("#hdnRuta").val() + '/Teatro.png', 'iconValue': 'TeatroAlAireLibre'});
    iconSelect.refresh(icons);


    $("#txtColorPicker").colorpicker();

    var mapOptions = {
        zoom: 17,
        center: new google.maps.LatLng(-17.418905, -66.129765)
    };
    map = new google.maps.Map(document.getElementById('mapa'),
        mapOptions);

    map.overlayMapTypes.insertAt(
        0, new CoordMapType(new google.maps.Size(256, 256)));

    LlenarOverlay();

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
            clickable: true,
            animation: google.maps.Animation.DROP,
            icon: BuscarIcono($("#selected-text").val())
        },
        circleOptions: {
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 1,
            clickable: true,
            editable: false,
            draggable: false,
            zIndex: 1
        },
        rectangleOptions: {
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 1,
            clickable: true,
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
        if (event.type == google.maps.drawing.OverlayType.MARKER) {
            if ($("#txtNombre").val() == "") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                var image = {
                    url: BuscarIcono($("#selected-text").val()),
                    size: new google.maps.Size(196, 196),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(0, 32),
                    scaledSize: new google.maps.Size(48, 48)
                };
                event.overlay.icon = image;

                var coordenadas = [];
                var punto = event.overlay.getPosition();
                coordenadas.push(punto.lat(), punto.lng());

                id++;
                var marcador = {
                    "id": id,
                    "nombre": $("#txtNombre").val(),
                    "icono": $("#selected-text").val(),
                    "coordenadas": coordenadas,
                    "overlay": event.overlay
                };
                llenarCapas("marker", marcador);
                lstMarkers.push(marcador);
            }
        }

        //Circulo
        if (event.type == google.maps.drawing.OverlayType.CIRCLE) {
            if ($("#txtNombre").val() == "") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                id++;
                var circulo = {
                    "id": id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "centro": event.overlay.getCenter(),
                    "radio": event.overlay.getRadius(),
                    "overlay": event.overlay
                };
                llenarCapas("circulo", circulo);
                lstCirculos.push(circulo);
            }
        }


        //Polygon
        if (event.type == google.maps.drawing.OverlayType.POLYGON) {
            if ($("#txtNombre").val() == "") {
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
                    "id": id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "coordenadas": coordenadas,
                    "overlay": event.overlay
                };
                llenarCapas("poligono", poligono);
                lstPoligonos.push(poligono);
            }
        }

        //Rectangulo
        if (event.type == google.maps.drawing.OverlayType.RECTANGLE) {
            if ($("#txtNombre").val() == "") {
                alert("Ingrese un nombre para la capa");
                event.overlay.setMap(null);
            }
            else {
                id++;
                var puntos = event.overlay.getBounds();
                var rectangulo = {
                    "id": id,
                    "nombre": $("#txtNombre").val(),
                    "color": color,
                    "noreste": puntos.getNorthEast(),
                    "sudoeste": puntos.getSouthWest(),
                    "overlay": event.overlay
                };
                llenarCapas("rectangulo", rectangulo);
                lstRectangulos.push(rectangulo);
            }
        }
    });

    llenarDrawing();
    drawingManager.setMap(map);
});

BuscarIcono = function (value) {
    for (var i = 0; i < icons.length; i++)
        if (icons[i].iconValue == value)
            return icons[i].iconFilePath;
};

$("#txtColorPicker").change(function () {
    llenarDrawing();
});

$("#txtColorPicker").on("change.color", function (event, color) {
    llenarDrawing();
});


LlenarOverlay = function () {
    var credencial = $("#credencial").val();
    var url = "../api/v1/mapas/" + credencial;
    $.ajax({
            type: "GET",
            url: url,
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                if (parseInt(result.intCodigo) == 1) {
                    var lstMapas = result.resultado.mapas;

                    for (var i = 0; i < lstMapas.length; i++) {
                        var objCapa = lstMapas[i].capa;
                        //Llenar Markers
                        if (lstMapas[i].tipo == "marker") {
                            var image =
                            {
                                url: BuscarIcono(objCapa[0].icono),
                                size: new google.maps.Size(196, 196),
                                origin: new google.maps.Point(0, 0),
                                anchor: new google.maps.Point(0, 32),
                                scaledSize: new google.maps.Size(48, 48)
                            };

                            var coordenadas = (objCapa[0].punto.replace("[", "").replace("]", "")).split(",");
                            var myLatLng = new google.maps.LatLng(parseFloat(coordenadas[0]), parseFloat(coordenadas[1]));
                            var markerOptions =
                            {
                                map: map,
                                position: myLatLng,
                                draggable: false,
                                clickable: true,
                                animation: google.maps.Animation.DROP,
                                icon: image
                            };

                            var overlay = new google.maps.Marker(markerOptions);
                            overlay.setMap(map);

                            var coordenadasNuevas = [];
                            var punto = overlay.getPosition();
                            coordenadasNuevas.push(punto.lat(), punto.lng());

                            var marcador = {
                                "id": objCapa[0].id,
                                "nombre": objCapa[0].nombre,
                                "icono": objCapa[0].icono,
                                "coordenadas": coordenadasNuevas,
                                "overlay": overlay
                            };
                            llenarCapas("marker", marcador);
                            lstMarkers.push(marcador);
                        }
                        //Llenar Circulos
                        else if (lstMapas[i].tipo == "circulo") {
                            var objCentro = objCapa[0].centro;
                            var myLatLng = new google.maps.LatLng(objCentro["k"], objCentro["D"]);
                            var circleOptions = {
                                map: map,
                                fillColor: objCapa[0].color,
                                fillOpacity: 1,
                                strokeWeight: 1,
                                clickable: true,
                                editable: false,
                                draggable: false,
                                zIndex: 1,
                                center: myLatLng,
                                radius: parseFloat(objCapa[0].radio)
                            };

                            var overlay = new google.maps.Circle(circleOptions);

                            var circulo = {
                                "id": objCapa[0].id,
                                "nombre": objCapa[0].nombre,
                                "color": objCapa[0].color,
                                "centro": overlay.getCenter(),
                                "radio": overlay.getRadius(),
                                "overlay": overlay
                            };
                            llenarCapas("circulo", circulo);
                            lstCirculos.push(circulo);
                        }
                        //Llenar Poligonos
                        else if (lstMapas[i].tipo == "poligono") {
                            var coords = [];
                            var coordenadas = objCapa[0].punto;
                            var i = 0;
                            while (i < coordenadas.length) {
                                coords.push(new google.maps.LatLng(parseFloat(coordenadas[i]), parseFloat(coordenadas[i + 1])));
                                i = i + 2;
                            }


                            var polygonOptions = {
                                map: map,
                                fillColor: objCapa[0].color,
                                fillOpacity: 1,
                                strokeWeight: 1,
                                clickable: true,
                                editable: false,
                                draggable: true,
                                zIndex: 1,
                                paths: coords
                            };
                            var overlay = new google.maps.Polygon(polygonOptions);


                            var coordenadasNuevas = [];
                            var puntos = overlay.getPath();

                            for (var j = 0; j < puntos.length; j++) {
                                coordenadasNuevas.push(puntos.getAt(j).lat(), puntos.getAt(j).lng());
                            }

                            var poligono = {
                                "id": objCapa[0].id,
                                "nombre": objCapa[0].nombre,
                                "color": objCapa[0].color,
                                "coordenadas": coordenadasNuevas,
                                "overlay": overlay
                            };
                            llenarCapas("poligono", poligono);
                            lstPoligonos.push(poligono);

                        }
                        //Llenar Recangulos
                        else if (lstMapas[i].tipo == "rectangulo") {
                            var coords = new google.maps.LatLngBounds(
                                new google.maps.LatLng(parseFloat(objCapa[0].sudoeste["k"]), parseFloat(objCapa[0].sudoeste["D"])),
                                new google.maps.LatLng(parseFloat(objCapa[0].noreste["k"]), parseFloat(objCapa[0].noreste["D"]))
                            );


                            var rectangleOptions = {
                                map: map,
                                fillColor: objCapa[0].color,
                                fillOpacity: 1,
                                strokeWeight: 1,
                                clickable: true,
                                editable: false,
                                draggable: false,
                                zIndex: 1,
                                bounds: coords
                            };

                            var overlay = new google.maps.Rectangle(rectangleOptions);

                            var puntos = overlay.getBounds();
                            var rectangulo = {
                                "id": objCapa[0].id,
                                "nombre": objCapa[0].nombre,
                                "color": objCapa[0].color,
                                "noreste": puntos.getNorthEast(),
                                "sudoeste": puntos.getSouthWest(),
                                "overlay": overlay
                            };
                            llenarCapas("rectangulo", rectangulo);
                            lstRectangulos.push(rectangulo);
                        }
                    }
                    //console.log(JSON.stringify(lstMapas));
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                mensaje("error");
            }
        }
    )
    ;
}
;

$("#btnGuardarCambios").click(function () {
    if (confirm("Desea guardar los cambios?. Cualquier capa que no esté visible en pantalla será eliminado de la base de datos")) {
        $("#loagingGuardarCambios").removeClass("hidden");
        $("#btnGuardarCambios").addClass("disabled");
        var data = [];

        //Marcadores
        for (var i = 0; i < lstMarkers.length; i++) {
            if (lstMarkers[i] != null) {
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
        }
        //Circulos
        for (var i = 0; i < lstCirculos.length; i++) {
            if (lstCirculos[i] != null) {
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
        }

        //Poligonos
        for (var i = 0; i < lstPoligonos.length; i++) {
            if (lstPoligonos[i] != null) {
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
        }

        //Rectangulos
        for (var i = 0; i < lstRectangulos.length; i++) {
            if (lstRectangulos[i] != null) {
                var obj = {
                    "nombre": lstRectangulos[i].nombre,
                    "tipo": "rectangulo",
                    "color": lstRectangulos[i].color,
                    "atributo1": JSON.stringify(lstRectangulos[i].noreste),
                    "atributo2": JSON.stringify(lstRectangulos[i].sudoeste),
                    "atributo3": "",
                    "json": ""
                };
                data.push(obj);
            }
        }

        var url = "../ws/mapa";
        $.ajax({
            type: "POST",
            url: url,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function (result) {
                //console.log(JSON.stringify(result));
                if (parseInt(result.intCodigo) == 1) {
                    mensaje("ok");
                    $("#loagingGuardarCambios").addClass("hidden");
                    $("#btnGuardarCambios").removeClass("disabled");
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " " + textStatus);
                $("#loagingGuardarCambios").addClass("hidden");
                $("#btnGuardarCambios").removeClass("disabled");
                mensaje("Error al guardar puntos en el mapa");
            }
        });
    }
});

llenarDrawing = function () {
    var color = "#ffff00";
    if ($("#txtColorPicker").val() != "")
        color = $("#txtColorPicker").val();

    drawingManager.circleOptions.fillColor = color;
    drawingManager.rectangleOptions.fillColor = color;
    drawingManager.polygonOptions.fillColor = color;
};

llenarCapas = function (tipo, obj) {
    $("#txtNombre").val("");
    $("#btnGuardarCambios").removeClass("disabled");

    var html = '<li>';
    html += '<a>';
    html += '<input id="check_' + obj.id + '" class="filtrochk" type="checkbox" checked/>&nbsp;';
    html += '<span class="eliminar" id="obj_' + obj.id + '" style="cursor:pointer;"><span class="fa fa-times"></span>&nbsp;</span>&nbsp;&nbsp;';
    if (tipo == "marker")
        html += '<span class="fa fa-map-marker"></span>';
    else if (tipo == "circulo")
        html += '<span class="fa fa-circle-o"></span>';
    else if (tipo == "rectangulo")
        html += '<span class="fa fa-square-o"></span>';
    else if (tipo == "poligono")
        html += '<span class="fa fa-bookmark-o"></span>';

    html += '&nbsp; ' + obj.nombre;
    html += '</a>';
    html += '</li>';
    $("#ulLstCapas").append(html);

    var overlay = obj.overlay;
    //Evento click
    google.maps.event.addListener(overlay, 'click', function (event) {
        if (confirm("Desea eliminar: " + obj.nombre + "?")) {
            eliminarObjeto(tipo, obj);
        }
    });
};

eliminarObjeto = function (tipo, obj) {
    if (tipo == "marker") {
        for (var i = 0; i < lstMarkers.length; i++) {
            if (lstMarkers[i] != null) {
                if (lstMarkers[i].id == obj.id) {
                    lstMarkers[i].overlay.setMap(null);
                    lstMarkers[i] = null;
                }
            }
        }
    }
    else if (tipo == "circulo") {
        for (var i = 0; i < lstCirculos.length; i++) {
            if (lstCirculos[i] != null) {
                if (lstCirculos[i].id == obj.id) {
                    lstCirculos[i].overlay.setMap(null);
                    lstCirculos[i] = null;
                }
            }
        }
    }
    else if (tipo == "rectangulo") {
        for (var i = 0; i < lstRectangulos.length; i++) {
            if (lstRectangulos[i] != null) {
                if (lstRectangulos[i].id == obj.id) {
                    lstRectangulos[i].overlay.setMap(null);
                    lstRectangulos[i] = null;
                }
            }
        }
    }
    else if (tipo == "poligono") {
        for (var i = 0; i < lstPoligonos.length; i++) {
            if (lstPoligonos[i] != null) {
                if (lstPoligonos[i].id == obj.id) {
                    lstPoligonos[i].overlay.setMap(null);
                    lstPoligonos[i] = null;
                }
            }
        }
    }

    $("#ulLstCapas").html("");
    for (var i = 0; i < lstMarkers.length; i++)
        if (lstMarkers[i] != null)
            llenarCapas("marker", lstMarkers[i]);
    for (var i = 0; i < lstCirculos.length; i++)
        if (lstCirculos[i] != null)
            llenarCapas("circulo", lstCirculos[i]);
    for (var i = 0; i < lstRectangulos.length; i++)
        if (lstRectangulos[i] != null)
            llenarCapas("rectangulo", lstRectangulos[i]);
    for (var i = 0; i < lstPoligonos.length; i++)
        if (lstPoligonos[i] != null)
            llenarCapas("poligono", lstPoligonos[i]);
};

mensaje = function (tipo) {
    $("#mensaje").html('');
    $("#mensaje").addClass("alert");
    var html = '';
    if (tipo == "ok") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Tags agregados!</strong>';
    }
    else if (tipo == "editada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Tags agregados!</strong>';
    }
    else if (tipo == "eliminada") {
        $("#mensaje").removeClass("alert-danger");
        $("#mensaje").addClass("alert-success");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Tag eliminado!</strong>';
    }
    else {
        $("#mensaje").removeClass("alert-success");
        $("#mensaje").addClass("alert-danger");
        html += '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        html += '<strong>¡Error!</strong> ' + tipo;
    }
    $("#mensaje").html(html);
};

$('body').on('click', '.eliminar', function (e) {
    var encontrado = false;
    var id = parseInt($(this).attr("id").replace("obj_", ""));
    for (var i = 0; i < lstMarkers.length; i++)
        if (lstMarkers[i] != null)
            if (lstMarkers[i].id == id) {
                if (confirm("Desea eliminar: " + lstMarkers[i].nombre + "?")) {
                    eliminarObjeto("marker", lstMarkers[i]);
                }
            }

    if (!encontrado)
        for (var i = 0; i < lstCirculos.length; i++)
            if (lstCirculos[i] != null)
                if (lstCirculos[i].id == id) {
                    if (confirm("Desea eliminar: " + lstCirculos[i].nombre + "?")) {
                        eliminarObjeto("circulo", lstCirculos[i]);
                    }
                }

    if (!encontrado)
        for (var i = 0; i < lstRectangulos.length; i++)
            if (lstRectangulos[i] != null)
                if (lstRectangulos[i].id == id) {
                    if (confirm("Desea eliminar: " + lstRectangulos[i].nombre + "?")) {
                        eliminarObjeto("rectangulo", lstRectangulos[i]);
                    }
                }

    if (!encontrado)
        for (var i = 0; i < lstPoligonos.length; i++)
            if (lstPoligonos[i] != null)
                if (lstPoligonos[i].id == id) {
                    if (confirm("Desea eliminar: " + lstPoligonos[i].nombre + "?")) {
                        eliminarObjeto("poligono", lstPoligonos[i]);
                    }
                }
    e.stopPropagation();
});

$('body').on('click', '.filtrochk', function (e) {
    var id = parseInt($(this).attr("id").replace("check_", ""));
    var encontrado = false;
    for (var i = 0; i < lstMarkers.length; i++)
        if (lstMarkers[i] != null)
            if (lstMarkers[i].id == id) {
                if (lstMarkers[i].overlay.getMap() != null)
                    lstMarkers[i].overlay.setMap(null);
                else
                    lstMarkers[i].overlay.setMap(map);
            }

    if (!encontrado)
        for (var i = 0; i < lstCirculos.length; i++)
            if (lstCirculos[i] != null)
                if (lstCirculos[i].id == id) {
                    if (lstCirculos[i].overlay.getMap() != null)
                        lstCirculos[i].overlay.setMap(null);
                    else
                        lstCirculos[i].overlay.setMap(map);
                }

    if (!encontrado)
        for (var i = 0; i < lstRectangulos.length; i++)
            if (lstRectangulos[i] != null)
                if (lstRectangulos[i].id == id) {
                    if (lstRectangulos[i].overlay.getMap() != null)
                        lstRectangulos[i].overlay.setMap(null);
                    else
                        lstRectangulos[i].overlay.setMap(map);
                }

    if (!encontrado)
        for (var i = 0; i < lstPoligonos.length; i++)
            if (lstPoligonos[i] != null)
                if (lstPoligonos[i].id == id) {
                    if (lstPoligonos[i].overlay.getMap() != null)
                        lstPoligonos[i].overlay.setMap(null);
                    else
                        lstPoligonos[i].overlay.setMap(map);
                }
    e.stopPropagation();
});
