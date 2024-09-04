<?php

namespace App\Helpers;

use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use App\Models\Comprobante;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Storage;
use PclZip;
use SoapClient;
use SoapFault;

class generarXML
{

    protected $xml;
    protected $privateKey;
    protected $publicKey;
    protected $headers;
    protected $wsdl;

    public function __construct()
    {
        // $this->privateKey = dirname(__FILE__) . '/pem/private_key.pem';
        // $this->publicKey = dirname(__FILE__) . '/pem/public_key.pem';

        $this->xml = new DomDocument('1.0', "UTF-8");
        $this->xml->standalone = false;
        $this->xml->preserveWhiteSpace = false;
    }

    public function startXML(Comprobante $comprobante)
    {

        $DespatchAdvice = $this->xml->createElement('Invoice');
        $DespatchAdvice = $this->xml->appendChild($DespatchAdvice);

        $DespatchAdvice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
        $DespatchAdvice->setAttribute('xmlns:cac', "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2");
        $DespatchAdvice->setAttribute('xmlns:cbc', "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2");
        $DespatchAdvice->setAttribute('xmlns:ccts', "urn:un:unece:uncefact:documentation:2");
        $DespatchAdvice->setAttribute('xmlns:ds', "http://www.w3.org/2000/09/xmldsig#");
        $DespatchAdvice->setAttribute('xmlns:ext', "urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
        $DespatchAdvice->setAttribute('xmlns:qdt', "urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
        $DespatchAdvice->setAttribute('xmlns:udt', "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");
        $DespatchAdvice->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");

        $UBLExtension = $this->xml->createElement('ext:UBLExtensions');
        $UBLExtension = $DespatchAdvice->appendChild($UBLExtension);

        $ext = $this->xml->createElement('ext:UBLExtension');
        $ext = $UBLExtension->appendChild($ext);

        $contents = $this->xml->createElement('ext:ExtensionContent');
        $contents = $ext->appendChild($contents);

        $cbc = $this->xml->createElement('cbc:UBLVersionID', '2.1');
        $cbc = $DespatchAdvice->appendChild($cbc);

        $cbc = $this->xml->createElement('cbc:CustomizationID', '2.0');
        $cbc = $DespatchAdvice->appendChild($cbc);
        $cbc->setAttribute('schemeAgencyName', 'PE:SUNAT');

        $ProfileID = $this->xml->createElement('cbc:ProfileID', '0101');
        $ProfileID = $DespatchAdvice->appendChild($ProfileID);
        $ProfileID->setAttribute('schemeName', 'Tipo de Operacion');
        $ProfileID->setAttribute('schemeAgencyName', 'PE:SUNAT');
        $ProfileID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17');

        $this->getClavesFacturacion($comprobante->empresa);
        $this->addSignXML($contents);
    }

    public function addHeaderXML(Comprobante $comprobante)
    {

        $pathResult = $this->xml->getElementsByTagName('Invoice');
        //CLAVE

        if ($pathResult->length > 0) {
            $DespatchAdvice = $pathResult->item(0);

            $cbc = $this->xml->createElement('cbc:ID', $comprobante->seriecompleta); //Serie y número del comprobante
            $cbc = $DespatchAdvice->appendChild($cbc);

            $cbc = $this->xml->createElement('cbc:IssueDate', Carbon::parse($comprobante->date)->format('Y-m-d')); //Fecha de emisión
            $cbc = $DespatchAdvice->appendChild($cbc);

            $cbc = $this->xml->createElement('cbc:IssueTime', Carbon::parse($comprobante->date)->format("H:i:s")); //Hora de emisión
            $cbc = $DespatchAdvice->appendChild($cbc);

            $cbc = $this->xml->createElement('cbc:DueDate', Carbon::parse($comprobante->expire)->format('Y-m-d')); //Fecha de vencimiento
            $cbc = $DespatchAdvice->appendChild($cbc);

            $InvoiceTypeCode = $this->xml->createElement('cbc:InvoiceTypeCode', $comprobante->serie->code);  //Código de tipo de documento
            $InvoiceTypeCode = $DespatchAdvice->appendChild($InvoiceTypeCode);
            $InvoiceTypeCode->setAttribute('listAgencyName', 'PE:SUNAT');
            $InvoiceTypeCode->setAttribute('listName', 'Tipo de Documento');
            $InvoiceTypeCode->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01');
            $InvoiceTypeCode->setAttribute('listID', '0101');
            $InvoiceTypeCode->setAttribute('name', 'Tipo de Operacion');
            $InvoiceTypeCode->setAttribute('listSchemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51');


            $Note = $this->xml->createElement('cbc:Note', $comprobante->leyenda); //Leyenda
            $Note = $DespatchAdvice->appendChild($Note);
            $Note->setAttribute('languageLocaleID', '1000'); //Código de leyenda

            $leyenda = $this->xml->createElement('cbc:Note', "BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVA PARA SER CONSUMIDOS EN LA MISMA");
            $leyenda = $DespatchAdvice->appendChild($leyenda);
            $leyenda->setAttribute('languageLocaleID', '2001');

            $DocumentCurrencyCode = $this->xml->createElement('cbc:DocumentCurrencyCode', $comprobante->moneda);
            $DocumentCurrencyCode = $DespatchAdvice->appendChild($DocumentCurrencyCode);
            $DocumentCurrencyCode->setAttribute('listID', 'ISO 4217 Alpha');
            $DocumentCurrencyCode->setAttribute('listName', 'Currency');
            $DocumentCurrencyCode->setAttribute('listAgencyName', 'United Nations Economic Commission for Europe');


            $cbc = $this->xml->createElement('cbc:LineCountNumeric', count($comprobante->comprobanteitems)); //Cantidad de ítems de la factura
            $cbc = $DespatchAdvice->appendChild($cbc);
        }
    }

    public function addEmisorXML(Comprobante $comprobante)
    {

        $pathResult = $this->xml->getElementsByTagName('Invoice');

        if ($pathResult->length > 0) {
            $DespatchAdvice = $pathResult->item(0);

            //DATOS DEL EMISOR
            $signature = $this->xml->createElement('cac:Signature');
            $signature = $DespatchAdvice->appendChild($signature);

            $cbc = $this->xml->createElement('cbc:ID', 'IDSignSP');
            $cbc = $signature->appendChild($cbc);

            $signatoryParty = $this->xml->createElement('cac:SignatoryParty');
            $signatoryParty = $signature->appendChild($signatoryParty);

            $partyIdentif = $this->xml->createElement('cac:PartyIdentification');
            $partyIdentif = $signatoryParty->appendChild($partyIdentif);

            $cbc = $this->xml->createElement('cbc:ID', $comprobante->empresa->ruc);
            $cbc = $partyIdentif->appendChild($cbc);

            $partyName = $this->xml->createElement('cac:PartyName');
            $partyName = $signatoryParty->appendChild($partyName);

            $cbc = $this->xml->createElement('cbc:Name', $comprobante->empresa->name);
            $cbc = $partyName->appendChild($cbc);

            $sigAttachment = $this->xml->createElement('cac:DigitalSignatureAttachment');
            $sigAttachment = $signature->appendChild($sigAttachment);

            $extReference = $this->xml->createElement('cac:ExternalReference');
            $extReference = $sigAttachment->appendChild($extReference);

            $cbc = $this->xml->createElement('cbc:URI', '#SignatureSP');
            $cbc = $extReference->appendChild($cbc);


            $AccountingSupplierParty = $this->xml->createElement('cac:AccountingSupplierParty');
            $AccountingSupplierParty = $DespatchAdvice->appendChild($AccountingSupplierParty);

            $cac_party = $this->xml->createElement('cac:Party');
            $cac_party = $AccountingSupplierParty->appendChild($cac_party);

            $PartyIdentification = $this->xml->createElement('cac:PartyIdentification');
            $PartyIdentification = $cac_party->appendChild($PartyIdentification);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->empresa->ruc);
            $ID = $PartyIdentification->appendChild($ID);
            $ID->setAttribute('schemeID', '6');
            $ID->setAttribute('schemeName', 'Documento de Identidad');
            $ID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $ID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');

            $partyName = $this->xml->createElement('cac:PartyName');
            $partyName = $cac_party->appendChild($partyName);

            $cbc = $this->xml->createElement('cbc:Name');
            $cbc->appendChild($this->xml->createCDATASection($comprobante->empresa->name)); //Nombre Comercial del emisor
            $cbc = $partyName->appendChild($cbc);

            $PartyTaxScheme = $this->xml->createElement('cac:PartyTaxScheme');
            $PartyTaxScheme = $cac_party->appendChild($PartyTaxScheme);

            $RegistrationName = $this->xml->createElement('cbc:RegistrationName');
            $RegistrationName->appendChild($this->xml->createCDATASection($comprobante->empresa->name)); //Nombre o razón social del emisor 
            $RegistrationName = $PartyTaxScheme->appendChild($RegistrationName);

            $CompanyID = $this->xml->createElement('cbc:CompanyID', $comprobante->empresa->ruc); //Número de RUC del emisor
            $CompanyID = $PartyTaxScheme->appendChild($CompanyID);
            $CompanyID->setAttribute('schemeID', '6'); //Tipo de Documento de Identidad del Emisor
            $CompanyID->setAttribute('schemeName', 'SUNAT:Identificador de Documento de Identidad');
            $CompanyID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $CompanyID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');

            $TaxScheme = $this->xml->createElement('cac:TaxScheme');
            $TaxScheme = $PartyTaxScheme->appendChild($TaxScheme);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->empresa->ruc);
            $ID = $TaxScheme->appendChild($ID);
            $ID->setAttribute('schemeID', '6');
            $ID->setAttribute('schemeName', 'SUNAT:Identificador de Documento de Identidad');
            $ID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $ID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');

            $PartyLegalEntity = $this->xml->createElement('cac:PartyLegalEntity');
            $PartyLegalEntity = $cac_party->appendChild($PartyLegalEntity);

            $RegistrationName = $this->xml->createElement('cbc:RegistrationName');
            $RegistrationName->appendChild($this->xml->createCDATASection($comprobante->empresa->name));
            $RegistrationName = $PartyLegalEntity->appendChild($RegistrationName);

            $RegistrationAddress = $this->xml->createElement('cac:RegistrationAddress');
            $RegistrationAddress = $PartyLegalEntity->appendChild($RegistrationAddress);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->empresa->ubigeo);
            $ID = $RegistrationAddress->appendChild($ID);
            $ID->setAttribute('schemeName', 'Ubigeos');
            $ID->setAttribute('schemeAgencyName', 'PE:INEI');

            $AddressTypeCode = $this->xml->createElement('cbc:AddressTypeCode', '0000');
            $AddressTypeCode = $RegistrationAddress->appendChild($AddressTypeCode);
            $AddressTypeCode->setAttribute('listAgencyName', 'PE:SUNAT');
            $AddressTypeCode->setAttribute('listName', 'Establecimientos anexos');

            $cbc = $this->xml->createElement('cbc:CityName');
            $cbc->appendChild($this->xml->createCDATASection($comprobante->empresa->departamento));
            $cbc = $RegistrationAddress->appendChild($cbc);

            $cbc = $this->xml->createElement('cbc:CountrySubentity');
            $cbc->appendChild($this->xml->createCDATASection($comprobante->empresa->provincia));
            $cbc = $RegistrationAddress->appendChild($cbc);

            $cbc = $this->xml->createElement('cbc:District');
            $cbc->appendChild($this->xml->createCDATASection($comprobante->empresa->distrito));
            $cbc = $RegistrationAddress->appendChild($cbc);

            $AddressLine = $this->xml->createElement('cac:AddressLine');
            $AddressLine = $RegistrationAddress->appendChild($AddressLine);

            $Line = $this->xml->createElement('cbc:Line');
            $Line->appendChild($this->xml->createCDATASection($comprobante->empresa->direccion));
            $Line = $AddressLine->appendChild($Line);

            $country = $this->xml->createElement('cac:Country');
            $country = $RegistrationAddress->appendChild($country);

            $IdentificationCode = $this->xml->createElement('cbc:IdentificationCode', "PE");
            $IdentificationCode = $country->appendChild($IdentificationCode);
            $IdentificationCode->setAttribute('listID', 'ISO 3166-1');
            $IdentificationCode->setAttribute('listAgencyName', 'United Nations Economic Commission for Europe');
            $IdentificationCode->setAttribute('listName', 'Country');

            $Contact = $this->xml->createElement('cac:Contact');
            $Contact = $cac_party->appendChild($Contact);

            $Name = $this->xml->createElement('cbc:Name');
            $Name->appendChild($this->xml->createCDATASection(''));
            $Name = $Contact->appendChild($Name);
        }
    }

    public function addClientXML(Comprobante $comprobante)
    {

        $pathResult = $this->xml->getElementsByTagName('Invoice');

        if ($pathResult->length > 0) {

            $DespatchAdvice = $pathResult->item(0);

            $AccountingCustomerParty = $this->xml->createElement('cac:AccountingCustomerParty');
            $AccountingCustomerParty = $DespatchAdvice->appendChild($AccountingCustomerParty);

            $cac_party = $this->xml->createElement('cac:Party');
            $cac_party = $AccountingCustomerParty->appendChild($cac_party);

            $PartyIdentification = $this->xml->createElement('cac:PartyIdentification');
            $PartyIdentification = $cac_party->appendChild($PartyIdentification);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->client->document);
            $ID = $PartyIdentification->appendChild($ID);
            $ID->setAttribute('schemeID', Str::length($comprobante->client->document) == 11 ? 6 : 1);
            $ID->setAttribute('schemeName', 'Documento de Identidad');
            $ID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $ID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');

            $PartyName = $this->xml->createElement('cac:PartyName');
            $PartyName = $cac_party->appendChild($PartyName);

            $Name = $this->xml->createElement('cbc:Name');
            $Name->appendChild($this->xml->createCDATASection($comprobante->client->name));
            $Name = $PartyName->appendChild($Name);


            $PartyTaxScheme = $this->xml->createElement('cac:PartyTaxScheme');
            $PartyTaxScheme = $cac_party->appendChild($PartyTaxScheme);

            $RegistrationName = $this->xml->createElement('cbc:RegistrationName');
            $RegistrationName->appendChild($this->xml->createCDATASection($comprobante->client->name));
            $RegistrationName = $PartyTaxScheme->appendChild($RegistrationName);

            $CompanyID = $this->xml->createElement('cbc:CompanyID', $comprobante->client->document);
            $CompanyID = $PartyTaxScheme->appendChild($CompanyID);
            $CompanyID->setAttribute('schemeID', Str::length($comprobante->client->document) == 11 ? 6 : 1);
            $CompanyID->setAttribute('schemeName', 'SUNAT:Identificador de Documento de Identidad');
            $CompanyID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $CompanyID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');

            $TaxScheme = $this->xml->createElement('cac:TaxScheme');
            $TaxScheme = $PartyTaxScheme->appendChild($TaxScheme);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->client->document);
            $ID = $TaxScheme->appendChild($ID);
            $ID->setAttribute('schemeID', Str::length($comprobante->client->document) == 11 ? 6 : 1);
            $ID->setAttribute('schemeName', 'SUNAT:Identificador de Documento de Identidad');
            $ID->setAttribute('schemeAgencyName', 'PE:SUNAT');
            $ID->setAttribute('schemeURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06');


            $PartyLegalEntity = $this->xml->createElement('cac:PartyLegalEntity');
            $PartyLegalEntity = $cac_party->appendChild($PartyLegalEntity);

            $RegistrationName = $this->xml->createElement('cbc:RegistrationName');
            $RegistrationName->appendChild($this->xml->createCDATASection($comprobante->client->name));
            $RegistrationName = $PartyLegalEntity->appendChild($RegistrationName);

            $RegistrationAddress = $this->xml->createElement('cac:RegistrationAddress');
            $RegistrationAddress = $PartyLegalEntity->appendChild($RegistrationAddress);

            $ID = $this->xml->createElement('cbc:ID');
            $ID = $RegistrationAddress->appendChild($ID);
            $ID->setAttribute('schemeName', 'Ubigeos');
            $ID->setAttribute('schemeAgencyName', 'PE:INEI');

            $CityName = $this->xml->createElement('cbc:CityName');
            $CityName->appendChild($this->xml->createCDATASection(''));
            $CityName = $RegistrationAddress->appendChild($CityName);

            $CountrySubentity = $this->xml->createElement('cbc:CountrySubentity');
            $CountrySubentity->appendChild($this->xml->createCDATASection(''));
            $CountrySubentity = $RegistrationAddress->appendChild($CountrySubentity);

            $District = $this->xml->createElement('cbc:District');
            $District->appendChild($this->xml->createCDATASection(''));
            $District = $RegistrationAddress->appendChild($District);

            $AddressLine = $this->xml->createElement('cac:AddressLine');
            $AddressLine = $RegistrationAddress->appendChild($AddressLine);

            $Line = $this->xml->createElement('cbc:Line');
            $Line->appendChild($this->xml->createCDATASection($comprobante->client->direccion));
            $Line = $AddressLine->appendChild($Line);

            $country = $this->xml->createElement('cac:Country');
            $country = $RegistrationAddress->appendChild($country);

            $IdentificationCode = $this->xml->createElement('cbc:IdentificationCode', 'PERU');
            $IdentificationCode = $country->appendChild($IdentificationCode);
            $IdentificationCode->setAttribute('listID', 'ISO 3166-1');
            $IdentificationCode->setAttribute('listAgencyName', 'United Nations Economic Commission for Europe');
            $IdentificationCode->setAttribute('listName', 'Country');
        }
    }

    public function addBodyXML(Comprobante $comprobante)
    {

        $pathResult = $this->xml->getElementsByTagName('Invoice');

        if ($pathResult->length > 0) {

            $DespatchAdvice = $pathResult->item(0);

            $PaymentTerms = $this->xml->createElement('cac:PaymentTerms');
            $PaymentTerms = $DespatchAdvice->appendChild($PaymentTerms);

            $ID = $this->xml->createElement('cbc:ID', 'FormaPago');
            $ID = $PaymentTerms->appendChild($ID);

            $PaymentMeansID = $this->xml->createElement('cbc:PaymentMeansID', $comprobante->payment);
            $PaymentMeansID = $PaymentTerms->appendChild($PaymentMeansID);

            $TaxTotal = $this->xml->createElement('cac:TaxTotal');
            $TaxTotal = $DespatchAdvice->appendChild($TaxTotal);

            $sumaIGV = $this->xml->createElement('cbc:TaxAmount', $comprobante->igv); //Monto total del impuestos (Corresponde al importe total de impuestos ISC, IGV e IVAP de Corresponder) Se deberá colocar la sumatoria total de los impuestos. 

            $sumaIGV = $TaxTotal->appendChild($sumaIGV);
            $sumaIGV->setAttribute('currencyID', $comprobante->moneda);

            $TaxSubtotal = $this->xml->createElement('cac:TaxSubtotal');
            $TaxSubtotal = $TaxTotal->appendChild($TaxSubtotal);

            $TaxableAmount = $this->xml->createElement('cbc:TaxableAmount', $comprobante->total); //Monto las operaciones gravadas/exoneradas/inafectas del impuesto || RESUMEN = Valor de venta total sin impuestos|| (Total valor de venta - operaciones gravadas. ==Este elemento es usado solo si al menos una línea de ítem está gravada con el IGV. Contiene a la sumatoria de los valores de venta gravados por ítem (ver definición de valor de venta en punto 37) y la deducción de descuentos globales si lo hubiere. El total valor de venta no incluye IGV, ISC, cargos y otros Tributos si los hubiera. La sumatoria tampoco debe contener el valor de venta de las transferencias de bienes o servicios prestados a título gratuito comprendidos en la factura y que estuviesen gravados con el IGV.)

            // Total valor de venta - operaciones exoneradas.==  Este elemento es usado solo si al menos una línea de ítem se encuentra exonerada al IGV. Contiene a la sumatoria de valor de venta por ítem exonerados por item (ver definición de valor de venta x ítem en punto 37) y la deducción de descuentos globales si lo hubiere. El valor de venta no incluye ISC, cargos u otros Tributos si los hubiera. La sumatoria tampoco debe contener el valor de venta de las transferencias de bienes o servicios prestados a título gratuito comprendidos en la factura y que estuviesen exonerados del IGV.

            //ejm Gravado : TaxableAmount = 8560 es el total base de venta y en TaxAmount = al igv(18%)= 8560 * 0.18

            //ejm Exonerado TaxableAmount = 8560 es el total base de venta y en TaxAmount = al 0.00


            $TaxableAmount = $TaxSubtotal->appendChild($TaxableAmount);
            $TaxableAmount->setAttribute('currencyID', $comprobante->moneda);

            $sumaIGV = $this->xml->createElement('cbc:TaxAmount', $comprobante->igv); //Monto total del impuesto

            $sumaIGV = $TaxSubtotal->appendChild($sumaIGV);
            $sumaIGV->setAttribute('currencyID', $comprobante->moneda);

            $TaxCategory = $this->xml->createElement('cac:TaxCategory');
            $TaxCategory = $TaxSubtotal->appendChild($TaxCategory);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->tribute->abreviatura); //Categoría de impuestos [E - S]
            $ID = $TaxCategory->appendChild($ID);
            $ID->setAttribute('schemeID', 'UN/ECE 5305');
            $ID->setAttribute('schemeName', 'Tax Category Identifier');
            $ID->setAttribute('schemeAgencyName', 'United Nations Economic Commission for Europe');

            $TaxScheme = $this->xml->createElement('cac:TaxScheme');
            $TaxScheme = $TaxCategory->appendChild($TaxScheme);

            $ID = $this->xml->createElement('cbc:ID', $comprobante->tribute->code); //Código de tributo   [9997]
            $ID = $TaxScheme->appendChild($ID);
            $ID->setAttribute('schemeID', 'UN/ECE 5153');
            $ID->setAttribute('schemeAgencyID', '6');

            $cbc = $this->xml->createElement('cbc:Name', $comprobante->tribute->name); //Nombre de tributo [EXO - IGV]
            $cbc = $TaxScheme->appendChild($cbc);
            $cbc = $this->xml->createElement('cbc:TaxTypeCode', $comprobante->tribute->simbolo); //Código internacional tributo [VAT]
            $cbc = $TaxScheme->appendChild($cbc);

            $monetaryTotal = $this->xml->createElement('cac:LegalMonetaryTotal');
            $monetaryTotal = $DespatchAdvice->appendChild($monetaryTotal);

            $LineExtensionAmount = $this->xml->createElement('cbc:LineExtensionAmount', ($comprobante->total - $comprobante->igv - $comprobante->descuento)); //Total valor de venta(Es decir el importe total de la venta sin considerar los descuentos, impuestos u otros tributos)

            $LineExtensionAmount = $monetaryTotal->appendChild($LineExtensionAmount);
            $LineExtensionAmount->setAttribute('currencyID', $comprobante->moneda);

            $TaxInclusiveAmount = $this->xml->createElement('cbc:TaxInclusiveAmount', $comprobante->total); //Total precio de venta (se debe indicar el valor de venta total de la operación incluido los impuestos)

            $TaxInclusiveAmount = $monetaryTotal->appendChild($TaxInclusiveAmount);
            $TaxInclusiveAmount->setAttribute('currencyID', $comprobante->moneda);

            $AllowanceTotalAmount = $this->xml->createElement('cbc:AllowanceTotalAmount', $comprobante->descuento); //Monto total de descuentos globales del comprobante(se debe indicar el valor total de los descuentos globales realizados de ser el caso. )


            $AllowanceTotalAmount = $monetaryTotal->appendChild($AllowanceTotalAmount);
            $AllowanceTotalAmount->setAttribute('currencyID', $comprobante->moneda);

            $ChargeTotalAmount = $this->xml->createElement('cbc:ChargeTotalAmount',  $comprobante->otros); //Monto total de otros cargos del comprobante(Corresponde al total de otros cargos cobrados al adquirente o usuario y que no forman parte de la operación que se factura, es decir no forman parte del(os) valor(es) de ventas señaladas anteriormente, pero sí forman parte del importe total de la Venta (Ejemplo:propinas, garantías para devolución de envases, etc.))


            $ChargeTotalAmount = $monetaryTotal->appendChild($ChargeTotalAmount);
            $ChargeTotalAmount->setAttribute('currencyID', $comprobante->moneda);

            $payableAmount = $this->xml->createElement('cbc:PayableAmount', $comprobante->total); //Importe total de la venta, cesión en uso o del servicio prestado (Corresponde al importe total de la venta, de la cesión en uso o del servicio prestado. Es el resultado de la suma y/o resta (Según corresponda) de los siguientes puntos: 31-32+33 (Total precio Venta + Total Descuentos + Sumatoria Otros Cargos) menos los anticipos que hubieran sido recibidos. )


            $payableAmount = $monetaryTotal->appendChild($payableAmount);
            $payableAmount->setAttribute('currencyID', $comprobante->moneda);
        }
    }

    public function addItemsXML(Comprobante $comprobante)
    {

        $pathResult = $this->xml->getElementsByTagName('Invoice');

        if ($pathResult->length > 0) {

            $DespatchAdvice = $pathResult->item(0);

            foreach ($comprobante->comprobanteitems as $item) {

                $InvoiceLine = $this->xml->createElement('cac:InvoiceLine');
                $InvoiceLine = $DespatchAdvice->appendChild($InvoiceLine);
                $cbc = $this->xml->createElement('cbc:ID', $item->item); //Número de orden del Ítem
                $cbc = $InvoiceLine->appendChild($cbc);

                $cbc = $this->xml->createElement('cbc:InvoicedQuantity', floatval($item->cantidad)); //Cantidad de unidades del ítem
                $cbc = $InvoiceLine->appendChild($cbc);
                $cbc->setAttribute('unitCode', "NIU"); //Código de unidad de medida del ítem
                $cbc->setAttribute('unitCodeListID', 'UN/ECE rec 20');
                $cbc->setAttribute('unitCodeListAgencyName', 'United Nations Economic Commission for Europe');

                $cbc = $this->xml->createElement('cbc:LineExtensionAmount', $item->importe); //Valor de venta del ítem(es el producto de la cantidad por el valor unitario (Q x Valor Unitario) y la deducción de los descuentos aplicados a dicho ítem (de existir). Este importe no incluye los tributos (IGV, ISC y otros Tributos), los descuentos globales o cargos.)


                $cbc = $InvoiceLine->appendChild($cbc);
                $cbc->setAttribute('currencyID', $comprobante->moneda); //Código de tipo de moneda del valor de venta del ítem

                $pricingRef = $this->xml->createElement('cac:PricingReference');
                $pricingRef = $InvoiceLine->appendChild($pricingRef);

                $altCondicion = $this->xml->createElement('cac:AlternativeConditionPrice');
                $altCondicion = $pricingRef->appendChild($altCondicion);

                $cbc = $this->xml->createElement('cbc:PriceAmount', floatval($item->importe)); // 01 Precio de venta unitario/ 02  Valor referencial unitario en operaciones no onerosas (es el monto correspondiente al precio unitario facturado del bien vendido o servicio vendido. Este monto es la suma total que queda obligado a pagar el adquirente o usuario por cada bien o servicio. Esto incluye los tributos (IGV, ISC y otros Tributos) y la deducción de descuentos por ítem. Para identificar este monto se debe consignar el código “01” (Catálogo No. 16))

                //(Cuando la transferencia de bienes o de servicios se efectúe gratuitamente, se consignará el importe del valor de venta unitario que hubiera correspondido a dicho bien o servicio, en operaciones onerosas con terceros. En su defecto se aplicará el valor de mercado. Para identificar este valor, se debe de consignar el código “02” (incluido en el Catálogo No. 16).


                $cbc = $altCondicion->appendChild($cbc);
                $cbc->setAttribute('currencyID', $comprobante->moneda);

                $PriceTypeCode = $this->xml->createElement('cbc:PriceTypeCode', "01"); //Código de tipo de precio (01 || 02)

                $PriceTypeCode = $altCondicion->appendChild($PriceTypeCode);
                $PriceTypeCode->setAttribute('listName', 'Tipo de Precio');
                $PriceTypeCode->setAttribute('listAgencyName', 'PE:SUNAT');
                $PriceTypeCode->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16');

                $taxTotal = $this->xml->createElement('cac:TaxTotal');
                $taxTotal = $InvoiceLine->appendChild($taxTotal);

                $itemIGV = $this->xml->createElement('cbc:TaxAmount', floatval($item->igv)); //Monto de tributo del ítem (Total)
                $itemIGV = $taxTotal->appendChild($itemIGV);
                $itemIGV->setAttribute('currencyID', $comprobante->moneda);

                $taxSubTotal = $this->xml->createElement('cac:TaxSubtotal');
                $taxSubTotal = $taxTotal->appendChild($taxSubTotal);

                $TaxableAmount = $this->xml->createElement('cbc:TaxableAmount', floatval($item->importe)); //Monto de la operación
                $TaxableAmount = $taxSubTotal->appendChild($TaxableAmount);
                $TaxableAmount->setAttribute('currencyID', $comprobante->moneda);

                $itemIGV = $this->xml->createElement('cbc:TaxAmount', floatval($item->igv)); //Monto de tributo del ítem
                $itemIGV = $taxSubTotal->appendChild($itemIGV);
                $itemIGV->setAttribute('currencyID', $comprobante->moneda);

                $TaxCategory = $this->xml->createElement('cac:TaxCategory');
                $TaxCategory = $taxSubTotal->appendChild($TaxCategory);

                $ID = $this->xml->createElement('cbc:ID', "S"); //Categoría de impuestos [E - S]
                $ID = $TaxCategory->appendChild($ID);
                $ID->setAttribute('schemeID', 'UN/ECE 5305');
                $ID->setAttribute('schemeName', 'Tax Category Identifier');
                $ID->setAttribute('schemeAgencyName', 'United Nations Economic Commission for Europe');

                $Percent = $this->xml->createElement('cbc:Percent', $comprobante->percent); //Porcentaje del impuesto
                $Percent = $TaxCategory->appendChild($Percent);

                $reasonCode = $this->xml->createElement('cbc:TaxExemptionReasonCode', $item->igv == 0.00 ? '20' : '10'); //Código de tipo de afectación del IGV [10 ||20]
                $reasonCode = $TaxCategory->appendChild($reasonCode);
                $reasonCode->setAttribute('listAgencyName', 'PE:SUNAT');
                $reasonCode->setAttribute('listName', 'Afectacion del IGV');
                $reasonCode->setAttribute('listURI', 'urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07');

                $TaxScheme = $this->xml->createElement('cac:TaxScheme');
                $TaxScheme = $TaxCategory->appendChild($TaxScheme);

                $ID = $this->xml->createElement('cbc:ID', $comprobante->tribute->code); //Código internacional tributo [9997]
                $ID = $TaxScheme->appendChild($ID);
                $ID->setAttribute('schemeID', 'UN/ECE 5153');
                $ID->setAttribute('schemeName', 'Codigo de tributos');
                $ID->setAttribute('schemeAgencyName', 'PE:SUNAT');

                $cbc = $this->xml->createElement('cbc:Name', $comprobante->tribute->name); //Nombre de tributo [EXO - IGV]
                $cbc = $TaxScheme->appendChild($cbc);

                $cbc = $this->xml->createElement('cbc:TaxTypeCode', $comprobante->tribute->simbolo); //Código del tributo [VAT]
                $cbc = $TaxScheme->appendChild($cbc);


                $cacItem = $this->xml->createElement('cac:Item');
                $cacItem = $InvoiceLine->appendChild($cacItem);

                $cbc = $this->xml->createElement('cbc:Description');
                $cbc->appendChild($this->xml->createCDATASection($item->descripcion)); //Descripción detallada del servicio prestado, bien vendido o cedido en uso, indicando las características.
                $cbc = $cacItem->appendChild($cbc);

                $sellers = $this->xml->createElement('cac:SellersItemIdentification');
                $sellers = $cacItem->appendChild($sellers);

                // $cbc = $this->xml->createElement('cbc:ID',$item->"codigo_producto"]); 
                // $cbc = $sellers->appendChild($cbc);

                $cbc = $this->xml->createElement('cbc:ID');
                $cbc->appendChild($this->xml->createCDATASection($item->id));  //Código de producto del ítem
                $cbc = $sellers->appendChild($cbc);


                $Price = $this->xml->createElement('cac:Price');
                $Price = $InvoiceLine->appendChild($Price);

                $cbc = $this->xml->createElement('cbc:PriceAmount', floatval($item->importe)); //Valor unitario del ítem(Se consignará el importe correspondiente al valor o monto unitario del bien vendido, cedido o servicio prestado, indicado en una línea o ítem de la factura. Este importe no incluye los tributos (IGV, ISC y otros Tributos) ni los cargos globales.)

                $cbc = $Price->appendChild($cbc);
                $cbc->setAttribute('currencyID', $comprobante->moneda);
            }
        }
    }

    public function getClavesFacturacion($empresa)
    {
        $this->privateKey = Storage::disk('local')->path('empresa/pem/' . $empresa->ruc . '_private.pem');
        $this->publicKey = Storage::disk('local')->path('empresa/pem/' . $empresa->ruc . '_public.pem');
        $this->headers = new CustomHeaders($empresa->ruc . $empresa->usuariosol, $empresa->clavesol);
        // $this->headers = new CustomHeaders('20538954099NEXTSOPO', 'Next2021');
    }

    public function saveXML($filename, $ruta)
    {
        $this->xml->formatOutput = true;
        $strings_xml       = $this->xml->saveXML();
        // $ruta = public_path($filename . '.xml');
        Storage::put($ruta . $filename . '.xml', $strings_xml);
        // $this->xml->save($ruta . $filename . '.xml');
    }

    public function addSignXML($content)
    {

        $signature = new XMLSecurityDSig();
        $signature->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
        $signature->addReference(
            $this->xml,
            XMLSecurityDSig::SHA1,
            array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
            array('force_uri' => true)
        );
        $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
        //Cargamos la clave privada
        $key->loadKey($this->privateKey, true);
        $signature->sign($key);
        // Agregue la clave pública asociada a la firma
        $signature->add509Cert(file_get_contents($this->publicKey), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
        $signature->appendSignature($content);
        // $signature->appendSignature($this->xml->getElementsByTagName('ExtensionContent')->item(0));
    }

    public function extractDigestValue()
    {
        $pathResult = $this->xml->getElementsByTagName('DigestValue');
        return $pathResult->item(0)->nodeValue ?? null;
    }

    public function addToZipXML($path, $filename)
    {

        $zip = new PclZip($path . $filename . '.zip');
        $result = $zip->create($path . $filename . '.xml', PCLZIP_OPT_REMOVE_PATH, $path);

        if ($result === 0) {
            return $zip->errorInfo(true);
        } else {

            // generarXML::sendXMLSUNAT($pathZip, $pathCDR, $filename, $wsdl);;
            // $response = $this->sendXMLSUNAT($path, $filename, public_path('storage/CDR/'));
            return true;
        }
        // $zip->errorInfo()

    }

    public function sendXMLSUNAT($pathZip, $filename, $pathCDR)
    {

        try {

            // $wsdl = config('app.wsdl_sunat');
            // $wsdl = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
            $wsdl = __DIR__ . '/wsdl-sunat/billService.wsdl';

            $params = array(
                'fileName' => $filename . '.zip',
                'contentFile' => file_get_contents($pathZip . $filename . '.zip')
            );

            $client = new SoapClient($wsdl, [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => TRUE,
                // 'soap_version' => SOAP_1_1 
            ]);

            $client->__setSoapHeaders([$this->headers]);
            // $fcs = $client->__getFunctions();
            // $status = $client->sendBill($params);
            $client->sendBill($params);

            if ($client->__getLastResponse()) {

                $status = $client->__getLastResponse();

                $archivo = fopen($pathCDR . 'C' . $filename . '.xml', 'w+');
                fputs($archivo, $status);
                fclose($archivo);

                /*LEEMOS EL ARCHIVO XML*/
                $xml = simplexml_load_file($pathCDR . 'C' . $filename . '.xml');
                foreach ($xml->xpath('//applicationResponse') as $response) {
                }

                /*AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)*/
                $cdr = base64_decode($response);
                $archivo = fopen($pathCDR . 'R-' . $filename . '.zip', 'w+');
                fputs($archivo, $cdr);
                fclose($archivo);

                $cdrZip = new PclZip($pathCDR . 'R-' . $filename . ".zip");
                $result = $cdrZip->extract(PCLZIP_OPT_PATH, $pathCDR);

                if ($result === 0) {
                    return $cdrZip->errorInfo(true);
                } else {
                    unlink($pathCDR . 'C' . $filename . '.xml');
                    unlink($pathZip . $filename . '.zip');
                    $cdrZip->delete();
                    return 'Comprobante enviado correctamente';
                }
            }
        } catch (SoapFault $fault) {
           
            return   "Código : " . substr($fault->faultcode, -4) . "</br>" . $fault->faultstring;
        }
    }

    public function extractCodeSUNAT($path)
    {

        $xml = file_get_contents($path);
        $DOM = new DOMDocument('1.0', 'utf-8');
        $DOM->preserveWhiteSpace = FALSE;
        $DOM->loadXML($xml);

        // Obteniendo Codigo de Respuesta.
        $DocXML = $DOM->getElementsByTagName('ResponseCode');
        $responseCode = $DocXML->item(0)->nodeValue;

        // Obteniendo descripcion.
        $DocXML = $DOM->getElementsByTagName('Description');
        $descripcion = $DocXML->item(0)->nodeValue;

        return [
            "code" => $responseCode ?? null,
            "descripcion" => $descripcion ?? null
        ];
    }

    private function evaluate($xpath)
    {
        $xpathObject = new \DOMXPath($this->xml);
        return $xpathObject->evaluate($xpath);
    }
}
