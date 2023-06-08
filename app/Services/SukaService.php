<?php

namespace App\Services;

use App\Models\Suka;
use App\Services\BaseRepository;
use App\Services\Contracts\SukaContract;
use Illuminate\Support\Facades\Hash;


class SukaService extends BaseRepository implements SukaContract
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Suka $suka)
    {
        $this->model = $suka->whereNotNull('id');
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
