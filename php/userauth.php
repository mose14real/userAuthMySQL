<?php

session_start();
require_once "../config.php";

#register users
function registerUser($fullnames, $email, $password, $country, $gender){
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
    $sql_emailCheck = "SELECT email FROM students WHERE email='$email'";
    $result = mysqli_query($conn, $sql_emailCheck);  
    if (mysqli_num_rows($result) > 0) {
        echo "User already exist" . "<br>" . "Please wait redirecting ... to register page!";
        header("refresh: 2; ../forms/register.html");
        exit;
    }
    $sql_insert = "INSERT INTO students (id, full_names, country, email, gender, password) VALUES (null, '$fullnames', '$country', '$email', '$gender', '$password')";
    if (mysqli_query($conn, $sql_insert)) {     
        echo "User Successfully registered" . "<br>". "Please wait ... redirecting to login page!";
        header("refresh: 2; ../forms/login.html");
        exit;
    }
    mysqli_close($conn);
}
#login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if email exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dasbboard
    $sql_login = "SELECT email, password FROM students WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql_login);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['email'] = $email;
        echo "Please wait ... redirecting to dashboard page!";
        header("refresh: 2; ../dashboard.php");
        exit;
    } else {
        echo "Incorrect username or password" . "<br>". "Please wait ... redirecting to login page!";
        header("refresh: 2; ../forms/login.html");
        exit;
    }
    mysqli_close($conn);
}
#logout users
function logoutUser(){
    session_destroy();
    echo "Logging you out" . "<br>" . "Please wait redirecting ... to login page!";
    header("refresh: 2; ../forms/login.html");
}
#reset password
function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if email exist in the database
    //if it does, replace the password with $password given
    $sql_emailCheck = "SELECT * FROM students WHERE email='$email'";
    $result = mysqli_query($conn, $sql_emailCheck);  
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
        }
        $sql = "UPDATE students SET password='$password' WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {     
            echo "Password successfully updated" . "<br>". "Please wait ... redirecting to login page!";
            header("refresh: 2; ../forms/login.html");
        }
    } else {
        echo "User does not exist!" . "<br>". "Please wait ... redirecting to reset password page!";
        header("refresh: 2; ../forms/login.html");
    }
    mysqli_close($conn);
}
#show users data
function getusers(){
    $conn = db();
    //return users from the database
    //loop through the users and display them on a table
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
                    <td style='width: 150px'>" . $data['full_names'] . "</td>
                    <td style='width: 150px'>" . $data['email'] . "</td>
                    <td style='width: 150px'>" . $data['gender'] . "</td>
                    <td style='width: 150px'>" . $data['country'] . "</td>
                    <form action='action.php' method='post'>
                        <input type='hidden' name='id'" . "value=" . $data['id'] . ">".
                        "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                    "</form>" .
                "</tr>"
            ;
        }
        echo "</table></table></center></body></html>";
    }
    mysqli_close($conn);
}
#delete accounts
function deleteAccount($id){
    $conn = db();
    $sql = "DELETE FROM students WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {     
        echo "Record deleted successfull" . "<br>" . "Please wait ... redirecting to dashbaord page!";
        header("refresh: 2; ../dashboard.php");
    } else {
        echo "Record not deleted" .  "<br>" . "Please wait ... redirecting to dashbaord page!";
        header("refresh: 2; ../dashboard.php");
    }
    mysqli_close($conn);
}