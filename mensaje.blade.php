{{-- {{var_dump($msg)}} --}}
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Notificación Activación portal de pagos</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        </head>
        <body style="margin: 0; padding: 0;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">	
                <tr>
                    <td style="padding: 10px 0 30px 0;">
                        <table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc; border-collapse: collapse;">
               
                            <tr>
                                <td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="color: #153643; font-family: Arial, sans-serif; font-size: 24px;">
                                                <b>Señor(a) {{ $msg['nombres'] }}  </b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 20px 0 30px 0; color: #131c20; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
                                                <p>¡Felicitaciones!, {{ $msg['nombres'] }} {{ $msg['apellidos'] }}, la cuenta se activo correctamente, a continuación encontrará las credenciales para acceder:</p>
                                                <strong style="font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Usuario: {{ $msg['id'] }} </strong> <br>
                                                <strong style="font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">Contraseña: {{ $msg['password'] }}</strong>
                                                <p>Datos registrados:</p>                                       
                                                <p>Telefono: {{ $msg['telefono'] }}</p>
                                                <p>Email : {{ $msg['email'] }}</p>
                                                <br>
                                                <br>
                                                <strong style="font-family: Arial, sans-serif; font-size: 12px; line-height: 20px;">Gracias por preferirnos, enercer tu fuente de energía.</strong>
                                            </td>
                                        </tr>
                                        <tr>
            
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td bgcolor="#242323" style="padding: 20px 20px 20px 20px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px;" width="75%">
                                                &reg; Derechos Reservados Enercer 2021<br/>
                                                <a href="https://enercer.com/" style="color: #ffffff;"><font color="#ffffff">Enercer</font></a>  tu Fuente de Energía 
                                            </td>
                                            <td align="right" width="25%">
                                                <table border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                       
                                                               
                                                                <a href="https://ibb.co/hYnVXpK"><img width="120" height="120" src="https://i.ibb.co/hYnVXpK/Publiservicios.png" alt="Publiservicios" border="0"></a>
                                                           
                                                        </td>
                                                        <td style="font-size: 0; line-height: 0;" width="20">&nbsp;</td>
                                                        {{-- <td style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">
                                                            <a href="http://www.twitter.com/" style="color: #ffffff;">
                                                                <img src="img/facebook.png" alt="Facebook" width="38" height="38" style="display: block;" border="0" />
                                                            </a>
                                                        </td> --}}
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </body>
</html>