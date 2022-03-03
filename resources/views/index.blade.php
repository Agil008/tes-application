@extends('layouts.master')

@section('title', 'User')

@section('css_vendor')
    <link href="{{ asset('assets/plugins/bootstrap-table/bootstrap-table.min.css')}}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
<div class="row">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__head kt-portlet__head--lg">
            <div class="kt-portlet__head-label">
                <span class="kt-portlet__head-icon">
                    <i class="kt-font-brand flaticon-home"></i>
                </span>
                <h3 class="kt-portlet__head-title">
                    Data User
                </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-wrapper">
                    <div class="kt-portlet__head-actions">
                        <a href="#!" class="btn btn-brand btn-elevate btn-icon-sm" data-toggle="modal" data-target="#kt_modal_add">
                            <i class="la la-plus"></i>
                            Tambah Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-portlet__body">

            <!--begin: Datatable -->
            <table id="tb_user" 
            class="table table-striped- table-bordered table-hover" 
            data-toggle="table" 
            data-pagination="true" 
            data-search="true"
            data-page-size="10">
                    <thead>
                    <tr>
                        <th data-field="id" data-visible="false" >Id</th>
                        <th data-field="no" data-formatter="operateFormatterNo">No</th>
                        <th data-field="name" data-sortable="true">Nama</th>
                        <th data-field="phone_number" data-sortable="true">Phone Number</th>
                        <th data-field="email" data-sortable="true">Email</th>
                        <th data-field="address" data-sortable="true">Address</th>
                        <th data-field="operate" data-formatter="operateFormatter" data-events="operateEvents">Action</th>
                    </tr>
                    </thead>
                </table>

            <!--end: Datatable -->
        </div>
    </div>

</div>

<!-- MODAL ADD DATA -->
<div class="modal fade" id="kt_modal_add" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="form-simpan" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Name</label>
                        <input class="form-control" type="text" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Phone Number</label>
                        <input class="form-control" type="text" name="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Email</label>
                        <input class="form-control" type="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Address</label>
                        <input class="form-control" type="text" name="address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT DATA -->
<div class="modal fade" id="kt_modal_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="form-edit" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Name</label>
                        <input class="form-control" type="text" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Phone Number</label>
                        <input class="form-control" type="text" name="phone_number" id="phone_number" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Email</label>
                        <input class="form-control" type="email" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="example-text-input" class="col-form-label">*Address</label>
                        <input class="form-control" type="text" name="address" id="address" required>
                    </div>
                </div>
                <div class="modal-footer">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <button type="button" id="btn-batal-edit" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@section('javascript_vendor')
    <script src="{{ asset('assets/plugins/bootstrap-table/bootstrap-table.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('assets/plugins/sweetalert/sweetalert2.all.min.js')}}" type="text/javascript"></script>
@endsection

@section('javascript')
<script>
    $(document).ready(async function() {
        
        Swal.fire({
            title: 'Loading Data',
            text: 'Please Wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        })
        await load_data();
        Swal.close();
    });

    function operateFormatterNo(value, row, index) {
        return index+1;
    }

    function operateFormatter(value, row, index) {
        return [
            '<a class="btn btn-sm btn-icon btn-icon-sm btn-outline-brand edit" href="javascript:void(0)" title="Edit">',
            '<i class="la la-edit"></i>',
            '</a> ',
            '<a class="btn btn-sm btn-icon btn-icon-sm btn-outline-danger delete" href="javascript:void(0)" title="Delete">',
            '<i class="flaticon2-trash"></i>',
            '</a>'
        ].join('')
    }

    async function load_data() {
        return await $.ajax({
            url: '/data',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#tb_user').bootstrapTable('removeAll');
                $('#tb_user').bootstrapTable('load', response.data);
            },
            error: function (response) {
                Swal.close();
            }
        });
    }

    $("#form-simpan").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: '/create',
            type: 'post',
            data: $(this).serialize(),
            beforeSend : function() {
                Swal.fire({
                    title:'Saving Data',
                    text: 'Please Wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
            },
            success: async function(response) {
                if (response.status == 200) {
                    await load_data();
                    Swal.fire({
                        icon: 'success',
                        title: 'SUCCESS',
                        text: 'Data Addes Successfuly',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#kt_modal_add').modal('hide');
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Data Failed To Add'
                    })
                }
            },
            error: function(response){
                Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Data Failed To Add'
                    })
            },
        });
    });

    $("#form-edit").on("submit", function(e) {
        e.preventDefault();
        $.ajax({
            url: '/update',
            type: 'put',
            data: $(this).serialize(),
            beforeSend : function() {
                Swal.fire({
                    title:'Updating Data',
                    text: 'Please Wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
            },
            success: async function(response) {
                if (response.status == 200) {
                    await load_data();
                    Swal.fire({
                        icon: 'success',
                        title: 'SUCCESS',
                        text: 'Data Updated Successfuly',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    $('#kt_modal_edit').modal('hide');
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Data Failed To Update'
                    })
                }
            },
            error: function(data){
                Swal.fire({
                        icon: 'error',
                        title: 'ERROR',
                        text: 'Data Failed To Update'
                    })
            },
        });
    });

    window.operateEvents = {
        'click .edit': function (e, value, row, index) {
            $('#edit_id').val(row.id);
            $('#name').val(row.name);
            $('#phone_number').val(row.phone_number);
            $('#email').val(row.email);
            $('#address').val(row.address);
            $('#kt_modal_edit').modal('show');
        },
        'click .delete': function (e, value, row, index) {
            Swal.fire({
                title: 'Delete data',
                text: "Are You Sure To Delete This Data ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = row.id;
                    $.ajax({
                        url: '/delete',
                        type: 'delete',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id :id
                        },
                        beforeSend : function() {
                            Swal.fire({
                                title:'Deleting Data',
                                text: 'Please Wait...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading()
                                }
                            })
                        },
                        success: async function(response) {
                            if (response.status == 200) {
                                await load_data();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'SUCCESS',
                                    text: 'Data Deleted Successfuly',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }else{
                                Swal.fire({
                                    icon: 'error',
                                    title: 'ERROR',
                                    text: 'Data Failed To Delete'
                                })
                            }
                        },
                        error: function(data){
                            Swal.fire({
                                    icon: 'error',
                                    title: 'ERROR',
                                    text: 'Data Failed To Delete'
                                })
                        },
                    })
                }
                    
            })
        }
    }

</script>
@endsection