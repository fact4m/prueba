<?php
namespace App\Http\Controllers\Tenant;

use App\Core\WS\Signed\Certificate\X509Certificate;
use App\Core\WS\Signed\Certificate\X509ContentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\CompanyRequest;
use App\Http\Resources\Tenant\CompanyResource;
use App\Models\Tenant\Company;
use App\Models\Tenant\SoapType;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function create()
    {
        return view('tenant.companies.form');
    }

    public function tables()
    {
        $soap_types = SoapType::all();
        $custom_ticket_format = config('tenant.custom_ticket_format');

        return compact('soap_types','custom_ticket_format');
    }

    public function record()
    {
        $company = Company::first();
        if ($company) {
            $record = new CompanyResource($company);
        } else {
            $record = null;
        }

        return $record;
    }

    public function store(CompanyRequest $request)
    {
        $id = $request->input('id');
        $company = Company::firstOrNew(['id' => $id]);
        $company->fill($request->all());
        $company->save();

        return [
            'success' => true,
            'message' => 'Empresa actualizada'
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $company = Company::first();

            $type = $request->input('type');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = $type.'_'.$company->number.'.'.$ext;

            $file->storeAs(($type === 'logo')?'public/uploads/logos':'certificates', $name);

            $company->$type = $name;

            $company->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
                'type' => $type
            ];
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }
}