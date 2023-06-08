@forelse ($data as $key => $v )
<tr>
        <td>{{ ++$i }}</td>
        <td>{{ $v->nama }}</td>
        <td>{{ $v->alamat }}</td>
        <td>{{ $v->kategori->nama }}</td>
        <td>{{ $v->harga }}</td>
        <td>{{ $v->fasilitas }}</td>
        <td>{{ substr($v->deskripsi,0,100) }}</td>
        <td>
                <img style="max-width: 150px;max-height: 150px;" class="img-fluid table-avtar" src="{{url('uploads/wisata/'.$v->gambar)}}" alt="{{ $v->nama }}">
        </td>
        <td>
                <ul class="action">
                        {!! Helper::btn_action(1,1,$v->id) !!}
                </ul>
        </td>
</tr>
@empty
<tr>
        <td colspan="7" class="text-center">Data not found</td>
</tr>
@endforelse