<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\Config;
use Trucker\Facades\ResultOrderFactory;
use Trucker\Tests\TruckerTestCase;

class QueryResultOrderFactoryTest extends TruckerTestCase
{
    public function testCreateValidResultOrderDriver()
    {
        $this->swapConfig([
            'trucker::result_order.driver' => 'get_params',
        ]);
        Config::setApp($this->app);

        $cond = ResultOrderFactory::build();
        $this->assertInstanceOf(
            \Trucker\Finders\Conditions\QueryResultOrderInterface::class, $cond, "Expected transporter to implement \Trucker\Finders\Conditions\QueryResultOrderInterface"
        );

        $this->assertInstanceOf(
            \Trucker\Finders\Conditions\GetParamsResultOrder::class, $cond, "Expected transporter to be \Trucker\Finders\Conditions\GetParamsResultOrder"
        );
    }

    public function testCreateInvalidResultOrderDriver()
    {
        $this->swapConfig([
            'trucker::result_order.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = ResultOrderFactory::build();
    }
}
