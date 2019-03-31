<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\LoadConfiguration;

class LoadConfigTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $l = new LoadConfiguration();
        $l->bootstrap($app);
        // testing/app.phpの設定が反映されているか？
        $this->assertEquals('config-test', $app['config']['app.name']);
        // 配列の設定がマージされているか？
        $this->assertEquals([
            'key1' => 'value100',
            'key2' => 'value2',
            'key20' => 'value20',
        ], $app['config']['app.settingArray']);
    }
}
