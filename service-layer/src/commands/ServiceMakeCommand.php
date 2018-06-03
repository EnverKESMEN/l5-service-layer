<?php
namespace KesmenEnver\ServiceLayer\Commands;
use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class ServiceMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:service';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';
    /**
     * Get the class name from name input.
     *
     * @return string
     */

    /**
     * Execute the console command.
     *
     * @return bool|null
     */
    public function handle()
    {
        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);
        $interfacePath = $this->getInterfacePath($name);
        // First we will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((! $this->hasOption('force') ||
                ! $this->option('force')) &&
            $this->alreadyExists($this->getNameInput())) {
            $this->error($this->type.' already exists!');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);
        $this->makeDirectory($interfacePath);
        $this->files->put($path, $this->buildClass($name));
        $this->files->put($interfacePath, $this->buildInterface($name));

        $this->info($this->type.' Interface created successfully.');
        $this->info($this->type.' created successfully.');

    }

    protected function getClassName()
    {
        return ucwords(camel_case($this->getNameInput())) . 'Service';
    }

    protected function getInterfaceName()
    {
        return ucwords(camel_case($this->getNameInput())) . 'ServiceInterface';
    }
    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/service.stub';
    }

    /**
     * Get the Interface stub file for the generator.
     *
     * @return string
     */
    protected function getInterfaceStub()
    {
        return __DIR__ . '/../stubs/serviceInterface.stub';
    }
    /**
     * Build the class with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name = null)
    {
        $stub = $this->files->get($this->getStub());
        return $this->replaceClass($stub, $this->getClassName());
    }

    /**
     * Build the interface with the given name.
     *
     * @param  string $name
     * @return string
     */
    protected function buildInterface($name = null)
    {
        $stub = $this->files->get($this->getInterfaceStub());
        return $this->replaceClass($stub, $this->getClassName());
    }
    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);
        $stub = str_replace('{{class}}', $class, $stub);
        $stub = str_replace('{{interface}}', $this->getInterfaceName(), $stub);
        return $stub;
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return base_path() . '/app/Services/' . str_replace('\\', '/', $name.'Service') . '.php';
    }

    /**
     * Get the destination class path.
     *
     * @param  string $name
     * @return string
     */
    protected function getInterfacePath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        return base_path() . '/app/Services/Contracts/' . str_replace('\\', '/', $name.'ServiceInterface') . '.php';
    }
}