<?php

namespace App\Controllers;

class PageController extends BaseController
{
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        return view('login');
    }

   /* public function doLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $client = \Config\Services::curlrequest();

        $response = $client->post(base_url('api/login'), [
            'json' => [
                'email' => $email,
                'password' => $password
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        if (isset($result['success']) && $result['success'] == true) {
            session()->set([
                'isLoggedIn' => true,
                'user' => $result['data']
            ]);
            return redirect()->to('/dashboard');
        } else {
            return redirect()->back()->with('error', $result['message'] ?? 'Login gagal');
        }
    }*/
}
