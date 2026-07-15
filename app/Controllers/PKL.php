<?php

namespace App\Controllers;

class PKL extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Program PKL',
        ];

        return view('pkl/index', $data);
    }
}
