<?php

declare(strict_types=1);

namespace App\Enum;

use App\Exceptions\EnumException;
use App\Exceptions\UnsupportedFiletypeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SupportedFileTypes extends Enum
{
    const PHP = 'text/x-php';
    const JSON = 'application/json';
    const CSV = 'text/csv';

    public static function mappings()
    {
        return [
            'text/plain' => self::CSV,
        ];
    }

    /**
     * Verify is a certain filetype is supported.
     */
    public static function isFiletypeSupported(string $fileType): bool
    {
        return \in_array(
            strtolower($fileType),
            array_keys(static::shortNames())
        );
    }

    /**
     * Create an instance from the shortname.
     */
    public static function fromShortName(string $fileType): self
    {
        if (!static::isFiletypeSupported($fileType)) {
            throw new UnsupportedFiletypeException("{$fileType} is not supported");
        }

        return static::create(static::shortNames()[$fileType]);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException when key does not exist on enum
     */
    public function setValue($value): void
    {
        try {
            parent::setValue($value);
        } catch (EnumException $e) {
            throw new UnsupportedFiletypeException('Filetype \''.Str::lower($value).'\' is not supported. '.'Use one of '.implode('|', array_map(fn ($key) => Str::lower($key), static::keys(), ), ));
        }
    }

    /**
     * Get the extension.
     */
    public function getFileExtension(): string
    {
        $inverted = array_combine(static::values(), static::keys());

        return Str::lower($inverted[$this->value]);
    }

    public static function create($value): self
    {
        if (Arr::exists(self::mappings(), $value)) {
            $value = Arr::get(self::mappings(), $value);
        }

        return parent::create($value);
    }

    /**
     * Get the mimetype from a lowercase key value.
     *
     * @throws \App\Exceptions\UnsupportedFiletypeException when key does not exist on enum
     */
    public static function fromFileExtension(string $type): self
    {
        $type = Str::upper($type);

        $key = static::all()[$type] ?? $type;

        return new static($key);
    }

    /**
     * Allowd shortnames.
     */
    private static function shortNames(): array
    {
        return [
            'php' => static::PHP,
            'json' => static::JSON,
            'csv' => static::CSV,
        ];
    }
}
