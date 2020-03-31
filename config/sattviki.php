<?php

return [
    'job_application_status' => [
        1 => 'Pending',
        2 => 'Interview Scheduled',
        // Once you set status interview_scheduled and again setting OnHold that's fine :)
        // But After that if you again creating the Schedule status then it will make a reject and hire null on interview table
        3 => 'On Hold',
        4 => 'Rejected',
        5 => 'Hired',
    ],

    'file_structure' => [
        'documents'
    ]
];
