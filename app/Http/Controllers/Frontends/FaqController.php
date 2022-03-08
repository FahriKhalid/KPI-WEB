<?php

namespace App\Http\Controllers\Frontends;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    public function index()
    {
        return view('frontends.faq.index');
    }
}
