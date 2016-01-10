<?php

include('session.php'); 

if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'server_state':
            exec("sudo service weperf-all status", $output, $return);
            echo($return);
            break;
        case 'server_start':
            exec("sudo service weperf-all start > /dev/null &", $output, $return);
            break;
        case 'server_restart':
            exec("sudo service weperf-all stop", $output, $return);
            exec("sudo service weperf-all start > /dev/null &", $output, $return);
            break;
        case 'server_update':
            echo("Not yet implemented");
            break;
        case 'server_stop':
            exec("sudo service weperf-all stop", $output, $return);
            foreach ($output as $value) {
                echo("OUTPUT".$value);
            }
            echo("RETURN".$return);
            break;
    }
}

exit;
?>