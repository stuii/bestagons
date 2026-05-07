<?php

namespace Stui\Bestagons\Tests;

use PHPUnit\Framework\TestCase;
use Stui\Bestagons\Enums\HexOrientation;
use Stui\Bestagons\Hex\Hex;
use Stui\Bestagons\Map\Shapes\RectangularMap;
use Stui\Bestagons\Pathfinding\AStar;

/**
 * @internal
 */
class AStarTest extends TestCase
{
    public function testFindPathSimple(): void
    {
        $map = new RectangularMap(0, 5, 0, 5, HexOrientation::POINTY_TOP);
        $start = $map->get(0, 0);
        $goal = $map->get(2, 2);
        if ($start === null || $goal === null) {
            $this->fail('Could not find start or goal hex');
        }
        $path = AStar::findPath($map, $start, $goal);

        $this->assertNotNull($path);
        $this->assertEquals($start, $path[0]);
        $this->assertEquals($goal, end($path));
        $this->assertCount(5, $path);
        $this->assertEquals('0*0*0', $path[0]->getKey());
        $this->assertEquals('1*0*-1', $path[1]->getKey());
        $this->assertEquals('2*0*-2', $path[2]->getKey());
        $this->assertEquals('2*1*-3', $path[3]->getKey());
        $this->assertEquals('2*2*-4', $path[4]->getKey());
    }

    public function testFindPathWithBlockage(): void
    {
        $map = new RectangularMap(0, 5, 0, 5, HexOrientation::POINTY_TOP);
        $start = $map->get(0, 1);
        $goal = $map->get(2, 1);
        if ($start === null || $goal === null) {
            $this->fail('Could not find start or goal hex');
        }
        $blocked = ['1*1*-2', '2*0*-2', '0*2*-2'];

        $isTraversable = static function (Hex $hex) use ($blocked) {
            return !in_array($hex->getKey(), $blocked);
        };

        $path = AStar::findPath($map, $start, $goal, $isTraversable);

        $this->assertNotNull($path);
        foreach ($path as $hex) {
            $this->assertNotContains($hex->getKey(), $blocked);
        }
        $this->assertCount(6, $path);
        $this->assertEquals('0*1*-1', $path[0]->getKey());
        $this->assertEquals('-1*2*-1', $path[1]->getKey());
        $this->assertEquals('-1*3*-2', $path[2]->getKey());
        $this->assertEquals('0*3*-3', $path[3]->getKey());
        $this->assertEquals('1*2*-3', $path[4]->getKey());
        $this->assertEquals('2*1*-3', $path[5]->getKey());
    }

    public function testNoPathFound(): void
    {
        $map = new RectangularMap(0, 5, 0, 5, HexOrientation::POINTY_TOP);
        $start = $map->get(0, 0);
        $goal = $map->get(0, 5);
        if ($start === null || $goal === null) {
            $this->fail('Could not find start or goal hex');
        }

        // Surround start with blockages
        $blocked = ['1*0*-1', '0*1*-1', '1*-1*0'];

        $isTraversable = static function (Hex $hex) use ($blocked) {
            return !in_array($hex->getKey(), $blocked);
        };

        $path = AStar::findPath($map, $start, $goal, $isTraversable);

        $this->assertNull($path);
    }

    public function testFindLongerButLessCostlyPath(): void
    {
        $map = new RectangularMap(0, 5, 0, 5, HexOrientation::POINTY_TOP);
        $start = $map->get(0, 1);
        $goal = $map->get(2, 1);
        if ($start === null || $goal === null) {
            $this->fail('Could not find start or goal hex');
        }

        $costArray = ['1*1*-2' => 99];

        $cost = static function (Hex $hex) use ($costArray) {
            return $costArray[$hex->getKey()];
        };

        $path = AStar::findPath($map, $start, $goal, null, $cost);

        $this->assertNotNull($path);
        foreach ($path as $hex) {
            $this->assertNotEquals($hex->getKey(), '1*1*-2');
        }
        $this->assertCount(4, $path);
        $this->assertEquals('0*1*-1', $path[0]->getKey());
        $this->assertEquals('1*0*-1', $path[1]->getKey());
        $this->assertEquals('2*0*-2', $path[2]->getKey());
        $this->assertEquals('2*1*-3', $path[3]->getKey());
    }

    public function testFindLongPath(): void
    {
        $map = new RectangularMap(0, 100, 0, 200, HexOrientation::POINTY_TOP);
        $start = $map->get(0, 1);
        $goal = $map->get(-61, 159);
        if ($start === null || $goal === null) {
            $this->fail('Could not find start or goal hex');
        }

        $path = AStar::findPath($map, $start, $goal);

        $this->assertNotNull($path);
        $this->assertCount(159, $path);
    }
}
