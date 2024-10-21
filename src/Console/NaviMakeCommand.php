<?php

namespace Log1x\Navi\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class NaviMakeCommand extends GeneratorCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Navi component';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'navi:make';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        $component = Str::of($this->argument('name'))
            ->lower()
            ->trim();

        $default = $this->option('default') ?? 'primary_navigation';

        $locations = collect(get_registered_nav_menus())
            ->take(5)
            ->map(fn ($name, $slug) => $slug === $default
                ? "{$name}: <fg=blue><x-{$component} /></>"
                : "{$name}: <fg=blue><x-{$component} name=\"{$slug}\" /></>"
            );

        $this->components->info("Navi component <fg=blue><x-{$component} /></> is ready for use.");

        if ($locations->isEmpty()) {
            $this->components->warn('Your theme does not appear to have any registered navigation menu locations.');

            return;
        }

        $this->components->bulletList($locations->all());
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $contents = parent::buildClass($name);

        return str_replace(
            '{{ default }}',
            $this->option('default') ? Str::wrap($this->option('default'), "'") : 'null',
            $contents,
        );
    }

    /**
     * Get the destination view path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.'.$this->getView()).'.blade.php'
        );

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Get the view name relative to the components directory.
     *
     * @return string
     */
    protected function getView()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(fn ($part) => Str::kebab($part))
            ->implode('.');
    }

    /**
     * Get the desired view name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        $name = str_replace(['\\', '.'], '/', $this->argument('name'));

        return $name;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->resolveStubPath(
            '/stubs/view.stub',
        );
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__.$stub;
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => [
                'What should the Navi component be named?',
                'E.g. Navigation',
            ],
        ];
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['default', 'd', InputOption::VALUE_OPTIONAL, 'The default menu name'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the view component even if the component already exists'],
        ];
    }
}
