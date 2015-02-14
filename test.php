<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'start':
            echo("start");
            exec("mkdir start");
            echo("start2");
            break;
        case 'stop':
            echo("stop");
            exec("mkdir stop");
            echo("stop2");
            break;
        case 'ping':
            echo(system("/home/ubuntu/tools/ping ". $_POST['sensor_id'] ." 5 &"));
            break;
        case 'server_state':
            exec("service tnp-server status", $output, $return);
            echo($return);
            break;
        case 'server_start':
            exec("/usr/bin/sudo /usr/bin/service tnp-server start > /dev/null &", $output, $return);
            break;
        case 'server_restart':
            exec("sudo service tnp-server stop", $output, $return);
            exec("/usr/bin/sudo /usr/bin/service tnp-server start > /dev/null &", $output, $return);
            break;
        case 'server_stop':
            exec("sudo service tnp-server stop", $output, $return);
            foreach ($output as $value) {
                echo("OUTPUT".$value);
            }
            echo("RETURN".$return);
            break;
    }
}

exit;
?>