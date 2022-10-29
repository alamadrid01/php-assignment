<?php

require_once "../config.php";


//register users
function registerUser($fullnames, $email, $password, $gender, $country){
    //create a connection variable using the db function in config.php

    $conn = db();
   //check if user with this email already exist in the database
   $checked = "SELECT * FROM students WHERE email='$email' ";
   $data = mysqli_query($conn, $checked);
   $num = mysqli_num_rows($data);
   
   if($num== 1){
     echo "<p style= 'color:red' >Username already exist </p>" ;
   }else{
    $data = "INSERT INTO students(`full_names`, `country`, `email`, `gender`, `password`)
    VALUES(?,?,?,?,?);";
 
    $stmt = $conn->prepare($data);
    $stmt->bind_param("sssss", $fullnames, $country, $email, $gender, $password);
    $stmt->execute();
    echo "Registration successful";
    $stmt->close();
    $conn->close();
   }
   
}




//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();

    // echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dashboard

    $data = "SELECT email, password FROM students where email='$email' && password='$password'";
    $result = mysqli_query($conn, $data);
    $num = mysqli_num_rows($result);

    if($num ==1){
        header("location:../dashboard.php");
        session_start();
        $_SESSION["username"]= $email ;
    }else{
        echo "<h1 style='color: red'> Username or password does not exist </h1>";
    }
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    //if it does, replace the password with $password given
    $data = "SELECT * FROM students WHERE email='$email'";
   $result = mysqli_query($conn, $data);
   $num= mysqli_num_rows($result);

   if($num == 1){
     "UPDATE students 
     SET password='$password'
     WHERE email='$email'";

   }else{
    echo "User does not exist";
   }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     "DELETE FROM students 
     where id= $id ";
 }
