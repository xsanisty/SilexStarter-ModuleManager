<?php

Route::group(
    Config::get('@silexstarter-dashboard.config.admin_prefix'),
    function () {
        Route::resource('/module', 'ModuleController', ['as' => 'modulemanager.module', 'only' => ['index', 'delete', 'store']]);
        Route::post('/module/publish-asset', 'ModuleController:publishAsset', ['as' => 'modulemanager.module.publish-asset']);
        Route::post('/module/publish-config', 'ModuleController:publishConfig', ['as' => 'modulemanager.module.publish-config']);
        Route::post('/module/publish-template', 'ModuleController:publishTemplate', ['as' => 'modulemanager.module.publish-template']);
        Route::post('/module/remove', 'ModuleController:remove', ['as' => 'modulemanager.module.remove']);
        Route::post('/module/migrate', 'ModuleController:migrate', ['as' => 'modulemanager.module.migrate']);
    },
    [
        'before'    => 'admin.auth',
        'namespace' => 'Xsanisty\ModuleManager\Controller',
        'permission'=> 'modulemanager.manage_module'
    ]
);
