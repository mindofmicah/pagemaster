<?php
namespace MindOfMicah\PageMaster;

trait ViewResolverTrait
{
    protected function view()
    {
        return view($this->generateViewName(config('pagemaster.route_options.as'), app('request')->route()->getName()));
    }

    /**
     * Looking at the configuration + current route, determine the view file to return
     * @param $name_prefix
     * @param $route_name
     * @return mixed
     */
    protected function generateViewName($name_prefix, $route_name)
    {
        return preg_replace('/^' . $name_prefix . '/', config('pagemaster.view_directory') . '.', $route_name);
    }
}