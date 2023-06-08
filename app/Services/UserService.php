<?php

namespace App\Services;

use App\Models\User;
use App\Services\BaseRepository;
use App\Services\Contracts\UserContract;
use Illuminate\Support\Facades\Hash;


class UserService extends BaseRepository implements UserContract
{
    /**
     * @var
     */
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user->whereNotNull('id');
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

    /**
     * Store Data
     */
    public function store(array $request)
    {
        $request['created_by'] = auth()->user()->id;
        $request['password'] = Hash::make($request['password']);
        return $this->model->create($request);
    }

    /**
     * Update Data By ID
     */
    public function update(array $request, $id)
    {
        $data = $this->model->find($id);
        if ($request['password'] == '') {
            $request['password'] = $data->password;
        } else {
            $request['password'] = Hash::make($request['password']);
        }
        return $this->model->find($id)->update($request);
    }
}
