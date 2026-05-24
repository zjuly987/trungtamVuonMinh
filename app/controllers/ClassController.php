<?php

class ClassController
extends Controller
{
    public function index()
    {
        $this->view(
            "class/index",
            [
                "role"=>
                "secretary"
            ]
        );
    }
}