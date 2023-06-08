@forelse ($data as $key => $v )
<tr>
        <td>{{ ++$i }}</td>
        <td>{{ $v->name }}</td>
        <td>{{ $v->email }}</td>
        <td><span class="badge rounded-pill badge-light-success">{{ $v->role->name }}</span></td>
        <td><span class="badge rounded-pill badge-light-{{ $v->active==1 ? 'primary' : 'danger' }}">{{ $v->active == 1 ? 'Active' : 'Inactive' }}</span></td>
        <td>
                <ul class="action">
                        {!! Helper::btn_action(1,1,$v->id) !!}
                </ul>
        </td>
</tr>
@empty
<tr>
        <td colspan="6" class="text-center">Data not found</td>
</tr>
@endforelse