@extends('tenant.layouts.app')


@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <div>
                    <h4 class="card-title">Consulta de Documentos</h4>
                </div>
            </div>

            <div class="card-body">

                <div>
                    <form action="{{route('tenant.search')}}" class="el-form demo-form-inline el-form--inline" method="POST">
                        {{csrf_field()}}
                        <tenant-calendar></tenant-calendar>
                    </form>
                </div>

                @if(!empty($reports) && $reports->count())
                <div class="box">
                    <div class="box-body no-padding">
                        <div style="margin-bottom: 10px">
                                @if(isset($reports))
                                <form action="{{route('tenant.report_pdf')}}" class="d-inline"   method="POST">
                                    {{csrf_field()}}
                                    <input type="hidden" value="{{$d}}" name="d">
                                    <input type="hidden" value="{{$a}}" name="a">
                                    <input type="hidden" value="{{$td}}" name="td">
                                    <button class="btn btn-custom   mt-2 mr-2"  type="submit"><i class="fa fa-file-pdf"></i> Exportar PDF</button>
                                </form>

                                <form action="{{route('tenant.report_excel')}}" class="d-inline"   method="POST">
                                        {{csrf_field()}}
                                        <input type="hidden" value="{{$d}}" name="d">
                                        <input type="hidden" value="{{$td}}" name="td">
                                         <input type="hidden" value="{{$a}} " name="a">
                                        <button class="btn btn-custom   mt-2 mr-2" type="submit"><i class="fa fa-file-excel"></i> Exportar Excel</button>
                                </form>
                                @endif
                        </div>

                        <table width="100%" class="table table-striped table-responsive-xl table-bordered table-hover">
                            <thead class="">
                                <tr>
                                    <th class="">#</th>
                                    <th class="">Tipo Documento</th>
                                    <th class="" >Serie</th>
                                    <th class="">Número</th>
                                    <th class="">Fecha emisión</th>
                                    <th class="">Cliente</th>
                                    <th class="">RUC</th>
                                     @if (auth()->user()->admin)
                                        <th class="">Estado</th>
                                    @endif
                                    <th class="">Moneda</th>
                                    <th class="">T. Gravado</th>
                                    <th class="">T. Exonerado</th>
                                    <th class="">T. IGV</th>
                                    <th class="">Total</th>
                                    <th class="">Documento Relacionado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $key => $value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->document_type_code}}</td>
                                    <td>{{$value->series}}</td>
                                    <td>{{$value->number}}</td>
                                    <td>{{$value->date_of_issue->format('Y-m-d')}}</td>
                                    <td>{{$value->customer->name}}</td>
                                    <td>{{$value->customer->number}}</td>
                                     @if (auth()->user()->admin)
                                        <td>{{$value->state_type->description}}</td>,
                                    @endif
                                    <td>{{$value->currency_type_code}}</td>
                                    <td>{{$value->total_taxed}}</td>
                                    <td>{{$value->total_exonerated}}</td>
                                    <td>{{$value->total_igv}}</td>
                                    <td>{{$value->total}}</td>
                                    @if($value->document_type_code === '07')
                                    <td>{{$value->note->affected_document_series}}-{{$value->note->affected_document_number}}</td>
                                    @else
                                    <td>-</td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper">

                            {{--  {{ $reports->appends(['search' => Session::get('form_document_list')])->render()  }}  --}}
                            {{--  {{$reports->links()}}  --}}

                        </div>
                    </div>
                </div>
                @else
                <div class="box box-body no-padding">
                    <strong>No se encontraron registros</strong>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<script>



</script>

@endpush


