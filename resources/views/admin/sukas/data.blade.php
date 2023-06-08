@forelse ($data as $key => $v )
<tr>
        <td>{{ ++$i }}</td>
        <td>{{ $v->user->name }}</td>
        <td>{{ $v->wisata->nama }}</td>
        <td>
                <?php
                if ($v->riwayat->count() > 0) {
                        $star_orange = $v->riwayat->toArray()[0]['star'];
                        $star_grey = 5 - $v->riwayat->toArray()[0]['star'];

                        for ($i = 0; $i < $star_orange; $i++) {
                                echo '<span class="fa fa-star" style="color: orange;"></span>';
                        }
                        for ($i = 0; $i < $star_grey; $i++) {
                                echo '<span class="fa fa-star"></span>';
                        }
                } else {

                        for ($i = 0; $i < 5; $i++) {
                                echo '<span class="fa fa-star"></span>';
                        }
                }
                ?>
</tr>
@empty
<tr>
        <td colspan="3" class="text-center">Data not found</td>
</tr>
@endforelse