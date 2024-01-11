<?php
declare(strict_types=1);

namespace Exewen\Config\Contract;

interface ConfigInterface
{
    public function get(string $key, $default = null);

    public function has(string $keys): bool;

    public function set(string $key, $value);
}