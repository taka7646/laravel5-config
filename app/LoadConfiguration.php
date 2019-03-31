<?php

namespace App;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Config\Repository as RepositoryContract;

class LoadConfiguration extends \Illuminate\Foundation\Bootstrap\LoadConfiguration
{
    /**
     * Load the configuration items from all of the files.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Config\Repository  $repository
     * @return void
     *
     * @throws \Exception
     */
    protected function loadConfigurationFiles(Application $app, RepositoryContract $repository)
    {
        $files = $this->getConfigurationFiles($app);

        if (! isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }

        $env = env('APP_ENV');
        $envConfigs = [];
        foreach ($files as $key => $path) {
            if (strpos($key, 'env.') === 0) {
                // config/env/ 以下のファイルは環境別設定として処理する
                [$_, $envName, $envKey] = explode('.', $key, 3);
                if ($env == $envName) {
                    // 現在の環境の設定のみロードする
                    $envConfigs[$envKey] = require $path;
                }
                continue;
            }
            $repository->set($key, require $path);
        }
        // 既存の設定を環境の設定で上書きする
        // 再帰的に上書きした方がいいかは設定の書き方次第かも・・。
        foreach ($envConfigs as $key => $envConf) {
            $conf = $repository->get($key);
            $repository->set($key, array_replace_recursive($conf, $envConf));
        }
    }
}
