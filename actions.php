<?php

require_once('config.php');

$action = optional_param('action');

$errors = [];
if (!post_data_submitted()) {
    $errors['nopost'] = true;
    echo json_encode($errors);
    exit();
}

switch ($action) {
    case 'add':
        $sex = clean_param(optional_param('sex', ''), PARAM_RAW);
        $lastname = clean_param(optional_param('lastname', ''), PARAM_RAW);
        $firstname = clean_param(optional_param('firstname', ''), PARAM_RAW);
        $middlename = clean_param(optional_param('middlename', ''), PARAM_RAW);
        $birth = clean_param(optional_param('birth', ''), PARAM_DATE);
        $color = clean_param(optional_param('color', 'rgb(15, 15, 15)'), PARAM_RAW);
        $skills = clean_param(optional_param('skills', ''), PARAM_RAW);
        $personal = optional_param('personal', '');
        $avatar = get_file('avatar', true);
        $photos = get_file('photos', true);

        if ($sex !== 'm' && $sex !== 'f') {
            $errors['sex'] = true;
        }
        if ($lastname == false) {
            $errors['lastname'] = true;
        }
        if ($birth == false) {
            $errors['birth'] = true;
        }
        if ($skills == false) {
            $errors['skills'] = true;
        }
        $personals = [];
        if (isset($personal['assiduity'])) {
            $personals['assiduity'] = true;
        }
        if (isset($personal['neatness'])) {
            $personals['neatness'] = true;
        }
        if (isset($personal['selflearning'])) {
            $personals['selflearning'] = true;
        }
        if (isset($personal['diligence'])) {
            $personals['diligence'] = true;
        }
        if ($avatar && (!AcImage::isFileImage($avatar['tmp_name']) || filesize($avatar['tmp_name']) / 1024 > 1000)) {
            $errors['avatar'] = true;
        }
        if ($photos && is_array($photos)) {
            foreach ($photos as $photo) {
                if (!AcImage::isFileImage($photo['tmp_name']) || filesize($photo['tmp_name']) / (1024 * 1024) > 5) {
                    $errors['photos'] = true;
                    break;
                }
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
                'personal' => json_encode($personals),
            ));
            if ($avatar) {
                FileManager::add_file($id, 'avatar', $avatar);
            }
            if ($photos && is_array($photos)) {
                $count = 1;
                foreach ($photos as $photo) {
                    FileManager::add_file($id, 'photos', $photo);
                    if (++$count > 5) break;
                }
            }
            echo 'true';
        } else {
            echo json_encode($errors);
        }
        break;
}
