/**
 * Created by andresvasquez on 3/16/15.
 */
$(document).ready(function(){
   $("#formLogin").submit(function(event)
   {
       event.preventDefault();
       var datos= {
           "email":$("#email").val(),
           "password":encriptar($("#password").val())
       };

       $("#btnEnviar").attr('disabled', 'disabled');
       $.ajax({
           type: "POST",
           url: $(this).attr("action"),
           data:  JSON.stringify(datos),
           contentType: "application/json; charset=utf-8",
           dataType: "json",
           success: function(result)
           {
               if(parseInt(result.intCodigo)==1)
               {
                   $("#password").val("");
                   $("#email").val("");

                   mensaje("ok");
                   $("#btnEnviar").removeAttr('disabled');
                   window.location.href = "../pilar";
               }
               else
               {
                   mensaje(result.resultado.errores);
                   $("#btnEnviar").removeAttr('disabled');
               }
           },
           error: function(XMLHttpRequest, textStatus, errorThrown) {
               console.log(XMLHttpRequest + " "+textStatus);
               mensaje("error");
           }
       });
   });

    mensaje=function(tipo) {
        $("#mensaje").html('');
        $("#mensaje").addClass("alert");
      var html='';
        if(tipo=="ok")
        {
            $("#mensaje").removeClass("alert-danger");
            $("#mensaje").addClass("alert-success");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Datos correctos!</strong> Ingresando a la plataforma "PILAR"';
        }
        else
        {
            $("#mensaje").removeClass("alert-success");
            $("#mensaje").addClass("alert-danger");
            html+='<button type="button" class="close" data-dismiss="alert">&times;</button>';
            html+='<strong>¡Error!</strong> '+tipo;
        }
        $("#mensaje").html(html);
    };

    encriptar=function(password){
        var hash = CryptoJS.MD5(password);
        return CryptoJS.MD5(hash.toString().substring(0,10)).toString();
    };
});