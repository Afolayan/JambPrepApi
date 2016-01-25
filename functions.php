<?php

/**
 * Created by PhpStorm.
 * User: Afolayan
 * Date: 24/1/2016
 * Time: 11:27 PM
 */
class functions{

    var $func;
    var $connect;
    function __construct() {
        include_once './config.php';
        $this->func = new config();
        $this->func->getDBConnection();
    }

    function __destruct() {

    }

    public function createJambTable(){
        /*
         * 1. Get rows where exam_body is jamb
         * 2. Create if not exists table for jamb-only questions
         * 3. Insert the result in 1 above
         * columns needed: id, subject_id, exam_year, questions,
         * option_a, option_b, option_c, option_d, option_e, answer,
         * explanation, photo, answer_photo
         */
        $temp = array();

        $connect = $this->func->getDBConnection();

        if($connect)
            echo "Established connection"."<br/>";


        $new_query = "SELECT id FROM jamb_only_table";
        $new_result = $connect->query( $new_query);

        //2. Create if not exists table for jamb-only questions
        if ( empty($new_result)) {
            echo "creating new table for jamb " . "<br/>";

            $query = "CREATE TABLE jamb_only_table(
                    id int(11) AUTO_INCREMENT PRIMARY KEY ,
                    subject_id TEXT,
                    exam_year TEXT,
                    questions TEXT,
                    option_a TEXT,
                    option_b TEXT,
                    option_c TEXT,
                    option_d TEXT,
                    option_e TEXT,
                    answer TEXT,
                    explanation TEXT,
                    photo TEXT,
                    answer_photo TEXT
                    )";
            $result1 = $connect->query($query);

            if (!$result1) die("Invalid Query " . mysqli_error($connect));
            else{
                echo "table created " . "<br/>";
            }


            //1.  Get rows where exam_body is jamb
            $query = "SELECT * FROM classroom WHERE exam_body = 'jamb'";
            $result = $connect->query($query);
            echo "now getting results";
            // while( $row = mysql_fetch_array($result1)){
            while ( $row = $result->fetch_array() ) {
                $id =  $connect->real_escape_string($row["id"]);
                $subject_id =  $connect->real_escape_string($row["cat_id"]);
                $exam_year =  $connect->real_escape_string($row["exam_year"]);
                $questions=  $connect->real_escape_string($row["q_text"]);
                $option_a =  $connect->real_escape_string($row["option_a"]);
                $option_b =  $connect->real_escape_string($row["option_b"]);
                $option_c =  $connect->real_escape_string($row["option_c"]);
                $option_d =  $connect->real_escape_string($row["option_d"]);
                $option_e =  $connect->real_escape_string($row["option_e"]);
                $answer =  $connect->real_escape_string($row["q_answer"]);
                $explanation =  $connect->real_escape_string($row["q_explanation"]);
                $photo =  $connect->real_escape_string($row["photo"]);
                $answer_photo =  $connect->real_escape_string($row["answer_photo"]);

                echo " question ".$id ." is ".$option_a."<br/>";

                //3. Insert the result in 1 above

               $insert_query =
                    "INSERT INTO jamb_only_table (subject_id, exam_year, questions, option_a,
                            option_b, option_c, option_d, option_e, answer, explanation, photo,
                            answer_photo)
                          VALUES ('$subject_id',
                          '$exam_year',
                          '$questions',
                          '$option_a',
                            '$option_b',
                            '$option_c',
                            '$option_d',
                            '$option_e',
                            '$answer',
                            '$explanation',
                            '$photo',
                            '$answer_photo'
                            )";
                $insert_result = $connect->query($insert_query);

                if(!$insert_result){
                    die('MySQL query failed'.mysqli_error($connect)."<br/>");
                } else{
                    echo "question ".$id ." is successfully inserted into jamb_only_table"."<br/>";

                }
            }

        }
            //2b. get results from the query
        else {
            //1.  Get rows where exam_body is jamb
            $query = "SELECT * FROM classroom WHERE exam_body = 'jamb'";
            $result = $connect->query($query);
            echo "now getting results 1"."<br/>";
            // while( $row = mysql_fetch_array($result1)){
            while ( $row = $result->fetch_array() ) {
                $id =  $connect->real_escape_string($row["id"]);
                $subject_id =  $connect->real_escape_string($row["cat_id"]);
                $exam_year =  $connect->real_escape_string($row["exam_year"]);
                $questions=  $connect->real_escape_string($row["q_text"]);
                $option_a =  $connect->real_escape_string($row["option_a"]);
                $option_b =  $connect->real_escape_string($row["option_b"]);
                $option_c =  $connect->real_escape_string($row["option_c"]);
                $option_d =  $connect->real_escape_string($row["option_d"]);
                $option_e =  $connect->real_escape_string($row["option_e"]);
                $answer =  $connect->real_escape_string($row["q_answer"]);
                $explanation =  $connect->real_escape_string($row["q_explanation"]);
                $photo =  $connect->real_escape_string($row["photo"]);
                $answer_photo =  $connect->real_escape_string($row["answer_photo"]);

                    echo " question ".$id ." is ".$option_a."<br/>";

                    //3. Insert the result in 1 above

                    $insert_query =
                        "INSERT INTO jamb_only_table (subject_id, exam_year, questions, option_a,
                            option_b, option_c, option_d, option_e, answer, explanation, photo,
                            answer_photo) VALUES ('$subject_id', '$exam_year', '$questions', '$option_a',
                            '$option_b', '$option_c', '$option_d', '$option_e', '$answer', '$explanation',
                            '$photo', '$answer_photo')";

                $insert_result = $connect->query($insert_query);

                if(!$insert_result){
                        die('MySQL query failed '.$connect->error."<br/>");
                } else{
                        echo "question ".$id ." is successfully inserted into jamb_only_table"."<br/>";

                }
            }
        }

    }
}