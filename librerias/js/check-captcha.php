<?php

    session_start();

    if (isset($_POST['captcha'])) {
        if(strtolower($_POST['captcha']) != strtolower($_SESSION["captcha"])){
            echo json_encode(array('status' => 2));
            exit();
        }
        else{
            echo json_encode(array('status' => 1));
            exit();
        }
    }