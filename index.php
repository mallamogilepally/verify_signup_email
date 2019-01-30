<?php
$msg = "";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//$var = strip_tags($_POST['submit']);
//after submitting the request
if(isset($_POST['submit'])){
    $con = new mysqli('localhost', 'root', 'mysql', 'verifyemail');
    // Check connection
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    echo "Connected successfully";

    $name = $con->real_escape_string($_POST['name']);
    $email =$con->real_escape_string( $_POST['email']);
    //$category = $con->real_escape_string ($_POST['category']);
    echo $email;
    if($name == "" || $email == ""){
        $msg = "Please provide your Name and Email";
        echo "<script type='text/javascript'>alert('$msg');</script>";}

    else {
        $sql = $con->query("SELECT id FROM users WHERE Email = '$email'");

        if($sql->num_rows>0){
            $msg = "Email already exists in our database";
            echo "<script type='text/javascript'>alert('$msg');</script>";

        }
        else{
            //creating a unique token to identify the users
            $token = 'qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM1234567890';
            $token = str_shuffle($token);
            $token = substr($token, 0, 10 );
            $con->query("INSERT INTO users (name, Email, emailConfirmed, token) VALUES('$name','$email', '0', '$token')");

            //sending the unique token with in a link to the signed up users

            include_once "PHPMailer/PHPMailer.php";
            include_once "PHPMailer/Exception.php";

            $mail = new PHPMailer();
            $mail->setFrom('mallareddynz20@gmail.com');
            $mail->addAddress($email, $name);
            $mail->Subject = "Please verify email!";
            $mail->isHTML(true);
            $mail->Subject = "Please verify email!";
            $mail->Body ="Please click on this linke below to varify your Email Id <br><br>
                <a href = 'http://localhost/verifyemail.com/confirm.php?email=$email&token=$token'>Click Here</a>
                ";

            if($mail->send()){
                $msg = " Your signup has been successful please Verify using link sent to your Email";
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }

            else {
                $msg = "Something Went Wrong . $mail->ErrorInfo";
                echo "<script type='text/javascript'>alert('$msg');</script>";
            }

        }

    }

}



?>


<!doctype html>
<html lang= "en">
    <head>
        <meta char  = "UTF-8">
        <meta name = "viewport"
              content = "width=device-cidth, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale= 1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Singup</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    <body>
        <div class="container" style="margin-top:100px;" >
            <div class = "form-group row justify-content-center">
                <div class ="col-md-6 col-sm-6">
                    <form method="POST" action="">
                        <input class="form-control" name="name" placeholder="Name..."><br>
                        <input class="form-control" name="email" type = "email" placeholder="Email..."><br>
                        <p>Please selec a Category that you are intrested in </p>
                        <div class = "form-check">
                            <input class = "form-check-input" type="checkbox" name="category[]" valu="travel">
                            <label class ="form-check-label" for = "travel"> Travel</label> <br/>
                            <input class = "form-check-input" type="checkbox" name="category[]" valu="catering">
                            <label class ="form-check-label" for = "catering">Catering</label><br/>
                            <input class = "form-check-input" type="checkbox" name="category[]" valu="engineering">
                            <label class ="form-check-label" for = "engineering">Engineering</label><br/><br/>
                        </div>
                        <input class="btn btn-primary" type="submit" name="submit" value="submit">
                    </form>  
                </div>
            </div>
        </div>

    </body>

</html>