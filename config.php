<?php

/**
 * Created by PhpStorm.
 * User: Afolayan
 * Date: 24/1/2016
 * Time: 11:08 PM
 */
class config{


 var $GOOGLE_API_KEY = "";

    function __construct() {

    }

    function __destruct() {
    }


    public function getDBConnection(){
        $DB_HOST =  "localhost";
        $DB_USER = "root";
        $DB_PASSWORD = "";
        $DB_DATABASE = "jamb_prep";

        //require_once 'config.php';
        // connecting to mysql
        $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD);
        // selecting database
        mysqli_select_db($con, $DB_DATABASE);

        // return database handler
        return $con;
    }

    public function close($con) {
        mysqli_close($con);
    }
}