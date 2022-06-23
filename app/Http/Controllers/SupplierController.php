<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    protected $menu = 'vendor';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor = SupplierModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $vendor
        ];
        return view('supplier.list')->with($data);
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
        return view('supplier.add')->with($data);
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
            'nama' => 'required|max:64',
            'pic' => 'required|max:64',
            'email' => 'required|email:dns|unique:vendor,email,NULL,id,deleted_at,NULL|max:64',
            'no_tlp' => 'required|max:64',
            'alamat' => 'required|max:128',
        ]);
        DB::beginTransaction();
        try {
            SupplierModel::create([
                'nama' => $validated['nama'],
                'pic' => $validated['pic'],
                'email' => $validated['email'],
                'no_tlp' => $validated['no_tlp'],
                'alamat' => $validated['alamat'],
                'id_user' => Auth::user()->id,
            ]);
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('supplier');
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
            'item' => SupplierModel::findorfail(Crypt::decryptString($id))
        ];
        return view('supplier.view')->with($data);
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
            'item' => SupplierModel::findorfail(Crypt::decryptString($id))
        ];
        return view('supplier.edit')->with($data);
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
            'nama' => 'required|max:64',
            'pic' => 'required|max:64',
            'email' => "required|email:dns|max:64|unique:vendor,email,$decrypted_id,id,deleted_at,NULL",
            'no_tlp' => 'required|max:64',
            'alamat' => 'required|max:128',
        ]);

        DB::beginTransaction();
        try {
            $vendor = SupplierModel::findOrFail($decrypted_id);
            $vendor->nama = $validated['nama'];
            $vendor->pic = $validated['pic'];
            $vendor->email = $validated['email'];
            $vendor->no_tlp = $validated['no_tlp'];
            $vendor->alamat = $validated['alamat'];
            $vendor->save();
            DB::commit();
            AlertHelper::updateAlert(true);
            return redirect('supplier');
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
            $vendor = SupplierModel::findOrFail(Crypt::decryptString($id));
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
