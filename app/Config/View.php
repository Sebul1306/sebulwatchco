<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class View extends BaseConfig
{
    public string $defaultView = '';
    public bool   $saveData    = true;
    public array  $filters     = [];
    public array  $plugins     = [];
    public array  $decorators  = [];
}