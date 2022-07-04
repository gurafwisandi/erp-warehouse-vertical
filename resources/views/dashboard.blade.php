@extends('layouts.main')
@section('container')
    <div class="page-content-wrapper ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <div class="btn-group float-right">
                            <ol class="breadcrumb hide-phone p-0 m-0">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-3 align-self-center">
                                            <div class="round">
                                                <i class="ti-user"></i>
                                            </div>
                                        </div>
                                        <div class="col-9 text-right align-self-center">
                                            <div class="m-l-10 ">
                                                <h5 class="mt-0">{{ $users }}</h5>
                                                <p class="mb-0 text-muted">Users</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height:3px;">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 48%;"
                                            aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex flex-row">
                                        <div class="col-3 align-self-center">
                                            <div class="round">
                                                <i class="ti-flag-alt"></i>
                                            </div>
                                        </div>
                                        <div class="col-9 align-self-center text-right">
                                            <div class="m-l-10">
                                                <h5 class="mt-0">{{ $gudang }}</h5>
                                                <p class="mb-0 text-muted">Gudang</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height:3px;">
                                        <div class="progress-bar  bg-success" role="progressbar" style="width: 35%;"
                                            aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="search-type-arrow"></div>
                                    <div class="d-flex flex-row">
                                        <div class="col-3 align-self-center">
                                            <div class="round ">
                                                <i class="ti-package"></i>
                                            </div>
                                        </div>
                                        <div class="col-9 align-self-center text-right">
                                            <div class="m-l-10 ">
                                                <h5 class="mt-0">{{ $items }}</h5>
                                                <p class="mb-0 text-muted">Items</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height:3px;">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 61%;"
                                            aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="search-type-arrow"></div>
                                    <div class="d-flex flex-row">
                                        <div class="col-3 align-self-center">
                                            <div class="round ">
                                                <i class="ti-layout-grid3"></i>
                                            </div>
                                        </div>
                                        <div class="col-9 align-self-center text-right">
                                            <div class="m-l-10 ">
                                                <h5 class="mt-0">{{ $rak }}</h5>
                                                <p class="mb-0 text-muted">Rak (Penyimpanan)</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height:3px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 61%;"
                                            aria-valuenow="61" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body new-user">
                            <h5 class="header-title mb-4 mt-0">Status Penerimaan</h5>
                            <div class="d-flex flex-row">
                                <div class="col-3 align-self-center">
                                    <div class="round">
                                        <i class="ti-download"></i>
                                    </div>
                                </div>
                                <div class="col-9 text-right align-self-center">
                                    <div class="m-l-10 ">
                                        <h5 class="mt-0">{{ $penerimaan }}</h5>
                                        <p class="mb-0 text-muted">Penerimaan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height:3px;">
                                <div class="progress-bar bg-secondary" role="progressbar" style="width: 48%;"
                                    aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-body new-user">
                            <h5 class="header-title mb-4 mt-0">Status Pengiriman</h5>
                            <div class="d-flex flex-row">
                                <div class="col-3 align-self-center">
                                    <div class="round">
                                        <i class="ti-upload"></i>
                                    </div>
                                </div>
                                <div class="col-9 text-right align-self-center">
                                    <div class="m-l-10 ">
                                        <h5 class="mt-0">{{ $pengeluaran }}</h5>
                                        <p class="mb-0 text-muted">pengeluaran</p>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height:3px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 48%;"
                                    aria-valuenow="48" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
