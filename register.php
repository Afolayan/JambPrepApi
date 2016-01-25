<?php
/**
 * Created by PhpStorm.
 * User: Afolayan
 * Date: 25/1/2016
 * Time: 2:09 PM
 */

    include_once './config.php';
    include_once './functions.php';

    // response json
    $json = array();
    $func = new functions();

    /**
     * Registering a user device
     * Store reg id in users table
     */

    if (isset($_GET["name"]) && isset($_GET["email"]) && isset($_GET["regId"])) {
        $name = $_GET["name"];
        $email = $_GET["email"];
        $regId = $_GET["regId"]; // GCM Registration ID
        // Store user details in db

        $time = date('Y-m-d H:i:s');


        $func->registerUser( $name, $email, $regId);

        $json["status"] = "success";


    } else {
    // user details missing
        $json["status"] = "failure";
    }
 echo json_encode($json, 1, 256);