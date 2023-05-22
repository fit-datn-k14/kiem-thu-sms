<?php

namespace App\Http\Controllers\Backend\Layout;

use App\Http\Controllers\Backend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HeaderController extends Controller
{
    public function compose(View $view){
        $data['full_name'] = Auth::user()->full_name;
        $view->with($data);
    }
}
