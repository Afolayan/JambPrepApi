<?php
/**
 * Created by PhpStorm.
 * User: Afolayan
 * Date: 25/1/2016
 * Time: 4:25 PM
 */

include_once './config.php';
include_once './functions.php';

$db = new functions();

    $func = new config();
    $connect = $func->getDBConnection();
    $response = array();
    $tmp = array();

    if ($connect)
        echo "Established connection" . "<br/>";

    if ( isset($_GET['year'])) {

        $year = $connect->real_escape_string($_GET["year"]);
        $query = "SELECT * FROM jamb_only_table WHERE exam_year= ".$year;

        $result = $connect->query($query);

        if (!$result) die ("Invalid query " . $connect->error);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_array()) {

                $tmp["id"] = $connect->real_escape_string($row["id"]);
                $tmp["subject_id"] = $connect->real_escape_string($row["subject_id"]);
                $tmp["exam_year"] = $connect->real_escape_string($row["exam_year"]);
                $tmp["questions"] = $connect->real_escape_string($row["questions"]);
                $tmp["option_a"] = $connect->real_escape_string($row["option_a"]);
                $tmp["option_b"] = $connect->real_escape_string($row["option_b"]);
                $tmp["option_c"] = $connect->real_escape_string($row["option_d"]);
                $tmp["option_d"] = $connect->real_escape_string($row["option_d"]);
                $tmp["option_e"] = $connect->real_escape_string($row["option_e"]);
                $tmp["answer"] = $connect->real_escape_string($row["answer"]);
                $tmp["explanation"] = $connect->real_escape_string($row["explanation"]);
                $tmp["photo"] = $connect->real_escape_string($row["photo"]);
                $tmp["answer_photo"] = $connect->real_escape_string($row["answer_photo"]);

                //echo "<p>".$tmp["subject_id"]. " ". $tmp["exam_year"]."</p>";
                array_push($response, $tmp);
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }