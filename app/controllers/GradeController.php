<?php

class GradeController
extends Controller
{
    public function index()
    {
        $this->view(
            "grade/index",
            [
                "role"=>
                "teacher"
            ]
        );
    }
}