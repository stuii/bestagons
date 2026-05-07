<?php

namespace Stui\Bestagons\Pathfinding;

use Stui\Bestagons\Hex\Hex;
use Stui\Bestagons\Map\Map;

class AStar
{
    /**
     * Finds a path between two hexes on a map.
     *
     * @param Map $map The map to search on.
     * @param Hex $start The starting hex.
     * @param Hex $goal The target hex.
     * @param callable(Hex): bool|null $isTraversable Callback to check if a given hex is traversable.
     * @param callable(Hex, Hex): (float|int)|null $cost Callback to get the cost of moving between two adjacent hexes.
     * @return array<int, Hex>|null The path as an array of hexes, or null if no path was found.
     */
    public static function findPath(
        Map $map,
        Hex $start,
        Hex $goal,
        ?callable $isTraversable = null,
        ?callable $cost = null
    ): ?array {
        $openSet = [$start->getKey() => $start];
        $pathTrace = [];

        $gScore = [$start->getKey() => 0];
        $fScore = [$start->getKey() => $start->distanceTo($goal)];

        while (!empty($openSet)) {
            $currentKey = null;
            $minFScore = INF;
            foreach ($openSet as $key => $node) {
                if ($fScore[$key] < $minFScore) {
                    $minFScore = $fScore[$key];
                    $currentKey = $key;
                }
            }

            $current = $openSet[$currentKey];

            if ($current->equalTo($goal)) {
                return self::reconstructPath($pathTrace, $current);
            }

            unset($openSet[$currentKey]);

            foreach ($map->getNeighborsOf($current) as $neighbor) {
                if ($isTraversable !== null && !$isTraversable($neighbor)) {
                    continue;
                }

                $neighborKey = $neighbor->getKey();

                $stepCost = $cost ? $cost($current, $neighbor) : 1;
                $tentativeGScore = $gScore[$currentKey] + $stepCost;

                if (!isset($gScore[$neighborKey]) || $tentativeGScore < $gScore[$neighborKey]) {
                    $pathTrace[$neighborKey] = $current;
                    $gScore[$neighborKey] = $tentativeGScore;
                    $fScore[$neighborKey] = $gScore[$neighborKey] + $neighbor->distanceTo($goal);
                    $openSet[$neighborKey] = $neighbor;
                }
            }
        }

        return null;
    }

    /**
     * @param array<string, Hex> $pathTrace
     * @return array<int, Hex>
     */
    private static function reconstructPath(array $pathTrace, Hex $current): array
    {
        $totalPath = [$current];
        while (isset($pathTrace[$current->getKey()])) {
            $current = $pathTrace[$current->getKey()];
            array_unshift($totalPath, $current);
        }

        return $totalPath;
    }
}
