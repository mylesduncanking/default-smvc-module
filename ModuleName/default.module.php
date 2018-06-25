<?php

Use Helpers\Hooks;

$curDir    = explode('/', __DIR__);
$moduleDir = $curDir[count($curDir) - 1];

Hooks::addHook('routes', 'Modules\\'.$moduleDir.'\Routes@routes');
