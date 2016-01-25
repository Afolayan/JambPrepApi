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
        include_once './functions.php';
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


    }

    public function registerUser($name, $email, $regId){
        $json = array();

        /**
         * Registering a user device
         * Store reg id in users table
         */

         $time = date('Y-m-d H:i:s');

         $mysql = $this->func->getDBConnection();

            if(!$mysql){
                die('MySQL connection failed'.$mysql->error);
            }


            $sql = "INSERT INTO users (name, email, reg_id, time)
              VALUES ( '$name', '$email',
                      '$regId',  '$time'
                  )";

            if(!$mysql->query($sql, MYSQLI_ASSOC)){
                die('MySQL query failed'.$mysql->error);
            }

            $json["status"] = "success";

        $mysql->close();
    }

    public function notify( $message, $meta_data ){
        $registration_ids = array();

        $connect = $this->func->getDBConnection();
        $goo = new config();

        $sql = "SELECT * FROM users";
        $result = $connect->query($sql);

        while($row = $result->fetch_array()){
            array_push($registration_ids, $row['reg_id']);
        }

        // Set POST variables
        $url = 'https://android.googleapis.com/gcm/send';

        $message = array("Notice" => $message, "meta"=>$meta_data/*, phone="", any other message can stay in here*/);
        $fields = array(
            'registration_ids' => $registration_ids,
            'data' => $message,
        );

        $headers = array(
            'Authorization: key='. $goo->GOOGLE_API_KEY,
            /*'Authorization: key=AIzaSyDzHveYbmrrMkLd9-TK-K4rWLWtqtsSnFQ',*/
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        echo $result;
    }

    public function getQuestionsPerYear( $year ){
        $connect = $this->func->getDBConnection();
        $response = array();
        $tmp = array();

        if($connect)
            echo "Established connection"."<br/>";

        $query = sprintf("SELECT * FROM jamb_only_table WHERE exam_year='%s'",
                $connect->real_escape_string($year));

        $result = $connect->query( $query );

        if (! $result ) die ( "Invalid query ".$connect->error);

        if ( $result->num_rows > 0){
            while($row = $result->fetch_array()){

                $tmp["id"] = $connect->real_escape_string($row["id"]);
                $tmp["subject_id"] = $connect->real_escape_string($row["subject_id"]);
                $tmp["exam_year"] = $connect->real_escape_string($row["exam_year"]);
                $tmp["questions"] = $connect->real_escape_string($row["questions"]);
                $tmp["option_a"] = $connect->real_escape_string($row["option_a"]);
                $tmp["option_b"] = $connect->real_escape_string($row["option_b"]);
                $tmp["option_c"]  = $connect->real_escape_string($row["option_d"]);
                $tmp["option_d"]  = $connect->real_escape_string($row["option_d"]);
                $tmp["option_e"]  = $connect->real_escape_string($row["option_e"]);
                $tmp["answer"]  = $connect->real_escape_string($row["answer"]);
                $tmp["explanation"]  = $connect->real_escape_string($row["explanation"]);
                $tmp["photo"]  = $connect->real_escape_string($row["photo"]);
                $tmp["answer_photo"]  = $connect->real_escape_string($row["answer_photo"]);

                //echo "<p>".$tmp["subject_id"]. " ". $tmp["exam_year"]."</p>";
                array_push($response, $tmp);
                echo "pushed";
            }

            header('Content-Type: application/json');
            echo json_encode($response);

            // return $response;
        }

    }
}