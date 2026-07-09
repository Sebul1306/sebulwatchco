<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Feature extends BaseConfig
{
    public bool $multipleFilters       = false;
    public bool $limitCallMakeOnce     = false;
    public bool $autoRoutesImproved    = false;
    public bool $DBQueryBuilderGroupBy = false;
}
