<?php

declare(strict_types=1);

namespace Exewen\Config;

use Exewen\Config\Contract\ConfigInterface;
use Exewen\Config\Services\LoadConfig;
use Exewen\Utils\Contract\ContainerInterface;
use Symfony\Component\Finder\Finder;

class ConfigProvider
{

//    private ContainerInterface $container;
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 配置注册
     * @return void
     */
    public function register()
    {
        $config = $this->getConfig();
        $this->container->singleton(ConfigInterface::class, new Config($config));
    }

    /**
     * 获取配置
     * @return array
     */
    public function getConfig():array
    {
        !defined('BASE_PATH_CONFIG') && define('BASE_PATH_CONFIG', '/config/exewen');
        $autoloadConfig = $this->readPath([BASE_PATH_PKG . BASE_PATH_CONFIG]); // 项目配置
        $composerConfig = LoadConfig::loadComposer(); // composer extra 配置
        return array_replace_recursive($composerConfig, $autoloadConfig);
    }

    /**
     * 读取指定文件配置
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
     * 读取项目配置
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