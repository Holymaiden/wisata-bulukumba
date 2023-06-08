@extends('admin._layouts.index')

@push('cssScript')
@include('admin._layouts._css-table')
@endpush

@push($title)
active
@endpush

@section('content')
<div class="page-body">
        <div class="container-fluid">
                <div class="page-title">
                        <div class="row">
                                <div class="col-6">
                                        <h3>Profile</h3>
                                </div>
                                <div class="col-6">
                                        <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="index.html"> <i data-feather="home"></i></a></li>
                                                <li class="breadcrumb-item">Users</li>
                                                <li class="breadcrumb-item active"> Edit Profile</li>
                                        </ol>
                                </div>
                        </div>
                </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
                <div class="edit-profile">
                        <div class="row">
                                <div class="col-xl-12">
                                        <form class="card">
                                                <div class="card-header pb-0">
                                                        <h2 class="card-title mb-0">Edit Profile</h2>
                                                        <div class="card-options"><a class="card-options-collapse" href="javascript:void(0)" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="javascript:void(0)" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                                                </div>
                                                <div class="card-body">
                                                        <form id="formInput" name="formInput" enctype="multipart/form-data">
                                                                <input name="_method" type="hidden" value="POST" id="methodId">
                                                                <input type="hidden" name="id" id="formId">
                                                                <div class="row">
                                                                        <div class="col-sm-12">
                                                                                <div class="mb-3">
                                                                                        <label class="form-label">Username</label>
                                                                                        <input class="form-control" type="text" placeholder="Username" id="name" name="name">
                                                                                </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                                <div class="mb-3">
                                                                                        <label class="form-label">Email address</label>
                                                                                        <input class="form-control" type="email" placeholder="Email" id="email" name="email">
                                                                                </div>
                                                                        </div>
                                                                        <div class="col-sm-12">
                                                                                <div class="mb-3">
                                                                                        <label class="form-label">Password</label>
                                                                                        <input class="form-control" type="password" placeholder="********" id="password" name="password">
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                                <div class="text-end">
                                                                        <button class="btn btn-primary" type="submit" id="updateBtn">Update Profile </button>
                                                                </div>
                                                        </form>
                                                </div>
                                        </form>
                                </div>
                        </div>
                </div>
        </div>
        <!-- Container-fluid Ends-->
</div>

@endsection

@push('jsScript')
@include('admin._layouts._js-table')

<script type="text/javascript">
        $(document).ready(function() {
                let title = "../admin/users";
                getData(title);

                var setting = {
                        type: 'primary',
                        allow_dismiss: true,
                        newest_on_top: false,
                        mouse_over: false,
                        showProgressbar: true,
                        spacing: 10,
                        timer: 1000,
                        placement: {
                                from: 'top',
                                align: 'right'
                        },
                        offset: {
                                x: 30,
                                y: 30
                        },
                        delay: 1000,
                        z_index: 10000,
                        animate: {
                                enter: 'animated bounceIn',
                                exit: 'animated bounceOut'
                        }
                }

                var notify = $.notify('Memproses data...', setting);

                function getData(title) {
                        $.ajax({
                                url: title + '/{{ auth()->user()->id }}/edit',
                                type: "GET",
                                dataType: "JSON",
                                success: function(data) {
                                        $('#methodId').val('PUT');
                                        $('#formId').val(data.id);
                                        $("#name").val(data.name);
                                        $("#email").val(data.email);
                                },
                                error: function() {
                                        notify.update('message', 'Create it data!');
                                }
                        });
                }

                // proses update
                $('#updateBtn').click(function(e) {
                        let id = $('#formId').val();
                        e.preventDefault();
                        let formData = new FormData(formInput);
                        $.ajax({
                                headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: title + "/" + id,
                                data: formData,
                                type: "POST",
                                dataType: 'json',
                                processData: false,
                                contentType: false,
                                success: function(data) {
                                        getData(title)
                                        notify.update('message', '<strong>Saving</strong> Data.');
                                },
                                error: function(data) {
                                        getData(title)
                                        notify.update('type', 'primary');
                                        notify.update('message', 'Create it data!');
                                }
                        });
                });

        });
</script>
@endpush