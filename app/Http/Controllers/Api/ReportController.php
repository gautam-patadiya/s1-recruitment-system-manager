<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\ReportService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\CrudResource;
use Illuminate\Http\Request;

class ReportController extends CrudController
{
    /**
     * @var ReportService
     */
    public $service;

    public $resource = CrudResource::class;

    public function __construct(ReportService $reportService)
    {
        $this->service = $reportService;
    }

    public function getCandidateOverview(Request $request)
    {
        return response()->json([
            'data' => $this->service->getCandidateOverview($request->all())
        ]);
    }

    public function getJobOverview(Request $request)
    {
        return response()->json([
            'data' => $this->service->getJobOverview($request->all())
        ]);
    }
}
