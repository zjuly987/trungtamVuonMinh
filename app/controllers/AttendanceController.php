<?php

class AttendanceController
extends Controller
{
    public function index()
    {
        $this->view(
            "attendance/index",
            [
                "role"=>
                "teacher"
            ]
        );
    }
}