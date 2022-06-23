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
                            <form action="{{ route('supplier.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Nama Vendor</label>
                                    <input type="text" class="form-control" name="nama" required
                                        value="{{ old('nama') }}" placeholder="Nama Vendor" />
                                    {!! $errors->first('nama', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Pic</label>
                                    <input type="text" class="form-control" name="pic" required placeholder="Pic"
                                        value="{{ old('pic') }}" />
                                    {!! $errors->first('pic', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">E-Mail</label>
                                    <div>
                                        <input type="email" class="form-control" name="email" required
                                            value="{{ old('email') }}" parsley-type="email" placeholder="E-Mail" />
                                        {!! $errors->first('email', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">No Tlp</label>
                                    <div>
                                        <input data-parsley-type="number" type="text" name="no_tlp" class="form-control"
                                            value="{{ old('no_tlp') }}" required placeholder="No Tlp" />
                                        {!! $errors->first('no_tlp', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="my-2 py-1">Alamat</label>
                                    <div>
                                        <textarea required name="alamat" class="form-control" rows="5" placeholder="Alamat">{{ old('alamat') }}</textarea>
                                        {!! $errors->first('alamat', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <div>
                                        <a class="btn btn-secondary waves-effect m-l-5"
                                            href="{{ route('supplier.index') }}">Kembali</a>
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
