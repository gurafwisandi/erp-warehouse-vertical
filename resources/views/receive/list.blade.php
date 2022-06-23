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
                            @if (Auth::user()->roles == 'Purchasing')
                                <p class="text-muted mb-4 font-14">
                                    <a class="btn btn-success" href="{{ route('receive.create') }}">Tambah</a>
                                </p>
                            @endif
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Receive</th>
                                        <th>Tgl</th>
                                        <th>Keterangan</th>
                                        <th>Vendor</th>
                                        <th>User Input</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_receive }}</td>
                                            <td>{{ $item->tgl_receive }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>{{ $item->vendors->nama }}</td>
                                            <td>{{ $item->users->name }}</td>
                                            <td>
                                                @if ($item->status == 'Selesai Penerimaan')
                                                    <span class="badge badge-success">{{ $item->status }}</span>
                                                @elseif($item->status == 'Proses Penerimaan')
                                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                                @elseif($item->status == 'Proses Penempatan')
                                                    <span class="badge badge-info">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                    <div class="btn-group btn-group-sm" style="float: none;">
                                                        <?php $id = Crypt::encryptString($item->id); ?>
                                                        <form class="delete-form"
                                                            action="{{ route('receive.destroy', $id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="{{ route('receive.show', $id) }}"
                                                                class="tabledit-edit-button btn btn-sm btn-primary"
                                                                style="float: none; margin: 5px;">
                                                                <span class="ti-eye"></span>
                                                            </a>
                                                            @if ($item->status == 'Proses Penerimaan')
                                                                @if (Auth::user()->roles == 'Purchasing')
                                                                    <button type="button"
                                                                        class="tabledit-delete-button btn btn-sm btn-danger delete_confirm"
                                                                        style="float: none; margin: 5px;">
                                                                        <span class="ti-trash"></span>
                                                                    </button>
                                                                    <a href="{{ route('receive.edit', $id) }}"
                                                                        class="tabledit-edit-button btn btn-sm btn-info"
                                                                        style="float: none; margin: 5px;">
                                                                        <span class="ti-pencil"></span>
                                                                    </a>
                                                                @endif
                                                            @endif
                                                            @if ($item->status == 'Proses Penempatan' and Auth::user()->roles == 'Gudang')
                                                                <a href="{{ route('receive.penempatan', $id) }}"
                                                                    class="tabledit-edit-button btn btn-sm btn-secondary"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-package"></span>
                                                                </a>
                                                            @endif
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
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
