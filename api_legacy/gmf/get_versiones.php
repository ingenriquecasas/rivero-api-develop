<?php
require_once("../commun/_config.php");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
?>

<script type="text/javascript" src="https://code.jquery.com/jquery-1.11.1.js"></script>
<input type="hidden" value='<?=$_GET["tipo"]?>' id="tipo"/>



<script>


        var data={
            "makeCode":$_POST["marca"],"modelCode":$_POST["modelo"],"modelYear": $_POST["ano"]
        }
         postRequest('GetVehicleModelGroupPackagesByCode', data);
      
    
    
    
    
    
    
if($_POST["tipo"]=='test'){
    var servicesBaseUrl='https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/';
    var servicioGM = 'https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/';
        /*
        1- Buick
        2- Cadillac
        3- Chevrolet
        4- GMC

        */
}else{
    var servicesBaseUrl = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    var servicioGM = 'https://www.response.com.mx/CWSServiceProxy/Services/';
}


function postRequest(service, data) {
    var result = postRequest(service, data, true);
    return result;
}



/*
         *Posts a request to the CWS services.
         */
        function postRequest(service, data, asynchronous) {
            var result = null;
            var startTime = new Date().getTime();

            var token = getVerificationToken();
            //Encapsulate Verification Token in order to send it in the Headers of the request.
            //The Verification Token is necessary for executing a service, without it, the service will not be called.
            var headers = { __RequestVerificationToken: token };

            $.ajax({
                async: (asynchronous == null || asynchronous == undefined) ? true : asynchronous,
                cache: false,
                type: "POST",
                url: servicesBaseUrl + service,
                data: JSON.stringify(data),
                crossDomain: true,
                contentType: "application/json; charset=utf-8",
                xhrFields: {
                withCredentials: true},
                dataType: "json",
                processData: true,
                headers: headers,
                success: function (data, status, jqXHR) {
                    result = data;
                    if (asynchronous == null || asynchronous == undefined || asynchronous == true) {
                       
                        var respuesta=JSON.stringify(data);
                       
                        var requestTime = new Date().getTime() - startTime;
                       
                        document.write(respuesta);
                    }
                },
                error: function (xhr) {
                     document.write(xhr.responseText);
                }
            });

            return result;
        }
        
        

         /*
         *Gets the Verification Token value. The Verification Token is necessary for executing a service.
         */
        function getVerificationToken() {
            var tokenContent = null;
            var token = null;

            $.ajax({
                async: false,
                cache: false,
                type: "GET",
                url: servicesBaseUrl,
                xhrFields: {
                // The 'xhrFields' property sets additional fields on the XMLHttpRequest.
                // This can be used to set the 'withCredentials' property.
                // Set the value to 'true' if you'd like to pass cookies to the server.
                // If this is enabled, your server must respond with the header
                // 'Access-Control-Allow-Credentials: true'.
                withCredentials: true},
                processData: true,
                success: function (data, status, jqXHR) {

                    tokenContent = data;
                },
                error: function (xhr, a, b) {

                }
            });

            token = $.parseHTML(tokenContent)[0].defaultValue;

            return token;
        }

</script>