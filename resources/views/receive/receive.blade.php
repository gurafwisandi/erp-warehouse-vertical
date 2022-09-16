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
                                            <label class="my-2 pb-1">Kode Receive</label>
                                            <input type="text" class="form-control" name="kode_receive" disabled
                                                value="{{ $item->kode_receive }}" placeholder="Kode Receive" />
                                            {!! $errors->first('kode_receive', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 pb-1">Tgl Receive</label>
                                            <input type="date" class="form-control" name="tgl_receive" disabled
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
                                                <textarea name="keterangan" class="form-control" rows="5" disabled placeholder="Keterangan">{{ $item->keterangan }}</textarea>
                                                {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Tgl Produksi</th>
                                                <th>Qty yang diterima</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($details as $detail)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $detail->items->nama . ' - ' . $detail->items->panjang . 'm' }}
                                                    </td>
                                                    <td>{{ $detail->qty . ' ' . $detail->items->satuan }}</td>
                                                    <td>{{ $detail->tgl_produksi }}</td>
                                                    <td>{{ $detail->qty_terima }}
                                                        @if ($detail->qty_terima)
                                                            {{ $detail->items->satuan }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group mb-0">
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div>
                                        <button type="button" class="btn btn-info waves-effect waves-light"
                                            data-toggle="modal" data-target="#myModal">Penempatan</button>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 ml-auto">
                                        @if (count($invens) > 0)
                                            <form class="delete-form"
                                                action="{{ route('receive.approve_penempatan', Crypt::encryptString($item->id)) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="tabledit-delete-button btn btn-sm btn-success approve_confirm"
                                                    style="float: none; margin: 5px;">
                                                    <span class="ti-check"></span> Approve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Rak</th>
                                                <th>Qty</th>
                                                <th>Tgl Masuk Gudang</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invens as $inven)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $inven->items->nama . ' - ' . $inven->items->panjang . 'm' }}
                                                    </td>
                                                    <td>{{ $inven->raks->gudang->gudang . ' - ' . $inven->raks->no_rak }}
                                                    </td>
                                                    <td>{{ $inven->qty . ' ' . $inven->items->satuan }}</td>
                                                    <td>{{ $inven->tgl_masuk_gudang }}</td>
                                                    <td>
                                                        <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                            <?php $id = Crypt::encryptString($inven->id); ?>
                                                            <form class="delete-form"
                                                                action="{{ route('inventory.destroy', $id) }}"
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
                    <h5 class="modal-title mt-0" id="myModalLabel">Penempatan Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_receive" id="idReceive" value="{{ $item->id }}">
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="my-2 py-1">Item</label>
                            <div>
                                <select class=" form-control mb-3 custom-select item" name="item" id="item"
                                    required>
                                    <option value="">--Pilih Item--</option>
                                    @foreach ($details as $itm)
                                        <option value="{{ $itm->id_item }}">
                                            {{ $itm->items->nama . ' - ' . $itm->items->panjang . 'm' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="my-2 py-1">Rak</label>
                            <div>
                                <select class=" form-control mb-3 custom-select rak" name="rak" required>
                                    <option value="">--Pilih Rak--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="my-2 pb-1">Tgl Masuk Gudang</label>
                            <input type="date" class="form-control" name="tgl_masuk_gudang"
                                placeholder="Tgl Masuk Gudang" required />
                        </div>
                        <div class="form-group">
                            <label class="my-2 pb-1">Qty</label>
                            <input type="number" class="form-control" name="qty" placeholder="Qty" required />
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
        $(document).ready(function() {
            $(".item").change(function() {
                let item = $(this).val();
                $(".rak option").remove();
                $.ajax({
                    type: "POST",
                    url: '{{ route('item.dropdown_receive') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        item,
                    },
                    success: response => {
                        $('.rak').append(`<option value="">-- Pilih Rak --</option>`)
                        if (response) {
                            $.each(response, function(i, item) {
                                if (item.no_rak != null) {
                                    $('.rak').append(
                                        `<option value="${item.id}">${item.gudang+' - '+item.no_rak+' = stock ['+item.qty_stok+']'}</option>`
                                    )
                                }
                            })

                        }
                    },
                    error: (err) => {
                        console.log(err);
                    },
                });
            });
        });
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
