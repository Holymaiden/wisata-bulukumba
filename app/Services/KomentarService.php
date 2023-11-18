<?php

namespace App\Services;

use App\Models\Komentar;
use App\Services\BaseRepository;
use App\Services\Contracts\KomentarContract;


class KomentarService extends BaseRepository implements KomentarContract
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Komentar $komentar)
    {
        $this->model = $komentar->whereNotNull('id');
    }

    public function paginated(array $criteria)
    {
        $perPage = $criteria['jml'] ?? 5;
        $search = $criteria['cari'] ?? '';
        return $this->model->when($search, function ($query) use ($search) {
            $query->where('comment', 'like', "%{$search}%");
        })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}
