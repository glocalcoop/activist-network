<?php

if (!defined('ABSPATH')) exit;

require_once(GDBBPRESSATTACHMENTS_PATH.'code/attachments/public.php');

function d4p_bba_o($name) {
    global $gdbbpress_attachments;
    return $gdbbpress_attachments->o[$name];
}

?>