<?php
declare(strict_types=1);

namespace Exewen\Config;

use Exewen\Config\Contract\ConfigInterface;

class Config implements ConfigInterface
{
    protected array $configs = [];


    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    public function get(string $key, $default = null)
    {
        if (strpos($key, '.') === false) {
            return $this->configs[$key] ?? $default;
        }

        // 适配.配置查找
        $array = $this->configs;
        foreach (explode('.', $key) as $segment) {
            if (isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        return $array;
    }

    public function has(string $keys): bool
    {
        return isset($this->configs[$keys]);
    }

    public function set(string $key, $value)
    {
        $this->configs[$key] = $value;
    }

}