<?php

namespace SmartContact\SmartLogClient\Tests;

use SmartContact\SmartLogClient\SmartLogClientServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected $loadEnvironmentVariables = true;
    public function setUp(): void
    {
      parent::setUp();
      // additional setup
    }

    protected function getPackageProviders($app)
    {
      return [
        SmartLogClientServiceProvider::class,
      ];
    }

    protected function getEnvironmentSetUp($app)
    {
      // perform environment setup
    }
}
