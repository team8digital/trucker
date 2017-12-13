<?php

namespace Trucker\Tests\Responses;

use Trucker\Resource\Model;
use Trucker\Responses\Collection;
use Trucker\Tests\TruckerTestCase;

class CollectionTest extends TruckerTestCase
{
    public function testIteratorRewind()
    {
        $c = $this->getTestObject();
        $r = $c->rewind();
        $this->assertEquals(
            1234,
            $r->id
        );
        $this->assertInstanceOf(Model::class, $r);
    }

    public function testIteratorCurrent()
    {
        $c = $this->getTestObject();
        $c->next();
        $cur = $c->current();
        $this->assertEquals(
            1235,
            $cur->id
        );
        $this->assertInstanceOf(Model::class, $cur);
    }

    public function testIteratorKey()
    {
        $c = $this->getTestObject();
        $c->next();
        $this->assertEquals(
            1,
            $c->key()
        );
    }

    public function testIteratorNext()
    {
        $c = $this->getTestObject();
        $next = $c->next();
        $this->assertEquals(
            1235,
            $next->id
        );
        $this->assertInstanceOf(Model::class, $next);
    }

    public function testIteratorValid()
    {
        $c = $this->getTestObject();
        $this->assertTrue($c->valid(), 'Expected valid() to be true');

        $x = new Collection([]);
        $this->assertFalse($x->valid(), 'Expected valid() to be false');
    }

    public function testSizeGetter()
    {
        $c = $this->getTestObject();
        $this->assertEquals(5, $c->size());
    }

    public function testFirstGetter()
    {
        $c = $this->getTestObject();
        $first = $c->first();
        $this->assertEquals(1234, $first->id);
        $this->assertInstanceOf(Model::class, $first);

        $c = new Collection([]);
        $this->assertEquals(null, $c->first());
    }

    public function testLastGetter()
    {
        $c = $this->getTestObject();
        $last = $c->last();
        $this->assertEquals(1238, $last->id);
        $this->assertInstanceOf(Model::class, $last);

        $c = new Collection([]);
        $this->assertEquals(null, $c->last());
    }

    public function testToArray()
    {
        $c = $this->getTestObject();
        $this->assertEquals(
            $this->getRecordsArray(),
            $c->toArray()
        );

        $c = $this->getTestObject(true);
        $this->assertEquals(
            [
                'collection' => $this->getRecordsArray(),
                'meta' => $this->getMetaArray(),
            ],
            $c->toArray('collection', 'meta')
        );
    }

    public function testToJson()
    {
        $c = $this->getTestObject();
        $this->assertEquals(
            json_encode($this->getRecordsArray()),
            $c->toJson()
        );

        $c = $this->getTestObject(true);
        $this->assertEquals(
            json_encode([
                'collection' => $this->getRecordsArray(),
                'meta' => $this->getMetaArray(),
            ]),
            $c->toJson('collection', 'meta')
        );
    }

    /**
     * Helper function to create a popuplated
     * collection object for testing.
     *
     * @param bool $setMeta wether or not to set meta data
     *
     * @return Collection
     */
    private function getTestObject($setMeta = false)
    {
        $records = $this->getRecordsArray();

        $objects = [];
        foreach ($records as $r) {
            $m = $this->prophesize(Model::class);
            $m->getBase64Indicator()->willReturn('_base64')->shouldBeCalled();
            $m->id = $r['id'];
            $m->attributes()->willReturn($r);
            $objects[] = $m->reveal();
        }

        $meta = $this->getMetaArray();

        $collection = new Collection($objects);

        if ($setMeta) {
            $collection->metaData = $meta;
        }

        return $collection;
    }

    /**
     * Testing function to create an array of
     * data to test against.
     *
     * @return array
     */
    private function getRecordsArray()
    {
        $records = [
            [
                'id' => 1234,
                'name' => 'John Doe',
                'email' => 'jdoe@noboddy.com',
            ],
            [
                'id' => 1235,
                'name' => 'Sammy Smith',
                'email' => 'sammys@mysite.com',
            ],
            [
                'id' => 1236,
                'name' => 'Tommy Jingles',
                'email' => 'tjingles@gmail.com',
            ],
            [
                'id' => 1237,
                'name' => 'Brent Sanders',
                'email' => 'bsanders@yahoo.com',
            ],
            [
                'id' => 1238,
                'name' => 'Michael Blanton',
                'email' => 'mblanton@outlook.com',
            ],
        ];

        return $records;
    }

    /**
     * Testing function to create an array of
     * meta data to test against.
     *
     * @return array
     */
    private function getMetaArray()
    {
        $meta = [
            'per_page' => 25,
            'num_pages' => 4,
            'page' => 1,
        ];

        return $meta;
    }
}
