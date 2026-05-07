<?php

namespace Stui\Bestagons\Tests;

use PHPUnit\Framework\TestCase;
use Stui\Bestagons\Enums\HexOrientation;
use Stui\Bestagons\Hex\Hex;
use Stui\Bestagons\Map\Map;
use Stui\Bestagons\Map\Shapes\RectangularMap;

/**
 * @internal
 */
class MapTest extends TestCase
{
    public function testAddAndGet(): void
    {
        $map = new Map();
        $hex = new Hex(1, 2);
        $map->store($hex);

        $this->assertTrue($map->has(1, 2));
        $this->assertSame($hex, $map->get(1, 2));
    }

    public function testHas(): void
    {
        $map = new Map();
        $this->assertFalse($map->has(0, 0));
        $map->store(new Hex(0, 0));
        $this->assertTrue($map->has(0, 0));
    }

    public function testNeighborsOf(): void
    {
        $map = new Map();
        $center = new Hex(0, 0);
        $map->store($center);

        $n1 = new Hex(1, 0);
        $n2 = new Hex(0, 1);
        $map->store($n1);
        $map->store($n2);

        $neighbors = $map->getNeighborsOf($center);

        $this->assertCount(2, $neighbors);
        $this->assertContains($n1, $neighbors);
        $this->assertContains($n2, $neighbors);
    }

    public function testBigMapGeneration(): void
    {
        $map = new RectangularMap(0, 1000, 0, 1000, HexOrientation::POINTY_TOP);
        $this->assertCount(1501, $map->store);
    }

    public function testHexToString(): void
    {
        $hex = new Hex(1, -2, 1);
        $this->assertEquals('1*-2*1', (string) $hex);
        $this->assertEquals('1*-2*1', $hex->getKey());
    }

    public function testHexWrongSValue(): void
    {
        $hex = new Hex(1, -2, 5);
        $this->assertEquals('1*-2*1', $hex->getKey());
    }
}
