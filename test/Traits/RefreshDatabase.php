<?php

namespace Test\Traits;

use Hyperf\Redis\Redis;
use Hyperf\Utils\ApplicationContext;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Hyperf\Database\Commands\Migrations\RefreshCommand;
use Hyperf\Database\Commands\Seeders\SeedCommand;

trait RefreshDatabase
{
    public function refreshDatabase(): void
    {
        $container = ApplicationContext::getContainer();

        $input = new StringInput('');
        $output = new NullOutput();

        $container->get(RefreshCommand::class)->run($input, $output);
        $container->get(SeedCommand::class)->run($input, $output);

        $redis = $container->get(Redis::class);
        foreach ($redis->keys('*') as $key) {
            $redis->del(str_replace('acquirer_gateway:', '',$key));
        }
    }
}