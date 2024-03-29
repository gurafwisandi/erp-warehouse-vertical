<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\RakModel;
use App\Models\SupplierModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ItemController extends Controller
{
    protected $menu = 'item';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = ItemModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
        ];
        return view('item.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'add',
            'satuan' => ['Pasang', 'Unit', 'Pcs'],
        ];
        return view('item.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:64',
            'diameter' => 'required|max:64',
            'file' => 'required|image',
            'satuan' => 'required|max:64',
            'panjang' => 'required|max:64',
            'berat' => 'required|max:64',
            'keterangan' => 'required|max:128',
        ]);
        DB::beginTransaction();
        try {
            $item = new ItemModel();
            $item->nama = $request->nama;
            $item->satuan = $request->satuan;
            $item->diameter = $request->diameter;
            $item->panjang = $request->panjang;
            $item->berat = $request->berat;
            $item->keterangan = $request->keterangan;
            if ($request->file()) {
                $fileName = Carbon::now()->format('ymdhis') . '_' .  str::random(25) . '.' . $request->file->extension();
                $item->gambar = $fileName;
                $request->file->move(public_path('files/item'), $fileName);
            }
            $item->id_user = Auth::user()->id;
            $item->save();

            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('item');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'view',
            'item' => ItemModel::findorfail(Crypt::decryptString($id)),
            'satuan' => ['Pasang', 'Unit', 'Pcs'],
        ];
        return view('item.view')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'edit',
            'item' => ItemModel::findorfail(Crypt::decryptString($id)),
            'satuan' => ['Pasang', 'Unit', 'Pcs'],
        ];
        return view('item.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $decrypted_id = Crypt::decryptString($id);
        $request->validate([
            'nama' => 'required|max:64',
            'diameter' => 'required|max:64',
            'satuan' => 'required|max:64',
            'file' => 'image',
            'panjang' => 'required|max:64',
            'berat' => 'required|max:64',
            'keterangan' => 'required|max:128',
        ]);
        DB::beginTransaction();
        try {
            $item = ItemModel::findOrFail($decrypted_id);
            $item->nama = $request->nama;
            $item->diameter = $request->diameter;
            $item->satuan = $request->satuan;
            $item->panjang = $request->panjang;
            $item->berat = $request->berat;
            $item->keterangan = $request->keterangan;
            if ($request->file()) {
                $fileName = Carbon::now()->format('ymdhis') . '_' .  str::random(25) . '.' . $request->file->extension();
                $item->gambar = $fileName;
                $request->file->move(public_path('files/item'), $fileName);
                File::delete('files/item/' . $request->images_old);
            }
            $item->id_user = Auth::user()->id;
            $item->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return redirect('item');
        } catch (\Throwable $err) {
            DB::rollBack();
            AlertHelper::updateAlert(false);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $item = ItemModel::findOrFail(Crypt::decryptString($id));
            $item->delete();
            DB::commit();
            AlertHelper::deleteAlert(true);
            return back();
        } catch (\Throwable $err) {
            DB::rollBack();
            AlertHelper::deleteAlert(false);
            return back();
        }
    }

    public function dropdown(Request $request)
    {
        $item = ItemModel::select('id', 'nama')->where('type', $request->type)->get();
        return $item;
    }

    public function dropdown_receive(Request $request)
    {
        $item = DB::table('rak')
            ->select('rak.*', 'gudang')
            ->selectRaw('sum(inventory.qty) - sum(inventory.qty_out) as qty_stok')
            ->join('item', 'item.id', '=', 'rak.id_item')
            ->join('gudang', 'gudang.id', '=', 'rak.id_gudang')
            ->leftJoin("inventory", function ($join) {
                $join->on("inventory.id_rak", "=", "rak.id")
                    ->on("inventory.id_item", "=", "item.id");
            })
            ->where('rak.id_item', '=', $request->item)
            ->orderBy('rak.no_rak', 'ASC')
            ->get();
        return $item;
    }

    public function stock($id)
    {
        $item = InventoryModel::where('id_item', Crypt::decryptString($id))->orderBy('id', 'ASC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'stock',
            'list' => $item,
        ];
        return view('item.stock')->with($data);
    }

    public function dropdown_rak(Request $request)
    {
        $item = DB::table('inventory')
            ->select('rak.*', 'inventory.id as id_inventory', 'gudang')
            ->selectRaw("sum(CASE WHEN receive.status = 'Selesai' THEN inventory.qty else 0 end) as qty")
            ->selectRaw("sum(CASE WHEN pengeluaran.status = 'Selesai' THEN inventory.qty_out else 0 end) as qty_out")
            ->join('rak', 'rak.id', '=', 'inventory.id_rak')
            ->join('gudang', 'gudang.id', '=', 'rak.id_gudang')
            ->join('receive', 'receive.id', '=', 'inventory.id_receive', 'left')
            ->join('pengeluaran', 'pengeluaran.id', '=', 'inventory.id_pengeluaran', 'left')
            ->wherenull('inventory.deleted_at')
            ->where('inventory.id_item', '=', $request->item)
            ->groupBy('inventory.id_rak')
            ->groupBy('inventory.id_item')
            ->havingRaw("sum(CASE WHEN receive.status = 'Selesai' THEN inventory.qty else 0 end) != sum(CASE WHEN pengeluaran.status = 'Selesai' THEN inventory.qty_out else 0 end)")
            ->orderBy('rak.id', 'ASC')
            ->get();
        return $item;
    }
    // ----------------------------------------------------------------------------------------------------------------------------------------------------------------

    // public function dropdown_pengeluaran(Request $request)
    // {
    //     $item = ItemModel::select('id', 'nama', 'qty')->where('type', $request->type)->where('qty', '>', 0)->get();
    //     return $item;
    // }
}
