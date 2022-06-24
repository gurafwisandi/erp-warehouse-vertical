<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\ReceiveDetailModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
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
            'id_receive' => 'required',
            'item' => 'required',
            'rak' => 'required',
            'tgl_masuk_gudang' => 'required',
            'qty' => 'required',
        ]);
        DB::beginTransaction();
        try {
            // TODO : cek validasi
            // cari id detail receive
            $id_receive_detail = ReceiveDetailModel::where('id_receive', $request->id_receive)->where('id_item', $request->item)->first();
            if ($id_receive_detail->qty_terima >= $id_receive_detail->qty) {
                AlertHelper::addStock(false);
                return back();
            }
            if ($request->qty > $id_receive_detail->qty) {
                AlertHelper::addStock(false);
                return back();
            }
            if (($request->qty + $id_receive_detail->qty_terima) > $id_receive_detail->qty) {
                AlertHelper::addStock(false);
                return back();
            }
            // insert inventory
            $inven = new InventoryModel();
            $inven->tgl_masuk_gudang = $request->tgl_masuk_gudang;
            $inven->qty = $request->qty;
            $inven->id_rak = $request->rak;
            $inven->id_item = $request->item;
            $inven->id_receive = $request->id_receive;
            $inven->id_receive_detail = $id_receive_detail->id;
            $inven->save();
            // cek item yg sudah di terima
            $jml_qty = InventoryModel::where('id_receive', $request->id_receive)->where('id_item', $request->item)->sum('qty');
            // update qty yg sudah diterima
            $receive = ReceiveDetailModel::findOrFail($id_receive_detail->id);
            $receive->qty_terima = $jml_qty;
            $receive->save();
            $insertedId = Crypt::encryptString($request->id_receive);
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect()->route('receive.penempatan', $insertedId);
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
        //
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
            $receive = InventoryModel::findOrFail(Crypt::decryptString($id));
            $id_receive = $receive->id_receive;
            $id_item = $receive->id_item;
            $id_receive_detail = $receive->id_receive_detail;
            $receive->delete();

            // cek item yg sudah di terima
            $jml_qty = InventoryModel::where('id_receive', $id_receive)->where('id_item', $id_item)->sum('qty');
            // update qty yg sudah diterima
            $detail = ReceiveDetailModel::findOrFail($id_receive_detail);
            $detail->qty_terima = $jml_qty;
            $detail->save();

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
