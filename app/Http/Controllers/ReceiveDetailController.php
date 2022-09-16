<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\ReceiveDetailModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReceiveDetailController extends Controller
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
            'id_item' => 'required',
            'qty' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $cek_item = ReceiveDetailModel::where('id_receive', $request->id_receive)->where('id_item', $request->id_item)->get();
            if (count($cek_item) > 0) {
                AlertHelper::addDuplicate(false);
                return back();
            }
            $receive = new ReceiveDetailModel();
            $receive->id_receive = $request->id_receive;
            $receive->id_item = $request->id_item;
            $receive->qty = $request->qty;
            $receive->tgl_produksi = $request->tgl_produksi;
            $receive->save();
            $insertedId = Crypt::encryptString($request->id_receive);
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect()->route('receive.edit', $insertedId);
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
            $item = ReceiveDetailModel::findOrFail(Crypt::decryptString($id));
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
