@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $legends = $document->legends;
    $note = $document->note;
@endphp
{!! '<?xml version="1.0" encoding="utf-8" standalone="no"?>' !!}
<CreditNote xmlns="urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2"
            xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2"
            xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"
            xmlns:ccts="urn:un:unece:uncefact:documentation:2"
            xmlns:ds="http://www.w3.org/2000/09/xmldsig#"
            xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2"
            xmlns:qdt="urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2"
            xmlns:sac="urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1"
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
    @foreach($legends as $legend)
    <cbc:Note languageLocaleID="{{ $legend->code }}">{{ $legend->description }}</cbc:Note>
    @endforeach
    <cbc:DocumentCurrencyCode>{{ $document->currency_type_code }}</cbc:DocumentCurrencyCode>
    <cac:DiscrepancyResponse>
        <cbc:ReferenceID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ReferenceID>
        <cbc:ResponseCode>{{ $note->note_type_code }}</cbc:ResponseCode>
        <cbc:Description>{{ $note->description }}</cbc:Description>
    </cac:DiscrepancyResponse>
    <cac:BillingReference>
        <cac:InvoiceDocumentReference>
            <cbc:ID>{{ $note->affected_document_series.'-'.$note->affected_document_number }}</cbc:ID>
            <cbc:DocumentTypeCode>{{ $note->affected_document_type_code }}</cbc:DocumentTypeCode>
        </cac:InvoiceDocumentReference>
    </cac:BillingReference>
    <cac:Signature>
        <cbc:ID>{{ config('tenant.signature_uri') }}</cbc:ID>
        <cbc:Note>{{ config('tenant.signature_note') }}</cbc:Note>
        <cac:SignatoryParty>
            <cac:PartyIdentification>
                <cbc:ID>{{ $company->number }}</cbc:ID>
            </cac:PartyIdentification>
            <cac:PartyName>
                <cbc:Name><![CDATA[{{ $company->name }}]]></cbc:Name>
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
                    @if($establishment->urbanization)
                    <cbc:CitySubdivisionName>{{ $establishment->urbanization }}</cbc:CitySubdivisionName>
                    @endif
                    <cbc:CityName>{{ $establishment->province->description }}</cbc:CityName>
                    <cbc:CountrySubentity>{{ $establishment->department->description }}</cbc:CountrySubentity>
                    <cbc:District>{{ $establishment->district->description }}</cbc:District>
                    <cac:AddressLine>
                        <cbc:Line><![CDATA[{{ $establishment->address }}]]></cbc:Line>
                    </cac:AddressLine>
                    <cac:Country>
                        <cbc:IdentificationCode>{{ $establishment->country->code }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingSupplierParty>
    {{----}}
    {{--<cac:AccountingSupplierParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyName>--}}
                {{--<cbc:Name><![CDATA[{{ $company->trade_name }}]]></cbc:Name>--}}
            {{--</cac:PartyName>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName><![CDATA[{{ $company->name }}]]></cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="{{ $company->identity_document_type_code }}"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $company->number }}</cbc:CompanyID>--}}
                {{--<cac:RegistrationAddress>--}}
                    {{--<cbc:AddressTypeCode>{{ $establishment->code }}</cbc:AddressTypeCode>--}}
                {{--</cac:RegistrationAddress>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>-</cbc:ID>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingSupplierParty>--}}
    {{--<cac:AccountingCustomerParty>--}}
        {{--<cac:Party>--}}
            {{--<cac:PartyTaxScheme>--}}
                {{--<cbc:RegistrationName>{{ $customer->name }}</cbc:RegistrationName>--}}
                {{--<cbc:CompanyID schemeID="{{ $customer->identity_document_type_code }}"--}}
                               {{--schemeName="SUNAT:Identificador de Documento de Identidad"--}}
                               {{--schemeAgencyName="PE:SUNAT"--}}
                               {{--schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">{{ $customer->number }}</cbc:CompanyID>--}}
                {{--<cac:TaxScheme>--}}
                    {{--<cbc:ID>-</cbc:ID>--}}
                {{--</cac:TaxScheme>--}}
            {{--</cac:PartyTaxScheme>--}}
        {{--</cac:Party>--}}
    {{--</cac:AccountingCustomerParty>--}}
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
                        <cbc:IdentificationCode>{{ $customer->country->code }}</cbc:IdentificationCode>
                    </cac:Country>
                </cac:RegistrationAddress>
                @endif
            </cac:PartyLegalEntity>
        </cac:Party>
    </cac:AccountingCustomerParty>
    <cac:TaxTotal>
        <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_igv + $document->total_isc }}</cbc:TaxAmount>
        {{--@if($document->total_isc > 0)--}}
            {{--<cac:TaxSubtotal>--}}
                {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_base_isc }}</cbc:TaxableAmount>--}}
                {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_isc }}</cbc:TaxAmount>--}}
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
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_taxed }}</cbc:TaxableAmount>
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
        {{--@if($note->total_free > 0)--}}
            {{--<cac:TaxSubtotal>--}}
                {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $note->total_free }}</cbc:TaxableAmount>--}}
                {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">0</cbc:TaxAmount>--}}
                {{--<cac:TaxCategory>--}}
                    {{--<cac:TaxScheme>--}}
                        {{--<cbc:ID>9996</cbc:ID>--}}
                        {{--<cbc:Name>GRA</cbc:Name>--}}
                        {{--<cbc:TaxTypeCode>FRE</cbc:TaxTypeCode>--}}
                    {{--</cac:TaxScheme>--}}
                {{--</cac:TaxCategory>--}}
            {{--</cac:TaxSubtotal>--}}
        {{--@endif--}}
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
                {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_other_taxes }}</cbc:TaxableAmount>--}}
                {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_id }}">{{ $document->total_base_other_taxes }}</cbc:TaxAmount>--}}
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
        @if($note->total_global_discount > 0)
        <cbc:AllowanceTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $note->total_global_discount }}</cbc:AllowanceTotalAmount>
        @endif
        @if($document->total_other_charges > 0)
        <cbc:ChargeTotalAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total_other_charges }}</cbc:ChargeTotalAmount>
        @endif
        @if($note->total_prepayment > 0)
        <cbc:PrepaidAmount currencyID="{{ $document->currency_type_code }}">{{ $note->total_prepayment }}</cbc:PrepaidAmount>
        @endif
        <cbc:PayableAmount currencyID="{{ $document->currency_type_code }}">{{ $document->total }}</cbc:PayableAmount>
    </cac:LegalMonetaryTotal>
    @foreach($details as $row)
    <cac:CreditNoteLine>
        <cbc:ID>{{ $loop->iteration }}</cbc:ID>
        <cbc:CreditedQuantity unitCode="{{ $row->unit_type_code }}">{{ $row->quantity }}</cbc:CreditedQuantity>
        <cbc:LineExtensionAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:LineExtensionAmount>
        <cac:PricingReference>
            <cac:AlternativeConditionPrice>
                <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_price }}</cbc:PriceAmount>
                <cbc:PriceTypeCode>{{ $row->price_type_code }}</cbc:PriceTypeCode>
            </cac:AlternativeConditionPrice>
        </cac:PricingReference>
        <cac:TaxTotal>
            <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv + $row->total_isc }}</cbc:TaxAmount>
            {{--@if($detail->total_isc > 0)--}}
            {{--<cac:TaxSubtotal>--}}
                {{--<cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_value }}</cbc:TaxableAmount>--}}
                {{--<cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $detail->total_isc }}</cbc:TaxAmount>--}}
                {{--<cac:TaxCategory>--}}
                    {{--<cbc:Percent>{{ $detail->percentage_isc }}</cbc:Percent>--}}
                    {{--<cbc:TierRange>{{ $detail->system_isc_type_code }}</cbc:TierRange>--}}
                    {{--<cac:TaxScheme>--}}
                        {{--<cbc:ID>2000</cbc:ID>--}}
                        {{--<cbc:Name>ISC</cbc:Name>--}}
                        {{--<cbc:TaxTypeCode>EXC</cbc:TaxTypeCode>--}}
                    {{--</cac:TaxScheme>--}}
                {{--</cac:TaxCategory>--}}
            {{--</cac:TaxSubtotal>--}}
            {{--@endif--}}
            <cac:TaxSubtotal>
                <cbc:TaxableAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_value }}</cbc:TaxableAmount>
                <cbc:TaxAmount currencyID="{{ $document->currency_type_code }}">{{ $row->total_igv }}</cbc:TaxAmount>
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
            <cbc:Description>{{ $row->item_description }}</cbc:Description>
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
            <cbc:PriceAmount currencyID="{{ $document->currency_type_code }}">{{ $row->unit_value }}</cbc:PriceAmount>
        </cac:Price>
    </cac:CreditNoteLine>
    @endforeach
</CreditNote>