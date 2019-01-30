
<?php 
//link sent to the user will bring then here for verification

//redirect function
function redirect() {
    header('Location: index.php');
    exit();
}

if (!isset($_GET['email']) || !isset($_GET['token'])) {
?>
<script> alert( "something went wrong, the link is empty"); </script>
<?
    redirect();
}else {
    $con = new mysqli('localhost', 'root', 'mysql', 'verifyemail');
    $email = $con->real_escape_string($_GET['email']);
    $token = $con->real_escape_string($_GET['token']);
    $sql = $con->query("SELECT id FROM users WHERE Email='$email' AND token='$token' AND mailConfirmed=0");

    if ($sql->num_rows > 0) {
        $con->query("UPDATE users SET emailConfirmed=1, token='' WHERE Email='$email'");
?> <script> alert( 'Your email has been verified and addedd to email subscription list');
</script><?
    } else
?>
<script> alert( "something went wrong, please reclick your emailed link"); </script>
<?
        redirect();

}

?>