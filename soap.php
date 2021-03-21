<?php

namespace App\Http\Controllers;
use SoapClient;

class soap extends Controller{

public function soap(){

    try {
      //code...
      $soapClient = new SoapClient('https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx');

      $response=$soapClient->createTransactionPaymentHosting();

      var_dump($response);

    } catch (Exception $e) {
      //throw $th;
      $e->getMessage();
    }


}

}

?>
