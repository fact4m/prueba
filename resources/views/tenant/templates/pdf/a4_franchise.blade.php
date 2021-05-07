@php
    $establishment = $document->establishment;
    $customer = $document->customer;
    $details = $document->details;
    $optional = $document->optional;
    $document_number = $document->series.'-'.str_pad($document->number, 8, '0', STR_PAD_LEFT);
    $document_type_description_array = [
        '01' => 'FACTURA',
        '02' => 'FACTURA',
        '03' => 'BOLETA DE VENTA',
        '07' => 'NOTA DE CREDITO',
        '08' => 'NOTA DE DEBITO',
    ];
    $identity_document_type_description_array = [
        '-' => 'S/D',
        '0' => 'S/D',
        '1' => 'DNI',
        '6' => 'RUC',
        '7' => 'PAS',
    ];
    $document_type_description = $document_type_description_array[$document->document_type_code];
    $currency = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('02', $document->currency_type_code);
    $bank_accounts = \App\Models\Tenant\BankAccount::all();
@endphp
{{--$idDocument = $document->id;--}}

{{--if(!$note = \App\Models\Tenant\Note::where('document_id', $idDocument)->first()){--}}

{{--} else {--}}
{{--$document_affected = $note->affected_document_series.'-'.$note->affected_document_number;--}}

{{--$noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$note->note_type_code);--}}
{{--}--}}
<html>
<head>
    <title>{{ $document_number }}</title>
    <style>
        @font-face {
            font-family: 'unica';
            src: url('{{ storage_path('fonts/pdf/SWZ721LC.ttf')}}') format('truetype');
            font-weight: normal;
            font-style: normal;

        }
        @font-face {
            font-family: 'segunda';
            src: url('{{ storage_path('fonts/pdf/SWZCONDN.TTF')}}') format('truetype');
            font-weight: normal;
            font-style: normal;

        }
        body {
            font-family: 'unica', sans-serif;
        }
        html {
            font-family: 'unica', sans-serif;
            font-size: 10px;
        }
        h1, h2, h3, h4, h5, h6, p, div, table, td, tr {
            font-family: 'unica', sans-serif;
        }
        .segunda {
            font-family: "segunda", sans-serif;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-xsm {
            font-size: 9px;
        }
        .font-sm {
            font-size: 10px;
        }
        .font-lg {
            font-size: 12px;
        }
        .font-xlg {
            font-size: 14px;
        }
        .font-xxlg {
            font-size: 18px;
        }
        .font-bold {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-spacing: 0;
        }
        .voucher-company-right {
            border: 1px solid #333;
            padding-top: 15px;
            padding-bottom: 15px;
            margin-bottom: 10px;
        }
        .voucher-company-right tbody tr:first-child td {
            padding-top: 10px;
        }
        .voucher-company-right tbody tr:last-child td {
            padding-bottom: 10px;
        }
        .voucher-information {
            border: 1px solid #333;
        }
        .voucher-information.top-note, .voucher-information.top-note tbody tr td {
            border-top: 0;
        }
        .voucher-information tbody tr td {
            padding-top: 5px;
            padding-bottom: 5px;
            vertical-align: top;
        }
        .voucher-information-left tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-information-right tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-details {
        }
        .voucher-details thead {
            background-color: #f5f5f5;
        }
        .voucher-details thead tr th {
            /*border-top: 1px solid #333;*/
            /*border-bottom: 1px solid #333;*/
            padding: 5px 10px;
        }
        .voucher-details thead tr th:first-child {
            border-left: 1px solid #333;
        }
        .voucher-details thead tr th:last-child {
            border-right: 1px solid #333;
        }
        .voucher-details tbody tr td {
            /*border-bottom: 1px solid #333;*/
        }
        .voucher-details tbody tr td:first-child {
            border-left: 1px solid #333;
        }
        .voucher-details tbody tr td:last-child {
            border-right: 1px solid #333;
        }
        .voucher-details tbody tr td {
            padding: 5px 10px;
            vertical-align: middle;
        }
        .voucher-details tfoot tr td {
            padding: 3px 10px;
        }
        .voucher-totals {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .voucher-totals tbody tr td {
            padding: 3px 10px;
            vertical-align: top;
        }
        .voucher-footer {
            margin-bottom: 30px;
        }
        .voucher-footer tbody tr td{
            border-top: 1px solid #333;
            padding: 3px 10px;
        }
        .company_logo {
            width: 300px;
            height: auto;
            max-height: 80px;
        }
        .pt-1 {
            padding-top: 1rem;
        }

        /*NEW*/
        .full-width {
            width: 100%;
        }

        .m-0 {
          margin: 0 !important;
        }
        .m-10 {
            margin: 10px;
        }
        .mt-10 {
            margin-top: 10px;
        }
        .mb-10 {
            margin-bottom: 10px;
        }
        .m-20 {
            margin: 20px;
        }
        .mt-20 {
            margin-top: 20px;
        }
        .mb-20 {
            margin-bottom: 20px;
        }

        .p-20 {
            padding: 20px;
        }
        .pt-20 {
            padding-top: 20px;
        }
        .pb-20 {
            padding-bottom: 20px;
        }
        .p-10 {
            padding: 10px;
        }
        .pt-10 {
            padding-top: 10px;
        }
        .pb-10 {
            padding-bottom: 10px;
        }

    </style>
</head>
<body>
    <script type="text/php">
      if (isset($pdf)) {
        $pdf->page_text(120, 740, "Para consultar el comprobante ingresar a {!! url('/') !!}/buscar", "Arial", 8, array(0, 0, 0));
        $font = $fontMetrics->getFont("Arial", "bold");
        $pdf->page_text(530, 740, "Página {PAGE_NUM} de {PAGE_COUNT}", $font, 7, array(0, 0, 0));
      }
    </script>
<table class="p-20 full-width">
    <tr>
        <td width="65%">
            @if($company->logo)
                <img src="{{ asset('storage/uploads/logos/'.$company->logo) }}" class="company_logo">
            @endif
        </td>
        <td width="35%" class="voucher-company-right">
            <div class="text-center font-lg m-0" style="text-transform: uppercase;">{{ 'RUC '.$company->number }}</div>
            <div class="text-center font-lg m-0" style="text-transform: uppercase;">{{ $document_type_description }} ELECTRÓNICA</div>
            <div class="text-center font-xlg m-0" style="text-transform: uppercase;">{{ $document_number }}</div>
        </td>
    </tr>
</table>
<table class="voucher-company">
    <tr>
        <td width="100%">
            <table class="voucher-company-left">
                <tbody>
                <tr>
                    <td class="text-left">
                        <div class="m-0 font-lg segunda" style="text-transform: uppercase;">{{ $company->name }}</div>
                    </td>
                </tr>
                @if($establishment)
                    <tr>
                        <td class="text-left ">{{ strtoupper($establishment->getAddressFullAttribute()) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            {{ ($establishment->email != '-')? $establishment->email.' -' : '' }}
                            {{ ($establishment->phone != '-')? $establishment->phone : '' }}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <table class="voucher-company-left">
                <tbody>
                    <tr>
                        <td class="text-left">
                            <div class="font-lg segunda" style="text-transform: uppercase;">ADQUIRIENTE</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
<table class="full-width mb-10">
    <tr>
        <td width="55%">
            <table class="">
                <tbody>
                    <tr>
                        <td>
                            {{ $identity_document_type_description_array[$customer->identity_document_type->code] }}:{{ $customer->number }}
                        </td>
                    </tr>
                    <tr>
                        <td width="80%">{{ $customer->name }}</td>
                    </tr>
                    @if ($customer->getAddressFullAttribute() !== '')
                        <tr>
                            <td width="80%">{{ strtoupper($customer->getAddressFullAttribute()) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </td>
        <td width="45%">
            <table class="">
                <tbody>
                <tr>
                    <td width="50%">Fecha de emisión: </td>
                    <td width="50%">{{ $document->date_of_issue->format('d/m/Y') }}</td>
                </tr>
                @if($document_base->date_of_due)
                    <tr>
                        <td width="50%">Fecha de vencimiento: </td>
                        <td width="50%">{{ $document_base->date_of_due->format('d/m/Y') }}</td>
                    </tr>
                @endif
                    @if ($optional->method_payment)
                    <tr>
                        <td width="50%">Condición de Pago: </td>
                        <td width="50%">{{ $optional->method_payment }}</td>
                    </tr>
                    @endif

                    @if ($optional->salesman)
                    <tr>
                        <td width="50%">Vendedor: </td>
                        <td width="50%">{{ $optional->salesman }}</td>
                    </tr>
                    @endif

                    @if ($optional->box_number)
                    <tr>
                        <td width="50%">N° Caja: </td>
                        <td width="50%">{{ $optional->box_number }}</td>
                    </tr>
                    @endif

                    @if ($document_base->purchase_order)
                    <tr>
                        <td width="50%">Orden de Compra: </td>
                        <td width="50%">{{ $document_base->purchase_order }}</td>
                    </tr>
                    @endif
                    @if ($document->guides)
                    @foreach($document->guides as $guide)
                        <tr>
                            <td>{{ \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('01', $guide->document_type_code)->description }}</td>
                            <td>{{ $guide->number }}</td>
                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>
@if($document->note)
    @php
        $document_affected = $document_base->affected_document_series.'-'.$document_base->affected_document_number;
        $noteType = \App\Models\Tenant\Catalogs\Code::byCatalogAndCode('09',$document_base->note_type_code);
    @endphp
    <table class="voucher-information top-note">
        <tr>
            <td>
                <table class="voucher-information-left">
                    <tbody>
                        <tr>
                            <td width="20%">Documento Afectado:</td>
                            <td width="20%">{{ $document_affected }}</td>
                            <td width="25%" class="text-right">Tipo de nota:</td>
                            <td width="35%">{{ $noteType->description}}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%">
                <table class="voucher-information-left">
                    <tbody>
                        <tr>
                            <td width="20%">Descripción:</td>
                            <td width="80%" class="text-left">{{ $document_base->description }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
@endif
<table class="voucher-details full-width" style="border-top: 1px solid #333;border-bottom: 1px solid #333;">
    <tr style="background-color: #f5f5f5;">
        <td class="text-left" width="14%" style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">COD. </div>
        </td>
        <td class="text-center " style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">CANT. </div>
        </td>
        <td width="5%" class="" style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">UM</div>
        </td>
        <td width="52%" class="" style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">DESCRIPCIÓN </div>
        </td>
        <td class="text-right " width="16%" style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">P.UNIT</div>
        </td>
        <td class="text-right " style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">DTO. </div>
        </td>
        <td class="text-right " style="padding-top: 0px; padding-bottom: 0px;">
            <div class="font-lg" style="font-family: 'segunda', sans-serif; line-height: 14px;">TOTAL </div>
        </td>
    </tr>
    <tbody>
    @foreach($details as $row)
        <tr>
            <td class="text-left" style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;">
                {{ $row->item->internal_id }}
            </td>
            <td class="text-center" style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;">
                {{ $row->quantity }}
            </td>
            <td style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;">
                {{ $row->unit_type_code }}
            </td>
            <td style="padding-top: 0px; padding-bottom: 0px;">
                {!! str_replace(["\r\n", "\n\r", "\r", "\n"], '<br/>', $row->item_description) !!}
                @if($row->item_information)
                {!! '<br/>'.str_replace(["\r\n", "\n\r", "\r", "\n"], '<br/>', $row->item_information) !!}
                @endif
                @foreach($row->additional as $add)
                    <br/>{!! $add->name !!} : {{ $add->value }}
                @endforeach
                @if($row->discount_percentage > 0)
                    <br/><small>{{$row->discount_percentage}}% de descuento</small>
                @endif
            </td>
            <td  class="text-right" style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;" >
                {{ number_format($row->unit_price, 2) }}
            </td>
            <td  class="text-right" style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;" >
                {{ number_format($row->total_discount, 2) }}
            </td>
            <td class="text-right" style="vertical-align: top;padding-top: 0px; padding-bottom: 0px;">
                {{ number_format($row->total, 2) }}
            </td>
        </tr>
    @endforeach
        <tr><td colspan="7"></td></tr>
    </tbody>
</table>

<table class="full-width">
    <tr>
        <td width="65%">
            <table class="full-width">
                <tr style="vertical-align: top !important">
                    <td colspan="5" class="segunda" style="vertical-align: top !important">Importe en letras: {{ $document->number_to_letter }} {{ $currency->description }}</td>
                </tr>
                @if($document->legends)
                    @foreach($document->legends as $row)
                        @if($row->code != '1000')
                        <tr>
                            <td colspan="5" class="segunda">{{ $row->description }}</td>
                        </tr>
                        @endif
                    @endforeach
                @endif
                @if(isset($document->optional->observations))
                <tr>
                    <td colspan="3" class="segunda">Obsevaciones</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="3">{{ $document->optional->observations }}</td>
                    <td colspan="2"></td>
                </tr>
                @endif
            </table>
        </td>
        <td width="35%">
            <table class="voucher-totals-right">
                <tbody>
                @if($document_base->total_free)
                    @if($document_base->total_free > 0)
                        <tr>
                            <td class="text-right segunda" width="70%">OP. GRATUITAS: {{ $document->currency_type_code }}</td>
                            <td class="text-right segunda" width="30%">{{ number_format($document_base->total_free, 2) }}</td>
                        </tr>
                    @endif
                @endif
                @if($document->total_exonerated > 0)
                    <tr>
                        <td class="text-right segunda" width="70%">OP. EXONERADAS: {{ $document->currency_type_code }}</td>
                        <td class="text-right segunda" width="30%">{{ number_format($document->total_exonerated, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_taxed > 0)
                    <tr>
                        <td class="text-right segunda" width="70%">SUBTOTAL: {{ $document->currency_type_code }}</td>
                        <td class="text-right segunda" width="30%">{{ number_format($document->total_taxed, 2) }}</td>
                    </tr>
                @endif
                @if($document->total_discount)
                    @if($document->total_discount > 0)
                        <tr>
                            <td class="text-right segunda" width="70%">FRANQUICIA: {{ $document->currency_type_code }}</td>
                            <td class="text-right segunda" width="30%">{{ number_format($document->total_discount, 2) }}</td>
                        </tr>
                    @endif
                @endif
                @if($document->total_discount)
                    @if($document->total_discount > 0)
                        <tr>
                            <td class="text-right segunda" width="70%">SUBTOTAL: {{ $document->currency_type_code }}</td>
                            <td class="text-right segunda" width="30%">{{ number_format(($document->total_taxed - $document->total_discount), 2) }}</td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td class="text-right segunda" width="70%">IGV: {{ $document->currency_type_code }}</td>
                    <td class="text-right segunda" width="30%">{{ number_format($document->total_igv, 2) }}</td>
                </tr>
                <tr>
                    <td class="text-right segunda" width="70%">IMPORTE TOTAL: {{ $document->currency_type_code }}</td>
                    <td class="text-right segunda" width="30%">{{ number_format($document->total, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>

<table class="full-width mt-10">
    <tbody>
    <tr>
        <td width="65%">
            @if ($document_base->prepayments)
            <table class="full-width">
                @foreach($document_base->prepayments as $prepayment)
                    @if ($loop->first)
                    <tr>
                        <td colspan="2" class="segunda">ANTICIPOS</td>
                    </tr>
                    <tbody>
                    @endif
                    <tr>
                        <td>{{ $document_type_description_array[$prepayment->document_type_code] }} {{ $prepayment->number }}</td>
                        <td class="text-right">{{$prepayment->currency_type_code}} {{$prepayment->amount}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
            @if ($bank_accounts)
            <table class="full-width">
                <tr>
                    <td colspan="2" class="segunda">CUENTAS BANCARIAS</td>
                </tr>
                <tbody>
                 @foreach($bank_accounts as $bank_account)
                    <tr>
                        <td class="text-left">{{$bank_account->bank->description}} {{$bank_account->currency_type->description}} {{$bank_account->number}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </td>
        <td width="35%">
            <table class="voucher-totals-left">
                <tr>
                    <td class="text-right">
                        <img class="qr_code" src="data:image/png;base64, {{ $document->qr }}" />
                    </td>
                </tr>
                <tr><td class="text-right">Hash: {{ $document->hash }}</td></tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>

</body>
</html>