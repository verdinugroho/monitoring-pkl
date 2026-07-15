<?php

namespace App\Controllers;

class Penilaian extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Penilaian',
        ];

        return view('penilaian/index', $data);
    }
}
