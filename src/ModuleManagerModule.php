<?php

/**
 * This is user module, also works as sample module to show you how develop module as composer package
 */
namespace Xsanisty\ModuleManager;

use Silex\Application;
use SilexStarter\Module\ModuleInfo;
use SilexStarter\Module\ModuleResource;
use SilexStarter\Contracts\ModuleProviderInterface;

class ModuleManagerModule implements ModuleProviderInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getInfo()
    {
        return new ModuleInfo(
            [
                'name'          => 'SilexStarter Module Manager',
                'author_name'   => 'Xsanisty Development Team',
                'author_email'  => 'developers@xsanisty.com',
                'repository'    => 'https://github.com/xsanisty/SilexStarter-ModuleManager',
            ]
        );
    }

    public function getModuleIdentifier()
    {
        return 'silexstarter-modulemanager';
    }

    public function getRequiredModules()
    {
        return [];
    }

    public function getResources()
    {
        return new ModuleResource(
            [
                'routes'        => 'Resources/routes.php',
                'views'         => 'Resources/views',
                'controllers'   => 'Controller',
                'commands'      => 'Command',
            ]
        );
    }

    public function register()
    {
    }

    public function boot()
    {
        $this->registerSidebarMenu();
    }

    protected function registerSidebarMenu()
    {
        $menu   = Menu::get('admin_sidebar')->createItem(
            'module-manager',
            [
                'icon'  => 'cubes',
                'label' => 'Manage Modules',
                'url'   => '#'
            ]
        );
    }
}
