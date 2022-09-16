@extends('layouts.main')
@section('container')
    <div class="page-content-wrapper ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group float-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item active">{{ ucwords($title) }}</li>
                            </ol>
                        </div>
                        <h4 class="page-title">{{ strtoupper($menu) }}</h4>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">Nama Rak</label>
                                        @if (count($list))
                                            <?php
                                            $title = $list[0]->raks->no_rak . ' [' . $list[0]->raks->keterangan . ']';
                                            ?>
                                            <?php
                                            $stock = DB::table('inventory')
                                                ->selectraw("sum(CASE WHEN receive.status = 'Selesai' THEN qty else 0 end) as qty_in")
                                                ->selectraw("sum(CASE WHEN pengeluaran.status = 'Selesai' THEN qty_out else 0 end) as qty_out")
                                                ->join('receive', 'receive.id', '=', 'inventory.id_receive', 'left')
                                                ->join('pengeluaran', 'pengeluaran.id', '=', 'inventory.id_pengeluaran', 'left')
                                                ->where('id_rak', $list[0]->id_rak)
                                                ->get();
                                            $count = $stock[0]->qty_in - $stock[0]->qty_out;
                                            $satuan = $list[0]->items->satuan;
                                            ?>
                                        @else
                                            <?php
                                            $title = '-';
                                            $count = '-';
                                            $satuan = '';
                                            ?>
                                        @endif
                                        <input type="text" class="form-control" value="{{ $title }}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">Qty</label>
                                        <input type="text" class="form-control" value="{{ $count . ' ' . $satuan }}"
                                            disabled />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Transaksi</th>
                                        <th>Tgl Masuk Gudang</th>
                                        <th>Nama</th>
                                        <th>Jenis</th>
                                        <th>Qty</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($item->status_inventory == 'IN')
                                                    {{ $item->receive ? $item->receive->kode_receive : '' }}
                                                @else
                                                    {{ $item->pengeluaran ? $item->pengeluaran->kode_pengeluaran : '' }}
                                                @endif
                                            </td>
                                            <td>{{ $item->tgl_masuk_gudang }}</td>
                                            <td>{{ $item->items->nama . ' ' . $item->items->panjang . 'm' }}</td>
                                            <td>{{ $item->status_inventory }}</td>
                                            <td>
                                                @if ($item->status_inventory == 'IN')
                                                    {{ $item->qty . ' ' . $item->items->satuan }}
                                                @else
                                                    {{ $item->qty_out . ' ' . $item->items->satuan }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status_inventory == 'IN')
                                                    {{ $item->receive->status }}
                                                @else
                                                    {{ $item->pengeluaran->status }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="form-group mb-0">
                                <div>
                                    <a class="btn btn-secondary waves-effect m-l-5"
                                        href="{{ url()->previous() }}">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/alert.js') }}"></script>
    <script>
        $('.delete_confirm').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus Data',
                text: 'Ingin menghapus data?',
                icon: 'question',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: "Batal",
                focusConfirm: false,
            }).then((value) => {
                if (value.isConfirmed) {
                    $(this).closest("form").submit()
                }
            });
        });
    </script>
@endsection
