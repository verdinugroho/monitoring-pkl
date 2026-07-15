<?php

namespace App\Controllers;

class Perusahaan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Data Perusahaan',
        ];

        return view('perusahaan/index', $data);
    }
}
