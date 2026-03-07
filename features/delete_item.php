<?php
// Legacy compatibility shim. Public requests should use /public/delete_item.php.
$query_string = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== ''
    ? '?' . $_SERVER['QUERY_STRING']
    : '';

header('Location: ../public/delete_item.php' . $query_string);
exit;
