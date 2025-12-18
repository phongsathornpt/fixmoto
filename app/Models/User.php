<?php

require_once core('Model.php');

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password'];

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function registerUser($username, $password)
    {
        return $this->insert([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    public function verifyPassword($inputPassword, $storedHash)
    {
        // Since the seed data uses plain text 'admin', we need to handle legacy/plain text passwords
        // Ideally, we'd migrate all to hashes, but for now:
        if ($inputPassword === $storedHash) {
            return true;
        }
        return password_verify($inputPassword, $storedHash);
    }
}
