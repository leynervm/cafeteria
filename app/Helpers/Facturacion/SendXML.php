<?php

namespace App\Helpers\Facturacion;

use DOMDocument;
use ZipArchive;

class SendXML
{
    public function enviarComprobante($emisor, $nombre, $rutacertificado = "", $ruta_archivo_xml = "xml/", $ruta_archivo_cdr = "cdr/")
    {
        $objFirma = new Signature();
        $flg_firma = 0;
        $ruta = $ruta_archivo_xml . $nombre . '.xml';
        $ruta_firma = $rutacertificado . 'certificado_prueba_sunat.pfx';
        $pass_firma = '12345678';
        $hash = $objFirma->signatureXML($flg_firma, $ruta, $ruta_firma, $pass_firma);

        $zip = new ZipArchive();
        $nombrezip = $nombre . '.zip';
        $rutazip = $ruta_archivo_xml . $nombrezip;

        if ($zip->open($rutazip, ZipArchive::CREATE) == TRUE) {
            $zip->addFile($ruta, $nombre . '.xml');
            $zip->close();
        }

        // $ruta_archivo = $rutazip;
        // $nombre_archivo = $nombrezip;
        $contenidoBase64 = base64_encode(file_get_contents($rutazip));

        //WS BETA DE SUNAT
        $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
        //$ws = https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService //ws DE SUNAT PRODUCCION

        $xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                        <wsse:Username>' . $emisor->document . $emisor->usuariosol . '</wsse:Username>
                        <wsse:Password>' . $emisor->clavesol . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:sendBill>
                    <fileName>' . $nombrezip . '</fileName>
                    <contentFile>' . $contenidoBase64 . '</contentFile>
                </ser:sendBill>
            </soapenv:Body>
        </soapenv:Envelope>';

        $header = array(
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-lenght: " . strlen($xml_envio)
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $ws);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        //windows, cuando estemos productivos comenta esta linea
        curl_setopt($ch, CURLOPT_CAINFO, storage_path('app/empresa/cert/cacert.pem'));

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpcode == 200) {
            $doc = new DOMDocument();
            $doc->loadXML($response);

            if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)) {
                $cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue;
                $cdr = base64_decode($cdr);

                file_put_contents($ruta_archivo_cdr . "R-" . $nombrezip, $cdr);
                $zip = new ZipArchive();
                if ($zip->open($ruta_archivo_cdr . 'R-' . $nombrezip) == TRUE) {
                    $zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
                    $zip->close();

                    $respuestaCDR = $this->getRespuestaCDR($ruta_archivo_cdr . 'R-' . $nombre . '.xml');
                    $mensaje = json_encode([
                        'code' => $respuestaCDR->code,
                        'title' => 'Enviado correctamente',
                        'text' => $respuestaCDR->descripcion,
                        'hash' => $hash['hash_cpe'],
                        'type' => 'success',
                    ]);
                } else {
                    $mensaje = json_encode([
                        'code' => 'UNKNOWN',
                        'title' => 'Error',
                        'text' => 'No se pudo descomprimir el archivo CDR',
                        'hash' => $hash['hash_cpe'],
                        'type' => 'error',
                    ]);
                }
            } else {

                $codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
                $descripcion = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
                $mensaje = json_encode([
                    'code' => $codigo,
                    'title' => 'Mensaje',
                    'text' => $descripcion,
                    'hash' => $hash['hash_cpe'],
                    'type' => 'info',
                ]);
            }
        } else {
            $mensaje = json_encode([
                'code' => '500',
                'title' => 'Error de conexion , Codigo de error: ' . $httpcode,
                'text' => curl_error($ch),
                'hash' => $hash['hash_cpe'],
                'type' => 'error',
            ]);
        }

        return $mensaje;
    }


    public function getRespuestaCDR($ruta)
    {
        $doc = new DOMDocument();

        $doc->formatOutput = FALSE;
        $doc->preserveWhiteSpace = TRUE;
        $doc->load($ruta);

        //===================rescatamos respuesta CDR==================
        $code = $doc->getElementsByTagName('ResponseCode')->item(0)->nodeValue;
        $descripcion = $doc->getElementsByTagName('Description')->item(0)->nodeValue;

        return response()->json([
            'code' =>  $code,
            'descripcion' => $descripcion
        ])->getData();
    }
}
