<?php

namespace App\Core\Container;


class ContainerBuilder
{
    private $coreFactories = [];

    public function build()
    {
        return new Container($this->getMergedFactories());
    }

    private function getMergedFactories()
    {
        $factories = [];
        require_once('../config/container_factories.php');
        return array_merge($this->coreFactories, $factories);
    }
}