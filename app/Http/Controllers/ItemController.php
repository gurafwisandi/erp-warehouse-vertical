<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\ItemModel;
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
            'vendor' => SupplierModel::orderBy('nama', 'ASC')->get(),
            'type' => ['Chemical', 'Alat'],
            'satuan' => ['Pasang', 'Unit', 'Pcs', 'Stell', 'Liter', 'Tube', 'Gram', 'KG'],
            'bentuk' => ['Cair', 'Padat', 'Padat Pasir', 'Padat Bubuk'],
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
            'type' => 'required|max:64',
            'file' => 'required|image',
            'satuan' => 'required|max:64',
            'bentuk_barang' => 'required|max:64',
            'keterangan' => 'required|max:128',
            'id_vendor' => 'required|max:64',
        ]);
        DB::beginTransaction();
        try {

            $item = new ItemModel();
            $item->nama = $request->nama;
            $item->type = $request->type;
            $item->satuan = $request->satuan;
            $item->bentuk_barang = $request->bentuk_barang;
            $item->keterangan = $request->keterangan;
            if ($request->file()) {
                $fileName = Carbon::now()->format('ymdhis') . '_' .  str::random(25) . '.' . $request->file->extension();
                $item->gambar = $fileName;
                $request->file->move(public_path('files/item'), $fileName);
            }
            $item->id_vendor = $request->id_vendor;
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
            'vendor' => SupplierModel::orderBy('nama', 'ASC')->get(),
            'type' => ['Chemical', 'Alat'],
            'satuan' => ['Pasang', 'Unit', 'Pcs', 'Stell', 'Liter', 'Tube', 'Gram', 'KG'],
            'bentuk' => ['Cair', 'Padat', 'Padat Pasir', 'Padat Bubuk'],
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
            'vendor' => SupplierModel::orderBy('nama', 'ASC')->get(),
            'type' => ['Chemical', 'Alat'],
            'satuan' => ['Pasang', 'Unit', 'Pcs', 'Stell', 'Liter', 'Tube', 'Gram', 'KG'],
            'bentuk' => ['Cair', 'Padat', 'Padat Pasir', 'Padat Bubuk'],
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
            'type' => 'required|max:64',
            'satuan' => 'required|max:64',
            'file' => 'image',
            'bentuk_barang' => 'required|max:64',
            'keterangan' => 'required|max:128',
            'id_vendor' => 'required|max:64',
        ]);
        DB::beginTransaction();
        try {
            $item = ItemModel::findOrFail($decrypted_id);
            $item->nama = $request->nama;
            $item->type = $request->type;
            $item->satuan = $request->satuan;
            $item->bentuk_barang = $request->bentuk_barang;
            $item->keterangan = $request->keterangan;
            if ($request->file()) {
                $fileName = Carbon::now()->format('ymdhis') . '_' .  str::random(25) . '.' . $request->file->extension();
                $item->gambar = $fileName;
                $request->file->move(public_path('files/item'), $fileName);
                File::delete('files/item/' . $request->images_old);
            }
            $item->id_vendor = $request->id_vendor;
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

    public function dropdown_pengeluaran(Request $request)
    {
        $item = ItemModel::select('id', 'nama', 'qty')->where('type', $request->type)->where('qty', '>', 0)->get();
        return $item;
    }

    public function dropdown_receive(Request $request)
    {
        $item = DB::table('receive')
            ->select('item.*', 'receive_detail.id as id_receive_detail')
            ->selectRaw('sum(receive_detail.qty) as qty')
            ->join('receive_detail', 'receive_detail.id_receive', '=', 'receive.id')
            ->join('item', 'item.id', '=', 'receive_detail.id_item')
            ->where('receive.id', '=', $request->idReceive)
            ->where('receive_detail.type', '=', $request->type)
            ->wherenull('receive_detail.deleted_at')
            ->groupBy('receive_detail.type')
            ->orderBy('item.nama', 'ASC')
            ->get();
        return $item;
    }

    public function dropdown_rak(Request $request)
    {
        $item = DB::table('inventory')
            ->select('rak.*', 'inventory.id as id_inventory')
            ->selectRaw('sum(inventory.qty) as qty')
            ->join('rak', 'rak.id', '=', 'inventory.id_rak')
            ->wherenull('inventory.deleted_at')
            ->where('inventory.id_item', '=', $request->item)
            ->where('inventory.status', '=', 'IN')
            ->groupBy('inventory.id_rak')
            ->groupBy('inventory.id_item')
            ->orderBy('rak.id', 'ASC')
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
}
