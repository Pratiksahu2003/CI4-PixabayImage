<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $allowedFields    = ['id','name', 'email', 'image','password','status','created_at','updated_at']; 

    public function login($email, $password) {
        $user = $this->where('email', $email)->first(); 
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false; 
    }
}
