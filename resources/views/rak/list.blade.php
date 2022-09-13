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
                            @if (Auth::user()->roles == 'Gudang')
                                <p class="text-muted mb-4 font-14">
                                    <a class="btn btn-success" href="{{ route('rak.create') }}">Tambah</a>
                                </p>
                            @endif
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Lokasi Gudang</th>
                                        <th>No Rak</th>
                                        <th>Keterangan</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->gudang ? $item->gudang->gudang : '' }}</td>
                                            <td>{{ $item->no_rak }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->items->nama . ' - ' . $item->items->panjang . 'm' }}</td>
                                            <td>
                                                <?php
                                                $stock = DB::table('inventory')
                                                    ->selectraw('sum(qty) as qty_in')
                                                    ->selectraw('sum(qty_out) as qty_out')
                                                    ->where('id_rak', $item->id)
                                                    ->get();
                                                echo $count = $stock[0]->qty_in - $stock[0]->qty_out;
                                                ?>
                                            </td>
                                            <td>
                                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                    <div class="btn-group btn-group-sm" style="float: none;">
                                                        <?php $id = Crypt::encryptString($item->id); ?>
                                                        <form class="delete-form" action="{{ route('rak.destroy', $id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            @if (Auth::user()->roles == 'Gudang')
                                                                <button type="button"
                                                                    class="tabledit-delete-button btn btn-sm btn-danger delete_confirm"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-trash"></span>
                                                                </button>
                                                                <a href="{{ route('rak.edit', $id) }}"
                                                                    class="tabledit-edit-button btn btn-sm btn-info"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-pencil"></span>
                                                                </a>
                                                            @endif
                                                            <a href="{{ route('rak.show', $id) }}"
                                                                class="tabledit-edit-button btn btn-sm btn-primary"
                                                                style="float: none; margin: 5px;">
                                                                <span class="ti-eye"></span>
                                                            </a>
                                                            <a href="{{ route('rak.stock_rak', $id) }}"
                                                                class="tabledit-edit-button btn btn-sm btn-success"
                                                                style="float: none; margin: 5px;">
                                                                <span class="ti-dropbox"></span>
                                                            </a>
                                                        </form>
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
