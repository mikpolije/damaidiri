<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentationApiController extends Controller
{
    private $param;

    public function data_api_list(): Array
    {
        return [
            ['id' => 1, 'name' => 'API Akses Masuk', 'type' => 'POST', 'endpoint' => '/login'],
            ['id' => 2, 'name' => 'API Registrasi', 'type' => 'POST', 'endpoint' => '/register'],
            ['id' => 3, 'name' => 'API Keluar Sistem', 'type' => 'POST', 'endpoint' => '/logout'],
            ['id' => 4, 'name' => 'API Rincian Profil', 'type' => 'GET', 'endpoint' => '/my-profile'],
            ['id' => 5, 'name' => 'API Pembaruan Akun', 'type' => 'PUT', 'endpoint' => '/update-account'],
            ['id' => 6, 'name' => 'API Pembaruan Profil', 'type' => 'PUT', 'endpoint' => '/update-profile'],
            ['id' => 7, 'name' => 'API Pembaruan Kata Sandi', 'type' => 'PUT', 'endpoint' => '/update-password'],
        ];
    }

    public function list(Request $request) : View
    {
        $this->param['data_api'] = $this->data_api_list();

        return view('pages.documentation-api.list', $this->param);
    }
}
