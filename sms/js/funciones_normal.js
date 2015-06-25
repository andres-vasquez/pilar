/**
 * Created by andresvasquez on 6/21/15.
 */
var keyStr = "ABCDEFGHIJKLMNOP" +
    "QRSTUVWXYZabcdef" +
    "ghijklmnopqrstuv" +
    "wxyz0123456789+/" +
    "=";

var credencial="42F32At6AG2y8mW";

$(document).ready(function ()
{
    $("#btnRegistro").click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        $("#panel").removeClass("hidden");
        $("#divRegistro").removeClass("hidden");
        $("#divAcceso").addClass("hidden");
    });

    $("#btnSaldo").click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        $("#panel").removeClass("hidden");
        $("#divRegistro").addClass("hidden");
        $("#divAcceso").removeClass("hidden");
    });


    mensaje = function (tipo, mensaje) {
        var html = '';
        switch (tipo) {
            case "Registrado":
                html += '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>';
                html += 'Bienvenido a Sm$, tu código de usuario es: <b>' + mensaje + '</b>';
                html += '</div>';
                break;
            case "Error":
                html += '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert">&times;</button>';
                html += mensaje;
                html += '</div>';
                break;
        }
        $("#mensaje").html(html);
        $('html, body').animate({ scrollTop: $('#mensaje').offset().top }, 1500);
    };

    $("#formRegistro").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();

        $('#dialogMensaje').modal('show');
        $("#btnYaEnvie").click(function(event){
            event.preventDefault();
            event.stopPropagation();

            var url = getUrlBase() + "/apisms/v1/usuarios/registrar";
            var datos={
                "credencial":credencial,
                "nombre":$("#txtNombre").val(),
                "nombre_deposito":$("#txtNombreDeposito").val(),
                "digitos_celular":$("#txtDigitos").val(),
                "clabe":$("#txtClabe").val(),
                "email":$("#txtEmail").val()
            };

            $(this).addClass("disabled").attr("disabled",true);
            llamarWs(url,datos, function(data)
            {
                $('#dialogMensaje').modal('hide');
                $(this).removeClass("disabled").removeAttr("disabled");
                if(parseInt(data.intCodigo)==1)
                {
                    mensaje("Registrado",data.resultado.smsusuario.username.toUpperCase());
                    $("#txtNombre,#txtNombreDeposito,#txtDigitos,#txtClabe,#txtEmail").val("");
                    $("#chkSinClabe").removeProp("checked");
                    $("#btnSaldo").trigger("click");
                }
                else
                    mensaje("Error",data.resultado.errores);
            });
        });
    });

    $("#formAcceso").submit(function (event) {
        event.preventDefault();
        event.stopPropagation();
        obtenerDatos($("#txtCodigo").val(),0,0);
    });

    obtenerDatos=function(txtCodigo,mes,ano)
    {
        var url = getUrlBase() + "/apisms/v1/usuarios/auth";
        if(mes!=0 && ano!=0)
        {
            var datos={
                "credencial":credencial,
                "username":txtCodigo,
                "email":"usuario@facebook.com",
                "mes":mes,
                "ano":ano,
                "origen":"facebook"
            };
        }
        else
        {
            var datos={
                "credencial":credencial,
                "username":txtCodigo,
                "email":"usuario@facebook.com",
                "origen":"facebook"
            };
        }

        $(this).addClass("disabled").attr("disabled",true);
        $("#mensaje").html("");
        $("#loading").removeClass("hidden");
        llamarWs(url,datos, function(data)
        {
            $("#loading").addClass("hidden");
            $(this).removeClass("disabled").removeAttr("disabled");
            if(parseInt(data.intCodigo)==1)
            {
                $("#formAcceso").addClass("hidden");
                $("#divPanelDatos").removeClass("hidden");

                var datos=data.resultado.datos;
                var cuentas=datos.cuentas[0];
                var configuraciones=datos.configuraciones[0];

                $("#txtFechaActualizacion").html(configuraciones.valido_mensaje);
                $("#txtGanancia").html(configuraciones.ganancia+"!!!");
                $("#txtAvisos").html(configuraciones.mensaje_pago);

                var monto=parseInt(cuentas.mensajes[0]) * parseFloat(configuraciones.ganancia);
                $("#txtCantidadMensajes").html(cuentas.mensajes[0]);
                $("#txtMonto").html(monto);

                if(mes!=0 && ano!=0)
                {
                    $("#periodo").html(literalMeses(mes)+"/"+ano);
                }
                else
                {
                    var fecha=new Date();
                    var m=fecha.getMonth()+1;
                    $("#periodo").html(literalMeses(m)+"/"+fecha.getFullYear());
                }


            }
            else
                mensaje("Error",data.resultado.errores);
        });
    };

    $("#chkSinClabe").click(function () {
        if ($(this).prop('checked'))
            $("#txtClabe").val("").addClass("disabled").attr("disabled",true);
        else
            $("#txtClabe").removeClass("disabled").removeAttr("disabled");
    });


    $("#btnActualizar").click(function(event){
        event.preventDefault();
        event.stopPropagation();
        obtenerDatos($("#txtCodigo").val(),0,0);
    });

    $("#btnCerrarSesion").click(function(event) {
        event.preventDefault();
        event.stopPropagation();

        $("#formAcceso").removeClass("hidden");
        $("#txtFechaActualizacion").html("");
        $("#txtGanancia").html("");
        $("#txtAvisos").html("");
        $("#txtCantidadMensajes").html("");
        $("#txtMonto").html("");
        $("#periodo").html("");

        $("#txtCodigo").val("");
        $("#divPanelDatos").addClass("hidden");
    });

    $("#txtDigitos,#txtClabe").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    getUrlBase = function () {
        var protocolo = String.fromCharCode(104, 116, 116, 112, 115) + ":" + String.fromCharCode(47, 47);
        //var enBase64 = "bG9jYWxob3N0Ojg4ODg="; // Desarrollo //TODO descomentar
        var enBase64="cGlsYXIuY2xvdWRhcHAubmV0"; // Prod
        var pilar = "pilar";
        return protocolo + decode64(enBase64) + "/" + pilar;
    };

    decode64 = function (input) {
        var output = "";
        var chr1, chr2, chr3 = "";
        var enc1, enc2, enc3, enc4 = "";
        var i = 0;
        var base64test = /[^A-Za-z0-9\+\/\=]/g;
        if (base64test.exec(input)) {
            alert("There were invalid base64 characters in the input text.\n" +
            "Valid base64 characters are A-Z, a-z, 0-9, '+', '/',and '='\n" +
            "Expect errors in decoding.");
        }
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        do {
            enc1 = keyStr.indexOf(input.charAt(i++));
            enc2 = keyStr.indexOf(input.charAt(i++));
            enc3 = keyStr.indexOf(input.charAt(i++));
            enc4 = keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
            chr1 = chr2 = chr3 = "";
            enc1 = enc2 = enc3 = enc4 = "";
        } while (i < input.length);
        return unescape(output);
    };

    llamarWs=function(url,datos,callback)
    {
        var headers = {
            'Access-Control-Allow-Origin' : '*',
            'Access-Control-Allow-Methods' : 'POST',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        $.ajax({
            type: "POST",
            url: url,
            data:JSON.stringify(datos),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            headers: headers,
            success: callback,
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(XMLHttpRequest + " "+textStatus);
            }
        });
    };

    llenarMeses=function()
    {
        var fecha=new Date();
        var mes=fecha.getMonth()+1;

        $("#lstMeses").append('<li><a id="'+fecha.getFullYear()+pad(mes,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mes)+'/'+fecha.getFullYear()+'</a></li>');

        if(mes==1)
        {
            var mesObj=12;
            var anioObj=fecha.getFullYear()-1;
            $("#lstMeses").append('<li><a id="'+anioObj+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+anioObj+'</a></li>');
            mesObj-=1;
            $("#lstMeses").append('<li><a id="'+anioObj+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+anioObj+'</a></li>');
        }
        else if(mes==2)
        {
            var mesObj=mes-1
            $("#lstMeses").append('<li><a id="'+anioObj+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+fecha.getFullYear()+'</a></li>');
            var anioObj=fecha.getFullYear()-1;
            mesObj=12;
            $("#lstMeses").append('<li><a id="'+anioObj+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+anioObj+'</a></li>');
        }
        else
        {
            var mesObj=mes-1
            $("#lstMeses").append('<li><a id="'+fecha.getFullYear()+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+fecha.getFullYear()+'</a></li>');
            mesObj-=1;
            $("#lstMeses").append('<li><a id="'+fecha.getFullYear()+pad(mesObj,2)+'" class="meses" href="#divPanelDatos">'+literalMeses(mesObj)+'/'+fecha.getFullYear()+'</a></li>');
        }
    };

    $('body').on('click', '.meses', function(event){
        var id=$(this).attr("id");
        var anio=id.substring(0,4);
        var mes=id.substring(4,6);
        obtenerDatos($("#txtCodigo").val(),parseInt(mes),parseInt(anio));
    });


    pad=function(value, length) {
        return (value.toString().length < length) ? pad("0"+value, length):value;
    };

    literalMeses=function(idMes)
    {
        switch (idMes)
        {
            case 1: return "Enero";
            case 2: return "Febrero";
            case 3: return "Marzo";
            case 4: return "Abril";
            case 5: return "Mayo";
            case 6: return "Junio";
            case 7: return "Julio";
            case 8: return "Agosto";
            case 9: return "Septiembre";
            case 10: return "Octubre";
            case 11: return "Noviembre";
            case 12: return "Diciembre";
        }
    };

    llenarMeses();
});
