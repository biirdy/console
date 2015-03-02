<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'server_state':
            exec("service network-sensor-server status", $output, $return);
            echo($return);
            break;
        case 'server_start':
            exec("/usr/bin/sudo /usr/bin/service network-sensor-server start > /dev/null &", $output, $return);
            break;
        case 'server_restart':
            exec("sudo service tnp-server stop", $output, $return);
            exec("/usr/bin/sudo /usr/bin/service network-sensor-server start > /dev/null &", $output, $return);
            break;
        case 'server_stop':
            exec("/usr/bin/sudo /usr/bin/service network-sensor-server stop", $output, $return);
            foreach ($output as $value) {
                echo("OUTPUT".$value);
            }
            echo("RETURN".$return);
            break;
    }
}

exit;
?>