<?php

namespace Trucker\Tests\Factories;

use Trucker\Facades\ConditionFactory;
use Trucker\Facades\Config;
use Trucker\Tests\TruckerTestCase;

class QueryConditionFactoryTest extends TruckerTestCase
{
    public function testCreateValidQueryConditionDriver()
    {
        $this->swapConfig([
            'trucker::query_condition.driver' => 'get_array_params',
        ]);
        Config::setApp($this->app);

        $cond = ConditionFactory::build();
        $this->assertInstanceOf(
            \Trucker\Finders\Conditions\QueryConditionInterface::class, $cond, "Expected transporter to implement \Trucker\Finders\Conditions\QueryConditionInterface"
        );

        $this->assertInstanceOf(
            \Trucker\Finders\Conditions\GetArrayParamsQueryCondition::class, $cond, "Expected transporter to be \Trucker\Finders\Conditions\GetArrayParamsQueryCondition"
        );
    }

    public function testCreateInvalidQueryConditionDriver()
    {
        $this->swapConfig([
            'trucker::query_condition.driver' => 'invalid',
        ]);
        Config::setApp($this->app);

        $this->expectException('ReflectionException');
        $this->expectException('InvalidArgumentException');
        $foo = ConditionFactory::build();
    }
}
