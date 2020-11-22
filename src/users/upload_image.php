<?php
    function uploadImage($first_name, $last_name)
    {
        $BASE_URL = getenv('BASE_URL') ? getenv('BASE_URL') : "http://localhost:8080";
        if (imageIsUploaded()) {
            $user_path = '/images/uploads/' . $first_name . '-' . $last_name . '/';
            $saveDir = dirname(getcwd()) . $user_path;
            if (!file_exists($saveDir)) {
                mkdir($saveDir, 0777, true);
            }
            $path = $saveDir . basename($_FILES['fileToUpload']['name']);

            if (!move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path)) {
                echo "Invalid file!\n";
                die();
            }
            return $BASE_URL . $user_path . $_FILES['fileToUpload']['name'];
        }
        return $BASE_URL.'/images/profile(default).png';
    }


    function imageIsUploaded()
    {
        return file_exists($_FILES['fileToUpload']['tmp_name']) && is_uploaded_file($_FILES['fileToUpload']['tmp_name']);
    }