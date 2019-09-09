<?php


namespace Dogadamycie\UI\Http\Errors;


class MethodNotAllowedResponse implements \JsonSerializable
{
    /**
     * @var int
     */
    private $code = 405;

    /**
     * @var string
     */
    private $message = 'Method not allowed';

    /**
     * @var array
     */
    private $data = [];

    /**
     * NotFoundResponse constructor.
     * @param string $message
     * @param array $data
     */
    public function __construct(array $data)
    {
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