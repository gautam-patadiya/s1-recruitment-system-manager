<?php

namespace App\Domain\Services;

use App\Domain\Models\DocumentType;
use App\Domain\Models\Interview;

class DocumentTypeService extends BaseService
{
    /**
     * @var Interview
     */
    public $model = DocumentType::class;

    public $searchColumns = ['name'];

    public $filterColumns = ['name'];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';
}
