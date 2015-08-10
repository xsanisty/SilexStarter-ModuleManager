<?php

/**
 * This is user module, also works as sample module to show you how develop module as composer package
 */
namespace Xsanisty\ModuleManager;

use Silex\Application;
use SilexStarter\Module\ModuleInfo;
use SilexStarter\Module\ModuleResource;
use SilexStarter\Contracts\ModuleProviderInterface;
use Xsanisty\Admin\DashboardModule;

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
                'assets'        => 'Resources/assets',
                'views'         => 'Resources/views',
                'controllers'   => 'Controller',
                'commands'      => 'Command',
            ]
        );
    }

    public function register()
    {
        $provider = $this;

        $this->app['dispatcher']->addListener(
            DashboardModule::INIT,
            function () use ($provider) {
                $provider->registerSidebarMenu();
            }
        );
    }

    public function boot()
    {
    }

    protected function registerSidebarMenu()
    {
        $menu   = Menu::get('admin_sidebar')->createItem(
            'module-manager',
            [
                'icon'      => 'cubes',
                'label'     => 'Module',
                'url'       => '#',
                'permission'=> ['modulemanager.manage_module']
            ]
        );

        $menu->addChildren(
            'manage-module',
            [
                'icon'  => 'cubes',
                'label' => 'Manage Module',
                'url'   => Url::to('modulemanager.module.index'),
                'permission'=> 'modulemanager.manage_module'
            ]
        );
    }
}
