<?php
namespace MindOfMicah\PageMaster;

use Exception;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class PageMasterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/pagemaster.php' => config_path('pagemaster.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pagemaster.php', 'pagemaster');

        $this->loadRoutes();
    }

    private function loadRoutes()
    {
        $excluded_views = config('pagemaster.exclude', []);
        $this->app['router']->group($this->getRouteOptions(), function () use ($excluded_views){
            foreach ($this->getFiles() as $file) {
                $slashed = preg_replace('/\.blade\.php/', '', $file->getRelativePathname());
                $dotted_name = strtr($slashed, '/', '.');

                if (in_array($dotted_name, $excluded_views)) {
                    continue;
                }

                $this->app['router']->get($slashed, 'MindOfMicah\PageMaster\PageMasterController@show')
                    ->name($dotted_name);
            }
        });
    }

    /**
     * @return []
     */
    private function getRouteOptions()
    {
        return array_reduce(['middleware', 'as', 'prefix'], function ($carry, $key) {
            $value = config('pagemaster.' . $key);
            if ($value) {
                $carry[$key] = $value;
            }

            return $carry;
        }, []);
    }

    /**
     * @return Finder|SplFileInfo[]
     */
    private function getFiles()
    {
        $finder = (new Finder)->files()->ignoreDotFiles(true)->name('*.blade.php');

        foreach (config('view.paths') as $path) {
            try {
                $finder->in($path . '/' . config('pagemaster.view_directory'));
            } catch (Exception $e) {
            }
        }

        return $finder;
    }
}
