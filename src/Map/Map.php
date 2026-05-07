<?php

namespace Stui\Bestagons\Map;

use Stui\Bestagons\Hex\Hex;

class Map
{
    /**
     * @var array<int, array<int, Hex>>
     */
    public array $store = [];

    public function store(Hex $hex): self
    {
        if (!isset($this->store[$hex->q])) {
            $this->store[$hex->q] = [];
        }
        $this->store[$hex->q][$hex->r] = $hex;

        return $this;
    }

    public function get(int $q, int $r): ?Hex
    {
        return $this->store[$q][$r] ?? null;
    }

    public function has(int $q, int $r): bool
    {
        return isset($this->store[$q][$r]);
    }

    /**
     * @return array<int, Hex>
     */
    public function getNeighborsOf(Hex $hex): array
    {
        $neighbors = [];
        for ($direction = 0; $direction < 6; $direction++) {
            $neighbor = $hex->neighbor($direction);
            if ($this->has($neighbor->q, $neighbor->r)) {
                $mapNeighbor = $this->get($neighbor->q, $neighbor->r);
                if ($mapNeighbor !== null) {
                    $neighbors[] = $mapNeighbor;
                }
            }
        }

        return $neighbors;
    }
}
