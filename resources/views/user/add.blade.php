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
                            <form action="{{ route('user.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Nama</label>
                                    <input type="text" class="form-control" name="name" required
                                        value="{{ old('name') }}" placeholder="Nama" />
                                    {!! $errors->first('name', '<div class="invalid-validasi">:message</div>') !!}
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
                                    <label class="my-2 py-1">Password</label>
                                    <div>
                                        <input data-parsley-type="password" type="password" name="password"
                                            class="form-control" value="" required placeholder="Password" />
                                        {!! $errors->first('password', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Roles</label>
                                    <div>
                                        <select class="select2 form-control mb-3 custom-select" name="roles" required>
                                            <option value="">--Pilih Roles--</option>
                                            <option value="Purchasing">Purchasing</option>
                                            <option value="Sales">Sales</option>
                                            <option value="Gudang">Gudang</option>
                                        </select>
                                    </div>
                                    {!! $errors->first('roles', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">&nbsp;</label>
                                </div>
                                <div class="form-group mb-0">
                                    <div>
                                        <a class="btn btn-secondary waves-effect m-l-5"
                                            href="{{ route('user.index') }}">Kembali</a>
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
