<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use App\Http\Resources\System\ClientCollection;
use App\Http\Requests\System\ClientRequest;
use Hyn\Tenancy\Environment;
use App\Models\System\Client;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {          
        return view('system.clients.index');
    }

    public function create()
    {
        return view('system.clients.form');
    }

    public function records()
    {
        $records = Client::with(['hostname'=> function ($query) {
            $query->with(['website']);
        }])->get();         
 
        foreach ($records as $r) { 
            $tenancy = app(Environment::class);
            $tenancy->tenant($r->hostname->website);   
            $r['num_doc'] = DB::connection('tenant')->table('documents')->count(); 
        }        
   
        return new ClientCollection($records); 

    }

    public function store(ClientRequest $request)
    {
        $subDom = $request->input('subdomain');

        DB::connection('system')->beginTransaction();
        try {
            $uuid = config('tenant.prefix_database').'_'.$subDom;

            $website = new Website();
            $website->uuid = $uuid;
            app(WebsiteRepository::class)->create($website);

            $hostname = new Hostname();
            $hostname->fqdn = $subDom.'.'.config('tenant.app_url_base');
            app(HostnameRepository::class)->attach($hostname, $website);

            $tenancy = app(Environment::class);
            $tenancy->tenant($website);

            $token = str_random(50);

            $client = new Client();
            $client->hostname_id = $hostname->id;
            $client->token = $token;
            $client->email = $request->input('email');
            $client->name = $request->input('name');
            $client->number = $request->input('number');
            $client->save();

            DB::connection('system')->commit();
        }
        catch (Exception $e) {
            DB::connection('system')->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }

        DB::connection('tenant')->table('companies')->insert([
            'identity_document_type_id' => '06000006',
            'number' => $request->input('number'),
            'name' => $request->input('name'),
            'soap_type_id' => '01'
        ]);

        DB::connection('tenant')->table('establishments')->insert([
            'description' => 'Oficina Principal',
            'country_id' => '040000PE',
            'department_id' => '15',
            'province_id' => '1501',
            'district_id' => '150101',
            'address' => '-',
            'email' => $request->input('email'),
            'phone' => '-',
            'code' => '0000'
        ]);

        DB::connection('tenant')->table('users')->insert([
            'name' => 'Administrador',
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'is_admin' => $request->input('profile'),
            'api_token' => $token
        ]);


        DB::connection('tenant')->table('customers')->insert([
            'identity_document_type_id' => '06000000',
            'number' => '99999999',            
            'name' => 'clientes varios',
            'trade_name' => null,
            'country_id' => '040000PE',
            'department_id' => null,
            'province_id' => null,
            'district_id' => null,
            'address' => null,
            'email' => null,
            'phone' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);



        return [
            'success' => true,
            'message' => 'Cliente Registrado satisfactoriamente'
        ];
    }

    public function destroy($id)
    {
        $client = Client::find($id);

        $hostname = Hostname::find($client->hostname_id);
        $website = Website::find($hostname->website_id);

        app(HostnameRepository::class)->delete($hostname, true);
        app(WebsiteRepository::class)->delete($website, true);

        return [
            'success' => true,
            'message' => 'Cliente eliminado con éxito'
        ];
    }

    public function password($id)
    {
        $client = Client::find($id);
        $website = Website::find($client->hostname->website_id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($website);
        DB::connection('tenant')->table('users')
            ->where('id', 1)
            ->update(['password' => bcrypt($client->number)]);

        return [
            'success' => true,
            'message' => 'Clave cambiada con éxito'
        ];
    }
}
