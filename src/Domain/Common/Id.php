<?php

namespace Dogadamycie\Domain\Common;

use Dogadamycie\Domain\Common\Exceptions\InvalidIdException;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

/**
 * Class Id
 * @package Dogadamycie\Domain
 */
class Id
{
    /**
     * @var string
     */
    private $id;

    /**
     * Id constructor.
     * @param string $id
     */
    private function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return Id
     * @throws \Exception
     */
    public static function generate(): self
    {
        return new self(Uuid::uuid4());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->__toString();
    }

    /**
     * @param string $id
     * @return bool
     */
    public static function isValid(string $id): bool
    {
        return Uuid::isValid($id);
    }

    /**
     * @param string $aId
     * @return Id
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $aId): self
    {
        try {
            $id = Uuid::fromString($aId);
            return new self($id);
        } catch (InvalidUuidStringException $e) {
            throw new InvalidIdException($aId);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->id;
    }
}