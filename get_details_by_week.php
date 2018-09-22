<?php

//check whether request parameters are comming or not
if (empty($_REQUEST["device_id"]))
{
    //error message if those values contain null or not assign
    $returnArray["status"]=800;
    $returnArray["message"]="Missing Required information8";
    echo json_encode($returnArray);
    return;
}
//success if all the values enterd correctly
else {
        //access the Test.ini file
        $file = parse_ini_file("Test.ini"); //get the database name,username ,password values
        $reviceID = $_REQUEST["device_id"];
        //get the values form Test.ini and assign those values to the variable
            $host = trim($file["dbhost"]);
            $user = trim($file["dbuser"]);
            $pass = trim($file["dbpass"]);
            $name = trim($file["dbname"]);
        
        
        //require the access.php file to call the function for the future purpose
            require("Secure/access.php");
        
        //call the class and assign the values get from the Test.ini
            $access = new access($host, $user, $pass, $name);
        
        //call the connect function to connect with the database
            $access->connect();
        
            $device_id = $_REQUEST["device_id"];

        
            $get_details_week = $access->get_week_wise_data($device_id);
            if ($get_details_week->num_rows > 0){
                $output["status"] = "200";
                $output["details"] = $get_details_week;
                $output["message"] = "Device registered successfully";
                echo json_encode($output);
                //disconnect the database connection
                $access->disconnect();
            }
            else {
                //error result
                $returnArray["status"] = "400";
                $returnArray["message"] = "Device not registered successfully";
                echo json_encode($returnArray);
                //disconnect the database connection
                $access->disconnect();
                return;
            }
}


    
    



?>
