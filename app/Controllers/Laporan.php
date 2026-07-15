<?php

namespace App\Controllers;

class Laporan extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Laporan PKL',
        ];

        return view('laporan/index', $data);
    }
}
