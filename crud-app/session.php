<?php
session_start();

/*function set_flash_message($type, $message) {
    $_SESSION['flash'][$type] = $message;
}*/

function get_flash_message($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]); // Eliminamos el mensaje después de mostrarlo
        return $message;
    }
    return '';
}
?>
