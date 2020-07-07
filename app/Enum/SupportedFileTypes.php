<?php

namespace App\Enum;

use App\Exceptions\EnumException;
use App\Exceptions\UnsupportedFiletypeException;
use Illuminate\Support\Str;

class SupportedFileTypes extends Enum
{
    const PHP = 'text/x-php';
    const JSON = 'application/json';
    const CSV = 'text/csv';

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
            throw new UnsupportedFiletypeException('Filetype \''.Str::lower($value).'\' is not supported. '.'Use one of '.implode('|', array_map(function ($key) {
                return Str::lower($key);
            }, static::keys(), ), ));
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
}
