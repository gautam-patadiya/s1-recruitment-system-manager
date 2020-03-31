<?php


namespace App\Domain\Services\Crud;

use App\Http\Resources\CrudResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Arr as ArrayHelper;

trait ListQuery
{
    public static $perPage = 10;

    public $with = [];

    public $searchColumns = [];

    public $filterColumns = [];

    public $sortBy = 'created_at';

    public $sort = 'desc';

    public $resource = CrudResource::class;

    /**
     * @var boolean
     */
    public $belongsToOrganization = false;

    protected function listQuery(Builder $query, array $input)
    {
        if ($this->with) {
            $query->with($this->with);
        }
        $this->appendQuery($query, $input);
        $this->filterQuery($query, $input);
        $this->searchQuery($query, $input);
        $this->sortQuery($query, $input);

        return $query;
    }

    protected function filterQuery(Builder $query, $input = [])
    {
        foreach (ArrayHelper::get($input, 'filters', []) as $column => $value) {
            if ($value && in_array($column, $this->filterColumns)) {
                $query->where($column, $value);
            }
        }
    }

    protected function searchQuery(Builder $query, $input = [])
    {
        if ($searchValue = ArrayHelper::get($input, 'search')) {
            $query->where(function (Builder $query) use ($searchValue) {
                foreach ($this->searchColumns as $column) {
                    $query->orWhere($column, 'like', '%' . $searchValue . '%');
                }
            });
        }
    }

    protected function sortQuery(Builder $query, $input = [])
    {
        if ($sortBy = ArrayHelper::get($input, 'sortField')) {
            $query->orderBy($sortBy, ArrayHelper::get($input, 'sortOrder', 'ASC'));
        } else {
            $query->orderBy($this->sortBy, $this->sort);
        }
    }

    protected function resultQuery(Builder $query, $input = [])
    {
        if (ArrayHelper::has($input, 'page')) {
            $pageSize = (ArrayHelper::get($input, 'pageSize')) ? ArrayHelper::get($input, 'pageSize') : self::$perPage;
            return $query->paginate($pageSize);
        }

        return $query->get();
    }

    protected function appendQuery(Builder $query, $input = [])
    {
        return $query;
    }
}
