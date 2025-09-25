<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RememberMeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if ($session->has('user')) return;

        $token = $_COOKIE['remember_token'] ?? null;
        if ($token) {
            $db = \Config\Database::connect();
            $builder = $db->table('user_tokens')
                ->where('token', $token)
                ->where('expired_at >', date('Y-m-d H:i:s'));
            $row = $builder->get()->getRow();

            if ($row) {
                $user = $db->table('users')->where('id_user', $row->id_user)->get()->getRowArray();
                if ($user) {
                    $session->set([
                        'id_user'   => $user['id_user'],
                        'username'  => $user['username'],
                        'full_name' => $user['full_name'],
                        'role'      => $user['role'],
                        'isLoggedIn'=> true
                    ]);
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
