<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-spacing: 0;
            border: 1px solid black; 
        }

        .celda{
            text-align: center;
            padding: 5px;
            border: 0.1px solid black;
            
        }
        th{
            padding: 5px;
            text-align: center; 
            border-color: #0088cc;
            border: 0.1px solid black;
        }

        .title{
            font-weight: bold; 
            padding: 5px; 
            font-size: 20px !important;
            text-decoration: underline;
        }
        p>strong {
            margin-left: 5px;
            font-size: 13px; 
        }
        thead{
            font-weight: bold;
            background: #0088cc;
            color: white;
            
            text-align: center;
        }
         
    </style>
</head>
<body>
        <div>
            <p align="center" class="title"><strong>Reporte Documentos</strong></p>
        </div>
        <div style="margin-top:20px; margin-bottom:20px;">
            <table>  
                    <tr>
                        <td>
                          <p><strong>Empresa:  </strong>{{$company->name}}</p>        
                        </td>
                        <td>
                            <p><strong>Fecha:  </strong>{{date('Y-m-d')}}</p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                                <p><strong>Ruc:  </strong>{{$company->number}}</p>
                        </td>
                        <td>
                            <p><strong>Establecimiento:  </strong>{{$establishment->address}} - {{$establishment->department->description}} - {{$establishment->district->description}}</p>            
                        </td>
                    </tr> 
            </table>
        </div>
        @if(!empty($reports))
         
        <div class="">
            <div class=" ">
                <table class="" >
                    <thead>
                        <tr>
                            <th >#</th>
                            <th >Tipo Doc</th>
                            <th >Serie</th>
                            <th >N°</th>
                            <th >Fecha emisión</th>
                            <th >Cliente</th> 
                            <th >RUC</th>
                            {{-- @if (auth()->user()->admin)
                                <th >Estado</th>
                            @endif --}}
                            <th >Moneda</th>
                            <th >T. Gravado</th>
                            <th class="">T. Exonerado</th>
                            <th >T. IGV</th>
                            <th >Total</th>
                            <th class="">Documento Relacionado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $key => $value)
                        <tr>
                            <td class="celda">{{$loop->iteration}}</td>
                            <td class="celda">{{$value->document_type_code}}</td>
                            <td class="celda">{{$value->series}}</td>
                            <td class="celda">{{$value->number}}</td>
                            <td class="celda">{{$value->date_of_issue->format('Y-m-d')}}</td>
                            <td class="celda">{{$value->customer->name}}</td> 
                            <td class="celda">{{$value->customer->number}}</td>
                            {{-- @if (auth()->user()->admin)
                                <td class="celda">{{$value->state_type->description}}</td>
                            @endif --}}
                            <td class="celda">{{$value->currency_type_code}}</td>
                            <td class="celda">{{$value->total_taxed}}</td>
                            <td class="celda">{{$value->total_exonerated}}</td>
                            <td class="celda">{{$value->total_igv}}</td>
                            <td class="celda">{{$value->total}}</td>
                            @if($value->document_type_code === '07')
                                <td class="celda">{{$value->note->affected_document_series}}-{{$value->note->affected_document_number}}</td>
                            @else
                                <td class="celda">-</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table> 
            </div>
        </div>
        @else
        <div class="callout callout-info">
            <p>No se encontraron registros.</p>
        </div>
        @endif
</body>
</html>