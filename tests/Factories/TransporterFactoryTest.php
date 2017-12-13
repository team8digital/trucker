<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\Config;
use Trucker\Facades\TransporterFactory;
use Trucker\Tests\TruckerTestCase;

class TransporterFactoryTest extends TruckerTestCase
{
    public function tearDown()
    {
        parent::tearDown();
        $this->swapConfig([]);
        Config::setApp($this->app);
    }

    public function testCreateValidTransporter()
    {
        $this->swapConfig([
            'trucker::transporter.driver' => 'json',
        ]);
        Config::setApp($this->app);

        $json = TransporterFactory::build();
        $this->assertInstanceOf(
            \Trucker\Transporters\JsonTransporter::class, $json, "Expected transporter to be Trucker\Transporters\JsonTransporter\n" .
            'But it was ' . get_class($json)
        );

        $this->assertInstanceOf(
            \Trucker\Transporters\TransporterInterface::class, $json, "Expected transporter to implement Trucker\Transporters\TransporterInterface"
        );
    }

    public function testCreateInvalidTransporter()
    {
        $this->swapConfig([
            'trucker::transporter.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = TransporterFactory::build();
    }
}
