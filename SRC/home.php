<?php
    session_start();

    if(!isset($_SESSION['userId'])){
        header('refresh:0;url=signin.html');
    }else{
         $full_name = $_SESSION['fName'];
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>  
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Combox - Home</title>
    </head>
    <body>  
        Username: <?php echo $full_name; ?> |
        <a href="list_users.php">Ver lista de usuarios</a> |
        <a href="logout.php">logout</a>
    </body>
</html>
