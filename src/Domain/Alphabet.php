<?php

declare(strict_types=1);

namespace BrewLink\Domain;

enum Alphabet: string
{
    case BASE62 = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
}
