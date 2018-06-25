<?php

namespace Modules\ModuleName;

use Core\Router;

class Routes
{
    public function routes()
    {
        $curDir         = explode('/', __DIR__);
        $moduleDir      = $curDir[count($curDir) - 1];
        $controllersDir = 'Modules\\'.$moduleDir.'\Controllers\\';

        // Router::get('ROUTE', $controllersDir.'CONTROLLER@METHOD');
    }
}
