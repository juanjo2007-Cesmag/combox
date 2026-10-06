<?php
    //Get data base connection
    require('../config/database.php');

    //Get data from html form (client) 
    $f_name=$_POST['fname'];
    $l_name=$_POST['lname'];
    $m_phone=$_POST['mphone'];
    $e_mail=$_POST['email'];
    $p_assword=$_POST['psw'];

    // AGREGADO: Incluimos creaded_at y updated_at con NOW() para evitar bloqueos de campos obligatorios
    $sql = "
        INSERT INTO users (
            firstname, lastname, mobile_phone, email, password
        )
        VALUES(
            '$f_name', '$l_name', '$m_phone', '$e_mail', '$p_assword'
        )
    ";

    $local_res = pg_query($local_conn, $sql);
    $supa_res = pg_query($supa_conn, $sql);

    if($local_res){
        echo "User has been created successfully into local database !!!<br>";
    } else{
        echo "User hasn't been created into local database !!! <br>";
    }

    if($supa_res){
        echo "User has been created successfully into supabase database !!!<br>";
    } else{
        echo "User hasn't been created into supabase database !!!<br>";
    }

    /*
    echo "Firstname is: ". $f_name;
    echo "<br>Lastname is: ". $l_name;
    echo "<br>Mobile Phone is: ". $m_phone;
    echo "<br>E-mail is: ". $e_mail;
    echo "<br>Password is: ". $p_assword;
    */
?>
