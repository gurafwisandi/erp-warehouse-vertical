<?php

namespace App\Http\Controllers;

use App\Helper\AlertHelper;
use App\Models\InventoryModel;
use App\Models\ItemModel;
use App\Models\PengeluaranDetailModel;
use App\Models\PengeluaranModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PengeluaranController extends Controller
{
    protected $menu = 'pengeluaran';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = PengeluaranModel::orderBy('id', 'DESC')->get();
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
        ];
        return view('pengeluaran.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $registration_number = PengeluaranModel::pluck('kode_pengeluaran')->last();
        $now = Carbon::now();
        $month_and_year = $now->format('ymd');
        if (!$registration_number) {
            $generate_registration_number = "OUT" . $month_and_year . sprintf('%04d', 1);
        } else {
            $last_number = (int)substr($registration_number, 9);
            $generate_registration_number = "OUT" . $month_and_year . sprintf('%04d', $last_number + 1);
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'add',
            'kode_pengeluaran' => $generate_registration_number,
        ];
        return view('pengeluaran.add')->with($data);
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
            'kode_pengeluaran' => 'required|max:64|unique:pengeluaran,kode_pengeluaran,NULL,id,deleted_at,NULL',
            'tgl_pengeluaran' => 'required',
            'keterangan' => 'max:128',
        ]);
        DB::beginTransaction();
        try {
            $pengeluaran = new PengeluaranModel();
            $pengeluaran->kode_pengeluaran = $request->kode_pengeluaran;
            $pengeluaran->tgl_pengeluaran = $request->tgl_pengeluaran;
            $pengeluaran->keterangan = $request->keterangan;
            $pengeluaran->status = 'Proses Permintaan';
            $pengeluaran->id_user = Auth::user()->id;
            $pengeluaran->save();
            $insertedId = Crypt::encryptString($pengeluaran->id);
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
        $data = [
            'menu' => $this->menu,
            'title' => 'view',
            'item' => ItemModel::all(),
            'header' => PengeluaranModel::findorfail(Crypt::decryptString($id)),
            'details' => PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id))->get(),
            'type' => ['Chemical', 'Alat'],
        ];
        return view('pengeluaran.view')->with($data);
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
            'item' => ItemModel::all(),
            'header' => PengeluaranModel::findorfail(Crypt::decryptString($id)),
            'details' => PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id))->get(),
            'type' => ['Chemical', 'Alat'],
        ];
        return view('pengeluaran.edit')->with($data);
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
            'kode_pengeluaran' => 'required',
            'tgl_pengeluaran' => 'required',
            'keterangan' => 'max:128',
        ]);
        DB::beginTransaction();
        try {
            $pengeluaran = PengeluaranModel::findOrFail($request->id);
            $pengeluaran->kode_pengeluaran = $request->kode_pengeluaran;
            $pengeluaran->tgl_pengeluaran = $request->tgl_pengeluaran;
            $pengeluaran->keterangan = $request->keterangan;
            $pengeluaran->save();
            $insertedId = Crypt::encryptString($request->id);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // delete pengeluaran
            $pengeluaran = PengeluaranModel::findOrFail(Crypt::decryptString($id));
            $pengeluaran->delete();
            // delete detail pengeluaran
            $item = PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id));
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

    public function approve_pengeluaran($id)
    {
        DB::beginTransaction();
        try {
            // update status receive
            $pengeluaran = PengeluaranModel::findOrFail(Crypt::decryptString($id));
            $cek_alat = PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id))->where('type_out', 'Alat')->count('id');
            if ($cek_alat > 0) {
                $pengeluaran->status = 'Pengiriman Permintaan';
            } else {
                $pengeluaran->status = 'Selesai Permintaan';
            }
            $pengeluaran->save();
            // cek detail pengeluaran
            $detail = PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id))->get();
            for ($i = 0; $i < count($detail); $i++) {
                // update status menjadi OUT
                InventoryModel::where('id', $detail[$i]->id_inventory)->update(['status' => 'OUT']);
                // cek stock
                $stock = InventoryModel::findorfail($detail[$i]->id_inventory);
                $hasil_qty = InventoryModel::where('id_item', $stock->id_item)->where('status', 'IN')->sum('qty');
                ItemModel::where('id', $stock->id_item)->update(['qty' => $hasil_qty]);
                // update status menjadi OUT
                PengeluaranDetailModel::where('id', $detail[$i]->id)->update(['status_out' => 'OUT']);
            }
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('pengeluaran');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
        }
    }

    public function acceptance($id)
    {
        $data = [
            'menu' => $this->menu,
            'title' => 'penerimaan',
            'item' => ItemModel::all(),
            'header' => PengeluaranModel::findorfail(Crypt::decryptString($id)),
            'details' => PengeluaranDetailModel::where('id_pengeluaran', Crypt::decryptString($id))->orderby('type_out', 'ASC')->get(),
            'type' => ['Chemical', 'Alat'],
        ];
        return view('pengeluaran.penerimaan')->with($data);
    }

    public function approve_pengembalian(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // update status receive
            $pengeluaran = PengeluaranModel::findOrFail($request->id_pengeluaran);
            $pengeluaran->status = 'Selesai Permintaan';
            $pengeluaran->save();
            // menampilkan detail pengeluaran
            $detail = PengeluaranDetailModel::where('id_pengeluaran', $request->id_pengeluaran)->where('status_out', 'IN')->get();
            for ($i = 0; $i < count($detail); $i++) {
                // update inventory status jadi IN
                InventoryModel::where('id', $detail[0]->id_inventory)->update(['status' => 'IN']);
            }
            DB::commit();
            AlertHelper::addAlert(true);
            return redirect('pengeluaran');
        } catch (\Throwable $err) {
            DB::rollBack();
            throw $err;
            AlertHelper::addAlert(false);
            return back();
        }
    }
}
