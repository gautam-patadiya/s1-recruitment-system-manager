<?php

namespace App\Http\Controllers\Api;

use App\Domain\Services\DashboardService;
use App\Http\Controllers\CrudController;
use App\Http\Resources\JobApplicationResource;
use App\Http\Resources\JobResource;
use Illuminate\Http\Request;

class DashboardController extends CrudController
{
    /**
     * @var DashboardService
     */
    public $service;

    public function __construct(DashboardService $service)
    {
        $this->service = $service;
    }

    public function getInfoBoxes(Request $request)
    {
        $inputs = $request->all();

        return response()->json([
            'data' => [
                'users' => $this->service->getUsersCount($inputs),
                'candidates' => $this->service->getCandidatesCount($inputs),
                'companies' => $this->service->getCompaniesCount($inputs),
                'active_jobs' => $this->service->getActiveJobsCount($inputs)
            ]
        ]);
    }

    public function getTodayExpiresJobs(Request $request)
    {
        return JobResource::collection($this->service->getTodayExpiresJobs($request->all()));
    }

    public function getLatestJobApplication(Request $request)
    {
        return JobApplicationResource::collection($this->service->getLatestJobApplication($request->all()));
    }

    public function getCompanyJobRevenue(Request $request)
    {
        return response()->json([
            'data' => $this->service->getCompanyJobRevenue($request->all())
        ]);
    }

    public function getJobApplicationMonthlyRevenue(Request $request)
    {
        return response()->json([
            'data' => $this->service->getJobApplicationMonthlyRevenue($request->all())
        ]);
    }
}
