<?php

namespace App\Core\Container;

use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \LogicException implements ContainerExceptionInterface
{
}