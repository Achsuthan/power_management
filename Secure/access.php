<?php

//this class is going to interact with the database
class access
{

    //create needed variable for this class

    var $host=null;
    var $user=null;
    var $pass=null;
    var $dbname=null;
    var $con=null;
    var $result=null;
    var $payment;
    var $hand;
    var $submitted;
    var $uid;


    function __construct($dbhost,$dbuser,$dbpass,$dbname)   //get the values from the caller
    {
        $this->host=$dbhost;   //assgin the hostname
        $this->user=$dbuser;   //assign the username
        $this->pass=$dbpass;   //assign the password
        $this->dbname=$dbname;  //assgin the db name
    }

    public function connect()   //DB connection
    {
        $this->con=new mysqli($this->host,$this->user,$this->pass,$this->dbname);  //get the host,user,password and the dbname from the caller
        if(mysqli_connect_error())  //check whether the db connection contain any error
        {
            echo "Could no connect databe"; //prompt the error message
        }
        $this->con->set_charset("utf8");
    }

    //disconnect the db connection
    public function disconnect()
    {
        if($this->con!=null)  //check whether the con variable contain any value
        {
            $this->con->close();  //close the db connection
        }
    }

    //Register device
    public function register_device($device_name,$suport_device,$limit_value,$created_by,$voltage)
    {
        $this->createid_deviceid();  //crate auto increment id for the user
        $created_date = date("Y-m-d h:i:s");
        $sql = "INSERT INTO device  (id, device_name, suport_device, limit_value, created_date, created_by,watt)
        VALUES ('$this->uid','$device_name', '$suport_device', '$limit_value', '$created_date', '$created_by','$voltage')";

        if ($this->con->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $this->con->error;
            return "";
        }

        return $this->uid;   //return the caller to notify that the user is inserted successfully
    }


    //creating userid with specific string and number
    public function createid_deviceid()
    {

        $sql= "Select * from device ORDER BY id DESC LIMIT 1; ";  //get the last value form the database

        $result=$this->con->query($sql); //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                $id=substr($row["id"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit
                $id=$id+1;  //increase the last 6 digit value by one
                $this->uid="DIV".$id;  //asign back to id as a USR111112
            }
        }
        else {
            $this->uid = "DIV1111";
        }
    }

    public function createid_readingid()
    {

        $sql= "Select * from reading ORDER BY id DESC LIMIT 1; ";  //get the last value form the database

        $result=$this->con->query($sql); //get the result by executing the sql query

        if ($result !=null && (mysqli_num_rows($result)>=1))  //check whether the the result contain value or not
        {
            $row=$result->fetch_array(MYSQLI_ASSOC);   //get the rows value form the database and assign that value to row
            if(!empty($row))  //check whether the variable row contain value or not
            {
                $id=substr($row["id"], 3, 6);  //get the integer potion part for  fro example if the database contain a uid USR111111, get the last 6 digit
                $id=$id+1;  //increase the last 6 digit value by one
                $this->uid="RED".$id;  //asign back to id as a USR111112
            }
        }
        else {
            $this->uid = "RED1111";
        }
    }

    public function check_device($device_id){
        $sql = "SELECT id FROM device WHERE id ='$device_id'";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            //value available
            return 1;
        } else {
            //value not available
            return 0;
        }
    }

    //Register device
    public function register_reading($device_id, $is_on, $value)
    {
        $device_details = $this->check_deviceid($device_id);
        if ($device_details->num_rows > 0) {
            if ($this->check_limit_value($device_id,$value) == 1 ){
                $last_value = $this->get_last_value_in_reading_based_on_device_id($device_id);
                
                if($last_value->num_rows > 0){

                    $duration = 0;
                    $usage = 0;
                    while ($row = mysqli_fetch_assoc($last_value)) {

                        $timeFirst  = strtotime($row["recorded_date"]);
                        $timeSecond = strtotime(date("Y-m-d h:i:s"));
                        $differenceInSeconds = $timeSecond - $timeFirst;

                        if ($row["is_on"] != 2){
                            $duration = $differenceInSeconds + $row["duration"];
                        }
                        else {
                            $duration = $row["duration"];
                        }
                        $usage = $row["usage"] + $differenceInSeconds;
                        break;
                    }

                    $this->createid_readingid();  //crate auto increment id for the user
                    $created_date = date("Y-m-d h:i:s");
                    $sql = "INSERT INTO reading  (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
                    VALUES ('$this->uid','$value', '$created_date', '$is_on','$duration', '$usage','$device_id')";

                    if ($this->con->query($sql) === TRUE) {
                    } else {
                        echo "Error: " . $sql . "<br>" . $this->con->error;
                        return "";
                    }
                    return $this->uid;   //return the caller to notify that the user is inserted successfully

                }
                else{
                    $this->createid_readingid();  //crate auto increment id for the user
                    $created_date = date("Y-m-d h:i:s");
                    $sql = "INSERT INTO reading  (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
                    VALUES ('$this->uid','$value', '$created_date', '$is_on', 0, 0,'$device_id')";

                    if ($this->con->query($sql) === TRUE) {
                    } else {
                        echo "Error: " . $sql . "<br>" . $this->con->error;
                        return "";
                    }
                    return $this->uid;   //return the caller to notify that the user is inserted successfully
                }
            }
            else {
                echo "less than the limit value";
                $last_value = $this->get_last_value_in_reading_based_on_device_id($device_id);
                
                if($last_value->num_rows > 0){

                    $duration = 0;
                    $usage = 0;
                    $lastDate = "";
                    while ($row = mysqli_fetch_assoc($last_value)) {
                        echo $row["duration"];
                        $duration = $row["duration"];
                        $usage = $row["usage"];
                        $lastDate = strtotime($row["recorded_date"]);
                        break;
                    }
                    $timeSecond = strtotime(date("Y-m-d h:i:s"));
                    $differenceInSeconds = $timeSecond - $lastDate;
                    $usage = $usage + $differenceInSeconds;


                    $this->createid_readingid();  //crate auto increment id for the user
                    $created_date = date("Y-m-d h:i:s");
                    $sql = "INSERT INTO reading  (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
                    VALUES ('$this->uid','$value', '$created_date', '$is_on','$duration', '$usage','$device_id')";

                    if ($this->con->query($sql) === TRUE) {
                    } else {
                        echo "Error: " . $sql . "<br>" . $this->con->error;
                        return "";
                    }
                    return $this->uid;
                }
                else {
                    $this->createid_readingid();  //crate auto increment id for the user
                    $created_date = date("Y-m-d h:i:s");
                    $sql = "INSERT INTO reading  (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
                    VALUES ('$this->uid','$value', '$created_date', '$is_on', '0','0','$device_id')";

                    if ($this->con->query($sql) === TRUE) {
                    } else {
                        echo "Error: " . $sql . "<br>" . $this->con->error;
                        return "";
                    }
                    return $this->uid;   //return the caller to notify that the user is inserted successfully
                }

            }
        }
        else {
            print("hello");
            $this->createid_readingid();  //crate auto increment id for the user
            $created_date = date("Y-m-d h:i:s");
            $sql = "INSERT INTO reading  (`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
            VALUES ('$this->uid','$value', '$created_date', '$is_on', '0', '0', '$device_id')";

            // $sql = "INSERT INTO `reading`(`id`, `value`, `recorded_date`, `is_on`, `duration`, `usage`, `device_id`) 
            // VALUES ('$this->uid','$value', '$created_date', '$is_on', '0','0', '$device_id')"

            if ($this->con->query($sql) === TRUE) {
            } else {
                echo "Error: " . $sql . "<br>" . $this->con->error;
                return "";
            }
            return $this->uid;   //return the caller to notify that the user is inserted successfully
        }
    }

    public function get_last_value_in_reading_based_on_device_id($device_id) {
        $sql = "SELECT * FROM reading WHERE device_id ='$device_id' ORDER BY recorded_date DESC;";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            //value is available
            return $result;
        } else {
            //value not available
            return $result;
        }
    }

    public function check_limit_value($device_id, $value){
        $sql = "SELECT id FROM device WHERE limit_value < $value and id = '$device_id'";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            //value available
            
            return 1;
        } else {
            //value not available
            return 0;
        }
    }


    //creating userid with specific string and number
    public function check_deviceid($device_id)
    {
        $sql = "SELECT id FROM reading WHERE device_id ='$device_id'";
        $result = $this->con->query($sql);

        if ($result->num_rows > 0) {
            //value available
            return $result;
        } else {
            //value not available
            return $result;
        }
    }


    //creating userid with specific string and number
    public function get_week_wise_data($id)
    {
        $sql= "select * from reading where device_id = '$id' group by from_unixtime(recorded_date, '%Y%m')";  //get the last value form the database
        $result=$this->con->query($sql); //get the result by executing the sql query
        //print_r($result);
        if ($result->num_rows > 0) {
            //value available
            print_r($result);
            while ($row = mysqli_fetch_assoc($result)) {
                echo $row["id"];
            }
            return $result;
        } else {
            //value not available
            return $result;
        }
    }

    public function get_by_month($id){
        $sql= "SELECT * FROM reading WHERE device_id = '$id' and recorded_date IN (SELECT   MAX(recorded_date)FROM reading GROUP BY MONTH(recorded_date), YEAR(recorded_date))ORDER BY id DESC";  //get the last value form the database
        $result=$this->con->query($sql); //get the result by executing the sql query
        if ($result->num_rows > 0) {
            //value available
            $usage = "";
            $wastage = "";
            while ($row = mysqli_fetch_assoc($result)) {
                $usage = $row["usage"];
                $wastage = $row["duration"];
                break;
            }
            $output["usage"] = $usage;
            $output["wastage"] = $wastage;
            return $output;
        } else {
            //value not available
            return [];
        }
    }

    public function get_by_month_money($id){

        $voltage = "";

        $sql_voltage= "SELECT watt FROM device WHERE id = '$id'";  //get the last value form the database
        $result_voltage=$this->con->query($sql_voltage); //get the result by executing the sql query
        if ($result_voltage->num_rows > 0) {
            
            while ($row = mysqli_fetch_assoc($result_voltage)) {
                $voltage = $row["watt"];
                break;
            }

            $sql= "SELECT * FROM reading WHERE device_id = '$id' and recorded_date IN (SELECT   MAX(recorded_date)FROM reading GROUP BY MONTH(recorded_date), YEAR(recorded_date))ORDER BY id DESC";  //get the last value form the database
            $result=$this->con->query($sql); //get the result by executing the sql query
            if ($result->num_rows > 0) {
                //value available
                $usage = "";
                $wastage = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $usage = $row["usage"];
                    $wastage = $row["duration"];
                    break;
                }
                $output["usage"] = $usage;
                $output["wastage"] = $wastage;


                $unit = ($usage * $voltage )/(1000 * 3600);
                $price = "" ;

                if ($unit > 180) {
                    $tunit = $unit - 180; 
                    $price = $tunit * 45 + 60 * 32 + 30 * 27.75 + 30 * 10 + 60 * 7.85 + 540;
                }
                else if ($unit > 120) {
                    $tunit = $unit - 120;
                    $price = $tunit * 32 + 30 * 27.75 + 30 * 10 + 60 * 7.85 + 480; 
                }
                else if ($unit > 90 ){
                    $tunit = $unit - 90;
                    $price = $tunit * 27.75 + 30 * 10 + 60 * 7.85 + 480;
                }
                else if ($unit > 60){
                    $tunit = $unit - 60;
                    $price = $tunit * 10 + 60 * 7.85 + 90;
                }
                else {
                    $price = $unit * 7.85;
                }

                $output["usageCharge"] = $price;

                $Actualunit = ($usage * $voltage )/(1000 * 3600) - ($wastage * $voltage)/(1000 * 36000);
                $pprice = "";
                if ($unit > 180) {
                    $tunit = $Actualunit - 180; 
                    $pprice = $tunit * 45 + 60 * 32 + 30 * 27.75 + 30 * 10 + 60 * 7.85 + 540;
                }
                else if ($unit > 120) {
                    $tunit = $Actualunit - 120;
                    $pprice = $tunit * 32 + 30 * 27.75 + 30 * 10 + 60 * 7.85 + 480; 
                }
                else if ($unit > 90 ){
                    $tunit = $Actualunit - 90;
                    $pprice = $tunit * 27.75 + 30 * 10 + 60 * 7.85 + 480;
                }
                else if ($unit > 60){
                    $tunit = $Actualunit - 60;
                    $pprice = $tunit * 10 + 60 * 7.85 + 90;
                }
                else {
                    $pprice = $Actualunit * 7.85;
                }





                $output["wastageCharge"] = floatval($price) - floatval($pprice);
                return $output;
        } else {
            //value not available
            return [];
        }
        } else {
            //value not available
            return [];
        }



        
    }


    //
}
?>