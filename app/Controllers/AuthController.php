<?php

require_once core('Controller.php');
require_once model('User.php');

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(): void
    {
        // If already logged in, redirect to home
        if ($this->isAuthenticated()) {
            $this->redirect("/home");
        }

        $this->view("auth/login");
    }

    public function authenticate(): void
    {
        $username = $this->getPost("username");
        $password = $this->getPost("password");

        if (!$username || !$password) {
            $this->view("auth/login", ["error" => "กรุณากรอกข้อมูลให้ครบถ้วน"]);
            return;
        }

        try {
            $user = $this->userModel->findByUsername($username);

            if (
                $user &&
                $this->userModel->verifyPassword($password, $user["password"])
            ) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["status"] = "login";
                $this->redirect("/home");
            } else {
                $this->view("auth/login", [
                    "error" => "รหัสผ่านผิดพลาด หรือ ไม่พบผู้ใช้งานนี้",
                ]);
            }
        } catch (Exception $e) {
            $this->view("auth/login", [
                "error" => "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล",
            ]);
        }
    }

    public function logout(): void
    {
        session_destroy();
        $this->redirect("/");
    }
}
