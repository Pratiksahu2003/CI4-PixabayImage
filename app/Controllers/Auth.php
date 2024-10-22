<?php

namespace App\Controllers;

use App\Models\Users;
use CodeIgniter\Controller;

class Auth extends BaseController
{

    public $session;
    public function __construct()
    {
        helper(['url']);
        $this->session = \Config\Services::session(); // Manually load session
    }
    public function registration()
    {
        return view('Auth/register');
    }

    public function login()
    {
        return view('Auth/login');
    }

    public function register() {
     
        $model = new Users();
            $validation = \Config\Services::validation();
            $validation->setRules([
                'name'      => 'required|min_length[3]',
                'email'     => 'required|valid_email|is_unique[users.email]',
                'password'  => 'required|min_length[6]',
                'cpassword' => 'required|matches[password]',  
                'image'     => 'uploaded[image]|is_image[image]|max_size[image,2048]|ext_in[image,jpg,jpeg,png]'
            ]);
    
           
            if ($validation->withRequest($this->request)->run()) {
               
                $file = $this->request->getFile('image');
                $imageName = '';
    
                if ($file && $file->isValid()) {
                    $imageName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads/users', $imageName);
                }
    
                
                $data = [
                    'name'     => $this->request->getPost('name'),
                    'email'    => $this->request->getPost('email'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
                    'image'    => $imageName, 
                ];
                try {
                    if ($model->insert($data)) {
                        return redirect()->back()->with('success', 'Registration successful!');
                    } else {
                        return redirect()->back()->with('error', 'Failed to register. Please try again.');
                    }
                } catch (\Exception $e) {
                    log_message('error', $e->getMessage());
                    return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
                }
            } else {
                
              return redirect()->back()->with('error',$validation->getErrors());
            }
        
    
       
        return view('register', isset($data) ? $data : []);
    }

    public function authLogin()
    {
        $model = new Users();
        
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        if (empty($email) || empty($password)) {
            return redirect()->to('login')->with('error', 'Email and password are required.');
        }
    
        $data = $model->login($email, $password);
    
        if ($data) {
            if (! $this->session->has('user_id')) {
                $this->session->start();
            }
    
            $this->session->set('user_id', $data['id']);
            $this->session->set('user_name', $data['name']);
    
            return redirect()->to('user/dashboard');
        } else {
            return redirect()->to('login')->with('error', 'Invalid email or password.');
        }
    }
    public function logout()
    {
        $this->session = session();
        $this->session->destroy();
        $this->session->setFlashdata('message', 'You have successfully logged out.');
        return redirect()->to('/login');
    }

    
    
        
    }

