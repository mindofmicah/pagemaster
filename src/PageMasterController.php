<?php
namespace MindOfMicah\PageMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageMasterController extends Controller
{
    public function show(Request $request)
    {
        return view(
            $this->generateViewName(config('pagemaster.route_options.as'), $request->route()->getName())
        );
    }

    /**
     * Looking at the configuration + current route, determine the view file to return
     * @param $name_prefix
     * @param $route_name
     * @return mixed
     */
    private function generateViewName($name_prefix, $route_name)
    {
        return preg_replace('/^' . $name_prefix . '/', config('pagemaster.view_directory') . '.', $route_name);
    }
}
