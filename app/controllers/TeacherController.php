<?php

class TeacherController
extends Controller
{
    public function index()
    {
        $this->view(
            "teacher/index",
            [
                "role"=>
                "secretary"
            ]
        );
    }
}