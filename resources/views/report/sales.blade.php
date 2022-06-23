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
                            <form>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Sales</label>
                                            <div>
                                                <select class="select2 form-control mb-3 custom-select" name="name">
                                                    <option value="">--Pilih Sales--</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"<?php
                                                        if (isset($_GET['name']) and $_GET['name'] != '' and $user->id == $_GET['name']) {
                                                            echo 'selected';
                                                        }
                                                        ?>>
                                                            {{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Item</label>
                                            <div>
                                                <select class="select2 form-control mb-3 custom-select" name="item">
                                                    <option value="">--Pilih Item--</option>
                                                    @foreach ($item as $item)
                                                        <option value="{{ $item->id }}"<?php
                                                        if (isset($_GET['item']) and $_GET['item'] != '' and $item->id == $_GET['item']) {
                                                            echo 'selected';
                                                        }
                                                        ?>>
                                                            {{ $item->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group mb-0">
                                            <label class="my-2 py-1">Type</label>
                                            <div>
                                                <select class="select2 form-control mb-3 custom-select" name="type">
                                                    <option value="">--Pilih Type--</option>
                                                    @foreach ($type as $type)
                                                        <option value="{{ $type }}"<?php
                                                        if (isset($_GET['type']) and $_GET['type'] != '' and $type == $_GET['type']) {
                                                            echo 'selected';
                                                        }
                                                        ?>>
                                                            {{ $type }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="my-2 pb-1">Tgl Pengeluaran</label>
                                        <div class="form-group mb-0">
                                            <div>
                                                <div class="input-daterange input-group" id="date-range">
                                                    <input type="text" class="form-control" name="start"
                                                        value="<?php if (isset($_GET['start']) and $_GET['start'] != '') {
                                                            echo $_GET['start'];
                                                        } ?>" placeholder="Start Date">
                                                    <input type="text" class="form-control" name="end"
                                                        value="<?php if (isset($_GET['end']) and $_GET['end'] != '') {
                                                            echo $_GET['end'];
                                                        } ?>" placeholder="End Date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="my-2 pb-1">Qty</label>
                                        <div class="form-group mb-0">
                                            <input type="number" class="form-control" name="qty" min='0'
                                                value="<?php if (isset($_GET['qty']) and $_GET['qty'] != '') {
                                                    echo $_GET['qty'];
                                                } ?>" placeholder="qty" />
                                        </div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">&nbsp;</label>
                                        <div class="form-group mb-0">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                                Cari
                                            </button>
                                            <a class="btn btn-secondary waves-effect m-l-5"
                                                href="{{ route('report.rep_sales') }}">Batal</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="text-center">Sales</th>
                                        <th>Nama Item</th>
                                        <th>Type</th>
                                        <th class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ $item->qty }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/alert.js') }}"></script>
    <script>
        $('.delete_confirm').on('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Hapus Data',
                text: 'Ingin menghapus data?',
                icon: 'question',
                showCloseButton: true,
                showCancelButton: true,
                cancelButtonText: "Batal",
                focusConfirm: false,
            }).then((value) => {
                if (value.isConfirmed) {
                    $(this).closest("form").submit()
                }
            });
        });
    </script>
@endsection
