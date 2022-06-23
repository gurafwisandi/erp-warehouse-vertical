<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\PengeluaranDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PengeluaranDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'id_pengeluaran' => 'required',
            'item' => 'required',
            'rak' => 'required', // isi rak.id|inventory.id as id_inventory
            'qty' => 'required',
        ]);
        DB::beginTransaction();
        try {
            // cek stock inventory
            $inven = InventoryModel::where('id_rak', $request->rak)->where('id_item', $request->item)->where('status', 'IN')->sum('qty');
            // cek detail pengeluaran berdasarkan id_item
            $cek_stock = PengeluaranDetailModel::where('id_rak', $request->rak)->where('id_item', $request->item)->wherenull('status_out')->sum('qty');
            if (($request->qty + $cek_stock) > $inven) {
                AlertHelper::addStock(false);
                return back();
            }
            $stock = InventoryModel::where('id_rak', $request->rak)->where('id_item', $request->item)->limit($request->qty)->where('status', 'IN')->get();
            for ($i = 0; $i < count($stock); $i++) {
                $pengeluaran = new PengeluaranDetailModel();
                $pengeluaran->id_pengeluaran = $request->id_pengeluaran;
                $pengeluaran->id_inventory = $stock[$i]->id; // id_inventory
                $pengeluaran->id_item = $request->item;
                $pengeluaran->id_rak = $request->rak;
                $pengeluaran->type_out = $request->type;
                $pengeluaran->qty = '1';
                $pengeluaran->save();
            }
            $insertedId = Crypt::encryptString($request->id_pengeluaran);
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect()->route('pengeluaran.edit', $insertedId);
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        DB::beginTransaction();
        try {
            $item = PengeluaranDetailModel::findOrFail(Crypt::decryptString($id));
            $item->status_out = 'IN';
            $item->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return back();
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
            $item = PengeluaranDetailModel::findOrFail(Crypt::decryptString($id));
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
}
