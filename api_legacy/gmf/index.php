<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

?>
<input type="hidden" value='<?=$_GET["tipo"]?>' id="tipo"/>
<apex:page docType="html-5.0" standardstylesheets="false" showHeader="false"> 
<script type="text/javascript"
               src="https://code.jquery.com/jquery-1.11.1.js"></script>
    <div id="areaMeses">
         <select id="meses">
             <option>12</option>
            <option>24</option>
            <option>36</option>
             <option>48</option>
             <option selected="selected">60</option>
        </select>
    </div>
    
  	<div id="areaAnos">
         <select id="ano">
             <option>2020</option>
            <option selected="selected">2019</option>
            <option>2018</option>
        </select>
    </div>
    <div id="areaMarcas">
        <select id="marca">
            
        </select>
    </div>
    <div id="areaModelos">
        <select id="modelo">
            
        </select>
    </div>
     <div id="areaPaquetes">
        <select id="paquetes">
            
        </select>
         <hr/>
          <div id="precio"></div>
         <table width='100%' >
             <tr>
             	<td>Minimo</td><td>Enganche</td><td>Máximo</td>
             </tr>
             <tr>
                 <td><div id="pagoMin"></div></td>
                 <td><input type="Number" id="eng" max="0" min="100"/></td>
                 <td><div id="pagoMax"></div></td>
             </tr>
         </table>
         <div id="btnConsultar">
             Consultar
         </div>
         
         <div id="mensualidad"></div>
    </div>
    
    <hr/>  
    <script language="Javascript">

if($('#tipo').val()=='test'){
    //var servicesBaseUrl = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    //var servicesBaseUrl = 'https://test.gmac-smartlink.com/CwsServiceProxyDEVTest/Services/';
    var servicesBaseUrl='https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/';

    //var servicioGM = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    //var http://test.gmac-smartlink.com/CwsServiceProxyDEVTest/Services
    var servicioGM = 'https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/';
        /*
        1- Buick
        2- Cadillac
        3- Chevrolet
        4- GMC

        */
}else{
     var servicesBaseUrl = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    //var servicesBaseUrl = 'https://test.gmac-smartlink.com/CwsServiceProxyDEVTest/Services/';
    //var servicesBaseUrl='https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/'
    
    var servicioGM = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    //var http://test.gmac-smartlink.com/CwsServiceProxyDEVTest/Services
    //var servicioGM = 'https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/';
}
        $(document).ready(function () {
            $('#btRunAll').click(function () {S

                var controls = $("button").not("#btRunAll, #btnErrorTest").each(
                    function (control) {
                        var service = $(this).text();
                        eval("test" + service + "();");

                    });
            });
            $('#btnGetMakes').click(function () {
                testGetMakes();
            });
            $('#btnGetModels').click(function () {
                testGetModels('2',2020);
            });
            $('#btnGetModelPackages').click(function () {
                testGetModelPackages();
            });
            $('#btnGetModelPackagesByCode').click(function () {
                testGetModelPackagesByCode();
            });
            $('#btnGetMontlyPayment').click(function () {
                testGetMontlyPayment();
            });
            $('#btnGetFinacialOptions').click(function () {
                testGetFinacialOptions();
            });
            $('#btnGeneratePdf').click(function () {
                testGeneratePdf();
            });
            $('#btnSendEmail').click(function () {
                testSendEmail();
            });
            $('#btnGenerateToken').click(function () {
                testGenerateToken();
            });
            $('#btnErrorTest').click(function () {
                testError();
            });
            $('#btnVehiclesInsurance').click(function () {
                testGetVehicleInsurance();
            });
            $('#btnObtenerConcesionarios').click(function () {
                ObtenerConcesionarios($('#zipcode').val(), $('#brand').val());
            });
            $('#btnObtenerConcesionarioDealerBAC').click(function () {
                ObtenerConcesionarioDealerBAC($('#txtBACId').val(), $('#txtMarcaId').val());
            });
            $('#btnObtenerInfoConcesionarios').click(function () {
                ObtenerInfoConcesionarios($('#txtmakeId').val(), $('#txtmakeCode').val(), $('#txtstate').val(), $('#txtdealerBACId').val());
            });
        }
        );

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
                // The 'xhrFields' property sets additional fields on the XMLHttpRequest.
                // This can be used to set the 'withCredentials' property.
                // Set the value to 'true' if you'd like to pass cookies to the server.
                // If this is enabled, your server must respond with the header
                // 'Access-Control-Allow-Credentials: true'.
                withCredentials: true},
                dataType: "json",
                processData: true,
                //Send Verification Token in the headers of the request.
                headers: headers,
                success: function (data, status, jqXHR) {
                    console.log(data);
                    /*
                     *All data that is retreived from the services returns a JSON, which contains
                     *an error member. If the error member is null, then the service was executed successfully,
                     *if not, then the service threw a business error (due validations) or an exception.
                     *In case of the services that require a token as argument, the token could be invalid, so,
                     *the result of the service would be as follows (GeneratePdf):
                     *
                     *  {"error":{"code":1,"message":"Token is not valid."},"file":null}
                     *OR
                     *  {"error":true,"file":null}
                     *
                     *If the service returns no error, then the result will look like this:
                     *
                     *{"error":null,"file":"filestringfilestringfilestringfilestring"}
                     *
                     *All the JSON responses have the previous "error" structure.
                     */
                    result = data;
                    if (asynchronous == null || asynchronous == undefined || asynchronous == true) {
                        if (data.error == null) {
                            $('#btn' + service).css('color', 'Green');
                        }
                        else {
                            $('#btn' + service).css('color', 'Red');
                        }
                        var respuesta=JSON.stringify(data);
                       
                        var requestTime = new Date().getTime() - startTime;
                        if(service=="GetMakes"){
                            //printMarca(data);
                         }
                        if(service=="GetModels"){
                            //printModels(data);
                         }
                        if(service=="GetModelPackages"){
                            //printPaquetes(data);
                         }
                        if(service=="GetMontlyPayment"){
                            //printMensualidad(data);
                         }
                        console.log(JSON.stringify(data));
                         $('#divGetMakes').text(JSON.stringify(data));
                        $('#divTimerGetMakes').text("Time(ms): " + requestTime);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    $('#btn' + service).css('color', 'Red');
                    $('#div' + service).html(xhr.responseText);
                }
            });

            return result;
        }
    /*Contrsuctor*/
    function printMarca(respuesta){
        console.log(respuesta["makes"]);
        var cadena="";
        var makeId=0;
       for(var i=0;i<respuesta["makes"].length;i++){
           if(respuesta["makes"][i].name=="Chevrolet"){
               makeId=respuesta["makes"][i].makeId;
           	cadena+='<option value="'+respuesta["makes"][i].makeId+'" selected>'+respuesta["makes"][i].name+'</option>';
      		}else{
                cadena+='<option value="'+respuesta["makes"][i].makeId+'">'+respuesta["makes"][i].name+'</option>';
            }
       }
       	$("#marca").html(cadena);
        testGetModels(makeId,2020);
    }
	
     function printModels(respuesta){
        console.log(respuesta["models"]);
        var cadena="";
       for(var i=0;i<respuesta["models"].length;i++){
           	cadena+='<option value="'+respuesta["models"][i].modelId+'">'+respuesta["models"][i].name+'</option>';
      		
       }
       	$("#modelo").html(cadena);
         var sel = $('#modelo');
        var selected = sel.val(); // cache selected value, before reordering
        var opts_list = sel.find('option');
        opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
        sel.html('').append(opts_list);
        sel.val(selected);
        sel.html('<option disabled value selected>Modelo</option>'+sel.html());        
        
    }
    
     function printPaquetes(respuesta){
        console.log(respuesta["packages"]);
        var cadena="";
         
       for(var i=0;i<respuesta["packages"].length;i++){
           	cadena+='<option value="'+respuesta["packages"][i].modelPackageId +'" data-max="'+respuesta["packages"][i].maximumDownpayment+'" data-min="'+respuesta["packages"][i].minimumDownpayment+'" data-precio="'+respuesta["packages"][i].price+'">'+respuesta["packages"][i].name+' | $'+formatNumber(respuesta["packages"][i].price)+'</option>';
      		
       }
       	$("#paquetes").html(cadena);
         var sel = $('#paquetes');
        var selected = sel.val(); // cache selected value, before reordering
        var opts_list = sel.find('option');
        opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
        sel.html('').append(opts_list);
        sel.val(selected);
         sel.html('<option disabled value selected>Paquete</option>'+sel.html());
                 
        
    }
    function printMensualidad(respuesta){
        $("#mensualidad").html("$"+formatNumber(respuesta["payment"].value));
    }
        
    /*ONCHANGE*/
    $("#paquetes").change(function(){
        
        $("#pagoMin").html('$'+formatNumber($('#paquetes option:selected').data("min")));
        $("#pagoMax").html('$'+formatNumber($('#paquetes option:selected').data("max")));
        $("#precio").html('Precio: $'+formatNumber($('#paquetes option:selected').data("precio")));
        $("#eng").val($('#paquetes option:selected').data("min"));
        $("#eng").attr("min",$('#paquetes option:selected').data("min"));
        $("#eng").attr("max",$('#paquetes option:selected').data("max"));   
    });

    $("#ano").change(function(){
         testGetModels($("#marca").val(),$(this).val());
    });
    $("#marca").change(function(){
         testGetModels($(this).val(),$("#ano").val());
    });	
    $("#modelo").change(function(){
        testGetModelPackages($(this).val(),$("#ano").val());
    });
    $("#btnConsultar").on("click",function(){
        testGetMontlyPayment($("#modelo option:selected").text(),$("#paquetes").val(),$("#eng").val(),$("#ano").val(),$("#meses").val());   
    });
    
    
    
    
    
    
    
    
    
    //var servicesBaseUrl='https://test.gmac-smartlink.com/CwsServiceProxyDEV/Services/'
    //var servicioGM = 'https://www.response.com.mx/CWSServiceProxy/Services/';
    
    var data={
        makeId:2,modelYear: 2020, vehicleTypeId:1
    }

    
   // postRequest('GetMakes', data);
     postRequest('btnGetModels', '');
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
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

        function testGetVehicleInsurance() {

            var data = {
                financingTypeId: 1,
                initialPercentage: 10,
                term:60,
                packageTypeId:1535,
                BOAKey:"CH1925A093644",
                invoiceValue:529100,
                postalCode:67140,
                specialTeam:0,
                SG:true,
                adaptations:0,
                optionalCoverage:''

            };
            postRequest('GetVehicleInsurance', data);
        }

    //postRequest('GetMakes', null);
    
        function testGetMakes() {
            postRequest('GetMakes', null);
        }

        function testGetModels(Id,Ano) {

            var data = {
                makeId: Id,
                modelYear: Ano
            };
            postRequest('GetModels', data);
        }

        function testGetModelPackages(modelIdVal,modelYearVal) {

            var data = {
                modelId: modelIdVal,
                modelYear: modelYearVal
            };
            postRequest('GetModelPackages', data);
        }

        function testGetModelPackagesByCode() {

            var data = {
                makeCode: 'chevrolet',
                modelCode: 'spark',
                modelYear: 2017
            };
            postRequest('GetModelPackagesByCode', data);
        }

        function testGetMontlyPayment(modelo,modelPackageIdVal,Eng,modelYearVal,termVal) {

            var data = {
                modelCode: modelo,
                modelPackageId: modelPackageIdVal,
                downpayment: Eng,
                modelYear: modelYearVal,
                term: termVal
            };
            postRequest('GetMontlyPayment', data);
        }

        function testGetFinacialOptions() {
            
            var data = {
                makeCode: "Chevrolet",
                makeId: 2,
                modelCode: "Spark",
                modelPackageId: 1436,
                downpayment: 19450,
                modelYear: 2017,
                term: 60,
                firstName: "Juan Vicente",
                lastName: "Sepúlveda",
                email: "vicente.sg9@gmail.com",
                postalCode: "64610",
                telephone: "8183002244",
                telephoneType: "HOME",
                dealerBACId: 1234,
                dealerName: 'Concesionario de Pruebas',
                programId: "GMMX_GMF",
                marketingId: "GMMX_GMF_WEB"
            };
            postRequest('GetFinacialOptions', data);
        }

        function testGeneratePdf() {

            var token = generateToken();

            if (token != null && token != undefined && token.error == null) {

                var options = new Array();
                options.push({ "category": "Plan Hot", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 15900.4, "planName": "2CCM| SUBS ACADIA 2016 (6-72M - 20%) #KLA", "term": 48 });
                options.push({ "category": "Plan Especial", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 17107.65, "planName": "HLCM| PLAN ACADIA 1ASG (6-72M-20%) #KLA", "term": 48 });
                options.push({ "category": "RightLease", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 10466.58, "planName": "LCCN| SUBS RIGHTLEASE GMF SUV 48M #KIG", "term": 48 });

                var data = {
                    options: options,
                    makeId: 6,
                    modelCode: "Acadia", modelYear: 2017, listPrice: 736800.00,
                    firstName: "Juan Vicente", lastName: "Sepúlveda González", email: "testtesttest@gmfinancial.com",
                    telephone: "83006544", postalCode: "64610", token: token.token,
                    dealerBACId: 1234,
                    dealerName: 'Concesionario de Pruebas',
                    modelPackage: "Paquete B"
                };

                //Get PDF file path.
                var pdfName = postRequest('GeneratePdf', data, false);

                var dialogPDF = $('#divPDF');
                dialogPDF.html('');

                $('#divGeneratePdf').text("");

                if (pdfName.error == null) {
                    $('#btnGeneratePdf').css('color', 'Green');
                }
                else {
                    $('#btnGeneratePdf').css('color', 'Red');
                }

                $('#divGeneratePdf').text($('#divGeneratePdf').text() + "\n" + JSON.stringify(pdfName));

                var downloadPdf = false;

                //Render PDF.
                var prnIFrame = $('<iframe style="width:100%; height:500px; overflow:scroll;">');
                //Set iFrame source as GetPdf service with fileName as argument.
                prnIFrame.prop('src', servicesBaseUrl + 'GetPdf?fileName=' + pdfName.file + '&downloadFile=' + downloadPdf);
                prnIFrame.appendTo(dialogPDF);

            }
        }

        function testSendEmail() {

            var token = generateToken();

            if (token != null && token != undefined && token.error == null) {

                var options = new Array();
                options.push({ "category": "Plan Hot", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 15900.4, "planName": "2CCM| SUBS ACADIA 2016 (6-72M - 20%) #KLA", "term": 48 });
                options.push({ "category": "Plan Especial", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 17107.65, "planName": "HLCM| PLAN ACADIA 1ASG (6-72M-20%) #KLA", "term": 48 });
                options.push({ "category": "RightLease", "downPayment": 150460, "modelCode": "TV14526 - E GMC Acadia Denali E", "monthlyPayment": 10466.58, "planName": "LCCN| SUBS RIGHTLEASE GMF SUV 48M #KIG", "term": 48 });

                var data = {
                    options: options,
                    makeId: 6,
                    modelCode: "Acadia", modelYear: 2016, listPrice: 736800.00,
                    firstName: "Juan Vicente", lastName: "Sepúlveda González", email: "testtesttest@gmfinancial.com",
                    telephone: "83006544", postalCode: "64610", token: token.token,
                    dealerBACId: 1234,
                    dealerName: 'Concesionario de Pruebas',
                    modelPackage: "Paquete B"
                };

                postRequest('SendEmail', data);
            }
        }

        function testGenerateToken() {

            var tokenData = { sessionId: getSessionId().toString() };

            var token = postRequest('GenerateToken', tokenData, true);
        }

        function testError() {

            var data = {
                modelCode: "Aveo", downpayment: 55434, term: 48, modelYear: 2016,
                firstName: "Cliente", lastName: "Perez", email: "testtesttest@gmfinancial.com",
                telephone: "83006544", postalCode: "64610", token: "invalid token"
            };

            var result = postRequest('GeneratePdf', data, false);

            $('#divErrorTest').text(JSON.stringify(result));
        }

        function ObtenerConcesionarios(codigoPostal, marca) {
            var data = { zipcode: codigoPostal, make: marca };

            var result = postRequest('GetDealerByZipCode', data, false);


            $('#btnObtenerConcesionarios').css('color', 'Green');
            $('#divObtenerConcesionarios').text(JSON.stringify(result));
        }

        function ObtenerConcesionarioDealerBAC(dealerBAC, marca) {
            var data = { dealerBACId: dealerBAC, make: marca };

            var result = postRequest('GetDealerInformation', data, false);


            $('#btnObtenerConcesionarioDealerBAC').css('color', 'Green');
            $('#divObtenerConcesionarioDealerBAC').text(JSON.stringify(result));
        }
        
        function ObtenerInfoConcesionarios(marca, nombremarca, estado, dealerBAC) {
            var data = { make: marca, makeCode: nombremarca, state: estado, dealerBACId: dealerBAC };

            var result = postRequest('GetDealerInformation', data, false);


            $('#btnObtenerInfoConcesionarios').css('color', 'Green');
            $('#divObtenerInfoConcesionarios').text(JSON.stringify(result));
        }

        function testObtenerConcesionarios(codigoPostal, marca) {
            var startTime = new Date().getTime();
            var data = { zipcode: codigoPostal, brands__id: marca };
            var resultado = null; 


            $.ajax({
                async: false,
                cache: false,
                type: "GET",
                url: servicioGM,
                data: { zipcode: codigoPostal, brands__id: marca, format: "json" }
            }).done(function (data, status, jqXHR) {
                resultado = data;
                if (data.error == null) {
                    $('#btnObtenerConcesionarios').css('color', 'Green');
                }
                else {
                    $('#btnObtenerConcesionarios').css('color', 'Red');
                }
                $('#divObtenerConcesionarios').text(JSON.stringify(data));
                var requestTime = new Date().getTime() - startTime;

                $('#divTimerObtenerConcesionarios').text("Time(ms): " + requestTime);

            }).fail(function (jqXHR, status, data) {
                $('#btnObtenerConcesionarios').css('color', 'Red');
                $('#divObtenerConcesionarios').text(jqXHR.responseText);
            });

            return resultado;
        }

        function generateToken() {

            var tokenData = { sessionId: getSessionId().toString() };

            var token = postRequest('GenerateToken', tokenData, false);

            return token;
        }

        function getSessionId() {
            return Math.floor((Math.random() * 100000) + 1);
        }
 function formatNumber(num) {
        if (!num || num == 'NaN') return '-';
        if (num == 'Infinity') return '&#x221e;';
        num = num.toString().replace(/\$|\,/g, '');
        if (isNaN(num))
            num = "0";
        sign = (num == (num = Math.abs(num)));
        num = Math.floor(num * 100 + 0.50000000001);
        cents = num % 100;
        num = Math.floor(num / 100).toString();
        if (cents < 10)
            cents = "0" + cents;
        for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
            num = num.substring(0, num.length - (4 * i + 3)) + ',' + num.substring(num.length - (4 * i + 3));
        return (((sign) ? '' : '-') + num + '.' + cents);
    }

    </script>

    <div id="divVerificationToken" style="display:none">
    </div>

        <button id="btRunAll">Run All</button>
    

    <br/>
        <input type="submit" id="btnGetMakes" value="GetMakes" />
        <div id="divGetMakes"></div>
    <div id="divTimerGetMakes"></div>

  

        <button id="btnGetModels">GetModels</button>
        <div id="divGetModels">
        </div>
        <div id="divTimerGetModels">
        </div>
  
        <button id="btnGetModelPackages">GetModelPackages</button>
        <div id="divGetModelPackages">
        </div>
        <div id="divTimerGetModelPackages">
        </div>

        <button id="btnGetModelPackagesByCode">GetModelPackagesByCode</button>
        <div id="divGetModelPackagesByCode">
        </div>
        <div id="divTimerGetModelPackagesByCode">
        </div>
 
        <button id="btnGetMontlyPayment">GetMontlyPayment</button>
        <div id="divGetMontlyPayment">
        </div>
        <div id="divTimerGetMontlyPayment">
        </div>
 
        <button id="btnGetFinacialOptions">GetFinacialOptions</button>
        <div id="divGetFinacialOptions">
        </div>
        <div id="divTimerGetFinacialOptions">
        </div>
 
        <button id="btnGeneratePdf">GeneratePdf</button>
        <div id="divGeneratePdf">
        </div>
        <div id="divTimerGeneratePdf">
        </div>
        <div id="divPDF" style="min-width: 500px; min-height: 300px; overflow: hidden;">
        </div>
  
        <button id="btnSendEmail">SendEmail</button>
        <div id="divSendEmail">
        </div>
        <div id="divTimerSendEmail">
        </div>
  
        <button id="btnGenerateToken">GenerateToken</button>
        <div id="divGetVehicleInsurance">
        </div>
        <div id="divTimerGetVehicleInsurance">
        </div>
  
        <button id="btnVehiclesInsurance">GetVehicleInsurance</button>
        <div id="divInsurance">
        </div>
        <div id="divTimerInsurance">
        </div>
  
        <button id="btnErrorTest">ErrorTest</button>
        <div id="divErrorTest">
        </div>
        <div id="divTimerErrorTest">
        </div>
 
    <p><label for="zipcode">Código postal: </label><input type="number" id="zipcode" value="64000" placeholder="Código postal" /></p>
    <p><label for="brand">Marca: </label><input type="number" id="brand" value="3" placeholder="Marca" /></p>
   
        <button id="btnObtenerConcesionarios">ObtenerConcesionarios</button>
        <div id="divObtenerConcesionarios" style="width:100%; height:200px; overflow:scroll;">
        </div>
        <div id="divTimerObtenerConcesionarios">
        </div>
  
    <p><label for="BACId">Dealer BAC : </label><input type="number" id="txtBACId" value="" placeholder="Dealer BAC" /></p>
    <p><label for="marcaId">Marca: </label><input type="number" id="txtMarcaId" value="3" placeholder="Marca" /></p>
  
        <button id="btnObtenerConcesionarioDealerBAC">Obtener Información Concesionarios</button>
        <div id="divObtenerConcesionarioDealerBAC" style="width:100%; height:100px; overflow:scroll;">
        </div>
        <div id="divTimerObtenerConcesionariosDealerBAC">
        </div>
    
    <p><label for="makeId">Marca Id: </label><input type="number" id="txtmakeId" value="3" placeholder="Marca Id" /></p>
    <p><label for="makeCode">Marca: </label><input type="text" id="txtmakeCode" value="" placeholder="Marca" /></p>
    <p><label for="state">Estado: </label><input type="text" id="txtstate" value="" placeholder="Estado" /></p>
    <p><label for="dealerBACId">Dealer BAC : </label><input type="number" id="txtdealerBACId" value="" placeholder="Dealer BAC" /></p>

        <button id="btnObtenerInfoConcesionarios">Obtener Información Concesionarios</button>
        <div id="divObtenerInfoConcesionarios" style="width:100%; height:200px; overflow:scroll;">
        </div>
        <div id="divTimerObtenerInfoConcesionarios">
        </div>

</apex:page>