<?php

namespace App\Http\Controllers\Frontend\Layout;

use App\Http\Controllers\Frontend\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class HeaderController extends Controller
{
    public function compose(View $view){
        $data['full_name'] = Auth::guard('customer')->user()->full_name;
        $data['email'] = Auth::guard('customer')->user()->email;
        $image = Auth::guard('customer')->user()->image;
        $data['avatar'] = File::isFile(IMAGE_PATH . $image) ? image_fit($image, 40, 40) : no_image(40);

        $view->with($data);
    }
}
