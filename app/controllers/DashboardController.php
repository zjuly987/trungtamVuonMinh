<?php

class DashboardController extends Controller
{
    public function index()
    {
        $this->secretary();
    }

    public function secretary()
    {
        $this->view(
            "dashboard/secretary",
            [
                "role"=>"secretary"
            ]
        );
    }

    public function teacher()
    {
        $this->view(
            "dashboard/teacher",
            [
                "role"=>"teacher"
            ]
        );
    }
}