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
                            <form action="{{ route('user.update', Crypt::encryptString($item->id)) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Nama</label>
                                    <input type="text" class="form-control" name="name" required
                                        value="{{ $item->name }}" placeholder="Nama Vendor" />
                                    {!! $errors->first('name', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">E-Mail</label>
                                    <div>
                                        <input type="email" class="form-control" name="email" required
                                            value="{{ $item->email }}" parsley-type="email" placeholder="E-Mail" />
                                        {!! $errors->first('email', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Password</label>
                                    <div>
                                        <input type="hidden" name="password_old" value="{{ $item->password }}">
                                        <input data-parsley-type="password" type="password" name="password"
                                            class="form-control" value="{{ $item->password }}" required
                                            placeholder="Password" />
                                        {!! $errors->first('password', '<div class="invalid-validasi">:message</div>') !!}
                                    </div>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Roles</label>
                                    <select class="select2 form-control mb-3 custom-select" name="roles" required>
                                        <option value="">--Pilih Status--</option>
                                        <option value="Purchasing" {{ $item->roles == 'Purchasing' ? 'selected' : '' }}>
                                            Purchasing
                                        </option>
                                        <option value="Sales" {{ $item->roles == 'Sales' ? 'selected' : '' }}>
                                            Sales</option>
                                        <option value="Gudang" {{ $item->roles == 'Gudang' ? 'selected' : '' }}>
                                            Gudang</option>
                                    </select>
                                    {!! $errors->first('roles', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 py-1">Status</label>
                                    <select class="select2 form-control mb-3 custom-select" name="status" required>
                                        <option value="">--Pilih Status--</option>
                                        <option value="Aktif" {{ $item->status == 'Aktif' ? 'selected' : '' }}>Aktif
                                        </option>
                                        <option value="Tidak Aktif"
                                            {{ $item->status == 'Tidak Aktif' ? 'selected' : '' }}>
                                            Tidak Aktif</option>
                                    </select>
                                    {!! $errors->first('status', '<div class="invalid-validasi">:message</div>') !!}
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
