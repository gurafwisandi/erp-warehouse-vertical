<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\PengeluaranModel;
use App\Models\ReceiveModel;
use App\Models\SupplierModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    protected $menu = 'report';

    public function penerimaan(Request $request)
    {
        if ($request->kode_receive or $request->start or $request->end or $request->status) {
            $matchThese = [];
            if ($request->kode_receive) {
                $kode_receive = array('kode_receive' => $request->kode_receive);
                array_push($matchThese, $kode_receive);
            } else {
                $kode_receive = array();
            }
            if ($request->status) {
                $status = array('status' => $request->status);
                array_push($matchThese, $status);
            } else {
                $status = array();
            }
            $matchThese = $kode_receive + $status;
            if ($request->start) {
                $start = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->start);
                $end = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->end);
                $item = ReceiveModel::whereBetween('tgl_receive', [$start, $end])->where($matchThese)->orderBy('id', 'DESC')->get();
            } else {
                $item = ReceiveModel::where($matchThese)->orderBy('id', 'DESC')->get();
            }
        } else {
            $item = ReceiveModel::orderBy('id', 'DESC')->get();
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
            'status' => ['Pembuatan Request', 'Proses Request', 'Selesai'],
        ];
        return view('report.penerimaan')->with($data);
    }

    public function rep_pengeluaran(Request $request)
    {
        if ($request->kode_pengeluaran or $request->id_user or $request->start or $request->end or $request->status) {
            $matchThese = [];
            if ($request->kode_pengeluaran) {
                $kode_pengeluaran = array('kode_pengeluaran' => $request->kode_pengeluaran);
                array_push($matchThese, $kode_pengeluaran);
            } else {
                $kode_pengeluaran = array();
            }
            if ($request->id_user) {
                $id_user = array('id_user' => $request->id_user);
                array_push($matchThese, $id_user);
            } else {
                $id_user = array();
            }
            if ($request->status) {
                $status = array('status' => $request->status);
                array_push($matchThese, $status);
            } else {
                $status = array();
            }
            $matchThese = $kode_pengeluaran + $id_user + $status;
            if ($request->start) {
                $start = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->start);
                $end = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->end);
                $item = PengeluaranModel::whereBetween('tgl_pengeluaran', [$start, $end])->where($matchThese)->orderBy('id', 'DESC')->get();
            } else {
                $item = PengeluaranModel::where($matchThese)->orderBy('id', 'DESC')->get();
            }
        } else {
            $item = PengeluaranModel::orderBy('id', 'DESC')->get();
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
            'user' => User::where('roles', 'Sales')->get(),
            'status' => ['Proses Permintaan', 'Pengajuan ke Gudang', 'Selesai'],
        ];
        return view('report.pengeluaran')->with($data);
    }

    public function rep_item(Request $request)
    {
        if ($request->type or $request->qty) {
            $matchThese = [];
            if ($request->type) {
                $type = array('type' => $request->type);
                array_push($matchThese, $type);
            } else {
                $type = array();
            }
            if ($request->qty) {
                $qty = array('qty' => $request->qty);
                array_push($matchThese, $qty);
            } else {
                $qty = array();
            }
            $matchThese = $type + $qty;
            $item = ItemModel::where($matchThese)->orderBy('id', 'DESC')->get();
        } else {
            $item = ItemModel::orderBy('id', 'DESC')->get();
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
            'type' => ['Chemical', 'Alat'],
        ];
        return view('report.item')->with($data);
    }

    public function rep_sales(Request $request)
    {
        if ($request->item or $request->name or $request->start or $request->end or $request->qty) {
            $matchThese = [];
            if ($request->name) {
                $name = array('users.id' => $request->name);
                array_push($matchThese, $name);
            } else {
                $name = array();
            }
            if ($request->item) {
                $item = array('item.id' => $request->item);
                array_push($matchThese, $item);
            } else {
                $item = array();
            }
            $matchThese = $item + $name;
            $start = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->start);
            $end = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$1-$2", $request->end);
            if ($request->qty == null and $request->start) {
                $item = DB::table('pengeluaran')
                    ->select('name', 'pengeluaran.kode_pengeluaran', 'pengeluaran.tgl_pengeluaran', 'item.nama', 'item.panjang', 'item.satuan')
                    ->selectRaw('SUM(pengeluaran_detail.qty_acc) as qty')
                    ->join('pengeluaran_detail', 'pengeluaran_detail.id_pengeluaran', '=', 'pengeluaran.id')
                    ->join('item', 'item.id', '=', 'pengeluaran_detail.id_item')
                    ->join('users', 'users.id', '=', 'pengeluaran.id_user')
                    ->where('pengeluaran.status', '=', 'Selesai')
                    ->whereBetween('tgl_pengeluaran', [$start, $end])
                    ->where($matchThese)
                    ->groupBy('users.id', 'pengeluaran_detail.id_item')
                    ->get();
            } else if ($request->qty != null and $request->start) {
                $item = DB::table('pengeluaran')
                    ->select('name', 'pengeluaran.kode_pengeluaran', 'pengeluaran.tgl_pengeluaran', 'item.nama', 'item.panjang', 'item.satuan')
                    ->selectRaw('SUM(pengeluaran_detail.qty_acc) as qty')
                    ->join('pengeluaran_detail', 'pengeluaran_detail.id_pengeluaran', '=', 'pengeluaran.id')
                    ->join('item', 'item.id', '=', 'pengeluaran_detail.id_item')
                    ->join('users', 'users.id', '=', 'pengeluaran.id_user')
                    ->where('pengeluaran.status', '=', 'Selesai')
                    ->whereBetween('tgl_pengeluaran', [$start, $end])
                    ->where($matchThese)
                    ->groupBy('users.id', 'pengeluaran_detail.id_item')
                    ->having('qty', '=', $request->qty)
                    ->get();
            } elseif ($request->qty == null) {
                $item = DB::table('pengeluaran')
                    ->select('name', 'pengeluaran.kode_pengeluaran', 'pengeluaran.tgl_pengeluaran', 'item.nama', 'item.panjang', 'item.satuan')
                    ->selectRaw('SUM(pengeluaran_detail.qty_acc) as qty')
                    ->join('pengeluaran_detail', 'pengeluaran_detail.id_pengeluaran', '=', 'pengeluaran.id')
                    ->join('item', 'item.id', '=', 'pengeluaran_detail.id_item')
                    ->join('users', 'users.id', '=', 'pengeluaran.id_user')
                    ->where('pengeluaran.status', '=', 'Selesai')
                    ->where($matchThese)
                    ->groupBy('users.id', 'pengeluaran_detail.id_item')
                    ->get();
            } else if ($request->qty != null) {
                $item = DB::table('pengeluaran')
                    ->select('name', 'pengeluaran.kode_pengeluaran', 'pengeluaran.tgl_pengeluaran', 'item.nama', 'item.panjang', 'item.satuan')
                    ->selectRaw('SUM(pengeluaran_detail.qty_acc) as qty')
                    ->join('pengeluaran_detail', 'pengeluaran_detail.id_pengeluaran', '=', 'pengeluaran.id')
                    ->join('item', 'item.id', '=', 'pengeluaran_detail.id_item')
                    ->join('users', 'users.id', '=', 'pengeluaran.id_user')
                    ->where('pengeluaran.status', '=', 'Selesai')
                    ->where($matchThese)
                    ->groupBy('users.id', 'pengeluaran_detail.id_item')
                    ->having('qty', '=', $request->qty)
                    ->get();
            }
        } else {
            $item = DB::table('pengeluaran')
                ->select('name', 'pengeluaran.kode_pengeluaran', 'pengeluaran.tgl_pengeluaran', 'item.nama', 'item.panjang', 'item.satuan')
                ->selectRaw('SUM(pengeluaran_detail.qty_acc) as qty')
                ->join('pengeluaran_detail', 'pengeluaran_detail.id_pengeluaran', '=', 'pengeluaran.id')
                ->join('item', 'item.id', '=', 'pengeluaran_detail.id_item')
                ->join('users', 'users.id', '=', 'pengeluaran.id_user')
                ->where('pengeluaran.status', '=', 'Selesai')
                ->groupBy('users.id', 'pengeluaran_detail.id_item')
                ->get();
        }
        $data = [
            'menu' => $this->menu,
            'title' => 'list',
            'list' => $item,
            'users' => User::where('roles', 'Sales')->get(),
            'item' => ItemModel::all(),
        ];
        return view('report.sales')->with($data);
    }
}
