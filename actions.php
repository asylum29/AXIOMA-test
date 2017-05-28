<?php

require_once('config.php');

$errors = array();
$action = optional_param('action');
switch ($action) {
    case 'add':
        post_required();
        
        $sex = clean_param(optional_param('sex', ''), PARAM_RAW);
        $lastname = clean_param(optional_param('lastname', ''), PARAM_RAW);
        $firstname = clean_param(optional_param('firstname', ''), PARAM_RAW);
        $middlename = clean_param(optional_param('middlename', ''), PARAM_RAW);
        $birth = clean_param(optional_param('birth', ''), PARAM_DATE);
        $color = clean_param(optional_param('color', ''), PARAM_RAW);
        $skills = clean_param(optional_param('skills', ''), PARAM_RAW);
        $personal = optional_param('personal', '');
        $avatar = get_file('avatar', true);
        $photos = array(
            get_file('photo1', true),
            get_file('photo2', true),
            get_file('photo3', true),
            get_file('photo4', true),
            get_file('photo5', true),
        );

        if ($sex !== 'm' && $sex !== 'f') {
            $errors['sex'] = 'required';
        }
        if ($lastname == false) {
            $errors['lastname'] = 'required';
        }
        if ($birth == false) {
            $errors['birth'] = 'required';
        }
        $interval = '([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])';
        $pattern = "/rgb\($interval, $interval, $interval\)/";
        if (!preg_match($pattern, $color)) {
            $errors['color'] = 'nocolor';
        }
        if ($skills == false) {
            $errors['skills'] = 'required';
        }
        if ($avatar) {
            if (!AcImage::isFileImage($avatar['tmp_name'])) {
                $errors['avatar'] = 'noimage';
            } else if ($avatar['size'] / 1024 > 100) {
                $errors['avatar'] = 'filesize';
            }
        }
        foreach ($photos as $photo) {
            if (!$photo) continue;
            if (!AcImage::isFileImage($photo['tmp_name'])) {
                $errors['photos'] = 'noimage';
                break;
            } else if ($photo['size'] / 1024 / 1024 > 1) {
                $errors['photos'] = 'filesize';
                break;
            }
        }

        if (count($errors) === 0) {
            $id = $DB->insert_record('questionnaires', array(
                'sex' => $sex,
                'lastname' => $lastname,
                'firstname' => $firstname,
                'middlename' => $middlename,
                'birth' => $birth,
                'color' => $color,
                'skills' => $skills,
                'assiduity' => (int)isset($personal['assiduity']),
                'neatness' => (int)isset($personal['neatness']),
                'selflearning' => (int)isset($personal['selflearning']),
                'diligence' => (int)isset($personal['diligence'])
            ));
            AcImage::setRewrite(true);
            if ($avatar) {
                $imаge = AcImage::createImage($avatar['tmp_name']);
                $imаge->cropCenter('1pr', '1pr'); // соотношение сторон 1:1
                $imаge->resize(60, 60); // ресайзинг полученного квадрата
                $imаge->save($avatar['tmp_name']);
                FileManager::add_file($id, 'avatar', $avatar);
            }
            foreach ($photos as $photo) {
                if (!$photo) continue;
                $imаge = AcImage::createImage($photo['tmp_name']);
                if ($imаge->getHeight() > $imаge->getWidth()) {
                    $imаge->resizeByHeight(700);
                } else {
                    $imаge->resizeByWidth(600);
                }
                $imаge->save($photo['tmp_name']);
                FileManager::add_file($id, 'photos', $photo);
            }
            echo 'true';
        } else {
            echo json_encode($errors);
        }
        break;
    
    case 'isadmin':
        echo $USER->is_admin() ? 'true' : 'false';
        break;

    case 'auth':
        post_required();

        $password = clean_param(optional_param('password', ''), PARAM_RAW);
        if ($USER->is_admin() || $USER->authorise($password)) {
            echo 'true';
        } else {
            $errors['invalidpassword'] = true;
            echo json_encode($errors);
        }
        break;

    case 'getlist':
        admin_required();

        $params = array();
        $personal = optional_param('personal', '');
        if (isset($personal['assiduity'])) {
            $params['assiduity'] = 1;
        }
        if (isset($personal['neatness'])) {
            $params['neatness'] = 1;
        }
        if (isset($personal['selflearning'])) {
            $params['selflearning'] = 1;
        }
        if (isset($personal['diligence'])) {
            $params['diligence'] = 1;
        }

        $order = optional_param('sort', '');
        switch ($order) {
            case '2':
                $order = 'ORDER BY birth';
                break;
            default:
                $order = 'ORDER BY lastname';
                break;
        }

        $questionnaires = $DB->get_records('questionnaires', $params, $order);
        echo json_encode($questionnaires);
        break;

    case 'getelement':
        admin_required();

        $id = optional_param('id');
        $record = $DB->get_record('questionnaires', array('id' => $id));
        if ($record) {
            $avatar = FileManager::get_file_by_filearea($id, 'avatar');
            $photos = FileManager::get_files_in_filearea($id, 'photos');

            if ($record['assiduity'] == 0) {
                unset($record['assiduity']);
            }
            if ($record['neatness'] == 0) {
                unset($record['neatness']);
            }
            if ($record['selflearning'] == 0) {
                unset($record['selflearning']);
            }
            if ($record['diligence'] == 0) {
                unset($record['diligence']);
            }

            $element = array();
            $element['questionnaire'] = $record;
            if ($avatar) {
                $element['avatar'] = $avatar;
            }
            if (count($photos) > 0) {
                $element['photos'] = $photos;
            }

            echo json_encode($element);
        } else {
            echo 'false';
        }
        break;

    case 'getfile':
        admin_required();

        $id = optional_param('id');
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
            echo file_get_contents($path);
        };
        break;
}

function post_required() {
    if (!post_data_submitted()) {
        echo json_encode(array('nopost' => true));
        exit();
    }
}

function admin_required() {
    global $USER;
    if (!$USER->is_admin()) {
        echo json_encode(array('noauth' => true));
        exit();
    }
}
