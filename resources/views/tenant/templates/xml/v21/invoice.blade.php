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
    <cbc:InvoiceTypeCode listID="{{ $invoice->operation_type_code }}">{{ $document->document_type_code }}</cbc:InvoiceTypeCode>
    @foreach($legends as $legend)
    <cbc:Note languageLocaleID="{{ $legend->code }}">{{ $legend->description }}</cbc:Note>
    @endforeach
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    <cbc:LineCountNumeric>{{ count($details) }}</cbc:LineCountNumeric>
    @if($invoice->purchase_order)
    <cac:OrderReference>
        <cbc:ID>{{ $invoice->purchase_order }}</cbc:ID>
    </cac:OrderReference>
    @endif
    @foreach($guides as $guide)
    <cac:DespatchDocumentReference>
        <cbc:ID>{{ $guide->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $guide->document_type_code }}</cbc:DocumentTypeCode>
    </cac:DespatchDocumentReference>
    @endforeach
    @if($invoice->prepayments)
    @foreach($invoice->prepayments as $prepayment)
    <cac:AdditionalDocumentReference>
        <cbc:ID>{{ $prepayment->number }}</cbc:ID>
        <cbc:DocumentTypeCode>{{ $prepayment->document_type_code }}</cbc:DocumentTypeCode>
        <cbc:DocumentStatusCode>{{ $loop->iteration }}</cbc:DocumentStatusCode>
        <cac:IssuerParty>
            <cac:PartyIdentification>
                <cbc:ID schemeID="6">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
        </cac:IssuerParty>
    </cac:AdditionalDocumentReference>
    @endforeach
    @endif
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
                <cbc:ID schemeID="{{ $company->identity_document_type->code }}">{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>
            </cac:PartyName>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>
                <cac:RegistrationAddress>
                    <cbc:ID>{{ $establishment->district_id }}</cbc:ID>
                    <cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    <cac:AccountingCustomerParty>
        <cac:Party>
            <cac:PartyIdentification>
                <cbc:ID schemeID="{{ $customer->identity_document_type->code }}">{{ $customer->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyLegalEntity>
                <cbc:RegistrationName>{{ $customer->name }}</cbc:RegistrationName>
                @if($customer->address)
                <cac:RegistrationAddress>
                    @if($customer->district_id)
                    <cbc:ID>{{ $customer->district_id }}</cbc:ID>
                    @endif
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $customer->address }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{ $customer->country_id }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
                @endif
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    @php
        $total_discount = ($invoice->total_global_discount > 0)?$invoice->total_global_discount:0;
        $total_value = $document->total_value;
        $total_base_igv = $total_value - $total_discount;
        $discount_factor = round($total_discount / $total_value, 4)
    @endphp
    @if($invoice->total_global_discount > 0)
    <cac:AllowanceCharge>
        <cbc:ChargeIndicator>false</cbc:ChargeIndicator>
        <cbc:AllowanceChargeReasonCode listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo53" listName="Cargo/descuento" listAgencyName="PE:SUNAT">02</cbc:AllowanceChargeReasonCode>
        <cbc:MultiplierFactorNumeric>{{ $discount_factor }}</cbc:MultiplierFactorNumeric>
        <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $total_discount }}</cbc:Amount>
        <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $total_value }}</cbc:BaseAmount>
    </cac:AllowanceCharge>
    @endif
    @if($invoice->prepayments)
    @foreach($invoice->prepayments as $prepayment)
    <cac:PrepaidPayment>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:PaidAmount currencyID="{{ $prepayment->currency_type_code }}">{{ $prepayment->amount }}</cbc:PaidAmount>
    </cac:PrepaidPayment>
    @endforeach
    @endif
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv + $document->total_isc }}</cbc:TaxAmount>
        {{--@if($document->total_isc > 0)--}}
        {{--<cac:TaxSubtotal>--}}
            {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_base_isc }}</cbc:TaxableAmount>--}}
            {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_isc }}</cbc:TaxAmount>--}}
            {{--<cac:TaxCategory>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>2000</cbc:ID>--}}
                    {{--<cbc:Name>ISC</cbc:Name>--}}
                    {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:TaxCategory>--}}
        {{--</cac:TaxSubtotal>--}}
        {{--@endif--}}
        @if($document->total_taxed > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $total_base_igv }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv }}</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>1000</cbc:ID>
                    <cbc:Name>IGV</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_unaffected > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_unaffected }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9998</cbc:ID>
                    <cbc:Name>INA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_exonerated > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exonerated }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9997</cbc:ID>
                    <cbc:Name>EXO</cbc:Name>
                    <cbc:TaxTypeCode>VAT</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($invoice->total_free > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_free }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9996</cbc:ID>
                    <cbc:Name>GRA</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        @if($document->total_exportation > 0)
        <cac:TaxSubtotal>
            <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_exportation }}</cbc:TaxableAmount>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">0</cbc:TaxAmount>
            <cac:TaxCategory>
                <cac:TaxScheme>
                    <cbc:ID>9995</cbc:ID>
                    <cbc:Name>EXP</cbc:Name>
                    <cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>
                </cac:TaxScheme>
            </cac:TaxCategory>
        </cac:TaxSubtotal>
        @endif
        {{--@if($document->total_other_taxes > 0)--}}
        {{--<cac:TaxSubtotal>--}}
            {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_taxes }}</cbc:TaxableAmount>--}}
            {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_base_other_taxes }}</cbc:TaxAmount>--}}
            {{--<cac:TaxCategory>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>9999</cbc:ID>--}}
                    {{--<cbc:Name>OTROS</cbc:Name>--}}
                    {{--<cbc:TaxTypeCode>OTH</cbc:TaxTypeCode>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:TaxCategory>--}}
        {{--</cac:TaxSubtotal>--}}
        {{--@endif--}}
    </cac:TaxTotal>
    <cac:LegalMonetaryTotal>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $total_base_igv }}</cbc:LineExtensionAmount>
        <cbc:TaxInclusiveAmount  currencyID="{{ $document->currency_type_code }}">{{ $total_base_igv + $document->total_igv }}</cbc:TaxInclusiveAmount >
        {{--@if($document->total_discount > 0)--}}
            {{--<cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_discount }}</cbc:AllowanceTotalAmount>--}}
        {{--@endif--}}
{{--        @if($document->total_other_charges > 0)--}}
            <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        {{--@endif--}}
        {{--@if($invoice->total_prepayment > 0)--}}
            <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $invoice->total_prepayment }}</cbc:PrepaidAmount>
        {{--@endif--}}
        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:InvoiceLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:InvoicedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:InvoicedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ ($row->price_type_code === '02')?0.00:$row->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        @if ($row->charge_type_code)
        <cac:AllowanceCharge>
            <cbc:ChargeIndicator>true</cbc:ChargeIndicator>
            <cbc:AllowanceChargeReasonCode>{{ $row->charge_type_code }}</cbc:AllowanceChargeReasonCode>
            <cbc:MultiplierFactorNumeric>{{ $row->charge_percentage }}</cbc:MultiplierFactorNumeric>
            <cbc:Amount currencyID="{{ $document->currency_type_code }}">{{ $row->total_charge }}</cbc:Amount>
            <cbc:BaseAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:BaseAmount>
        </cac:AllowanceCharge>
        @endif
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv + $row->total_isc }}</cbc:TaxAmount>
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ ($row->price_type_code === '02')?($row->total_value * 0.18):$row->total_igv }}</cbc:TaxAmount>
                <cac:TaxCategory>
                    <cbc:Percent>{{ $row->percentage_igv }}</cbc:Percent>
                    <cbc:TaxExemptionReasonCode>{{ $row->affectation_igv_type_code }}</cbc:TaxExemptionReasonCode>
                    @php($affectation = \App\Core\Helpers\FunctionTribute::getByAffectation($row->affectation_igv_type_code))
                    <cac:TaxScheme>
                        <cbc:ID>{{ $affectation['id'] }}</cbc:ID>
                        <cbc:Name>{{ $affectation['name'] }}</cbc:Name>
                        <cbc:TaxTypeCode>{{ $affectation['code'] }}</cbc:TaxTypeCode>
                    </cac:TaxScheme>
                </cac:TaxCategory>
            </cac:TaxSubtotal>
        </cac:TaxTotal>
        <cac:Item>
            @if($row->item_information)
            <cbc:Description><![CDATA[{{ substr(str_replace(["\r\n", "\n\r", "\r", "\n"], '|', $row->item_description).' '.str_replace(["\r\n", "\n\r", "\r", "\n"], '|', $row->item_information), 0, 500) }}]]></cbc:Description>
            @else
            <cbc:Description><![CDATA[{{ substr(str_replace(["\r\n", "\n\r", "\r", "\n"], '|', $row->item_description), 0, 500) }}]]></cbc:Description>
            @endif
            @if($row->internal_id)
                <cac:SellersItemIdentification>
                    <cbc:ID>{{ $row->internal_id }}</cbc:ID>
                </cac:SellersItemIdentification>
            @endif
            @if($row->item_code)
                <cac:CommodityClassification>
                    <cbc:ItemClassificationCode>{{ $row->item_code }}</cbc:ItemClassificationCode>
                </cac:CommodityClassification>
            @endif
            @if($row->item_code_gs1)
                <cac:StandardItemIdentification>
                    <cbc:ID>{{ $row->item_code_gs1 }}</cbc:ID>
                </cac:StandardItemIdentification>
            @endif
            @if($row->additional)
                @foreach($row->additional as $other)
                    <cac:AdditionalItemProperty>
                        <cbc:Name><![CDATA[{{ $other->name }}]]></cbc:Name>
                        <cbc:NameCode>{{ $other->code }}</cbc:NameCode>
                        @if($other->value)
                            <cbc:Value>{{ $other->value }}</cbc:Value>
                        @endif
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
            @endif
        </cac:Item>
        <cac:Price>
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ ($row->price_type_code === '02')?0.00:$row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:InvoiceLine>
    @endforeach
</Invoice>

