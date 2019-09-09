<?php

namespace Dogadamycie\UI\Http\Errors;

/**
 * Class InternalServerErrorResponse
 * @package Dogadamycie\UI\Http\Errors
 */
class InternalServerErrorResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code = 500;

    /**
     * @var string
     */
    private $message = 'Internal Server Error';

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'error' => [
                'code' => $this->code,
                'message' => $this->message,
            ]
        ];
    }
}