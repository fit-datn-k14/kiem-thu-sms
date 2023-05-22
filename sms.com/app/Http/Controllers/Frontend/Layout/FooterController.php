<?php

namespace App\Http\Controllers\Frontend\Layout;

use App\Http\Controllers\Frontend\Controller;
use Illuminate\View\View;

class FooterController extends Controller
{
    public function compose(View $view){
        load_lang(get_path_resource(__DIR__, __CLASS__));
        $data['copyright'] = sprintf(lang_trans('text_copyright'), date('Y', time()));

        $view->with($data);
    }
}
