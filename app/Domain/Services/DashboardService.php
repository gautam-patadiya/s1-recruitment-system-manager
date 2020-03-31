<?php

namespace App\Domain\Services;

use App\Domain\Models\Company;
use App\Domain\Models\Job;
use App\Domain\Models\JobApplication;
use App\Domain\Models\User;

class DashboardService extends BaseService
{

    public function getUsersCount(array $input)
    {
        $entities = User::query();

        if (isset($input['from_date']) && !empty($input['from_date'])) {
            $entities = $entities->whereDate('created_at', '>', $input['from_date']);
        }

        if (isset($input['to_date']) && !empty($input['to_date'])) {
            $entities = $entities->whereDate('created_at', '<', $input['to_date']);
        }

        return $entities->count();
    }

    public function getCandidatesCount(array $input)
    {
        $entities = User::query()->where('type', 2);

        if (isset($input['from_date']) && !empty($input['from_date'])) {
            $entities = $entities->whereDate('created_at', '>', $input['from_date']);
        }

        if (isset($input['to_date']) && !empty($input['to_date'])) {
            $entities = $entities->whereDate('created_at', '<', $input['to_date']);
        }

        return $entities->count();
    }

    public function getCompaniesCount(array $input)
    {
        $entities = Company::query();

        if (isset($input['from_date']) && !empty($input['from_date'])) {
            $entities = $entities->whereDate('created_at', '>', $input['from_date']);
        }

        if (isset($input['to_date']) && !empty($input['to_date'])) {
            $entities = $entities->whereDate('created_at', '<', $input['to_date']);
        }

        return $entities->count();
    }

    public function getActiveJobsCount(array $input)
    {
        $entities = Job::query()->whereNull('archived_at')->whereStatus(1);

        if (isset($input['l_from_date']) && !empty($input['l_from_date'])) {
            $entities = $entities->whereDate('from_date', '>', $input['l_from_date']);
        }

        if (isset($input['l_to_date']) && !empty($input['l_to_date'])) {
            $entities = $entities->whereDate('to_date', '<', $input['l_to_date']);
        }

        return $entities->count();
    }

    public function getTodayExpiresJobs(array $input)
    {
        $entities = Job::query();

        $entities = $entities->whereDate('to_date', '=', $input['today']);

        return $entities->paginate(8);
    }

    public function getLatestJobApplication(array $input)
    {
        $entities = JobApplication::query()->with(['candidate'])->latest();

        return $entities->paginate(8);
    }

    public function getCompanyJobRevenue(array $input)
    {
        $entities = Company::query()->
                select([
                    'name'
                ])
            ->whereHas('jobs')
            ->withCount('jobs');

        if (isset($input['l_from_date']) && !empty($input['l_from_date'])) {
            $entities = $entities->whereDate('created_at', '>=', $input['l_from_date']);
        }

        if (isset($input['l_to_date']) && !empty($input['l_to_date'])) {
            $entities = $entities->whereDate('created_at', '<=', $input['l_to_date']);
        }

        return $entities->get();
    }

    public function getJobApplicationMonthlyRevenue(array $input)
    {
        $yearStart = now()->startOfYear();
        $months = [
            'jan' => ['start' => $yearStart->clone()->startOfYear(), 'end' => $yearStart->clone()->endOfMonth()],
            'fab' => ['start' => $yearStart->clone()->startOfYear()->addMonth(), 'end' => $yearStart->clone()->addMonth()->endOfMonth()],
            'mar' => ['start' => $yearStart->clone()->startOfYear()->addMonths(2), 'end' => $yearStart->clone()->addMonths(2)->endOfMonth()],
            'apr' => ['start' => $yearStart->clone()->startOfYear()->addMonths(3), 'end' => $yearStart->clone()->addMonths(3)->endOfMonth()],
            'may' => ['start' => $yearStart->clone()->startOfYear()->addMonths(5), 'end' => $yearStart->clone()->addMonths(4)->endOfMonth()],
            'jun' => ['start' => $yearStart->clone()->startOfYear()->addMonths(5), 'end' => $yearStart->clone()->addMonths(5)->endOfMonth()],
            'jul' => ['start' => $yearStart->clone()->startOfYear()->addMonths(6), 'end' => $yearStart->clone()->addMonths(6)->endOfMonth()],
            'aug' => ['start' => $yearStart->clone()->startOfYear()->addMonths(7), 'end' => $yearStart->clone()->addMonths(7)->endOfMonth()],
            'sep' => ['start' => $yearStart->clone()->startOfYear()->addMonths(8), 'end' => $yearStart->clone()->addMonths(8)->endOfMonth()],
            'oct' => ['start' => $yearStart->clone()->startOfYear()->addMonths(9), 'end' => $yearStart->clone()->addMonths(9)->endOfMonth()],
            'nov' => ['start' => $yearStart->clone()->startOfYear()->addMonths(10), 'end' => $yearStart->clone()->addMonths(10)->endOfMonth()],
            'dec' => ['start' => $yearStart->clone()->startOfYear()->addMonths(11), 'end' => $yearStart->clone()->addMonths(11)->endOfMonth()],
        ];

        $datas = [];
        foreach($months as $key => $value) {
            $datas[] = JobApplication::query()
                ->whereDate('created_at', '>=', $value['start'])
                ->whereDate('created_at', '<=', $value['end'])
                ->count();
        }

        return $datas;
    }
}
