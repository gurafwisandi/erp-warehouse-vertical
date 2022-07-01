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
                                <div class="col-md-12">
                                    <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Rak</th>
                                                <th>Qty</th>
                                                <th>Tgl Masuk Gudang</th>
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
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/alert.js') }}"></script>
@endsection
