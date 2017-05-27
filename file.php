<?php

require_once('config.php');

if (!$USER->is_admin()) exit();

$id = required_param('id');
if ($file = FileManager::get_file_by_id($id)) {
    $path = FileManager::get_file_path($id);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header("Content-Disposition: attachment; filename=\"{$file['filename']}\"");
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    readfile($path);
};
