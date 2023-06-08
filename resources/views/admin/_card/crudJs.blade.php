<script type="text/javascript">
    $(document).ready(function() {
        let urlx = "{{ $title }}";

        loadpage('', 5);

        var $pagination = $('.twbs-pagination');
        var defaultOpts = {
            totalPages: 1,
            prev: '&#8672;',
            next: '&#8674;',
            first: '&#8676;',
            last: '&#8677;',
        };
        $pagination.twbsPagination(defaultOpts);

        function loaddata(page, cari, jml) {
            $.ajax({
                url: urlx + '/data',
                data: {
                    "page": page,
                    "cari": cari,
                    "jml": jml
                },
                type: "GET",
                datatype: "json",
                success: function(data) {
                    $(".datatabels").html(data.html);
                }
            });
        }

        function loadpage(cari, jml) {
            $.ajax({
                url: urlx + '/data',
                data: {
                    "cari": cari,
                    "jml": jml
                },
                type: "GET",
                datatype: "json",
                success: function(response) {
                    console.log(response);
                    if ($pagination.data("twbs-pagination")) {
                        $pagination.twbsPagination('destroy');
                        // $(".datatabels").html('<tr><td colspan="8">Data not found</td></tr>');
                    }
                    $pagination.twbsPagination($.extend({}, defaultOpts, {
                        startPage: 1,
                        totalPages: response.total_page,
                        visiblePages: 4,
                        prev: '&#8672;',
                        next: '&#8674;',
                        first: '&#8676;',
                        last: '&#8677;',
                        onPageClick: function(event, page) {
                            if (page == 1) {
                                var to = 1;
                            } else {
                                var to = page * jml - (jml - 1);
                            }
                            if (page == response.total_page) {
                                var end = response.total_data;
                            } else {
                                var end = page * jml;
                            }
                            $('#contentx').text('Showing ' + to + ' to ' + end + ' of ' + response.total_data + ' entries');
                            loaddata(page, cari, jml);
                        }

                    }));
                }
            });
        }

        $("#pencarian, #jumlah").on('keyup change', function(event) {
            let cari = $('#pencarian').val();
            let jml = $('#jumlah').val();
            loadpage(cari, jml);
        });

        // Notify
        var content = {
            message: 'Memproses data...'
        };
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
        var notify = $.notify(content, setting);

        // Notify Success
        function notifySuccess() {
            notify = $.notify(content, setting);
            setTimeout(function() {
                notify.update('message', '<strong>Saving</strong> Data.');
                notify.update('type', 'primary');
                notify.update('progress', 50);
            }, 1000);
            setTimeout(function() {
                notify.update('message', '<strong>Checking</strong> for errors.');
                notify.update('type', 'success');
                notify.update('progress', 100);
            }, 2000);
        }

        // Notify Error
        function notifyError() {
            notify = $.notify(content, setting);
            setTimeout(function() {
                notify.update('message', '<strong>Failet Saving</strong> Data.');
                notify.update('type', 'danger');
                notify.update('progress', 100);
            }, 1000);
        }
        // proses simpan
        $('#saveBtn').click(function(e) {
            e.preventDefault();
            $.ajax({
                data: $('#formInput').serialize(),
                url: "{{ route($title.'.store') }}",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    $('#formInput').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    loadpage('', 5);
                    notifySuccess();
                },
                error: function(data) {
                    console.log('Error:', data);
                    $('#formInput').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    notifyError();
                }
            });
        });

        // proses update
        $('#updateBtn').click(function(e) {
            let id = $('#formId').val();
            e.preventDefault();
            $.ajax({
                data: $('#formInput').serialize(),
                url: urlx + '/' + id,
                type: "PUT",
                dataType: 'json',
                success: function(data) {
                    $('#formInput').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    loadpage('', 5);
                    notifySuccess();
                },
                error: function(data) {
                    $('#formInput').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    notifyError();
                }
            });
        });

        $('body').on('click', '.deleteData', function() {
            var id = $(this).data("id");
            swal({
                    title: 'Are you sure?',
                    text: 'You want to delete this data!',
                    icon: 'warning',
                    dangerMode: true,
                    buttons: {
                        confirm: {
                            text: 'Yes, delete it!',
                        },
                        cancel: {
                            visible: true,
                            text: 'No, cancel!',
                        }
                    }
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "DELETE",
                            url: urlx + '/' + id,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(data) {
                                loadpage('', 5);
                                notifySuccess();
                            },
                            error: function(data) {
                                notifyError();
                            }
                        });
                    } else {
                        swal.close();
                    }
                });
        });

    });
</script>