<?php

namespace Dogadamycie\Application\Product\Commands;

/**
 * Class DeleteProductCommand
 * @package Dogadamycie\Application\Product\Commands
 */
class DeleteProductCommand
{
    /**
     * @var null
     */
    private $id;

    /**
     * DeleteProductCommand constructor.
     * @param null $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }
}