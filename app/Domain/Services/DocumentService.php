<?php

namespace App\Domain\Services;

use App\Domain\Models\DocumentType;
use App\Domain\Models\Interview;
use App\Domain\Models\UserDocument;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService extends BaseService
{
    /**
     * @var UserDocument
     */
    public $model = UserDocument::class;

    public $searchColumns = [];

    public $filterColumns = [];

    public $with = [];

    protected $primaryKey = 'id';

    protected $guidKey = 'guid';

    public function create(array $input)
    {
        $file = $input['file'];
        $disk = $input['disk'];
        $path = $input['path'];
        $newname = time() . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = Storage::disk($disk)->putFileAs($path, $file, $newname);
        $this->makeDir($disk, $path);

        $upload = new UserDocument();
        $upload->document_type_id = $input['document_type_id'];
        $upload->creator_id = $input['creator_id'];
        $upload->object_type = $input['object_type'];
        $upload->object_id = $input['object_id'];
        $upload->name = $newname;
        $upload->disk = $disk;
        $upload->path = $path;
        $upload->filename = $file->getClientOriginalName();
        $upload->mimetype = $file->getMimeType();
        $upload->size = $file->getClientSize();
        $upload->header = $file->getClientMimeType();
        $upload->save();

        return $upload;
    }

    public function makeDir($disk, $path)
    {
        $fPath = Storage::disk($disk)->path($path);

        if (!File::exists($fPath)) {
            mkdir($fPath, 0777, true);
        }

        return true;
    }

    public function deleteByGuid($id, array $input = [])
    {
        $entity = $this->model::query()->where('id', $id)->first();

        if ($entity)
            return $entity->delete();

        return false;
    }
}
