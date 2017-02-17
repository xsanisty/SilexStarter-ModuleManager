<?php

namespace Xsanisty\ModuleManager\Command;

use Illuminate\Support\Str;
use SilexStarter\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ModuleCreateCommand extends Command
{
    protected $app;
    protected $output;
    protected $input;

    protected function configure()
    {
        $this
            ->setName('module:create')
            ->setDescription('Create a scaffolding of a basic module structure')
            ->addArgument(
                'module-name',
                InputArgument::REQUIRED,
                'The module name, e.g. "awesome-module"'
            )->addOption(
                'module-namespace',
                'm',
                InputOption::VALUE_REQUIRED,
                'The root module namespace, e.g. "AwesomeVendor"'
            )->addOption(
                'module-class',
                'c',
                InputOption::VALUE_REQUIRED,
                'The module provider class name, e.g. "AwesomeModule"'
            )->addOption(
                'module-description',
                'd',
                InputOption::VALUE_REQUIRED,
                'The module description'
            )->addOption(
                'module-author',
                'a',
                InputOption::VALUE_REQUIRED,
                ''
            )->addOption(
                'module-author-email',
                'e',
                InputOption::VALUE_REQUIRED,
                ''
            )->addOption(
                'module-repo',
                'r',
                InputOption::VALUE_REQUIRED,
                'Public repository of this module'
            )->addOption(
                'module-version',
                's',
                InputOption::VALUE_REQUIRED,
                ''
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->app = $app = $this->getSilexStarter();

        $this->output   = $output;
        $this->input    = $input;
        $moduleManager  = $app['module'];
        $moduleName     = $input->getArgument('module-name');

        $moduleNs       = $input->getOption('module-namespace')
                        ? $input->getOption('module-namespace')
                        : Str::studly($moduleName);

        $moduleClass    = $this->input->getOption('module-class')
                        ? $this->input->getOption('module-class')
                        : Str::studly($moduleName . 'Module');

        $this->filesystem = $app['filesystem'];

        $this->generateModuleDirectory($moduleNs);
        $this->generateModuleClass($moduleNs, $moduleClass);
        $this->generateConfigFile($moduleNs);
        $this->generateRouteFile($moduleNs);
        $this->generateMiddlewareFile($moduleNs);

        $this->registerModule($moduleNs, $moduleClass);
    }

    /**
     * Generating necessary directory for the module
     * @param  string $namespace Module root namespace
     * @return void
     */
    protected function generateModuleDirectory($namespace)
    {
        $this->output->writeln('<comment>Generating necessary directory</comment>');

        $requiredDir    = [
            '/',
            '/Model',
            '/Command',
            '/Provider',
            '/Contracts',
            '/Controller',
            '/Repository',
            '/Resources',
            '/Resources',
            '/Resources/views',
            '/Resources/config',
            '/Resources/assets',
            '/Resources/assets/js',
            '/Resources/assets/css',
            '/Resources/assets/images',
            '/Resources/migrations',
            '/Resources/translations',
        ];

        $namespace = str_replace('\\', '/', $namespace);

        foreach ($requiredDir as $dir) {
            $this->output->writeln('<info> - "' . $this->app['path.module'] . $namespace . $dir . '" created successfully</info>');
            $this->filesystem->mkdir($this->app['path.module'] . $namespace . $dir);
        }
    }

    /**
     * Generate module provider class
     * @param  string $namespace   Module base namespace
     * @param  string $moduleClass Module class provider
     * @return void
     */
    protected function generateModuleClass($namespace, $moduleClass)
    {
        $this->output->writeln('<comment>Generating module provider class</comment>');
        $this->app['twig.loader.filesystem']->addPath(__DIR__ . '/../Resources/stubs', 'stubs');

        $moduleName     = $this->input->getArgument('module-name');

        $modulePath     = $this->app['path.module'] . str_replace('\\', '/', $namespace);
        $classPath      = $modulePath . '/' . $moduleClass . '.php';

        $moduleData     = [
            'namespace'         => $namespace,
            'moduleClass'       => $moduleClass,
            'moduleName'        => $moduleName,
            'moduleDescription' => $this->input->getOption('module-description'),
            'authorName'        => $this->input->getOption('module-author'),
            'authorEmail'       => $this->input->getOption('module-author-email'),
            'moduleRepository'  => $this->input->getOption('module-repo'),
            'moduleVersion'     => $this->input->getOption('module-version'),
        ];

        $compiledClass  = $this->app['twig']->render('@stubs/ModuleProvider.stub', $moduleData);

        $this->output->writeln('<info> - Module provider class created at "' . $classPath . '"</info>');
        $this->filesystem->dumpFile($classPath, $compiledClass);

    }

    /**
     * Generate route file for the module
     * @param  string $namespace Module base namespace
     * @return void
     */
    protected function generateRouteFile($namespace)
    {
        $this->output->writeln('<comment>Generating route file</comment>');
        $routePath = $this->app['path.module'] . str_replace('\\', '/', $namespace) . '/Resources/routes.php';

        $this->output->writeln('<info> - Route file created at "' . $routePath . '"</info>');
        $this->filesystem->dumpFile($routePath, "<?php\n\n/** register your route here */\n");
    }

    /**
     * Generate module config file
     * @param  string $namespace Module base namespace
     * @return void
     */
    protected function generateConfigFile($namespace)
    {
        $this->output->writeln('<comment>Generating basic config file</comment>');
        $namespace = str_replace('\\', '/', $namespace);

        foreach(['config', 'services'] as $config) {
            $configPath = $this->app['path.module'] . $namespace . '/Resources/config/' . $config . '.php';

            $this->output->writeln('<info> - Config file created at "' . $configPath . '"</info>');
            $this->filesystem->dumpFile($configPath, "<?php\n\nreturn [];\n");
        }
    }

    /**
     * Generate module middleware file
     * @param  string $namespace Module base namespace
     * @return void
     */
    protected function generateMiddlewareFile($namespace)
    {
        $this->output->writeln('<comment>Generating middleware file</comment>');
        $middlewarePath = $this->app['path.module'] . str_replace('\\', '/', $namespace) . '/Resources/middlewares.php';

        $this->output->writeln('<info> - Middleware file created at "' . $middlewarePath . '"</info>');
        $this->filesystem->dumpFile($middlewarePath, "<?php\n\n/** register your middleware here */\n");
    }

    /**
     * Registering module into main application
     * @param  string $namespace    Module base namespace
     * @param  string $moduleClass  Module provider class
     * @return void
     */
    protected function registerModule($namespace, $moduleClass)
    {
        $this->output->writeln('<comment>Registering new module...</comment>');

        $modules = $this->app['config']->get('modules');

        if (false === array_search($namespace . '\\' . $moduleClass, $modules)) {
            $modules[] = $namespace . '\\' . $moduleClass;
        }

        $this->app['config']->set('modules', $modules);
        $this->app['config']->save('modules');
        $this->output->writeln('<info> - New module registered successfully</info>');
    }
}
