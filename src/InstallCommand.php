<?php
namespace MindOfMicah\PageMaster;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagemaster:install {view_dir?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the configuration files and make sure that the view directory is created';
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $new_directory = $this->argument('view_dir') ? : config('pagemaster.view_directory');

        $view_exists = false;
        $view_directory = '';

        foreach (array_reverse(config('view.paths')) as $view_dir) {
            $view_directory = "$view_dir/$new_directory";
            if (file_exists($view_directory)) {
                $this->line("$new_directory exists");
                $view_exists = true;
                break;
            }
        }

        // We don't have an existing directory, so create a new one
        if (!$view_exists) {
            mkdir($view_directory);
            $this->line("Created directory $view_directory");
        }

        $this->handleConfiguration($new_directory);
    }

    /**
     * @param $dir
     */
    private function handleConfiguration($dir)
    {
        // Move the config file to the config directory
        $this->call('vendor:publish', ['--provider' => PageMasterServiceProvider::class]);

        $this->setConfigOption('view_directory', $dir);
    }

    /**
     * @param $option_name
     * @param $new_value
     */
    private function setConfigOption($option_name, $new_value)
    {
        $config = $this->filesystem->get(config_path('pagemaster.php'));
        $config = preg_replace('/(\'' . $option_name . '\'\s*=>\s*\')[^\']+/', "$1$new_value", $config);
        $this->filesystem->put(config_path('pagemaster.php'), $config);
    }
}
