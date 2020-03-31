<?php

namespace App\Domain\Services;

use App\Domain\Services\Crud\ListQuery;
use Illuminate\Database\Eloquent\Model;

class BaseService
{
    use ListQuery;

    /**
     * @var Model
     */
    public $model;

    /**
     * @var Model
     */
    protected $primaryKey;

    /**
     * @var string
     */
    protected $guidKey;

    public function getList(array $input = [])
    {
        $query = $this->model::query();

        $query = $this->listQuery($query, $input);

        return $this->resultQuery($query, $input);
    }

    public function getOwnList(array $input = [])
    {
        $query = $this->model::query();

        $query = $this->listQuery($query, $input);

        $query->where('creator_id', $input['creator_id']);

        return $this->resultQuery($query, $input);
    }

    public function create(array $input)
    {
        return $this->model::create($input);
    }

    public function getDetail($id, array $input = [])
    {
        $query = $this->model::query();

        return $query->findOrFail($id);
    }

    public function update($id, array $input)
    {
        $this->model::query()->where($this->primaryKey, $id)->update($input);

        return $this->getDetailByGuid($id, $input);
    }

    public function delete($id, array $input = [])
    {
        $entity = $this->model::query()->where($this->primaryKey, $id)->first();

        if ($entity)
            return $entity->delete();

        return false;
    }

    public function updateByGuid($id, array $input)
    {
        $this->model::query()->where('guid', $id)->update($input);
        return $this->getDetailByGuid($id, $input);
    }

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('guid', $id)->first();

        if ($entity)
            return $entity->delete();

        return false;
    }

    public function getDetailByGuid($id, array $input = [])
    {
        $query = $this->model::query();

        foreach ($input as $column => $value) {
            $query->where($column, $value);
        }

        return $query->findOrFailByGuid($id);
    }
}
