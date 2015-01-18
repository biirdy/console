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
    }
}

exit;
?>