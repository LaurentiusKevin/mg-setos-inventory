<div style="width: 60%">
    <h3>Add Verificator</h3>
    <hr>
    <table id="t_list_user" class="table table-hover table-bordered" style="width: 100%">
        <thead class="bg-dark">
        <tr>
            <th>Name</th>
            <th>Department</th>
            <th>e-Mail</th>
            <th>Action</th>
        </tr>
        </thead>
    </table>
</div>
<script type="text/javascript">
    $(function () {
        const t_list_user = $('#t_list_user').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: '{{ route('admin.master-data.sr-verificator.api.list-user') }}',
                method: 'post'
            },
            columns: [
                {data: 'name', name: 'users.name', className: 'align-middle'},
                {data: 'department', name: 'departments.name', className: 'align-middle'},
                {data: 'email', name: 'users.email', className: 'align-middle', width: '15%'},
                {data: 'action', searchable: false, width: '5%'},
            ]
        });

        const t_list_user_tbody = $('#t_list_user tbody');
        const t_list_user_data = row => t_list_user.row( row ).data();

        t_list_user_tbody.on('click','button.action-add', function (event) {
            let data = t_list_user_data($(event.target).parents('tr'));
            LoaderV2.button(this, 'spinner-border spinner-border-sm mr-2');

            axios({
                url: '{{ route('admin.master-data.sr-verificator.api.store') }}',
                method: 'post',
                data: {
                    user_id: data.id,
                }
            }).then(response => {
                LoaderV2.button(this, 'spinner spinner-white spinner-left');
                if (response.data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Data Tersimpan',
                        timer: 1200,
                        showConfirmButton: false,
                        willClose(popup) {
                            $('#t_list').dataTable().api().ajax.reload();
                            parent.$.fancybox.close();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: response.data.message
                    });
                }
            }).catch(error => {
                LoaderV2.button(this, 'spinner spinner-white spinner-left');
                Swal.fire({
                    icon: 'error',
                    title: 'Terdapat Kesalahan Pada System',
                    text: error.response.data.message,
                });
            })
        });
    });
</script>
