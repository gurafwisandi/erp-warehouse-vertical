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
                            <form action="{{ route('rak.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label class="my-2 py-1">Lokasi Gudang</label>
                                    <div>
                                        <select class="form-control mb-3 custom-select" name="id_gudang" required>
                                            <option value="">--Pilih Item--</option>
                                            @foreach ($gudang as $data_gudang)
                                                <option value="{{ $data_gudang->id }}">
                                                    {{ $data_gudang->gudang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('id_gudang', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">No Rak</label>
                                    <input type="text" class="form-control" name="no_rak" required
                                        value="{{ old('no_rak') }}" placeholder="No Rak" />
                                    {!! $errors->first('no_rak', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Keterangan</label>
                                    <div>
                                        <textarea required name="keterangan" class="form-control" rows="5" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                                        {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="my-2 py-1">Item</label>
                                    <div>
                                        <select class="form-control mb-3 custom-select" name="id_item" required>
                                            <option value="">--Pilih Item--</option>
                                            @foreach ($items as $item_ite)
                                                <option value="{{ $item_ite->id }}">
                                                    {{ $item_ite->nama . ' - ' . $item_ite->panjang . 'm' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('id_item', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <div>
                                        <a class="btn btn-secondary waves-effect m-l-5"
                                            href="{{ route('rak.index') }}">Kembali</a>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
