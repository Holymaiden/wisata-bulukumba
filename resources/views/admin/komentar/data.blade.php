@forelse ($data as $key => $v)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $v->wisata->nama }}</td>
        <td>{{ $v->user->name }}</td>
        <td>{{ $v->comment }}</td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center">Data not found</td>
    </tr>
@endforelse
