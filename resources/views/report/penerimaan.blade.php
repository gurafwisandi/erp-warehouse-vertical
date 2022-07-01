@extends('layouts.main')
@section('container')
    <div class="page-content-wrapper ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group float-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="page-title">{{ strtoupper($menu) }}</h4>
                            <h6 class="page-title">Penerimaan</h6>
                            <br>
                            <form>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label class="my-2 pb-1">Kode Receive</label>
                                        <div class="form-group mb-0">
                                            <input type="text" class="form-control" name="kode_receive"
                                                value="<?php if (isset($_GET['kode_receive']) and $_GET['kode_receive'] != '') {
                                                    echo $_GET['kode_receive'];
                                                } ?>" placeholder="Kode Receive" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="my-2 pb-1">Tgl Receive</label>
                                        <div class="form-group mb-0">
                                            <div>
                                                <div class="input-daterange input-group" id="date-range">
                                                    <input type="text" class="form-control" name="start"
                                                        value="<?php if (isset($_GET['start']) and $_GET['start'] != '') {
                                                            echo $_GET['start'];
                                                        } ?>" placeholder="Start Date">
                                                    <input type="text" class="form-control" name="end"
                                                        value="<?php if (isset($_GET['end']) and $_GET['end'] != '') {
                                                            echo $_GET['end'];
                                                        } ?>" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="my-2 py-1">Status</label>
                                        <div class="form-group mb-0">
                                            <div>
                                                <select class=" form-control mb-3 custom-select" name="status">
                                                    <option value="">--Pilih Status--</option>
                                                    @foreach ($status as $status)
                                                        <option value="{{ $status }}"<?php
                                                        if (isset($_GET['status']) and $_GET['status'] != '' and $status == $_GET['status']) {
                                                            echo 'selected';
                                                        }
                                                        ?>>
                                                            {{ $status }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">&nbsp;</label>
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Cari
                                            </button>
                                            <a class="btn btn-secondary waves-effect m-l-5"
                                                href="{{ route('report.penerimaan') }}">Batal</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Receive</th>
                                        <th>Tgl</th>
                                        <th>Keterangan</th>
                                        <th>User Input</th>
                                        <th>Status</th>
                                        <th>Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_receive }}</td>
                                            <td>{{ $item->tgl_receive }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->users->name }}</td>
                                            <td>
                                                @if ($item->status == 'Selesai')
                                                    <span class="badge badge-success">{{ $item->status }}</span>
                                                @elseif($item->status == 'Proses Request')
                                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                                @elseif($item->status == 'Pembuatan Request')
                                                    <span class="badge badge-secondary">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                    <div class="btn-group btn-group-sm" style="float: none;">
                                                        <?php
                                                        $qty = DB::table('receive_detail')
                                                            ->select('item.nama', 'panjang')
                                                            ->selectRaw('sum(qty_terima) as qty_terima')
                                                            ->join('item', 'item.id', '=', 'receive_detail.id_item')
                                                            ->where('id_receive', $item->id)
                                                            ->wherenull('receive_detail.deleted_at')
                                                            ->groupBY('receive_detail.id_item')
                                                            ->get();
                                                        ?>
                                                        @foreach ($qty as $item)
                                                            {{ $item->nama . ' - ' . $item->panjang . 'm [' . $item->qty_terima . ']' }}
                                                            <br>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/alert.js') }}"></script>
@endsection
