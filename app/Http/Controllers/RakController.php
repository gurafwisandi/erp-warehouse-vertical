<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\RakModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class RakController extends Controller
{
    protected $menu = 'rak';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = RakModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
        ];
        return view('rak.list')->with($data);
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
        ];
        return view('rak.add')->with($data);
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
            'no_rak' => 'required|max:64|unique:rak,no_rak,NULL,id,deleted_at,NULL',
            'keterangan' => 'required|max:128',
        ]);
        DB::beginTransaction();
        try {
            $item = new RakModel();
            $item->no_rak = $request->no_rak;
            $item->keterangan = $request->keterangan;
            $item->save();
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('rak');
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
            'item' => RakModel::findorfail(Crypt::decryptString($id)),
        ];
        return view('rak.view')->with($data);
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
            'item' => RakModel::findorfail(Crypt::decryptString($id)),
        ];
        return view('rak.edit')->with($data);
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
            'no_rak' => "required|max:64|unique:rak,no_rak,$decrypted_id,id,deleted_at,NULL",
            'keterangan' => 'required|max:128',
        ]);
        DB::beginTransaction();
        try {
            $item = RakModel::findOrFail($decrypted_id);
            $item->no_rak = $request->no_rak;
            $item->keterangan = $request->keterangan;
            $item->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return redirect('rak');
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
            $item = RakModel::findOrFail(Crypt::decryptString($id));
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

    public function stock_rak($id)
    {
        $item = InventoryModel::where('id_rak', Crypt::decryptString($id))->where('status', 'IN')->orderBy('id', 'ASC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'stock',
            'list' => $item,
        ];
        return view('item.stock')->with($data);
    }
}
