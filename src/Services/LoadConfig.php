<?php
declare(strict_types=1);

namespace Exewen\Config\Services;

use Exewen\Utils\Composer;

class LoadConfig
{
    /**
     * Providers配置
     * @var array
     */
//    private static array $providerConfigs = [];
    private static $providerConfigs = [];

    /**
     * 加载composer配置
     * @return array
     */
    public static function loadComposer(): array
    {
        if (!static::$providerConfigs) {
            // Extra['Extra'=>['exewen'=>'config']] 获取providers
            $providers = Composer::getPackageExtra('exewen')['config'] ?? [];
            static::$providerConfigs = static::loadProviders($providers);
        }
        return static::$providerConfigs;
    }

    /**
     * 加载Providers配置
     * @param array $providers
     * @return array
     */
    protected static function loadProviders(array $providers): array
    {
        $providerConfigs = [];
        foreach ($providers as $provider) {
            if (is_string($provider) && class_exists($provider) && method_exists($provider, '__invoke')) {
                $providerConfigs[] = (new $provider())();
            }
        }

        return static::merge(...$providerConfigs);
    }

    /**
     * 配置合并
     * @param ...$arrays
     * @return array
     */
    protected static function merge(...$arrays): array
    {
        if (empty($arrays)) {
            return [];
        }
        $result = array_merge_recursive(...$arrays);
        if (isset($result['dependencies'])) {
            $dependencies = array_column($arrays, 'dependencies');
            $result['dependencies'] = array_merge(...$dependencies);
        }

        return $result;
    }

}