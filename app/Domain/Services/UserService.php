<?php

namespace App\Domain\Services;

use App\Domain\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService extends BaseService
{
    /**
     * @var User
     */
    public $model = User::class;

    public $searchColumns = [];

    public $filterColumns = ['email', 'first_name', 'last_name', 'gender', 'type'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function create(array $input)
    {
        DB::beginTransaction();
        $user = User::whereEmail($input['email'])->first();

        if (!$user) {
            $user = User::query()
                ->create(
                    Arr::except($input, ['status'])
                );
            $user->password = bcrypt($input['password']);
            $user->email_verified_at = ($input['status'] ? now() : null);
            $user->save();
        }

        DB::commit();

        return $user;
    }

    public function updateByGuid($guid, array $input)
    {
        DB::beginTransaction();
        $user = User::query()->findOrFailByGuid($guid);
        if (!empty($input['password'])) {
            $input['password'] = bcrypt($input['password']);

            self::revokeToken($user);
        } else {
            unset($input['password']);
        }

        if(isset($input['status'])) {
            $user->email_verified_at = ($input['status'] ? now() : null);
            $user->save();
            if(!$input['status'])
                self::revokeToken($user);
        }

        User::query()->where('guid', $guid)
            ->update(Arr::except($input, ['status']));

        DB::commit();

        return $user;
    }

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();

        if ($entity->id == $input['creator_id']) {
            throw new \Exception('You can not delete your own data.');
        }

        self::revokeToken($entity);

        if ($entity)
            return $entity->delete();

        return false;
    }

    public static function revokeToken($user)
    {
        foreach ($user->tokens as $token) {
            $token->revoke();
        }
    }
}
