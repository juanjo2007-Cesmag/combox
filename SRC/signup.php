<?php
    // Conexión a la base de datos
    require('../CONFIG/database.php');

    // Recibir y limpiar datos del formulario (client)
    $f_name  = pg_escape_string($supa_conn, $_POST['fname']);
    $l_name  = pg_escape_string($supa_conn, $_POST['lname']);
    $m_phone = pg_escape_string($supa_conn, $_POST['mphone']);
    $e_mail  = pg_escape_string($supa_conn, $_POST['email']);
    $p_assword = pg_escape_string($supa_conn, $_POST['psw']);

    $sql = "
        INSERT INTO users (
            firstname, lastname, mobile_phone, email, password
        )
        VALUES(
            '$f_name', '$l_name', '$m_phone', '$e_mail', '$p_assword'
        )
    ";

    $supa_res = pg_query($supa_conn, $sql);

    if($supa_res){
        echo "<script>
                alert('Usuario creado exitosamente en Supabase');
                window.location.href = 'signin.html';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error al crear el usuario en Supabase');
                window.location.href = 'signup.html';
              </script>";
        exit();
    }
?>