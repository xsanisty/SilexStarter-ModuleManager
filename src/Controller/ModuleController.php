<?php

namespace Xsanisty\ModuleManager\Controller;

use Exception;
use SilexStarter\Module\ModuleManager;
use SilexStarter\Controller\DispatcherAwareController;
use Xsanisty\Admin\DashboardModule;

class ModuleController extends DispatcherAwareController
{
    protected $module;

    public function __construct(ModuleManager $module)
    {
        $this->module = $module;
    }

    public function index()
    {
        $this->getDispatcher()->dispatch(DashboardModule::INIT);

        Menu::get('admin_sidebar')->setActive('module-manager.manage-module');

        return Response::view('@silexstarter-modulemanager/module/index', ['modules' => $this->module->getRegisteredModules()]);
    }

    public function publishAsset()
    {
        return $this->publish('asset', Request::get('module'));
    }

    public function publishConfig()
    {
        return $this->publish('config', Request::get('module'));
    }

    public function publishTemplate()
    {
        return $this->publish('template', Request::get('module'));
    }

    protected function publish($what, $module)
    {
        try {
            switch ($what) {
                case 'asset':
                    $this->module->publishAsset($module);
                    break;
                case 'config':
                    $this->module->publishConfig($module);
                    break;
                case 'template':
                    $this->module->publishTemplate($module);
                    break;
            }

            return Response::ajax($what. ' for module '. $module . ' is published');

        } catch (Exception $e) {
            return Response::ajax(
                'Can not publish ' . $what . ' of module '. $module,
                500,
                false,
                [
                    'message'   => $e->getMessage(),
                    'code'      => $e->getCode()
                ]
            );
        }
    }
}
