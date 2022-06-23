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
                            @if (Auth::user()->roles == 'Sales')
                                <?php $readonly = ''; ?>
                            @else
                                <?php $readonly = 'readonly'; ?>
                            @endif
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
                                            {{ $readonly }} value="{{ $header->tgl_pengeluaran }}"
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
                            </div><br>
                            <div class="row">
                                <div class="col-sm-6">
                                </div>
                                <div class="col-sm-6">
                                    <div class="col-sm-3 ml-auto">
                                        {{-- @if ($details->where('status_out', 'IN')->count('id') > 0) --}}
                                        <form class="delete-form"
                                            action="{{ route('pengeluaran.approve_pengembalian', Crypt::encryptString($header->id)) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            @if (count($details) > 0)
                                                <input type="hidden" name="id_pengeluaran" value="{{ $header->id }}">
                                                <button type="button"
                                                    class="tabledit-delete-button btn btn-sm btn-success approve_confirm"
                                                    style="float: none; margin: 5px;">
                                                    <span class="ti-check"></span> Approve
                                                </button>
                                            @endif
                                        </form>
                                        {{-- @endif --}}
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
                                                <th>Kode Item</th>
                                                <th>Item</th>
                                                <th>Type</th>
                                                <th>Qty</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($details as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->inventorys->kode_item }}</td>
                                                    <td>{{ $item->items->nama }}</td>
                                                    <td>{{ $item->items->type }}</td>
                                                    <td>{{ $item->qty }}</td>
                                                    <td>
                                                        @if ($item->status_out == 'IN')
                                                            <span
                                                                class="badge badge-success">{{ $item->status_out }}</span>
                                                        @elseif($item->status_out == 'OUT')
                                                            <span
                                                                class="badge badge-warning">{{ $item->status_out }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->items->type == 'Alat' and $item->status_out == 'OUT')
                                                            <div class="tabledit-toolbar btn-toolbar"
                                                                style="text-align: left;">
                                                                <?php $id = Crypt::encryptString($item->id); ?>
                                                                <form class="delete-form"
                                                                    action="{{ route('pengeluaran_detail.update', $id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <button type="button"
                                                                        class="tabledit-delete-button btn btn-sm btn-info approve_tools">
                                                                        <span class="typcn typcn-flow-switch"></span>
                                                                    </button>
                                                                </form>
                                                            </div>
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
        $('.approve_tools').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Alat dikembalikan',
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
