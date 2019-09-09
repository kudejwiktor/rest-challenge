<?php

namespace Dogadamycie\Application\Product\Query;

interface ProductQuery
{
    /**
     * @param string $id
     * @return Product
     */
    public function productOfId(string $id);

    /**
     * @return Product[]
     */
    public function all();
}
