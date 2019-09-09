<?php

namespace Dogadamycie\Application\Cart\Commands;

/**
 * Class DeleteCartCommand
 * @package Dogadamycie\Application\Cart\Commands
 */
class DeleteCartCommand
{
    /**
     * @var string|null
     */
    private $id;

    /**
     * DeleteCartCommand constructor.
     * @param $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }
}