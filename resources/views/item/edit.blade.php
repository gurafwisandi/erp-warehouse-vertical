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
                                        {{ $item->gambar == null ? 'required' : '' }} placeholder="Gambar"
                                        value="" />
                                    @if ($item->gambar)
                                        <a class="highlighter-rouge waves-effect waves-light" style="color: #44a2d2;"
                                            data-toggle="modal" data-target="#myModal" href="#">Lihat Gambar</a>
                                    @endif
                                    {!! $errors->first('file', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Panjang (m)</label>
                                    <input type="number" step="0.01" min="0" max="100" class="form-control"
                                        name="panjang" required value="{{ $item->panjang }}" placeholder="Panjang" />
                                    {!! $errors->first('panjang', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Diameter (mm)</label>
                                    <input type="text" class="form-control" name="diameter" required
                                        value="{{ $item->diameter }}" placeholder="Diameter" />
                                    {!! $errors->first('diameter', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Berat (kg)</label>
                                    <input type="number" step="0.01" min="0" class="form-control" name="berat"
                                        required value="{{ $item->berat }}" placeholder="Berat" />
                                    {!! $errors->first('berat', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Satuan</label>
                                    <div>
                                        <select class="form-control mb-3 custom-select" name="satuan" required>
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
                                    <label class="my-2 py-1">Keterangan</label>
                                    <div>
                                        <textarea required name="keterangan" class="form-control" rows="5" placeholder="Keterangan">{{ $item->keterangan }}</textarea>
                                        {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
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
