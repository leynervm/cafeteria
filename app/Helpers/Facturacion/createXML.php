<?php

namespace App\Helpers\Facturacion;

use App\Helpers\FormatoPersonalizado;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\Storage;

class createXML
{
   function comprobanteVentaXML($nombreXML, $emisor, $cliente, $comprobante)
   {

      //   dd($nombreXML, $emisor, $emisor->ubigeo, $cliente->name, $comprobante);

      $doc = new DOMDocument();
      $doc->formatOutput = false;
      $doc->preserveWhiteSpace = false;
      $doc->encoding = 'utf-8';

      $codeDocumentClient = strlen(trim($cliente->document)) == 11 ? '6' : '1';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
           <cbc:ProfileID schemeName="Tipo de Operacion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
           <cbc:ID>' . $comprobante->seriecompleta . '</cbc:ID>
           <cbc:IssueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:IssueDate>
           <cbc:IssueTime>' . Carbon::parse($comprobante->date)->format('H:i:s') . '</cbc:IssueTime>
           <cbc:DueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:DueDate>
           <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01" listID="0101" name="Tipo de Operacion" listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51">' . $comprobante->serie->code . '</cbc:InvoiceTypeCode>
           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante->leyenda . ']]></cbc:Note>';

      if ($comprobante->exonerado > 0) {
         $xml .= '<cbc:Note languageLocaleID="2001"><![CDATA[ BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA ]]></cbc:Note>';
      }

      $xml .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $comprobante->moneda . '</cbc:DocumentCurrencyCode>
           <cbc:LineCountNumeric>' . count($comprobante->comprobanteitems) . '</cbc:LineCountNumeric>
           <cac:Signature>
              <cbc:ID>' . $emisor->ruc . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor->name . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor->ruc . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SignatureSP</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyTaxScheme>
                     <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                     <cbc:CompanyID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:CompanyID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:ID>
                     </cac:TaxScheme>
                  </cac:PartyTaxScheme>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $emisor->ubigeo . '</cbc:ID>
                       <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                       <cbc:CityName>
                           <![CDATA[' . trim($emisor->departamento) . ']]>
                       </cbc:CityName>
                       <cbc:CountrySubentity>
                           <![CDATA[' . trim($emisor->provincia) . ']]>
                       </cbc:CountrySubentity>
                       <cbc:District>
                           <![CDATA[' . trim($emisor->distrito) . ']]>
                       </cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . trim($emisor->direccion) . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
                 <cac:Contact>
                     <cbc:Name><![CDATA[ ]]></cbc:Name>
                  </cac:Contact>
              </cac:Party>
           </cac:AccountingSupplierParty>
           <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $codeDocumentClient . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cliente->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $comprobante->direccion . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PERU</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>
           <cac:PaymentTerms>
               <cbc:ID>FormaPago</cbc:ID>
               <cbc:PaymentMeansID>' . $comprobante->payment . '</cbc:PaymentMeansID>
            </cac:PaymentTerms>
            <cac:TaxTotal>
               <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>';

      if ($comprobante->gravado > 0) {
         $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gravado) . '</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>
                     <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                        <cac:TaxScheme>
                           <cbc:ID>1000</cbc:ID>
                           <cbc:Name>IGV</cbc:Name>
                           <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';
      }

      if ($comprobante->exonerado > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->exonerado) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                          <cbc:Name>EXO</cbc:Name>
                          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      $total_antes_de_impuestos = $comprobante->gravado + $comprobante->exonerado + $comprobante->descuentos;

      $xml .= '</cac:TaxTotal>
           <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($total_antes_de_impuestos) . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:TaxInclusiveAmount>
              <cbc:PayableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:PayableAmount>
           </cac:LegalMonetaryTotal>';

      foreach ($comprobante->comprobanteitems as $item) {

         $xml .= '<cac:InvoiceLine>
                  <cbc:ID>' . $item->item . '</cbc:ID>
                  <cbc:InvoicedQuantity unitCode="' . trim($item->unit) . '" unitCodeListAgencyName="United Nations Economic Commission for Europe" unitCodeListID="UN/ECE rec 20">' . FormatoPersonalizado::getValueDecimal($item->cantidad) . '</cbc:InvoicedQuantity>
                  <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->importe) . '</cbc:LineExtensionAmount>
                  <cac:PricingReference>
                     <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv > 0 ? $item->igv + $item->price : $item->price) . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
                     </cac:AlternativeConditionPrice>
                  </cac:PricingReference>
                  <cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv) . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->importe) . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv) . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                           <cbc:Percent>' . FormatoPersonalizado::getValueDecimal(0) . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">20</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">9997</cbc:ID>
                              <cbc:Name>EXO</cbc:Name>
                              <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>
                  </cac:TaxTotal>
                  <cac:Item>
                     <cbc:Description><![CDATA[' . $item->descripcion . ']]></cbc:Description>
                     <cac:SellersItemIdentification>
                        <cbc:ID>' . $item->code . '</cbc:ID>
                     </cac:SellersItemIdentification>
                  </cac:Item>
                  <cac:Price>
                     <cbc:PriceAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->price) . '</cbc:PriceAmount>
                  </cac:Price>
               </cac:InvoiceLine>';
      }

      $xml .= "</Invoice>";

      $doc->loadXML($xml);
      $xmlString = $doc->saveXML();
      Storage::disk('local')->put($nombreXML . '.xml', $xmlString);
   }



   function comprobanteNotaCreditoXML($nombreXML, $emisor, $cliente, $comprobante)
   {

      //   dd($nombreXML, $emisor, $emisor->ubigeo, $cliente->name, $comprobante);

      $doc = new DOMDocument();
      $doc->formatOutput = false;
      $doc->preserveWhiteSpace = false;
      $doc->encoding = 'utf-8';

      $codeDocumentClient = strlen(trim($cliente->document)) == 11 ? '6' : '1';
      $referencia = substr(trim($comprobante->referencia), 0, 1);
      $codeReferencia = $referencia == 'F' || $referencia == 'f' ? '01' : '03';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ccts="urn:un:unece:uncefact:documentation:2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2" xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2" xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1" xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2 ../../../Proyecto%20OSE/UBL/2.1/maindoc/UBL-CreditNote-2.1.xsd">
           <ext:UBLExtensions>
              <ext:UBLExtension>
                 <ext:ExtensionContent />
              </ext:UBLExtension>
           </ext:UBLExtensions>
           <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
           <cbc:CustomizationID schemeAgencyName="PE:SUNAT">2.0</cbc:CustomizationID>
           <cbc:ProfileID schemeName="Tipo de Operacion" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo17">0101</cbc:ProfileID>
           <cbc:ID>' . $comprobante->seriecompleta . '</cbc:ID>
           <cbc:IssueDate>' . Carbon::parse($comprobante->date)->format('Y-m-d') . '</cbc:IssueDate>
           <cbc:IssueTime>' . Carbon::parse($comprobante->date)->format('H:i:s') . '</cbc:IssueTime>
           <cbc:Note languageLocaleID="1000"><![CDATA[' . $comprobante->leyenda . ']]></cbc:Note>';

      if ($comprobante->exonerado > 0) {
         $xml .= '<cbc:Note languageLocaleID="2001"><![CDATA[ BIENES TRANSFERIDOS EN LA AMAZONÍA REGIÓN SELVAPARA SER CONSUMIDOS EN LA MISMA ]]></cbc:Note>';
      }

      $xml .= '<cbc:DocumentCurrencyCode listID="ISO 4217 Alpha" listName="Currency" listAgencyName="United Nations Economic Commission for Europe">' . $comprobante->moneda . '</cbc:DocumentCurrencyCode>
            <cac:DiscrepancyResponse>
               <cbc:ReferenceID>' . $comprobante->referencia . '</cbc:ReferenceID>
               <cbc:ResponseCode>01</cbc:ResponseCode>
               <cbc:Description><![CDATA[ANULACIÓN DE LA OPERACIÓN]]></cbc:Description>
            </cac:DiscrepancyResponse>
            <cac:BillingReference>
               <cac:InvoiceDocumentReference>
                  <cbc:ID>' . $comprobante->referencia . '</cbc:ID>
                  <cbc:DocumentTypeCode>' . $codeReferencia . '</cbc:DocumentTypeCode>
               </cac:InvoiceDocumentReference>
            </cac:BillingReference>
            <cac:Signature>
              <cbc:ID>' . $emisor->ruc . '</cbc:ID>
              <cbc:Note><![CDATA[' . $emisor->name . ']]></cbc:Note>
              <cac:SignatoryParty>
                 <cac:PartyIdentification>
                    <cbc:ID>' . $emisor->ruc . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
              </cac:SignatoryParty>
              <cac:DigitalSignatureAttachment>
                 <cac:ExternalReference>
                    <cbc:URI>#SignatureSP</cbc:URI>
                 </cac:ExternalReference>
              </cac:DigitalSignatureAttachment>
           </cac:Signature>
           <cac:AccountingSupplierParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyName>
                    <cbc:Name><![CDATA[' . $emisor->name . ']]></cbc:Name>
                 </cac:PartyName>
                 <cac:PartyTaxScheme>
                     <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                     <cbc:CompanyID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:CompanyID>
                     <cac:TaxScheme>
                        <cbc:ID schemeID="6" schemeName="SUNAT:Identificador de Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $emisor->ruc . '</cbc:ID>
                     </cac:TaxScheme>
                  </cac:PartyTaxScheme>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $emisor->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cbc:ID schemeName="Ubigeos" schemeAgencyName="PE:INEI">' . $emisor->ubigeo . '</cbc:ID>
                       <cbc:AddressTypeCode listAgencyName="PE:SUNAT" listName="Establecimientos anexos">0000</cbc:AddressTypeCode>
                       <cbc:CityName>
                           <![CDATA[' . trim($emisor->departamento) . ']]>
                       </cbc:CityName>
                       <cbc:CountrySubentity>
                           <![CDATA[' . trim($emisor->provincia) . ']]>
                       </cbc:CountrySubentity>
                       <cbc:District>
                           <![CDATA[' . trim($emisor->distrito) . ']]>
                       </cbc:District>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . trim($emisor->direccion) . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PE</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
                 <cac:Contact>
                     <cbc:Name><![CDATA[ ]]></cbc:Name>
                  </cac:Contact>
              </cac:Party>
           </cac:AccountingSupplierParty>
           <cac:AccountingCustomerParty>
              <cac:Party>
                 <cac:PartyIdentification>
                    <cbc:ID schemeID="' . $codeDocumentClient . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $cliente->document . '</cbc:ID>
                 </cac:PartyIdentification>
                 <cac:PartyLegalEntity>
                    <cbc:RegistrationName><![CDATA[' . $cliente->name . ']]></cbc:RegistrationName>
                    <cac:RegistrationAddress>
                       <cac:AddressLine>
                          <cbc:Line><![CDATA[' . $comprobante->direccion . ']]></cbc:Line>
                       </cac:AddressLine>
                       <cac:Country>
                          <cbc:IdentificationCode listID="ISO 3166-1" listAgencyName="United Nations Economic Commission for Europe" listName="Country">PERU</cbc:IdentificationCode>
                       </cac:Country>
                    </cac:RegistrationAddress>
                 </cac:PartyLegalEntity>
              </cac:Party>
           </cac:AccountingCustomerParty>
            <cac:TaxTotal>
               <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>';



      if ($comprobante->gravado > 0) {
         $xml .= '<cac:TaxSubtotal>
                     <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->gravado) . '</cbc:TaxableAmount>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->igv) . '</cbc:TaxAmount>
                     <cac:TaxCategory>
                     <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                        <cac:TaxScheme>
                           <cbc:ID>1000</cbc:ID>
                           <cbc:Name>IGV</cbc:Name>
                           <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                        </cac:TaxScheme>
                     </cac:TaxCategory>
                  </cac:TaxSubtotal>';
      }

      if ($comprobante->exonerado > 0) {
         $xml .= '<cac:TaxSubtotal>
                    <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->exonerado) . '</cbc:TaxableAmount>
                    <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">0.00</cbc:TaxAmount>
                    <cac:TaxCategory>
                       <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                       <cac:TaxScheme>
                          <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9997</cbc:ID>
                          <cbc:Name>EXO</cbc:Name>
                          <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                       </cac:TaxScheme>
                    </cac:TaxCategory>
                 </cac:TaxSubtotal>';
      }

      $total_antes_de_impuestos = $comprobante->gravado + $comprobante->exonerado + $comprobante->descuentos;

      $xml .= '</cac:TaxTotal>
           <cac:LegalMonetaryTotal>
              <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($total_antes_de_impuestos) . '</cbc:LineExtensionAmount>
              <cbc:TaxInclusiveAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:TaxInclusiveAmount>
              <cbc:PayableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($comprobante->total) . '</cbc:PayableAmount>
           </cac:LegalMonetaryTotal>';

      foreach ($comprobante->comprobanteitems as $item) {

         $xml .= '<cac:CreditNoteLine>
                  <cbc:ID>' . $item->item . '</cbc:ID>
                  <cbc:CreditedQuantity unitCode="' . trim($item->unit) . '" unitCodeListAgencyName="United Nations Economic Commission for Europe" unitCodeListID="UN/ECE rec 20">' . FormatoPersonalizado::getValueDecimal($item->cantidad) . '</cbc:CreditedQuantity>
                  <cbc:LineExtensionAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->importe) . '</cbc:LineExtensionAmount>
                  <cac:PricingReference>
                     <cac:AlternativeConditionPrice>
                        <cbc:PriceAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv > 0 ? $item->igv + $item->price : $item->price) . '</cbc:PriceAmount>
                        <cbc:PriceTypeCode listName="Tipo de Precio" listAgencyName="PE:SUNAT" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">01</cbc:PriceTypeCode>
                     </cac:AlternativeConditionPrice>
                  </cac:PricingReference>
                  <cac:TaxTotal>
                     <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv) . '</cbc:TaxAmount>
                     <cac:TaxSubtotal>
                        <cbc:TaxableAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->importe) . '</cbc:TaxableAmount>
                        <cbc:TaxAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->igv) . '</cbc:TaxAmount>
                        <cac:TaxCategory>
                            <cbc:ID schemeID="UN/ECE 5305" schemeName="Tax Category Identifier" schemeAgencyName="United Nations Economic Commission for Europe">E</cbc:ID>
                           <cbc:Percent>' . FormatoPersonalizado::getValueDecimal(0) . '</cbc:Percent>
                           <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT" listName="Afectacion del IGV" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">20</cbc:TaxExemptionReasonCode>
                           <cac:TaxScheme>
                              <cbc:ID schemeID="UN/ECE 5153" schemeName="Codigo de tributos" schemeAgencyName="PE:SUNAT">9997</cbc:ID>
                              <cbc:Name>EXO</cbc:Name>
                              <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                           </cac:TaxScheme>
                        </cac:TaxCategory>
                     </cac:TaxSubtotal>
                  </cac:TaxTotal>
                  <cac:Item>
                     <cbc:Description><![CDATA[' . $item->descripcion . ']]></cbc:Description>
                     <cac:SellersItemIdentification>
                        <cbc:ID>' . $item->code . '</cbc:ID>
                     </cac:SellersItemIdentification>
                  </cac:Item>
                  <cac:Price>
                     <cbc:PriceAmount currencyID="' . $comprobante->moneda . '">' . FormatoPersonalizado::getValueDecimal($item->price) . '</cbc:PriceAmount>
                  </cac:Price>
               </cac:CreditNoteLine>';
      }

      $xml .= "</CreditNote>";

      $doc->loadXML($xml);
      $xmlString = $doc->saveXML();
      Storage::disk('local')->put($nombreXML . '.xml', $xmlString);
   }
}
