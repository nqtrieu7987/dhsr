<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/user/approvedByType',
        '/change/status',
        '/change/job_status',
        '/change/statusUser',
        '/job-create-multi',
        '/user/updateComment',
        '/job/in-out-aj'
    ];
}
