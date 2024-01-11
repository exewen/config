<?php

declare(strict_types=1);

namespace Exewen\Config;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Config\Services\LoadConfig;
use Psr\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ConfigProvider
{

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function register()
    {
        $config = $this->getConfig();
        $this->container->singleton(ConfigInterface::class, new Config($config));
    }

    public function getConfig()
    {
        $autoloadConfig = $this->readPath([BASE_PATH_PKG . '/config/exewen']);
        return array_replace_recursive(LoadConfig::loadComposer(), $autoloadConfig);
    }

    /**
     * 指定文件配置
     * @param string $string
     * @return array
     */
    protected function readConfig(string $string): array
    {
        if (!is_file($string)) {
            return [];
        }

        $config = require $string;
        if (!is_array($config)) {
            return [];
        }
        return $config;
    }

    /**
     * 递归文件读取
     * @param array $dirs
     * @return array
     */
    protected function readPath(array $dirs): array
    {
        $dirs = $this->filterEmptyPath($dirs);
        $config = [];
        if (!empty($dirs)) {
            $finder = new Finder();
            $finder->files()->in($dirs)->name('*.php');
            foreach ($finder as $fileInfo) {
                $key = $fileInfo->getBasename('.php');
                $value = require $fileInfo->getRealPath();
                $config[$key] = $value;
            }
        }
        return $config;
    }

    /**
     * 过滤空路径
     * @param array $dirs
     * @return array
     */
    private function filterEmptyPath(array $dirs): array
    {
        foreach ($dirs as $k => $path) {
            if (!is_dir($path)) {
                unset($dirs[$k]);
            }
        }
        return $dirs;
    }


}