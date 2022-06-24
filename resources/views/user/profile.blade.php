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
                            <form action="{{ route('user.update_profile', Crypt::encryptString($item->id)) }}"
                                enctype="multipart/form-data" method="POST">
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
                                    <label class="my-2 pb-1">Roles</label>
                                    <input type="text" class="form-control" name="roles" readonly
                                        value="{{ $item->roles }}" placeholder="Roles" />
                                    {!! $errors->first('roles', '<div class="invalid-validasi">:message</div>') !!}
                                </div>
                                <div class="form-group mb-0">
                                    <label class="my-2 pb-1">Foto</label>
                                    <input type="file" class="form-control" name="foto" placeholder="Foto" />
                                    <input type="hidden" name="file_old" value="{{ $item->foto }}">
                                    <?php if($item->foto){ ?>
                                    <img src="{{ asset('files/foto/' . $item->foto) }}" alt="" width="300"
                                        class="rounded avatar-md">
                                    <?php } ?>
                                    {!! $errors->first('foto', '<div class="invalid-validasi">:message</div>') !!}
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
