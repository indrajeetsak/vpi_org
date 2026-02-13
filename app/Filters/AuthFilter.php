<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('auth/login'))->with('error', 'Please login first');
        }

        // Check for admin routes
        if (!empty($arguments) && in_array('admin', $arguments)) {
            if (!$session->get('isAdmin')) {
                return redirect()->to(base_url('dashboard'))->with('error', 'Access denied');
            }
        }

        return true;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
