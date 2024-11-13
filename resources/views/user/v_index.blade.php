@extends('layouts.v_app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $title }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-primary" onclick="tambahForm()">
                                <i class="fas fa-plus"></i> Tambah
                            </button>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <table id="datatables" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 1%">No</th>
                                        <th>Nama User</th>
                                        <th>Email User</th>
                                        <th>Status</th>
                                        <th style="width: 2%"></th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- Main content -->
    </section>
</div>

<!-- Modal -->
<form id="add">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <input type="hidden" id="id" name="id">
    <div class="modal fade lg" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="_method" id="methodput" value="POST">
                    <div class="row">
                        <div class="form-group col-12">
                            <label for="name">Nama User<span class="text-danger"> *</span></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nama User . . .">
                            <div id="invalid-name" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-12">
                            <label for="email">Email<span class="text-danger"> *</span></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Email . . .">
                            <div id="invalid-email" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-12">
                            <label for="password">Password<span class="text-danger"> *</span></label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Password . . .">
                            <div id="invalid-password" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group col-12">
                            <label for="is_active">Aktif ?<span class="text-danger"> *</span></label>
                            <div class="custom-control" id="is_active">
                                <div class="form-check pl-1">
                                    <input class="form-check-input" type="radio" id="is_active1" name="is_active"
                                        value="1">
                                    <label class="form-check-label" for="is_active1">Ya</label>
                                </div>
                                <div class="form-check pl-1">
                                    <input class="form-check-input" type="radio" id="is_active2" name="is_active"
                                        value="0">
                                    <label class="form-check-label" for="is_active2">Tidak</label>
                                </div>
                            </div>
                            <div id="invalid-is_active" class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-success" id="btnSave">Save</button>
                    <button type="submit" class="btn btn-success" id="btnUpdate">Update</button>
                    <button type="button" class="btn btn-success" id="btnloading"><span
                            class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        window.addEventListener("keydown", function(e) {
            // Hanya jalankan kode jika tombol enter ditekan
            if (e.key === "Enter") {
                // Cek apakah elemen input atau textarea yang memiliki fokus saat ini
                var target = e.target;
                if (target.tagName === "INPUT") {
                    // Jangan teruskan aksi default submit form
                    e.preventDefault();

                    // Anda juga dapat menambahkan logika lain di sini jika diperlukan
                    // Misalnya, validasi input sebelum mengizinkan submit
                }
            }
        });

        $('#id_realisasi').val('');
        $('#btnloading').hide();
        $('#btnUpdate').hide();
        $('#btnSave').show();

        $('.select2').select2();
        $(".select2-2").select2({
            dropdownParent: $("#staticBackdrop")
        });

        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ordering: true, // Set true agar bisa di sorting
            order: [
                [1, 'asc']
            ], // Default sortingnya berdasarkan kolom / field ke 0 (paling pertama)
            ajax: "{{ url('/') }}/user/data/json",
            columns: [{
                    "data": null,
                    "class": "align-top",
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'is_active',
                    name: 'is_active'
                },
                {
                    data: 'id',
                    name: 'id'
                }
            ],
            columnDefs: [{
                "targets": 4,
                "render": function(data, type, row, meta) {
                    return `<div class="btn-group">
                                <button type="button" class="btn-success btn-sm mr-1" onclick="editForm('${data}')">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </div>`;
                }
            }]
        });
    });

    // CREATE
    function tambahForm() {
        $('#staticBackdropLabel').text('Tambah User');
        $('#id').val('');
        $('#btnloading').hide();
        $('#btnUpdate').hide();
        $('#btnSave').show();

        $(document).find('.is-invalid').removeClass('is-invalid');
        $(document).find('.invalid-feedback').text('');
        let form = document.querySelector('form');
        form.reset();

        $('#methodput').val('POST');

        $("#staticBackdrop").modal('show');
    }

    // STORE
    $('#btnSave').on('click', function(e) {
        event.preventDefault();

        let formData = new FormData($('form#add')[0]);

        $.ajax({
            url: "{{ url('/') }}/user",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btnloading').show().prop('disabled', true);
                $('#btnSave').hide();
            },
            success: function(data) {
                $('#btnloading').hide();
                $('#btnUpdate').hide();
                $('#btnSave').show();

                $("#staticBackdrop").modal('hide');

                Swal.fire(
                    'Berhasil!',
                    data.message,
                    'success'
                )

                $('#datatables').DataTable().ajax.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#btnloading').hide();
                $('#btnUpdate').hide();
                $('#btnSave').show();

                $(document).find('.is-invalid').removeClass('is-invalid');
                $(document).find('.invalid-feedback').text('');

                let messageeror = jqXHR.responseJSON.errors;

                $.each(messageeror, function(key, value) {
                    $('#' + key).addClass('is-invalid');
                    $('#invalid-' + key).text(value);
                })
            },
        })
        return false;
    })

    // EDIT
    function editForm(id) {
        $(document).find('.is-invalid').removeClass('is-invalid');
        $(document).find('.invalid-feedback').text('');

        $('#staticBackdropLabel').text('Edit User');
        $('#btnloading').hide();
        $('#btnUpdate').show();
        $('#btnSave').hide();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ url('/') }}/user/" + id + "/edit",
            success: function(val) {
                let form = document.querySelector('form');
                form.reset();

                $('#id').val(val.id);
                $("#name").val(val.name);
                $("#email").val(val.email);

                if (val.is_active == 1){
                    $("#is_active1").prop("checked", true);
                    $("#is_active2").prop("checked", false);
                }else if(val.is_active == 0) {
                    $("#is_active1").prop("checked", false);
                    $("#is_active2").prop("checked", true);
                } else {
                    $("#is_active1").prop("checked", false);
                    $("#is_active2").prop("checked", false);
                }

                $("#methodput").val('PUT');

                $("#staticBackdrop").modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire(
                    'Gagal!',
                    'Data tidak di temukan',
                    'error'
                )
            }
        });
    }

    // UPDATE
    $('#btnUpdate').on('click', function(e) {
        event.preventDefault();

        let formData = new FormData($('form#add')[0]);
        let id = $('#id').val();

        $.ajax({
            url: "{{ url('/') }}/user/" + id,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#btnloading').show().prop('disabled', true);
                $('#btnSave').hide();
                $('#btnUpdate').hide();
            },
            success: function(data) {
                $('#btnloading').hide();
                $('#btnSave').hide();
                $('#btnUpdate').show();

                $("#staticBackdrop").modal('hide');

                Swal.fire(
                    'Berhasil!',
                    data.message,
                    'success'
                )

                $('#datatables').DataTable().ajax.reload();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#btnloading').hide();
                $('#btnSave').hide();
                $('#btnUpdate').show();

                $(document).find('.is-invalid').removeClass('is-invalid');
                $(document).find('.invalid-feedback').text('');

                let messageeror = jqXHR.responseJSON.errors;

                $.each(messageeror, function(key, value) {
                    $('#' + key).addClass('is-invalid');
                    $('#invalid-' + key).text(value);
                })
            },
        })
        return false;
    })
</script>
@endsection