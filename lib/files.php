<?php

defined('APP_INTERNAL') || die;

class FileManager
{

    public static function add_file($entity_id, $filearea, $file)
    {
        global $DB;
        $args = array(
            'entity'   => $entity_id,
            'filearea' => $filearea,
            'filename' => $file['name'],
        );
        $file_id = $DB->insert_record('files', $args);
        $path = self::get_file_path($file_id);
        if (!move_uploaded_file($file['tmp_name'], $path)) {
            die("Upload error");
        }
    }

    public static function get_file_by_filearea($entity_id, $filearea)
    {
        global $DB;
        $args = array(
            'entity'   => $entity_id,
            'filearea' => $filearea
        );
        return $DB->get_record('files', $args);
    }

    public static function get_files_in_filearea($entity_id, $filearea)
    {
        global $DB;
        $args = array(
            'entity'   => $entity_id,
            'filearea' => $filearea
        );
        return $DB->get_records('files', $args);
    }

    public static function get_file_by_id($id)
    {
        global $DB;
        return $DB->get_record('files', array('id' => $id));
    }

    public static function delete_file_by_id($id)
    {
        global $DB;
        $path = self::get_file_path($id);
        if (!unlink($path)) {
            die("Delete error");
        }
        $DB->delete_record('files', $id);
    }

    public static function delete_filearea($entity_id, $filearea)
    {
        $files = self::get_files_in_filearea($entity_id,  $filearea);
        foreach ($files as $file) {
            self::delete_file_by_id($file['id']);
        }
    }

    public static function delete_entity_files($entity_id)
    {
        global $DB;
        $records = $DB->get_records('files', array('entity' => $entity_id));
        foreach ($records as $record) {
            self::delete_file_by_id($record['id']);
        }
    }

    public static function get_file_path($id)
    {
        global $CONFIG;
        $a = $id % 100;
        $a1 = floor($id / 100);
        $b = $a1 % 100;
        $dirpath = "{$CONFIG->dataroot}/$a/$b";
        if (!file_exists($dirpath)) {
            mkdir($dirpath, 777, true);
        }
        return "$dirpath/$id";
    }

}
