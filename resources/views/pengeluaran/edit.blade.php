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
                            <?php $readonly = 'required'; ?>
                            <form action="{{ route('pengeluaran.update', Crypt::encryptString($header->id)) }}"
                                method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="id" value="{{ $header->id }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 pb-1">Kode Pengeluaran</label>
                                            <input type="text" class="form-control" name="kode_pengeluaran" readonly
                                                value="{{ $header->kode_pengeluaran }}" placeholder="Kode Pengeluaran" />
                                            {!! $errors->first('kode_pengeluaran', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 pb-1">Tgl Pengeluaran</label>
                                            <input type="date" class="form-control" name="tgl_pengeluaran"
                                                value="{{ $header->tgl_pengeluaran }}" {{ $readonly }}
                                                placeholder="Tgl Pengeluaran" />
                                            {!! $errors->first('tgl_pengeluaran', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Keterangan</label>
                                            <div>
                                                <textarea name="keterangan" class="form-control" rows="5" {{ $readonly }} required placeholder="Keterangan">{{ $header->keterangan }}</textarea>
                                                {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Sales</label>
                                            <div>
                                                <select class="form-control mb-3 custom-select" name="id_user" required>
                                                    <option value="">--Pilih Sales--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ $user->id == $header->id_user ? 'selected' : '' }}>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {!! $errors->first('id_user', '<div class="invalid-validasi">:message</div>') !!}
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
                                        action="{{ route('pengeluaran.approve_pengeluaran', Crypt::encryptString($header->id)) }}"
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
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $det)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $det->items->nama . ' - ' . $det->items->panjang . 'm' }}</td>
                                                <td>{{ $det->qty . ' ' . $det->items->satuan }}</td>
                                                <td>
                                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                        <?php $id = Crypt::encryptString($det->id); ?>
                                                        <form class="delete-form"
                                                            action="{{ route('pengeluaran_detail.destroy', $id) }}"
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
                                    href="{{ route('pengeluaran.index') }}">Kembali</a>
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
                <form action="{{ route('pengeluaran_detail.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_pengeluaran" value="{{ $header->id }}">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="my-2 py-1">Item</label>
                            <div>
                                <select class=" form-control mb-3 custom-select item" name="item" required>
                                    <option value="">--Pilih Item--</option>
                                    @foreach ($item as $itm)
                                        <option value="{{ $itm->id }}">
                                            {{ $itm->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="my-2 pb-1">Qty</label>
                            <input type="number" class="form-control" name="qty" placeholder="Qty" required />
                        </div>
                        <div class="form-group mb-0">
                            <label class="my-2 py-1">&nbsp;</label>
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
