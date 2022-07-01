<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\GudangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class GudangController extends Controller
{
    protected $menu = 'gudang';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gudang = GudangModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $gudang
        ];
        return view('gudang.list')->with($data);
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
        return view('gudang.add')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'gudang' => 'required|max:64|unique:gudang,gudang,NULL,id,deleted_at,NULL',
        ]);
        DB::beginTransaction();
        try {
            gudangModel::create([
                'gudang' => $validated['gudang'],
            ]);
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('gudang');
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
            'item' => gudangModel::findorfail(Crypt::decryptString($id))
        ];
        return view('gudang.edit')->with($data);
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
        $validated = $request->validate([
            'gudang' => "required|max:64|unique:gudang,gudang,$decrypted_id,id,deleted_at,NULL",
        ]);

        DB::beginTransaction();
        try {
            $vendor = gudangModel::findOrFail($decrypted_id);
            $vendor->gudang = $validated['gudang'];
            $vendor->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return redirect('gudang');
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
            $vendor = gudangModel::findOrFail(Crypt::decryptString($id));
            $vendor->delete();
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
