<?php

namespace App\Services;

use App\Models\Kategori;
use App\Models\Wisata;
use App\Services\BaseRepository;
use App\Services\Contracts\WisataContract;
use App\Traits\Uploadable;
use Illuminate\Database\Eloquent\Model;

class WisataService extends BaseRepository implements WisataContract
{
    use Uploadable;
    /**
     * @var
     */
    protected $model;
    protected $image_path = 'uploads/wisata';

    public function __construct(Wisata $wisata)
    {
        $this->model = $wisata->whereNotNull('id');
    }

    public function find($id): Model
    {
        return $this->model->with('kategori')->findOrFail($id);
    }

    public function paginated(array $criteria)
    {
        $perPage = $criteria['jml'] ?? 5;
        $search = $criteria['cari'] ?? '';
        return $this->model->when($search, function ($query) use ($search) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere("alamat", "like", "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Store Data
     */
    public function store(array $request)
    {
        $request['created_by'] = auth()->user()->id;
        if ($request['image'] == null || $request['image'] == '') {
            unset($request['image']);
        }
        $data = $this->model->create($request);
        Kategori::create([
            'wisata_id' => $data->id,
            'nama' => $request['kategori']
        ]);
        return $data;
    }

    /**
     * Update Data By ID
     */
    public function update(array $request, $id)
    {
        $request['updated_by'] = auth()->user()->id;
        if ($request['image'] == null || $request['image'] == '') {
            unset($request['image']);
        }
        $this->model->find($id)->update($request);
        $kategori = Kategori::where('wisata_id', $id)->first();
        $kategori->update([
            'nama' => $request['kategori']
        ]);
        return $this->find($id);
    }

    public function imageUpload($request)
    {
        if ($request->hasFile('image')) {
            $image = $request->file('image')->getClientOriginalName();
            $image_name = pathinfo($image, PATHINFO_FILENAME);
            $image_name = $this->uploadFile($request->file('image'), $this->image_path, '');
            return $image_name;
        }
        return "";
    }

    public function updateImage($request, $id)
    {
        $dataOld = $request->all();
        if ($request->hasFile('image')) {
            $this->deleteFile($dataOld['image_old'], $this->image_path);
            $image = $request->file('image')->getClientOriginalName();
            $image_name = pathinfo($image, PATHINFO_FILENAME);
            $image_name = $this->uploadFile($request->file('image'), $this->image_path, '');
            return $image_name;
        }
        return $dataOld['image_old'];
    }
}
