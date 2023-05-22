<?php

namespace App\Http\Controllers\Backend\Layout;

use App\Http\Controllers\Backend\Controller;
use Illuminate\View\View;

class FooterController extends Controller
{
    public function compose(View $view){
        load_lang(get_path_resource(__DIR__, __CLASS__));
        $data['copyright'] = sprintf(lang_trans('text_copyright'), date('Y', time()));

        $view->with($data);
    }
}
