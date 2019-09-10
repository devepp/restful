<?php

namespace App\Core\Container;

use Exception;
use Psr\Container\ContainerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use ReflectionClass;
use RegexIterator;

class Container implements ContainerInterface
{
    protected $entries = [];
    protected $fqcns = [];

    public function __construct($arr)
    {
        $this->entries = $arr;
    }

	/**
	 * @param string $id
	 * @return mixed|object
	 * @throws Exception
	 */
    public function get($id)
    {
        if ($this->has($id)) {
            try {
                $entry = $this->entries[$id];
                return $entry($this);
            } catch (Exception $e) {
                throw new ContainerException($e->getMessage());
            }
        } else {
        		return $this->reflectOnMagic($id);
        }
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->entries);
    }

    /**
     * @param $id
     * @return object
     * @throws Exception
     */
    private function reflectOnMagic($id)
    {
        $this->loadFqcns();
        if (in_array($id, $this->fqcns)) {
            return $this->makeClass($id);
        }

     throw new NotFoundException("Could not find " . $id);
    }

    private function makeClass($fqcn)
    {
        $reflectedClass = new ReflectionClass($fqcn);
        $reflectedConstructor = $reflectedClass->getConstructor();
        if (!$reflectedConstructor) {
            return new $fqcn;
        }
        $reflectedParameters = $reflectedConstructor->getParameters();

        $params = [];
        foreach ($reflectedParameters as $parameter) {
            if ($parameter->hasType()) {
                $parameterTypeName = $parameter->getType()->getName();
                $params[] = $this->get($parameterTypeName);
            } else {
                throw new Exception('Failed to construct '.$fqcn.'; missing '.$parameter);
            }
        }
        return $reflectedClass->newInstanceArgs($params);
    }

    private function loadFqcns()
    {

        if (!empty($this->fqcn)) {
            return;
        }
        $directory = new RecursiveDirectoryIterator('..\src');
        $iterator = new RecursiveIteratorIterator($directory);
        $regex = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

        foreach ($regex as $filepath) {
            $this->fqcns[] = str_replace(['..\src', '.php'], ['App', ''], reset($filepath));
        }
    }
}