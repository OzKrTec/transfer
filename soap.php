<?php

namespace App\Http\Controllers;

use SoapClient;

class soap extends Controller{

public function soap(){

    try {
      //code...
      // $soapClient = new SoapClient('https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?op=createTransactionPaymentHosting');

      // $response=$soapClient->createTransactionPaymentHosting();

      // var_dump($response);

      $servicio="https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?op=createTransactionPaymentHosting"; //url del servicio
      $parametros=array(); //parametros de la llamada
      $parametros['ticketOfficeID']="3109";
      $parametros['amount']="20000";
      $parametros['vatAmount']="0";
      $parametros['paymentID']="123";
      $parametros['paymentDescription']="desc";
      $parametros['referenceNumber1']="ref1";
      $parametros['referenceNumber2']="ref2";
      $parametros['serviceCode']="1001";
      $parametros['entity_url']="https://100.1.60.110";
      $client = new SoapClient($servicio, $parametros);
      $result = $client->createTransactionPaymentHosting($parametros);//llamamos al métdo que nos interesa con los parámetros

      var_dump($result);

    } catch (Exception $e) {
      //throw $th;
      $e->getMessage();
    }


}

}

?>
