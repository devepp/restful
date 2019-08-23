<?php

namespace App\Core\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected $entries = [];
    protected $fqcns = [];

    public function __construct($arr)
    {
        $this->entries = $arr;
    }

     /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for **this** identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        if ($this->has($id)) {
            try {
                $entry = $this->entries[$id];
                return $entry($this);
            } catch (\Exception $e) {
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
        // return true;
        return array_key_exists($id, $this->entries);
    }

	/**
	 * Try to find the entry using reflection before throwing error
	 *
	 * @param string $id Identifier of the entry to look for.
	 *
	 * @throws NotFoundException  No entry was found for **this** identifier.
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
		 $reflectedClass = new \ReflectionClass($fqcn);
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
		 		throw new \Exception('Failed to construct '.$fqcn.'; missing '.$parameter);
			}
		 }
		 return $reflectedClass->newInstanceArgs($params);
	 }

	 private function loadFqcns()
	 {
		 if (!empty($this->fqcn)) {
		 	return;
		 }
	 	$directory = new \RecursiveDirectoryIterator('..\src');
		 $iterator = new \RecursiveIteratorIterator($directory);
		 $regex = new \RegexIterator($iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

		 foreach ($regex as $filepath) {
			 $this->fqcns[] = str_replace(['..\src', '.php'], ['App', ''], reset($filepath));
		 }
	 }
}