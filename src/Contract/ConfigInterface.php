<?php
declare(strict_types=1);

namespace Exewen\Config\Contract;

interface ConfigInterface
{
    /**
     * 获取配置
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * 是否存在配置
     * @param string $keys
     * @return bool
     */
    public function has(string $keys): bool;

    /**
     * 设置配置
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function set(string $key, $value);
}