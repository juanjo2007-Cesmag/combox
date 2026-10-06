<?php
    // Local connection using PDO
    $local_host = "localhost";
    $local_port = "5432";
    $local_user = "postgres";
    $local_dbname = "combox";
    $local_password = "unicesmag";

    // Supabase connection using PDO
    $supa_host = "aws-0-us-east-2.pooler.supabase.com";
    $supa_port = "6543";
    $supa_user = "postgres.tukrgrbyjlyytarnuzsb";
    $supa_dbname = "postgres";
    $supa_password = "unicesmag@@";

    $local_conn = pg_connect("
        host=$local_host 
        port=$local_port 
        dbname=$local_dbname 
        user=$local_user 
        password=$local_password
    ");

    $supa_conn = pg_connect("
        host=$supa_host 
        port=$supa_port 
        dbname=$supa_dbname 
        user=$supa_user 
        password=$supa_password
    ");


    if (!$local_conn) {
        echo "Error en conexión Local... <br>";
    } else {
        echo "¡Conexión Local exitosa! <br>";
    }

    if (!$supa_conn) {
        echo "Error en conexión Supabase... <br>";
    } else {
        echo "¡Conexión Supabase exitosa! <br>";
    }
?>