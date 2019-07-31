<?php

namespace App\Core\Container;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    protected $entries = [];

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
                // var_dump($this->entries);
                // die('get');
                return $entry($this);
            } catch (\Exception $e) {
                throw new ContainerException();
            }
        } else {
            throw new NotFoundException();
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
}