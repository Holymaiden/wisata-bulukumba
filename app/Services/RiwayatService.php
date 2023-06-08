<?php

namespace App\Services;

use App\Models\Riwayat;
use App\Services\BaseRepository;
use App\Services\Contracts\RiwayatContract;
use Illuminate\Support\Facades\Hash;


class RiwayatService extends BaseRepository implements RiwayatContract
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Riwayat $riwayat)
    {
        $this->model = $riwayat->whereNotNull('id');
    }

    public function paginated(array $criteria)
    {
        $perPage = $criteria['jml'] ?? 5;
        $search = $criteria['cari'] ?? '';
        return $this->model->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere("email", "like", "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
