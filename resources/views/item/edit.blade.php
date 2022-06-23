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
                            <form action="{{ route('item.update', Crypt::encryptString($item->id)) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Nama</label>
                                    <input type="text" class="form-control" name="nama" required
                                        value="{{ $item->nama }}" placeholder="Nama" />
                                    {!! $errors->first('nama', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Gambar</label>
                                    <input type="hidden" name="images_old" value="{{ $item->gambar }}">
                                    <input type="file" class="form-control" name="file"
                                        {{ $item->gambar == null ? 'required' : '' }} placeholder="Gambar" value="" />
                                    @if ($item->gambar)
                                        <a class="highlighter-rouge waves-effect waves-light" style="color: #44a2d2;"
                                            data-toggle="modal" data-target="#myModal" href="#">Lihat Gambar</a>
                                    @endif
                                    {!! $errors->first('file', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Type</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="type" required>
                                            <option value="">--Pilih Type--</option>
                                            @foreach ($type as $type)
                                                <option value="{{ $type }}"
                                                    {{ $type == $item->type ? 'selected' : '' }}>
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
                                                <option value="{{ $satuan }}"
                                                    {{ $satuan == $item->satuan ? 'selected' : '' }}>
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
                                                <option value="{{ $bentuk }}"
                                                    {{ $bentuk == $item->bentuk_barang ? 'selected' : '' }}>
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
                                        <textarea required name="keterangan" class="form-control" rows="5" placeholder="Keterangan">{{ $item->keterangan }}</textarea>
                                        {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Vendor</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="id_vendor" required>
                                            <option value="">--Pilih Vendor--</option>
                                            @foreach ($vendor as $item_ven)
                                                <option value="{{ $item_ven->id }}"
                                                    {{ $item_ven->id == $item->id_vendor ? 'selected' : '' }}>
                                                    {{ $item_ven->nama }}
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
    <!-- modal -->
    <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="myModalLabel">Gambar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <img src="{{ URL::asset('files/item/' . $item->gambar) }}" width="100%">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
