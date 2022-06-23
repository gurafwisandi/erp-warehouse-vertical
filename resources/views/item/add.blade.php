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
                            <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Nama</label>
                                    <input type="text" class="form-control" name="nama" required
                                        value="{{ old('nama') }}" placeholder="Nama" />
                                    {!! $errors->first('nama', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Gambar</label>
                                    <input type="file" class="form-control" name="file" required placeholder="Gambar"
                                        value="" />
                                    {!! $errors->first('file', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Type</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="type" required>
                                            <option value="">--Pilih Type--</option>
                                            @foreach ($type as $type)
                                                <option value="{{ $type }}">
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('type', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Satuan</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="satuan" required>
                                            <option value="">--Pilih Satuan--</option>
                                            @foreach ($satuan as $satuan)
                                                <option value="{{ $satuan }}">
                                                    {{ $satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('satuan', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Bentuk Barang</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="bentuk_barang"
                                            required>
                                            <option value="">--Pilih Bentuk Barang--</option>
                                            @foreach ($bentuk as $bentuk)
                                                <option value="{{ $bentuk }}">
                                                    {{ $bentuk }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('bentuk_barang', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Keterangan</label>
                                    <div>
                                        <textarea required name="keterangan" class="form-control" rows="5" placeholder="Keterangan">{{ old('keterangan') }}</textarea>
                                        {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Vendor</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="id_vendor" required>
                                            <option value="">--Pilih Vendor--</option>
                                            @foreach ($vendor as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {!! $errors->first('id_vendor', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">&nbsp;</label>
                                </div>
                                <div class="form-group mb-0">
                                    <div>
                                        <a class="btn btn-secondary waves-effect m-l-5"
                                            href="{{ route('item.index') }}">Kembali</a>
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
