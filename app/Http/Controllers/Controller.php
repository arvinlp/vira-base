<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

abstract class Controller
{
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    //
    protected function getView($view, $data = [])
    {
        return Inertia::render("{$view}", $data);
    }
    protected function getViewStaff($view, $data = [])
    {
        return Inertia::render("Staff/{$view}", $data);
    }
    protected function getViewClient($view, $data = [])
    {
        return Inertia::render("Client/{$view}", $data);
    }
}
