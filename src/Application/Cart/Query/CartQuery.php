<?php

namespace Dogadamycie\Application\Cart\Query;

interface CartQuery
{
    /**
     * @param string $id
     * @return CartView|null
     */
    public function cartOfId(string $id);

    /**
     * @return mixed
     */
    public function all();
}