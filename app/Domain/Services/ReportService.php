<?php

namespace App\Domain\Services;

use App\Domain\Models\Job;
use App\Domain\Models\User;

class ReportService extends BaseService
{
    public function getCandidateOverview(array $input)
    {
        $entities = User::where('type', 2)
            ->select([
                'id', 'guid', 'gender', 'first_name', 'last_name', 'email', 'birthday', 'created_at', 'email_verified_at'
            ])
            ->with([
                'jobApplications' => function($query) {
                    $query->select([
                        'candidate_id', 'job_application_number', 'status', 'created_at',
                    ]);
                },
                'interviews' => function($query) {
                    $query->select([
                        'candidate_id', 'status', 'interview_date',
                    ]);
                },
                'documents' => function($query) {
                    $query->select([
                        'creator_id', 'object_type', 'filename'
                    ]);
                },
            ]);

        if (isset($input['filters']['fromRegistered'])) {
            $entities = $entities->whereDate('created_at', '>=', $input['filters']['fromRegistered']);
        }

        if (isset($input['filters']['toRegistered'])) {
            $entities = $entities->whereDate('created_at', '<=', $input['filters']['toRegistered']);
        }

        if (isset($input['filters']['candidates'])) {
            $entities = $entities->whereIn('guid', $input['filters']['candidates']);
        }

        if (isset($input['filters']['sortField'])) {
            $entities = $entities->orderBy($input['filters']['sortField'], (
            ((isset($input['filters']['sortOrder']) ? $input['filters']['sortOrder'] : 'ASC')
            )));
        }

        $entities = $entities->get()
        ->map(function($item) {
            $interviews = [];
            if($item->interviews) {
                $interviews = $item->interviews->map(function ($item) {
                    return [
                        'status' => config('sattviki.job_application_status')[$item['status']],
                        'interview_date' => $item['interview_date'],
                    ];
                });
            }

            $jobApplications = [];
            if($item->jobApplications) {
                $jobApplications = $item->jobApplications->map(function ($item) {
                    return [
                        'job_application_number' => $item['job_application_number'],
                        'status' =>  config('sattviki.job_application_status')[$item['status']],
                        'applied_at' => $item['created_at'],
                    ];
                });
            }

            $documents = [];
            if($item->documents) {
                $documents = $item->documents->map(function ($item) {
                    return [
                        'filename' =>  $item->filename,
                    ];
                });
            }

            return [
                'id' => $item->guid,
                'gender' => $item->gender,
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'email' => $item->email,
                'created_at' => $item->created_at,
                'birthday' => $item->birthday,
                'status' => (!!($item->email_verified_at)),
                'interviews' => $interviews,
                'job_applications' => $jobApplications,
                'documents' => $documents,
            ];
        })
        ->all();

        return $entities;
    }

    public function getJobOverview(array $input)
    {
        $entities = Job::query()
        ->with([
            'company' => function($query) use ($input){
                $query->select([
                    'id', 'guid', 'name',
                ]);
            },
            'jobApplications' => function($query) {
                $query->select([
                    'id', 'job_id', 'job_application_number',
                ]);
            },
            'jobDepartment' => function($query) use ($input) {
                $query->select([
                    'id', 'guid', 'name',
                ]);
            }
        ]);

        if (isset($input['filters']['companies']) && !empty($input['filters']['companies'])) {
            $entities = $entities->whereHas('company', function ($query) use ($input){
                $query->whereIn('guid', $input['filters']['companies']);
            });
        }

        if (isset($input['filters']['jobDepartments']) && !empty($input['filters']['jobDepartments'])) {
            $entities = $entities->whereHas('jobDepartment', function ($query) use ($input){
                $query->whereIn('guid', $input['filters']['jobDepartments']);
            });
        }

        if (isset($input['filters']['fromJob']) && !empty($input['filters']['fromJob'])) {
            $entities = $entities->whereDate('from_date', '>=', $input['filters']['fromJob']);
        }

        if (isset($input['filters']['toJob']) && !empty($input['filters']['toJob'])) {
            $entities = $entities->whereDate('to_date', '<=', $input['filters']['toJob']);
        }

        if (isset($input['filters']['archived_at']) && $input['filters']['archived_at'] != 'false') {
            $entities = $entities->whereNotNull('archived_at');
        }

        if (isset($input['filters']['expired']) && $input['filters']['expired'] != 'false') {
            $entities = $entities->whereDate('to_date', '<', $input['filters']['now']);
        }

        if (isset($input['filters']['sortField'])) {
            $entities = $entities->orderBy($input['filters']['sortField'], (
            (isset($input['filters']['sortOrder']) ? $input['filters']['sortOrder'] : 'ASC')
            ));
        }

        $entities = $entities->get()
        ->map(function($item) {
            $jobApplications = [];
            if($item->jobApplications) {
                $jobApplications = $item->jobApplications->map(function ($item) {
                    return [
                        'job_application_number' => $item['job_application_number'],
                    ];
                });
            }

            return [
                'id' => $item->guid,
                'company' => optional($item->company)->name,
                'job_department' => optional($item->jobDepartment)->name,
                'from_date' => $item->from_date,
                'to_date' => $item->to_date,
                'title' => $item->title,
                'status' => $item->status,
                'archived_at' => $item->archived_at,
                'job_applications' => $jobApplications,
            ];
        })
        ->all();

        return $entities;
    }
}
