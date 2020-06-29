<?php

namespace App\Enum;

use App\Exceptions\EnumException;

abstract class Enum implements \JsonSerializable
{
    protected string $value;

    /**
     * Enum constructor.
     *
     * @param string $value Value for this display type
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * If the enum is requested as a string then this function will be automatically
     * called and the value of this enum will be returned as a string.
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->__toString();
    }

    /**
     * Return string representation of this enum.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Tries to set the value  of this enum.
     *
     * @param string $value Set the value
     *
     * @throws \App\Exceptions\EnumException when key does not exist on enum
     */
    public function setValue($value): void
    {
        if ($this->isValidEnumValue($value)) {
            $this->value = $value;
        } else {
            throw new EnumException('Invalid type specified!');
        }
    }

    /**
     * Validates if the type given is part of this enum class.
     *
     * @param string $checkValue Value to check
     */
    public function isValidEnumValue($checkValue): bool
    {
        $reflector = new \ReflectionClass(\get_class($this));
        foreach ($reflector->getConstants() as $validValue) {
            if ($validValue == $checkValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create enum instance from a string value.
     *
     * @param Enum|string $value Value to create enum from
     *
     * @throws \App\Exceptions\EnumException when key does not exist on enum
     *
     * @return Enum enum object
     */
    public static function create($value): self
    {
        return new static($value);
    }

    /**
     * Get all key/valuse of the enum.
     *
     * @return array enum key/value names
     */
    public static function all(): array
    {
        $class = new \ReflectionClass(static::class);

        return $class->getConstants();
    }

    /**
     * Get all keys of the enum.
     *
     * @return array enum key names
     */
    public static function keys(): array
    {
        return array_keys(static::all());
    }

    /**
     * Get all values of the enum.
     *
     * @return array enum values
     */
    public static function values(): array
    {
        return array_values(static::all());
    }

    /**
     * Check if the enum has the value $value.
     *
     * @param Enum|string $value
     */
    public static function hasValue($value): bool
    {
        return \in_array($value, static::values());
    }
}
