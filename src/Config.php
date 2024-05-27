<?php
declare(strict_types=1);

namespace Exewen\Config;

use Exewen\Config\Contract\ConfigInterface;

class Config implements ConfigInterface
{
//    protected array $configs = [];
    protected $configs = [];


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
        if (strpos($key, '.') === false) {
            $this->configs[$key] = $value;
        } else {
            // 适配.配置设置
            $this->setNestedValue($this->configs, $key, $value);
        }
    }

    /**
     * 嵌套修改配置
     * @param $array
     * @param $key
     * @param $value
     * @return void
     */
    protected function setNestedValue($array, $key, $value)
    {
        $current = &$array;
        foreach (explode('.', $key) as $segment) {
            $current = &$current[$segment];
        }
        // 全部匹配进行修改
        $current = $value;
        $this->configs = $array;
    }

}