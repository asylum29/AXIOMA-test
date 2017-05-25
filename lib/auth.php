<?php

defined('APP_INTERNAL') || die;

class User {

    private $admin;
        
    public function __construct() {
        if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
            $this->admin = true;
        }
    }
    
    public function authorise($password) {
        global $CONFIG;
        if (strcmp($password, $CONFIG->password) == 0) {
            $this->admin = true;
            $_SESSION['admin'] = true;
            return true;
        }
        return false;
    }

    public function logout() {
        $this->admin = false;
        unset($_SESSION['admin']);
    }

    public function is_admin() {
        return $this->admin;
    }

}
