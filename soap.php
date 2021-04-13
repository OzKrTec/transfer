<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use SoapClient;
use SimpleXMLElement;

class soap extends Controller{

public function soap(Request $request){

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
echo 'Exito!';
$options = array(
    'cache_wsdl' => 0,
    'trace' => 1,
	'stream_context' => stream_context_create(array(
          'ssl' => array(
               'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
          )
    )),
    'ticketOfficeID' => '3109',
    'serviceCode' => '1001',
    'amount' => $request->amount,
    'vatAmount' => $request->amount,
    'vatAmount' => '1234',
    'paymentID' => $request->paymentID,
    'paymentDescription' => $request->paymentDescription,
    'referenceNumber1' => 'desc',
    'referenceNumber2' => 'desc',
    'referenceNumber3' => 'desc',
    'entity_url' => 'https://pruebas.enercer.com/main',
    'email' => 'demo@gmail.com',
    'name' => 'OzKr  SIerra',

);
    $url = 'https://200.1.124.65/PSEHostingWebServices/PSEHostingWS.asmx?WSDL';
    $client = new SoapClient($url,$options);

    // $xmlr = new SimpleXMLElement("<createTransactionPaymentHosting></createTransactionPaymentHosting>");
    // $xmlr->addChild('ticketOfficeID', '3109');
    // $xmlr->addChild('amount', '20000');
    // $xmlr->addChild('vatAmount', '0');
    // $xmlr->addChild('paymentID', '123');
    // $xmlr->addChild('paymentDescription', 'desc');
    // $xmlr->addChild('referenceNumber1', 'desc');
    // $xmlr->addChild('serviceCode', '1001');
    // $xmlr->addChild('entity_url', 'https://100.1.60.110');

    // $params = new \stdClass();
    // $params->xml = $xmlr->asXML();

    // $result = new SimpleXMLElement($client->createTransactionPaymentHosting($options));

     $result = $client->createTransactionPaymentHosting($options);//llamamos al métdo que nos interesa con los parámetros

    var_dump($result);

    // $value = get_object_vars($result);

    // echo $result[0]->PaymentIdentifier;
    $jsonString = json_encode($result);
    $variables = json_decode($jsonString,true);

    var_dump($variables);


    // $r = current($result->xpath('/Customers/ResultCode'));

    // if ( $r == '-1' ) {
    //         echo 'Fallo: '.$result->xpath('/Customers/ErrorMessage')[0];
    // } else {
    //         echo 'Exito!';
    // }
     return [
            'result' => $result
        ];
}

}

?>
