<?php

namespace App\Services;

use App\Repositories\StudentRepository;

class StudentService extends DefaultService
{
    public function __construct(StudentRepository $repository)
    {
        $this->repository = $repository;
    }
}
