<?php
/**
 * Created by PhpStorm.
 * User: Afolayan
 * Date: 25/1/2016
 * Time: 12:41 AM
 */

include_once './functions.php';
include_once './config.php';

$db = new functions();

$result = $db->createJambTable();
