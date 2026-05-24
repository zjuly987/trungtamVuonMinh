<?php

class StudentController
extends Controller
{
    public function index()
    {
        $this->view(
            "student/index",
            [
                "role"=>
                "secretary"
            ]
        );
    }
}