<?php

namespace Dogadamycie\UI\Http\Errors;

/**
 * Class NotFoundResponse
 * @package Dogadamycie\UI\Http\Errors
 */
class NotFoundResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code = 404;

    /**
     * @var string
     */
    private $message;

    /**
     * @var array
     */
    private $data = [];

    /**
     * NotFoundResponse constructor.
     * @param string $message
     * @param array $data
     */
    public function __construct(string $message, array $data)
    {
        $this->message = $message;
        $this->data = $data;
    }

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
                'data' => $this->data
            ]
        ];
    }
}