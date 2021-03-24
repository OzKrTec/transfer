<?php

namespace App\Http\Controllers;

use SoapClient;

class soap extends Controller{

public function soap(){

    try {
      //code...
        $client = new SoapClient('https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?op=createTransactionPaymentHosting');
        var_dump($client->__getFunctions());

    } catch (Exception $e) {
      //throw $th;
      $e->getMessage();
    }


}

}

?>
