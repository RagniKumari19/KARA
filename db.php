<?php

//agar kahin ise host krna hai to whi jo username,password,dbname provide kiya jata hai to whi yahan likh dena hai, servername same hi hota hai localhost
    $servername="localhost";
    $username="root";
    $password ="";
    $dbname = "chatting";
      session_start();
    // hostname = servername

    // mysqli_connect -> ye ke predefined function hai jo databse se connect krta hai

    $conn = mysqli_connect($servername,$username,$password,$dbname);
    if(!$conn){
        echo "not connection";
    }
    // if(session_start()===PHP_SESSION_NONE){
    //     session_start();
    // }

?>