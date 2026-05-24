<?php

class Controller
{
    public function view(
        $view,
        $data=[]
    ){

        extract($data);

        $content=
        ROOT .
        "/app/views/" .
        $view .
        ".php";

        $currentController=
        strtolower(
            str_replace(
                "Controller",
                "",
                get_class($this)
            )
        );

        require_once
        ROOT .
        "/app/views/layouts/main.php";
    }
}