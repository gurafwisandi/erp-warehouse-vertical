<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
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
            'type' => 'required',
            'item' => 'required',
            'rak' => 'required',
            'tgl_expired' => 'required',
            'tgl_masuk_gudang' => 'required',
            'qty' => 'required',
        ]);
        DB::beginTransaction();
        try {
            if ($request->type == 'Alat') {
                for ($i = 0; $i < $request->qty; $i++) {
                    // explode data item
                    $item = explode("|", $request->item);
                    $id_item = $item[0]; // item.id
                    $id_receive_detail = $item[1]; // receive_detail.id as id_receive_detail
                    $sum_qty = $item[2]; // sum(receive_detail.qty)
                    // cek stock dan penerimaan
                    $stock = InventoryModel::where('id_receive', $request->id_receive)->where('id_item', $id_item)->sum('qty');
                    if ($stock >= $sum_qty) {
                        AlertHelper::addStock(false);
                        return back();
                    }
                    // kode item
                    $registration_number = InventoryModel::wherenotnull('kode_item')->pluck('kode_item')->last();
                    $now = Carbon::now();
                    $month_and_year = $now->format('ymd');
                    if (!$registration_number) {
                        $generate_registration_number = "ITM" . $month_and_year . sprintf('%04d', 1);
                    } else {
                        $last_number = (int)substr($registration_number, 9);
                        $generate_registration_number = "ITM" . $month_and_year . sprintf('%04d', $last_number + 1);
                    }
                    // insert inventory
                    $inven = new InventoryModel();
                    $inven->kode_item = $generate_registration_number;
                    $inven->tgl_masuk_gudang = $request->tgl_masuk_gudang;
                    $inven->tgl_expired = $request->tgl_expired;
                    $inven->qty = '1';
                    $inven->id_rak = $request->rak;
                    $inven->id_item = $id_item;
                    $inven->id_receive = $request->id_receive;
                    $inven->id_receive_detail = $id_receive_detail;
                    $inven->save();
                }
            } else {
                for ($i = 0; $i < $request->qty; $i++) {
                    $item = explode("|", $request->item);
                    $id_item = $item[0]; // item.id
                    $id_receive_detail = $item[1]; // receive_detail.id as id_receive_detail
                    $sum_qty = $item[2]; // sum(receive_detail.qty)
                    // cek stock dan penerimaan
                    $stock = InventoryModel::where('id_receive', $request->id_receive)->where('id_item', $id_item)->sum('qty');
                    // dd(floatval($sum_qty));
                    if (floatval($stock) >= floatval($sum_qty)) {
                        AlertHelper::addStock(false);
                        return back();
                    }
                    if (floatval($request->qty) > floatval($sum_qty)) {
                        AlertHelper::addStock(false);
                        return back();
                    }
                    // insert inventory
                    $inven = new InventoryModel();
                    $inven->tgl_masuk_gudang = $request->tgl_masuk_gudang;
                    $inven->tgl_expired = $request->tgl_expired;
                    $inven->qty = '1';
                    $inven->id_rak = $request->rak;
                    $inven->id_item = $id_item;
                    $inven->id_receive = $request->id_receive;
                    $inven->id_receive_detail = $id_receive_detail;
                    $inven->save();
                }
            }
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
            $receive->delete();
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
