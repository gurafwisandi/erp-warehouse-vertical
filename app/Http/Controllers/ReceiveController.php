<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\RakModel;
use App\Models\ReceiveDetailModel;
use App\Models\ReceiveModel;
use App\Models\SupplierModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReceiveController extends Controller
{
    protected $menu = 'penerimaan';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = ReceiveModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
        ];
        return view('receive.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $registration_number = ReceiveModel::pluck('kode_receive')->last();
        $now = Carbon::now();
        $month_and_year = $now->format('ymd');
        if (!$registration_number) {
            $generate_registration_number = "TRM" . $month_and_year . sprintf('%04d', 1);
        } else {
            $last_number = (int)substr($registration_number, 9);
            $generate_registration_number = "TRM" . $month_and_year . sprintf('%04d', $last_number + 1);
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'add',
            'kode_receive' => $generate_registration_number,
        ];
        return view('receive.add')->with($data);
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
            'kode_receive' => 'required|max:64|unique:receive,kode_receive,NULL,id,deleted_at,NULL',
            'tgl_receive' => 'required',
            'keterangan' => 'max:128',
        ]);
        DB::beginTransaction();
        try {
            $receive = new ReceiveModel();
            $receive->kode_receive = $request->kode_receive;
            $receive->tgl_receive = $request->tgl_receive;
            $receive->keterangan = $request->keterangan;
            $receive->status = 'Pembuatan Request';
            $receive->id_user = Auth::user()->id;
            $receive->save();
            $insertedId = Crypt::encryptString($receive->id);
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
        $data = [
            'menu' => $this->menu,
            'title' => 'penempatan',
            'item' => ReceiveModel::findorfail(Crypt::decryptString($id)),
            'details' => ReceiveDetailModel::where('id_receive', Crypt::decryptString($id))->get(),
            'invens' => InventoryModel::where('id_receive', Crypt::decryptString($id))->get(),
        ];
        return view('receive.view')->with($data);
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
            'title' => 'penerimaan',
            'item' => ReceiveModel::findorfail(Crypt::decryptString($id)),
            'details' => ReceiveDetailModel::where('id_receive', Crypt::decryptString($id))->get(),
            'items' => ItemModel::all(),
        ];
        return view('receive.edit')->with($data);
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
        $request->validate([
            'kode_receive' => 'required',
            'tgl_receive' => 'required',
            'id_vendor' => 'required',
            'keterangan' => 'max:128',
        ]);
        DB::beginTransaction();
        try {
            $receive = ReceiveModel::findOrFail($request->id);
            $receive->kode_receive = $request->kode_receive;
            $receive->tgl_receive = $request->tgl_receive;
            $receive->keterangan = $request->keterangan;
            $receive->id_vendor = $request->id_vendor;
            $receive->save();
            $insertedId = Crypt::encryptString($request->id);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // delete receive
            $receive = ReceiveModel::findOrFail(Crypt::decryptString($id));
            $receive->delete();
            // delete detail receive
            $item = ReceiveDetailModel::where('id_receive', Crypt::decryptString($id));
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

    public function approve_purchasing($id)
    {
        DB::beginTransaction();
        try {
            $receive = ReceiveModel::findOrFail(Crypt::decryptString($id));
            $receive->status = 'Proses Request';
            $receive->save();
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('receive');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
        }
    }

    public function penempatan($id)
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'penempatan',
            'item' => ReceiveModel::findorfail(Crypt::decryptString($id)),
            'details' => ReceiveDetailModel::where('id_receive', Crypt::decryptString($id))->get(),
            'invens' => InventoryModel::where('id_receive', Crypt::decryptString($id))->get(),
        ];
        return view('receive.receive')->with($data);
    }

    public function approve_penempatan($id)
    {
        DB::beginTransaction();
        try {
            // update status receive
            $receive = ReceiveModel::findOrFail(Crypt::decryptString($id));
            $receive->status = 'Selesai';
            $receive->save();

            // update qty yg sudah diterima
            $detail = ReceiveDetailModel::where('id_receive', Crypt::decryptString($id))->get();
            foreach ($detail as $item) {
                $qty = InventoryModel::where('id_receive', $item->id_receive)->where('id_item', $item->id_item)->sum('qty');
                ItemModel::where('id', $item->id_item)->update(['qty' => $qty]);
            }
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('receive');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
        }
    }
}
