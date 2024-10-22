<?php

namespace App\Controllers;

use App\Models\Users;

use CodeIgniter\Controller;

class Home extends BaseController
{
    public function dashboard()
    {
        $session = \Config\Services::session();

        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access the dashboard.');
        }

        $model = new Users();
        $userId = $session->get('user_id');
        $userData = $model->find($userId);
        return view('user/dashboard', ['user' => $userData]);
    }

    public function img()
    {
        $imageName = $this->request->getGet('img');
        if (empty($imageName) || preg_match('/\.\./', $imageName)) {
            return $this->response->setStatusCode(400, 'Invalid image name.');
        }
        $imagePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $imageName;
        if (file_exists($imagePath)) {
            header('Content-Type: ' . mime_content_type($imagePath));
            header('Content-Length: ' . filesize($imagePath));
            ob_clean();
            flush();
            readfile($imagePath);
            exit;
        } else {
            return $this->response->setStatusCode(404, 'Image not found.');
        }
    }





    public function update()
    {
        $session = \Config\Services::session();
    
        if (!$session->has('user_id')) {
            return redirect()->to('/login')->with('error', 'You must be logged in to access the dashboard.');
        }
    
        $model = new Users();
        $userId = $session->get('user_id');
        $validation = \Config\Services::validation();
  
        $validation->setRules([
            'name' => 'required|min_length[3]|max_length[50]',
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email,id,' . $userId . ']', 
                'errors' => [
                    'is_unique' => 'This email is already in use.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
            ],
        ]);
      
      
            $userdata = $model->find($userId);
            $file = $this->request->getFile('image');
            $imageName = $userdata['image'];
           
            if ($file && $file->isValid()) {
                $imageName = $file->getRandomName();
                if ($file->move(WRITEPATH . 'uploads/users', $imageName)) {
                    $this->imgunlink($userdata['image']);
                } else {
                    return redirect()->back()->with('error', 'Failed to upload image. Please try again.');
                }
            }
           
            $data = [
                'name'  => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'image' => $imageName,
            ];
    
            try {
                if ($model->update($userId, $data)) {
                    return redirect()->back()->with('success', 'Profile updated successfully!');
                } else {
                    return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
            }
        } 
    
    
    

    public function imgunlink($imageName)
    {
        $imagePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'users' . DIRECTORY_SEPARATOR . $imageName;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } 
    }



    public function updatePassword()
    {
        $session = session();
        $model = new Users();
        
        $validation =  \Config\Services::validation();
        $validation->setRules([
            'current_password' => 'required',
            'new_password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $userId = $session->get('user_id'); 
        $user = $model->find($userId);
        
        if (!password_verify($this->request->getPost('current_password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Current password is incorrect.');
        }

        $newPasswordHash = password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT);

        $model->update($userId, ['password' => $newPasswordHash]);

        $session->setFlashdata('message', 'Password updated successfully.');

        return redirect()->back(); 
    }
}

