<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $fileController = new FileController();
        $files = $fileController->getFiles();

        $categories= [];

        foreach($files as $file) {
            if(!isset($categories[$file->meta_data['content_type']])) {
                $categories[$file->meta_data['content_type']] = $file->meta_data['content_type'];
            }
        }

        return view('home',
            [
                'files' => $files,
                'categories' => $categories,
            ]
        );
    }
}
