<?php

require_once('config.php');

$errors = [];
if (!post_data_submitted()) {
    $errors['nopost'] = true;
    echo json_encode($errors);
    exit();
}

$action = optional_param('action');
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
            } else if ($photo['size'] / 1024 / 1024 > 5) {
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
                'personal' => json_encode($personals),
            ));
            if ($avatar) {
                FileManager::add_file($id, 'avatar', $avatar);
            }
            foreach ($photos as $photo) {
                if (!$photo) continue;
                FileManager::add_file($id, 'photos', $photo);
            }
            echo 'true';
        } else {
            echo json_encode($errors);
        }
        break;
}
