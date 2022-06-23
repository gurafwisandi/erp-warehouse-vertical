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
                                            <input type="date" class="form-control" name="tgl_pengeluaran" disabled
                                                value="{{ $header->tgl_pengeluaran }}" placeholder="Tgl Pengeluaran" />
                                            {!! $errors->first('tgl_pengeluaran', '<div class="invalid-validasi">:message</div>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Keterangan</label>
                                            <div>
                                                <textarea name="keterangan" class="form-control" rows="5" disabled placeholder="Keterangan">{{ $header->keterangan }}</textarea>
                                                {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Item</th>
                                                <th>Item</th>
                                                <th>Type</th>
                                                <th>Qty</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($details as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->inventorys->kode_item }}</td>
                                                    <td>{{ $item->inventorys->items->nama }}</td>
                                                    <td>{{ $item->inventorys->items->type }}</td>
                                                    <td>{{ $item->inventorys->qty }}</td>
                                                    <td>
                                                        @if ($item->status_out == 'IN')
                                                            <span
                                                                class="badge badge-success">{{ $item->status_out }}</span>
                                                        @elseif($item->status_out == 'OUT')
                                                            <span
                                                                class="badge badge-warning">{{ $item->status_out }}</span>
                                                        @endif
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
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/alert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".type").change(function() {
                let type = $(this).val();
                $(".item option").remove();
                $.ajax({
                    type: "POST",
                    url: '{{ route('item.dropdown_pengeluaran') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        type
                    },
                    success: response => {
                        $('.item').append(`<option value="">-- Pilih Item --</option>`)
                        $.each(response, function(i, item) {
                            $('.item').append(
                                `<option value="${item.id}">${item.nama+' - stock = ['+item.qty+']'}</option>`
                            )
                        })
                    },
                    error: (err) => {
                        console.log(err);
                    },
                });
            });
            $(".item").change(function() {
                let item = $(this).val();
                $(".rak option").remove();
                $.ajax({
                    type: "POST",
                    url: '{{ route('item.dropdown_rak') }}',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        item
                    },
                    success: response => {
                        $('.rak').append(`<option value="">-- Pilih Rak --</option>`)
                        $.each(response, function(i, rak) {
                            $('.rak').append(
                                `<option value="${rak.id}">${rak.no_rak+' - ['+rak.keterangan+']'}</option>`
                            )
                        })
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
