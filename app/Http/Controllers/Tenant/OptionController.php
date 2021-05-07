<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\OptionRequest;
use App\Http\Resources\Tenant\ConfigurationResource;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Document;
use App\Models\Tenant\Summary;
use App\Models\Tenant\SummaryDocument;
use App\Models\Tenant\Voided;
use App\Models\Tenant\VoidedDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    public function create()
    {
        return view('tenant.options.form');
    }

    public function tables()
    {
        $configurations = Configuration::all();
        if(count($configurations) > 1) {
            DB::connection('tenant')->table('configurations')->truncate();
        }

        if(count($configurations) === 1) {
            $configuration = Configuration::first();
            return [
                'configuration' => new ConfigurationResource($configuration)
            ];
        } else {
            return [
                'configuration' => [
                    'id' => null,
                    'delete_document_demo' => false,
                ]
            ];
        }

//        $confi/guration = Configuration::firstOrCreate(['id' => 1], ['delete_document_demo' => false]);
//
//        return [
//            'configuration' => new ConfigurationResource($configuration)
//        ];
    }

    public function deleteDocuments(OptionRequest $request)
    {
        $period = $request->input('period');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $documents_production = Document::where('soap_type_id', '02')->get();
        if(count($documents_production) === 0 && $period === 'all') {
            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::connection('tenant')->table('summary_documents')->truncate();
            DB::connection('tenant')->table('summaries')->truncate();
            DB::connection('tenant')->table('voided_documents')->truncate();
            DB::connection('tenant')->table('voided')->truncate();
            DB::connection('tenant')->table('notes')->truncate();
            DB::connection('tenant')->table('invoices')->truncate();
            DB::connection('tenant')->table('details')->truncate();
            DB::connection('tenant')->table('documents')->truncate();
            DB::connection('tenant')->statement('SET FOREIGN_KEY_CHECKS=1;');

            return [
                'success' => true,
                'message' => 'Documentos de prueba eliminados, tablas reseteadas.'
            ];
        } else {
            Summary::where('soap_type_id', '01')->wherePeriod($period, $date_start, $date_end)->delete();
            Voided::where('soap_type_id', '01')->wherePeriod($period, $date_start, $date_end)->delete();
            Document::where('soap_type_id', '01')->wherePeriod($period, $date_start, $date_end)->delete();

            return [
                'success' => true,
                'message' => 'Documentos de prueba eliminados'
            ];
        }
    }

    public function deleteDocumentDemo(Request $request)
    {
        $record = Configuration::firstOrNew(['id' => $request->input('id')]);
        $record->delete_document_demo = (bool) $request->input('delete_document_demo');
        $record->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }
}