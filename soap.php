<?php

namespace App\Http\Controllers;

use SoapClient;

class soap extends Controller{

public function soap(){
define("DEBUG", TRUE);
if(DEBUG)
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
      //code...
      // $soapClient = new SoapClient('https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?op=createTransactionPaymentHosting');

      // $response=$soapClient->createTransactionPaymentHosting();

      // var_dump($response);
// try {
//       $servicio="https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?wsdl"; //url del servicio
//       $parametros=array(); //parametros de la llamada
//       $parametros['ticketOfficeID']="3109";
//       $parametros['amount']="20000";
//       $parametros['vatAmount']="0";
//       $parametros['paymentID']="123";
//       $parametros['paymentDescription']="desc";
//       $parametros['referenceNumber1']="ref1";
//       $parametros['referenceNumber2']="ref2";
//       $parametros['serviceCode']="1001";
//       $parametros['entity_url']="https://100.1.60.110";
//       $client = new SoapClient($servicio, $parametros);
//       $result = $client->createTransactionPaymentHosting($parametros);//llamamos al métdo que nos interesa con los parámetros

//       var_dump($result);

// }
// catch (Exception $e){
//     echo $e->getMessage();
// }
    $url = 'https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?wsdl';
    $client = new SoapClient($url);

    $xmlr = new SimpleXMLElement("<createTransactionPaymentHosting></createTransactionPaymentHosting>");
    $xmlr->addChild('ticketOfficeID', '3109');
    $xmlr->addChild('amount', '20000');
    $xmlr->addChild('vatAmount', '0');
    $xmlr->addChild('paymentID', '123');
    $xmlr->addChild('paymentDescription', 'desc');
    $xmlr->addChild('referenceNumber1', 'desc');
    $xmlr->addChild('serviceCode', '1001');
    $xmlr->addChild('entity_url', 'https://100.1.60.110');

    $params = new stdClass();
    $params->xml = $xmlr->asXML();

    $result = new SimpleXMLElement($client->createTransactionPaymentHosting($params)->CustomerSearchSResult->any);

    $r = current($result->xpath('/Customers/ResultCode'));

    if ( $r == '-1' ) {
            echo 'Fallo: '.$result->xpath('/Customers/ErrorMessage')[0];
    } else {
            echo 'Exito!';
    }

}

}

?>
