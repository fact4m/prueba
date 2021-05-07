<?php
namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\ItemRequest;
use App\Http\Resources\Tenant\ItemCollection;
use App\Http\Resources\Tenant\ItemResource;
use App\Models\Tenant\Catalogs\Code;
use App\Models\Tenant\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct() {
        $this->middleware('role', ['only' => ['index', 'create', 'store']]);
    }
    
    public function index()
    {
        return view('tenant.items.index');
    }

    public function columns()
    {
        return [
            'internal_id' => 'Código Interno',
            'description' => 'Descripción'
        ];
    }

    public function records(Request $request)
    {
        $records = Item::where($request->column, 'like', "%{$request->value}%")
                       ->orderBy('description');

        return new ItemCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function create()
    {
        return view('tenant.items.form');
    }

    public function tables()
    {
        $unit_types = Code::byCatalog('03');

        return compact('unit_types');
    }

    public function record($id)
    {
        $record = new ItemResource(Item::findOrFail($id));

        return $record;
    }

    public function store(ItemRequest $request)
    {
        $id = $request->input('id');
        $item = Item::firstOrNew(['id' => $id]);
        $item->item_type_id = '01';
        $item->fill($request->all());
        $item->save();

        return [
            'success' => true,
            'message' => ($id)?'Producto editado con éxito':'Producto registrado con éxito'
        ];
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return [
            'success' => true,
            'message' => 'Producto eliminado con éxito'
        ];
    }
}