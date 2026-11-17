<?php
    // Conexión a la base de datos
    require('../CONFIG/database.php');

    session_start();

    // Si ya tiene sesión activa, redirigir directo al home
    if(isset($_SESSION['userId'])){
        header('Location: home.php');
        exit();
    }

    // Recibir y limpiar datos de signin.html
    $email  = pg_escape_string($supa_conn, $_POST['email']);
    $passwd = pg_escape_string($supa_conn, $_POST['password']);

    $sql = "SELECT u.*, u.firstname || ' ' || u.lastname AS fullname 
            FROM users u 
            WHERE email = '$email' AND password = '$passwd'";
            
    $res = pg_query($supa_conn, $sql);

    if($res && pg_num_rows($res) > 0){
        $row = pg_fetch_assoc($res);

        $_SESSION['userId']   = $row['id'];
        $_SESSION['fullName'] = $row['fullname'];
        $_SESSION['fName']    = $row['firstname'];
        $_SESSION['lName']    = $row['lastname'];

        header('Location: home.php');
        exit();
    } else {
        echo "<script>
                alert('Invalid email or password :::');
                window.location.href = 'signin.html';
              </script>";
        exit();
    }
?>