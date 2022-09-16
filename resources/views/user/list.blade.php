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
                            @if (Auth::user()->roles == 'Admin')
                                <p class="text-muted mb-4 font-14">
                                    <a class="btn btn-success" href="{{ route('user.create') }}">Tambah</a>
                                </p>
                            @endif
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Roles</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->roles }}</td>
                                            <td>
                                                @if ($item->status == 'Aktif')
                                                    <span class="badge badge-success">{{ $item->status }}</span>
                                                @else
                                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->roles == 'Admin')
                                                    <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                        <div class="btn-group btn-group-sm" style="float: none;">
                                                            <?php $id = Crypt::encryptString($item->id); ?>
                                                            <form class="delete-form"
                                                                action="{{ route('user.destroy', $id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button"
                                                                    class="tabledit-delete-button btn btn-sm btn-danger delete_confirm"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-trash"></span>
                                                                </button>
                                                                <a href="{{ route('user.edit', $id) }}"
                                                                    class="tabledit-edit-button btn btn-sm btn-info"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-pencil"></span>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
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
