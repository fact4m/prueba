@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $guides = $document->guides;
    $invoice = $document->invoice;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"
         xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
         xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
         xmlns:ccts="urn:un:unece:uncefact:documentation:2"
         xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
         xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
         xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
         xmlns:udt="urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
         xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <ext:UBLExtensions>
        <ext:UBLExtension>
            <ext:ExtensionContent/>
        </ext:UBLExtension>
    </ext:UBLExtensions>
    <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
    <cbc:CustomizationID>2.0</cbc:CustomizationID>
    <cbc:ID>{{$document->series.'-'.$document->number}}</cbc:ID>
    <cbc:IssueDate>{{ $document->date_of_issue->format('Y-m-d') }}</cbc:IssueDate>
    <cbc:IssueTime>{{ $document->time_of_issue }}</cbc:IssueTime>
    @if($invoice->date_of_due)
    <cbc:DueDate>{{ $invoice->date_of_due->format('Y-m-d') }}</cbc:DueDate>
    @endif
    <cbc:InvoiceTypeCode listAgencyName="PE:SUNAT"
                         listID="{{ $invoice->operation_type_code }}"
                         listName="Tipo de Documento"
                         listSchemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo51"
                         listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01"
                         name="Tipo de Operacion">{{ $document->document_type_code }}</cbc:InvoiceTypeCode>
    @foreach($legends as $legend)
    <cbc:Note languageLocaleID="{{ $legend->code }}">{{ $legend->description }}</cbc:Note>
    @endforeach
    <cbc:DocumentCurrencyCode listID="ISO 4217 Alpha"
                              listName="Currency"
                              listAgencyName="United Nations Economic Commission for Europe">{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>{{ count($details) }}</cbc:LineCountNumeric>
    @if($invoice->purchase_order)
    <cac:OrderReference>
        <cbc:ID>{{ $invoice->purchase_order }}</cbc:ID>
    </cac:OrderReference>
    @endif
    @foreach($guides as $guide)
    <cac:DespatchDocumentReference>
        <cbc:ID>{{ $guide->number }}</cbc:ID>
        <cbc:DocumentTypeCode listAgencyName="PE:SUNAT"
                              listName="SUNAT:Identificador de Tipo de Documento"
                              listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">{{ $guide->document_type_code }}</cbc:DocumentTypeCode>
    </cac:DespatchDocumentReference>
    @endforeach
    <cac:Signature>
        <cbc:ID>{{ config('tenant.signature_uri') }}</cbc:ID>
        <cbc:Note>{{ config('tenant.signature_note') }}</cbc:Note>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name>{{ $company->name }}</cbc:Name>
            </cac:PartyName>
        </cac:SignatoryParty>
        <cac:DigitalSignatureAttachment>
            <cac:ExternalReference>
                <cbc:URI>#{{ config('tenant.signature_uri') }}</cbc:URI>
            </cac:ExternalReference>
        </cac:DigitalSignatureAttachment>
    </cac:Signature>
    <cac:AccountingSupplierParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $company->identity_document_type->code }}"
                        schemeName="SUNAT:Identificador de Documento de Identidad"
                        schemeAgencyName="PE:SUNAT"
                        schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    {{--<cac:AccountingSupplierParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyName>--}}
                {{--<cbc:Name>Distribuidora San Camilo</cbc:Name>--}}
            {{--</cac:PartyName>--}}
            {{--<cac:PostalAddress>--}}
                {{--<cbc:ID>150101</cbc:ID>--}}
                {{--<cbc:StreetName><![CDATA[AV LS]]></cbc:StreetName>--}}
                {{--<cbc:CityName>LIMA</cbc:CityName>--}}
                {{--<cbc:CountrySubentity>LIMA</cbc:CountrySubentity>--}}
                {{--<cbc:District>LIMA</cbc:District>--}}
                {{--<cac:Country>--}}
                    {{--<cbc:IdentificationCode>PE</cbc:IdentificationCode>--}}
                {{--</cac:Country>--}}
            {{--</cac:PostalAddress>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName><![CDATA[Mayorista CFF S. A.]]></cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="6"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20200464529</cbc:CompanyID>--}}
                {{--<cac:TaxScheme />--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingSupplierParty>--}}
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $customer->identity_document_type->code }}"
                        schemeName="SUNAT:Identificador de Documento de Identidad"
                        schemeAgencyName="PE:SUNAT"
                        schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $customer->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>{{ $customer->name }}</cbc:RegistrationName>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    {{--<cac:AccountingCustomerParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName>Bodega Gemi S.A.</cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="6"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20546687668</cbc:CompanyID>--}}
                {{--<cac:TaxScheme />--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingCustomerParty>--}}
    {{--<cac:Delivery>--}}
        {{--<cac:Shipment>--}}
            {{--<cbc:ID>1</cbc:ID>--}}
            {{--<cbc:HandlingCode>02</cbc:HandlingCode>--}}
            {{--<cbc:GrossWeightMeasure unitCode="KGM">2020.23</cbc:GrossWeightMeasure>--}}
            {{--<cac:ShipmentStage>--}}
                {{--<cbc:TransportModeCode>01</cbc:TransportModeCode>--}}
                {{--<cac:TransitPeriod>--}}
                    {{--<cbc:StartDate>2017-12-15</cbc:StartDate>--}}
                {{--</cac:TransitPeriod>--}}
                {{--<cac:CarrierParty>--}}
                    {{--<cac:PartyLegalEntity>--}}
                        {{--<cbc:RegistrationName><![CDATA[TRANS SAC]]></cbc:RegistrationName>--}}
                        {{--<cbc:CompanyID schemeID="6"--}}
                                       {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                                       {{--schemeAgencyName="PE:SUNAT"--}}
                                       {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">20100006376</cbc:CompanyID>--}}
                    {{--</cac:PartyLegalEntity>--}}
                {{--</cac:CarrierParty>--}}
                {{--<cac:TransportMeans>--}}
                    {{--<cbc:RegistrationNationalityID>21543131</cbc:RegistrationNationalityID>--}}
                    {{--<cac:RoadTransport>--}}
                        {{--<cbc:LicensePlateID>B9Y-778</cbc:LicensePlateID>--}}
                    {{--</cac:RoadTransport>--}}
                {{--</cac:TransportMeans>--}}
                {{--<cac:DriverPerson>--}}
                    {{--<cbc:ID schemeID="1"--}}
                            {{--schemeName="SUNAT:Indicador de Tipo de Documento de Identidad"--}}
                            {{--schemeAgencyName="PE:SUNAT"--}}
                            {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">22112233</cbc:ID>--}}
                {{--</cac:DriverPerson>--}}
            {{--</cac:ShipmentStage>--}}
            {{--<cac:Delivery>--}}
                {{--<cac:DeliveryAddress>--}}
                    {{--<cbc:CountrySubentityCode>070101</cbc:CountrySubentityCode>--}}
                    {{--<cac:AddressLine>--}}
                        {{--<cbc:Line><![CDATA[AV. REPUBLICA DE ARGENTINA N? 2976 URB.]]></cbc:Line>--}}
                    {{--</cac:AddressLine>--}}
                {{--</cac:DeliveryAddress>--}}
            {{--</cac:Delivery>--}}
            {{--<cac:TransportHandlingUnit>--}}
                {{--<cac:TransportEquipment>--}}
                    {{--<cbc:ID>B9Y-778</cbc:ID>--}}
                {{--</cac:TransportEquipment>--}}
            {{--</cac:TransportHandlingUnit>--}}
            {{--<cac:OriginAddress>--}}
                {{--<cbc:CountrySubentityCode>070101</cbc:CountrySubentityCode>--}}
                {{--<cac:AddressLine>--}}
                    {{--<cbc:Line><![CDATA[AV OSCAR R BENAVIDES No 5915  PE]]></cbc:Line>--}}
                {{--</cac:AddressLine>--}}
            {{--</cac:OriginAddress>--}}
        {{--</cac:Shipment>--}}
    {{--</cac:Delivery>--}}
    {{--@if($invoice->detraction)--}}
    {{--<cac:PaymentTerms>--}}
        {{--<cbc:ID schemeName="SUNAT:Codigo de detraccion"--}}
                {{--schemeAgencyName="PE:SUNAT"--}}
                {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo54">{{ $invoice->detraction->code }}</cbc:ID>--}}
        {{--<cbc:PaymentPercent>{{ $invoice->detraction->percentage }}</cbc:PaymentPercent>--}}
        {{--<cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $invoice->detraction->total }}</cbc:Amount>--}}
    {{--</cac:PaymentTerms>--}}
    {{--<cac:PaymentMeans>--}}
        {{--<cac:PayeeFinancialAccount>--}}
            {{--<cbc:ID>{{ $invoice->detraction->account }}</cbc:ID>--}}
        {{--</cac:PayeeFinancialAccount>--}}
    {{--</cac:PaymentMeans>--}}
    {{--@endif--}}
    @if($invoice->total_global_discount > 0)
    <cac:AllowanceCharge>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>
        <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_global_discount }}</cbc:Amount>
    </cac:AllowanceCharge>
    @endif
    {{--@foreach($invoice->prepayments as $prepayment)--}}
        {{--<cac:PrepaidPayment>--}}
            {{--<cbc:ID schemeID="{{ $prepayment->document_type_code }}">{{ $prepayment->series.'-'.$prepayment->number }}</cbc:ID>--}}
            {{--<cbc:PaidAmount currencyID="{{ $prepayment->currency_type_code }}">{{ $prepayment->total }}</cbc:PaidAmount>--}}
        {{--</cac:PrepaidPayment>--}}
    {{--@endforeach--}}
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv + $document->total_isc }}</cbc:TaxAmount>
        @if($document->total_igv > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cbc:ID schemeID="UN/ECE 5305"
                        schemeName="Tax Category Identifier"
                        schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>
                <cac:TaxScheme>
                    <cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        {{--<cac:TaxSubtotal>--}}
            {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">78128.64</cbc:TaxableAmount>--}}
            {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">21273.87</cbc:TaxAmount>--}}
            {{--<cac:TaxCategory>--}}
                {{--<cbc:ID schemeID="UN/ECE 5305"--}}
                        {{--schemeName="Tax Category Identifier"--}}
                        {{--schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">2000</cbc:ID>--}}
                    {{--<cbc:Name>ISC</cbc:Name>--}}
                    {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:TaxCategory>--}}
        {{--</cac:TaxSubtotal>--}}
        {{--<cac:TaxSubtotal>--}}
            {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">50.00</cbc:TaxableAmount>--}}
            {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0.00</cbc:TaxAmount>--}}
            {{--<cac:TaxCategory>--}}
                {{--<cbc:ID schemeID="UN/ECE 5305"--}}
                        {{--schemeName="Tax Category Identifier"--}}
                        {{--schemeAgencyName="United Nations Economic Commission for Europe">O</cbc:ID>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID schemeID="UN/ECE 5153" schemeAgencyID="6">9998</cbc:ID>--}}
                    {{--<cbc:Name>INAFECTO</cbc:Name>--}}
                    {{--<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:TaxCategory>--}}
        {{--</cac:TaxSubtotal>--}}
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        {{--@if($document->total_value_sale > 0)--}}
            {{--<cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_value_sale }}</cbc:LineExtensionAmount>--}}
        {{--@endif--}}
        {{--@if($document->total_price_sale > 0)--}}
            {{--<cbc:TaxInclusiveAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_price_sale }}</cbc:TaxInclusiveAmount>--}}
        {{--@endif--}}
        @if($document->total_discount > 0)
            <cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_discount }}</cbc:AllowanceTotalAmount>
        @endif
        @if($document->total_other_charges > 0)
            <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($invoice->total_prepayment > 0)
            <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_prepayment }}</cbc:PrepaidAmount>
        @endif
        @if($document->total > 0)
            <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
        @endif
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:InvoiceLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        {{--<cbc:InvoicedQuantity unitCode="{{ $row->unit_type_code }}"--}}
                              {{--unitCodeListID="UN/ECE rec 20"--}}
                              {{--unitCodeListAgencyName="United Nations Economic Commission forEurope">{{ $row->quantity }}</cbc:InvoicedQuantity>--}}
        <cbc:InvoicedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode listName="SUNAT:Indicador de Tipo de Precio"
                                   listAgencyName="PE:SUNAT"
                                   listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo16">{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        {{--<cac:AllowanceCharge>--}}
            {{--<cbc:ChargeIndicator>false</cbc:ChargeIndicator>--}}
            {{--<cbc:AllowanceChargeReasonCode>00</cbc:AllowanceChargeReasonCode>--}}
            {{--<cbc:MultiplierFactorNumeric>0.20</cbc:MultiplierFactorNumeric>--}}
            {{--<cbc:Amount currencyID="{{ $document->currency_type_code }}">8766.96</cbc:Amount>--}}
            {{--<cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">43834.78</cbc:BaseAmount>--}}
        {{--</cac:AllowanceCharge>--}}
        {{--@if($row->total_igv > 0)--}}
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv + $row->total_isc }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
                @php
                    $taxCategoryIdentifier = "S";
                    $taxSchemeIdentifier = "1000";
                    $taxSchemeName = "IGV";
                    $taxSchemeCode = "VAT";
                    if (in_array($row->affectation_igv_type_code, ['20'])) {
                        $taxCategoryIdentifier = "E";
                        $taxSchemeIdentifier = "9997";
                        $taxSchemeName = "EXO";
                        $taxSchemeCode = "VAT";
                    }
                    if (in_array($row->affectation_igv_type_code, ['30'])) {
                        $taxCategoryIdentifier = "O";
                        $taxSchemeIdentifier = "9998";
                        $taxSchemeName = "INA";
                        $taxSchemeCode = "FRE";
                    }
                    if (in_array($row->affectation_igv_type_code, ['21', '31', '32', '33', '34', '35', '36'])) {
                        $taxCategoryIdentifier = "Z";
                        $taxSchemeIdentifier = "9996";
                        $taxSchemeName = "GRA";
                        $taxSchemeCode = "FRE";
                    }
                @endphp
                <cac:TaxCategory>
                    <cbc:ID schemeID="UN/ECE 5305"
                            schemeName="Tax Category Identifier"
                            schemeAgencyName="United Nations Economic Commission for Europe">{{ $taxCategoryIdentifier }}</cbc:ID>
                    <cbc:Percent>{{ $row->percentage_igv }}</cbc:Percent>
                    <cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT"
                                                listName="SUNAT:Codigo de Tipo de Afectación del IGV"
                                                listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">{{ $row->affectation_igv_type_code }}</cbc:TaxExemptionReasonCode>
                    <cac:TaxScheme>
                        <cbc:ID schemeID="UN/ECE 5153"
                                schemeName="Tax Scheme Identifier"
                                schemeAgencyName="United Nations Economic Commission for Europe">{{ $taxSchemeIdentifier }}</cbc:ID>
                        <cbc:Name>{{ $taxSchemeName }}</cbc:Name>
                        <cbc:TaxTypeCode>{{ $taxSchemeCode }}</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
            {{--<cac:TaxSubtotal>--}}
                {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">74000.00</cbc:TaxableAmount>--}}
                {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">20572.00</cbc:TaxAmount>--}}
                {{--<cac:TaxCategory>--}}
                    {{--<cbc:ID schemeID="UN/ECE 5305"--}}
                            {{--schemeName="Tax Category Identifier"--}}
                            {{--schemeAgencyName="United Nations Economic Commission for Europe">S</cbc:ID>--}}
                    {{--<cbc:Percent>0.278</cbc:Percent>--}}
                    {{--<cbc:TaxExemptionReasonCode listAgencyName="PE:SUNAT"--}}
                                                {{--listName="SUNAT:Codigo de Tipo de Afectación del IGV"--}}
                                                {{--listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo07">10</cbc:TaxExemptionReasonCode>--}}
                    {{--<cbc:TierRange>03</cbc:TierRange>--}}
                    {{--<cac:TaxScheme>--}}
                        {{--<cbc:ID schemeID="UN/ECE 5153"--}}
                                {{--schemeName="Tax Scheme Identifier"--}}
                                {{--schemeAgencyName="United Nations Economic Commission for Europe">2000</cbc:ID>--}}
                        {{--<cbc:Name>ISC</cbc:Name>--}}
                        {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                    {{--</cac:TaxScheme>--}}
                {{--</cac:TaxCategory>--}}
            {{--</cac:TaxSubtotal>--}}
        </cac:TaxTotal>
        {{--@endif--}}
        <cac:Item>
            <cbc:Description><![CDATA[{{ $row->item_description }}]]></cbc:Description>
            @if($row->internal_id)
                <cac:SellersItemIdentification>
                    <cbc:ID>{{ $row->internal_id }}</cbc:ID>
                </cac:SellersItemIdentification>
            @endif
            @if($row->item_code)
                {{--<cac:CommodityClassification>--}}
                    {{--<cbc:ItemClassificationCode listID="UNSPSC"--}}
                                                {{--listAgencyName="GS1 US"--}}
                                                {{--listName="Item Classification">{{ $row->item_code }}</cbc:ItemClassificationCode>--}}
                {{--</cac:CommodityClassification>--}}
                <cac:CommodityClassification>
                    <cbc:ItemClassificationCode>{{ $row->item_code }}</cbc:ItemClassificationCode>
                </cac:CommodityClassification>
            @endif
            @foreach($row->additional as $other)
            <cac:AdditionalItemProperty>
                <cbc:Name><![CDATA[{{ $other->name }}]]></cbc:Name>
                {{--<cbc:NameCode listName="SUNAT :Identificador de la propiedad del ítem"--}}
                              {{--listAgencyName="PE:SUNAT">{{ $other->code }}</cbc:NameCode>--}}
                <cbc:NameCode>{{ $other->code }}</cbc:NameCode>
                <cbc:Value>{{ $other->value }}</cbc:Value>
                @if($other->start_date || $other->end_date || $other->duration)
                    <cac:UsabilityPeriod>
                        @if($other->start_date)
                        <cbc:StartDate>{{ $other->start_date }}</cbc:StartDate>
                        @endif
                        @if($other->end_date)
                        <cbc:EndDate>{{ $other->end_date }}</cbc:EndDate>
                        @endif
                        @if($other->duration)
                        <cbc:DurationMeasure unitCode="DAY">{{ $other->duration }}</cbc:DurationMeasure>
                        @endif
                    </cac:UsabilityPeriod>
                @endif
            </cac:AdditionalItemProperty>
            @endforeach
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
    @endforeach
</Invoice>

