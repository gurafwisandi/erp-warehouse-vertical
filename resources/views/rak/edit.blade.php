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
                            <form action="{{ route('rak.update', Crypt::encryptString($item->id)) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">No Rak</label>
                                    <input type="text" class="form-control" name="no_rak" required
                                        value="{{ $item->no_rak }}" placeholder="No Rak" />
                                    {!! $errors->first('no_rak', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group">
                                    <label class="my-2 py-1">Keterangan</label>
                                    <div>
                                        <textarea required name="keterangan" class="form-control" rows="5" placeholder="Keterangan">{{ $item->keterangan }}</textarea>
                                        {!! $errors->first('keterangan', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
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
