<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Services\StudentService;

class StudentController extends BaseController
{
    public function __construct(StudentService $service)
    {
        $this->service = $service;
        $this->jsonResource = StudentResource::class;
    }
}