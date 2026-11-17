<?php
    ob_start();
    require('../config/database.php');
    ob_end_clean();

    if (isset($_GET['userID']) && isset($_GET['action'])) {
        $user_id = intval($_GET['userID']);
        $action = $_GET['action'];

        if ($action === 'delete') {
            $sql = "DELETE FROM users WHERE id = $1";
            $res = pg_query_params($supa_conn, $sql, array($user_id));

            if ($res) {
                echo "<script>alert('User has been deleted successfully :::');</script>";
            } else {
                echo "<script>alert('User has not been deleted :::');</script>";
            }

        } elseif ($action === 'inactivate') {
            $sql = "UPDATE users SET status = false, deleted_at = NOW() WHERE id = $1";
            $res = pg_query_params($supa_conn, $sql, array($user_id));

            if ($res) {
                echo "<script>alert('User has been inactivated successfully :::');</script>";
            } else {
                echo "<script>alert('User has not been inactivated :::');</script>";
            }
        }
    }

    echo "<script>window.location.href = 'list_users.php';</script>";
    exit();
?>