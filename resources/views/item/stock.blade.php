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
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">Nama Rak</label>
                                        @if (count($list))
                                            <?php
                                            $qty = $list[0]->raks->no_rak . ' [' . $list[0]->raks->keterangan . ']';
                                            $count = count($list);
                                            ?>
                                        @else
                                            <?php
                                            $qty = '-';
                                            $count = '-';
                                            ?>
                                        @endif
                                        <input type="text" class="form-control" value="{{ $qty }}" disabled />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label class="my-2 pb-1">Qty</label>
                                        <input type="text" class="form-control" value="{{ $count }}" disabled />
                                    </div>
                                </div>
                            </div>
                            <br>
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Penerimaan</th>
                                        <th>Tgl Masuk Gudang</th>
                                        <th>Nama</th>
                                        <th>Panajang</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->receive->kode_receive }}</td>
                                            <td>{{ $item->tgl_masuk_gudang }}</td>
                                            <td>{{ $item->items->nama }}</td>
                                            <td>{{ $item->items->panjang . 'm' }}</td>
                                            <td>{{ $item->qty . ' ' . $item->items->satuan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="form-group mb-0">
                                <div>
                                    <a class="btn btn-secondary waves-effect m-l-5"
                                        href="{{ route('rak.index') }}">Kembali</a>
                                </div>
                            </div>
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
