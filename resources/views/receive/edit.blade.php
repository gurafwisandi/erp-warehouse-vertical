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
                            <form action="{{ route('receive.update', Crypt::encryptString($item->id)) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $item->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 pb-1">Kode Penerimaan</label>
                                            <input type="text" class="form-control" name="kode_receive" readonly
                                                value="{{ $item->kode_receive }}" placeholder="Kode Receive" />
                                            {!! $errors->first('kode_receive', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 pb-1">Tgl Penerimaan</label>
                                            <input type="date" class="form-control" name="tgl_receive"
                                                value="{{ $item->tgl_receive }}" placeholder="Kode Receive" />
                                            {!! $errors->first('tgl_receive', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Keterangan</label>
                                            <div>
                                                <textarea name="keterangan" class="form-control" rows="5" required placeholder="Keterangan">{{ $item->keterangan }}</textarea>
                                                {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div>
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Simpan
                                            </button>
                                            <button type="button" class="btn btn-info waves-effect waves-light"
                                                data-toggle="modal" data-target="#myModal">Tambah</button>
                                        </div>
                                    </div>
                            </form>
                            <div class="col-sm-6">
                                <div class="col-sm-3 ml-auto">
                                    <form class="delete-form"
                                        action="{{ route('receive.approve_purchasing', Crypt::encryptString($item->id)) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        @if (count($details) > 0)
                                            <button type="button"
                                                class="tabledit-delete-button btn btn-sm btn-success approve_confirm"
                                                style="float: none; margin: 5px;">
                                                <span class="ti-check"></span> Approve
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Tgl Produksi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $dat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $dat->items->nama . ' - ' . $dat->items->panjang . 'm' }}</td>
                                                <td>{{ $dat->qty . ' ' . $dat->items->satuan }}</td>
                                                <td>{{ $dat->tgl_produksi }}</td>
                                                <td>
                                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                        <?php $id = Crypt::encryptString($dat->id); ?>
                                                        <form class="delete-form"
                                                            action="{{ route('receive_detail.destroy', $id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="tabledit-delete-button btn btn-sm btn-danger delete_confirm">
                                                                <span class="ti-trash"></span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <div>
                                <a class="btn btn-secondary waves-effect m-l-5"
                                    href="{{ route('receive.index') }}">Kembali</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Tambah Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{ route('receive_detail.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_receive" value="{{ $item->id }}">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="my-2 py-1">Item</label>
                            <div>
                                <select class=" form-control mb-3 custom-select type" name="id_item" required>
                                    <option value="">--Pilih Type--</option>
                                    @foreach ($items as $itm)
                                        <option value="{{ $itm->id }}">
                                            {{ $itm->nama . ' - ' . $itm->panjang . 'm' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="my-2 pb-1">Qty</label>
                            <input type="number" class="form-control" name="qty" placeholder="Qty" required />
                        </div>
                        <div class="form-group">
                            <label class="my-2 pb-1">Tanggal Produksi</label>
                            <input type="date" class="form-control" name="tgl_produksi">
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Simpan
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                    </div>
                </form>
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
        $('.approve_confirm').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Approve Data',
                text: 'Ingin approve data?',
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
