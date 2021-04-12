<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Environment;

class EnvironmentTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function checkBasicAuthentication()
    {
        $instance = Environment::sandbox('dba3a8db-fa54-40e0-8bab-7bfb9b6f2e2e', 'D/ilRsfoqHlSUChwAMnlyKdDNd7FMsM7cU/vo02REag=');

        $this->assertTrue($instance->isSandbox());

        $this->assertEquals('dba3a8db-fa54-40e0-8bab-7bfb9b6f2e2e', $instance->getMerchantId());
        $this->assertEquals('D/ilRsfoqHlSUChwAMnlyKdDNd7FMsM7cU/vo02REag=', $instance->getMerchantKey());

        $this->assertEquals('dba3a8db-fa54-40e0-8bab-7bfb9b6f2e2e', $instance->getClientId());
        $this->assertEquals('D/ilRsfoqHlSUChwAMnlyKdDNd7FMsM7cU/vo02REag=', $instance->getMerchantKey());

        $this->assertEquals(
            'ZGJhM2E4ZGItZmE1NC00MGUwLThiYWItN2JmYjliNmYyZTJlOkQvaWxSc2ZvcUhsU1VDaHdBTW5seUtkRE5kN0ZNc003Y1Uvdm8wMlJFYWc9',
            $instance->getBasicAuthorization()
        );
    }
}
