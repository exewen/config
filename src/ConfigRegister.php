<?php

declare(strict_types=1);

namespace Exewen\Config;


use Exewen\Config\Contract\ConfigInterface;

/**
 * 注册加载配置信息
 */
class ConfigRegister
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ConfigInterface::class => Config::class,
            ],
        ];
    }
}
