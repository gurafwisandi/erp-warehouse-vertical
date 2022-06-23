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
                                    <a class="btn btn-success" href="{{ route('item.create') }}">Tambah</a>
                                </p>
                            @endif
                            <table id="datatable-buttons" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th class="text-center">Gambar</th>
                                        <th>Nama</th>
                                        <th>Type</th>
                                        <th class="text-center">Qty</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-center"><img
                                                    src="{{ URL::asset('files/item/' . $item->gambar) }}"
                                                    style="width: 150px;height:150px;" class="rounded-circle"></td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td class="text-center">{{ $item->qty }}
                                                {{ $item->qty ? $item->satuan : '' }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td>
                                                <div class="tabledit-toolbar btn-toolbar" style="text-align: left;">
                                                    <div class="btn-group btn-group-sm" style="float: none;">
                                                        <?php $id = Crypt::encryptString($item->id); ?>
                                                        <form class="delete-form"
                                                            action="{{ route('item.destroy', $id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            @if (Auth::user()->roles == 'Purchasing')
                                                                <button type="button"
                                                                    class="tabledit-delete-button btn btn-sm btn-danger delete_confirm"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-trash"></span>
                                                                </button>
                                                                <a href="{{ route('item.edit', $id) }}"
                                                                    class="tabledit-edit-button btn btn-sm btn-info"
                                                                    style="float: none; margin: 5px;">
                                                                    <span class="ti-pencil"></span>
                                                                </a>
                                                            @endif
                                                            <a href="{{ route('item.show', $id) }}"
                                                                class="tabledit-edit-button btn btn-sm btn-primary"
                                                                style="float: none; margin: 5px;">
                                                                <span class="ti-eye"></span>
                                                            </a>
                                                            <a href="{{ route('item.stock', $id) }}"
                                                                class="tabledit-edit-button btn btn-sm btn-success"
                                                                style="float: none; margin: 5px;">
                                                                <span class="ti-dropbox"></span>
                                                            </a>
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
