<?php
include('database.php');
session_start();

if(isset($_POST['userid'])){
    $userid=$_POST['userid'];
    $password=$_POST['password'];
    
    $sql="select * from user";
    $result=mysqli_query($conn,$sql);
    $find=FALSE;
    while($user=mysqli_fetch_array($result)){
        if($user['student_id']==$userid&&$user["password"]==$password){
            $find=TRUE;
            $_SESSION['username']=$user['student_id'];
            $_SESSION['status']='user';
            break;
        }
    }
    
if($find==FALSE){
    $sql="select * from admin";
    $result=mysqli_query($conn,$sql);
    while($admin=mysqli_fetch_array($result)){
        if($admin['staff_id']==$userid&&$admin["password"]==$password){
            $find=TRUE;
            $_SESSION['username']=$admin['staff_id'];
            $_SESSION['status']='admin';
            break;
        }
    }
}

if($find==TRUE){
    if($_SESSION['status']=='user')
    header("Location:index.php");
else
    header("Location:admin.php");
}
else
    echo"<script>alert('Incorrect ID or Password');
        window.location='login2.php'</script>";
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book4U - Login</title>
        <link rel="stylesheet"href="login.css">
         <link rel="stylesheet" href="create-event.css">
    </head>
<body>
    <div class="signup-container">
                <h2>Login to Book4U</h2>
    <form action=login2.php method=post class="login">
        <table>
            <tr>
                
            <td><label>User ID:</label><input type="text"name="userid" placeholder="User id" required></td>
                </tr>
            <td><label>Password:</label><input type="password"name="password" placeholder="Password" required></td>
        </table>
        <tr>
        <button class="login" type="submit">Login</button>
        </tr><br>
        <button class="signup" type="button" onclick="window.location='signup.php'">Sign Up</button>
    </form>
    </div>
</body>
</html>