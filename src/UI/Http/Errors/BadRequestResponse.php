<?php

namespace Dogadamycie\UI\Http\Errors;

/**
 * Class BadRequestResponse
 * @package Dogadamycie\UI\Http\Errors
 */
class BadRequestResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code = 400;

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $message;

    /**
     * BadRequestResponse constructor.
     * @param string $message
     * @param array $data
     */
    public function __construct(string $message, array $data)
    {
        $this->data = $data;
        $this->message = $message;
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