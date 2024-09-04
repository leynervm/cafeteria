<?php

namespace App\Helpers;

use DOMDocument;
use DOMException;
use SoapClient;

// $headers = new CustomHeaders('20538954099NEXTSOPO', 'Next2021');
// $sunatBeta = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';
// $xxx = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
// $sunatProduccion = 'wsdl-sunat/billService.wsdl';
// $sunatConsultas ='https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
// $serviceSunat = $sunatBeta;

# Procedimiento para enviar comprobante a la SUNAT
class feedSoap extends SoapClient
{

    public $XMLStr = "";

    public function setXMLStr($value)
    {
        $this->XMLStr = $value;
    }

    public function getXMLStr()
    {
        return $this->XMLStr;
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = $this->XMLStr;

        $dom = new DOMDocument('1.0');

        try {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }

        $request = $dom->saveXML();

        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }

    public function SoapClientCall($SOAPXML)
    {
        return $this->setXMLStr($SOAPXML);
    }
}
