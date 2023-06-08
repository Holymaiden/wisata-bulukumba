<div class="modal fade" id="ajaxModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenter" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                        <form id="formInput" name="formInput" action="">
                                @csrf
                                <input type="hidden" name="id" id="formId">
                                <div class="modal-header">
                                        <h3 class="modal-title"><label id="headForm"></label> {{ Helper::head($title) }}</h3>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                        <div class="row g-1">
                                                <div class="col-md-12">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" name="name" id="name" required />
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-6">
                                                        <label>Email</label>
                                                        <input type="text" class="form-control" name="email" id="email" required />
                                                </div>
                                                <div class="col-md-6">
                                                        <label>Password</label>
                                                        <input type="password" class="form-control" name="password" id="" />
                                                </div>
                                        </div>
                                        <div class="row g-2 mt-1">
                                                <div class="col-md-6">
                                                        <label>Role</label>
                                                        <select class="form-control selectric" id="role_id" name="role_id" style="width:100%">
                                                                @foreach(Helper::get_data('roles') as $v)
                                                                <option value="{{$v->id}}">{{$v->name}}</option>
                                                                @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-md-6">
                                                        <label>Active</label>
                                                        <select class="form-control selectric" id="active" name="active" style="width:100%">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                        </select>
                                                </div>
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                                        <button class="btn btn-primary" type="button" id="saveBtn" value="create">Save</button>
                                        <button class="btn btn-primary" type="button" id="updateBtn" value="update">Update</button>
                                </div>
                        </form>
                </div>
        </div>
</div>


@push('jsScriptAjax')
<script type="text/javascript">
        //Tampilkan form input
        function createForm() {
                $('#formInput').trigger("reset");
                $("#headForm").empty();
                $("#headForm").append("Form Input");
                $('#saveBtn').show();
                $('#updateBtn').hide();
                $('#formId').val('');
                $('#ajaxModel').modal('show');
        }

        //Tampilkan form edit
        function editForm(id) {
                let urlx = "{{ $title }}"
                $.ajax({
                        url: urlx + '/' + id + '/edit',
                        type: "GET",
                        dataType: "JSON",
                        success: function(data) {
                                $("#headForm").empty();
                                $("#headForm").append("Form Edit");
                                $('#formInput').trigger("reset");
                                $('#ajaxModel').modal('show');
                                $('#saveBtn').hide();
                                $('#updateBtn').show();
                                $('#formId').val(data.id);
                                $('#name').val(data.name);
                                $('#username').val(data.username);
                                $('#email').val(data.email);
                                $('#role_id').val(data.role_id).trigger('change');
                                $('#active').val(data.active).trigger('change');
                        },
                        error: function() {
                                var setting = {
                                        type: 'danger',
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
                                $.notify('Unable to display data!', setting);
                        }
                });
        }
</script>
@endpush