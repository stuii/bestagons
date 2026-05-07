<?php

namespace Stui\Bestagons\Map\Shapes;

use Stui\Bestagons\Enums\HexOrientation;
use Stui\Bestagons\Hex\Hex;
use Stui\Bestagons\Map\Map;

class RectangularMap extends Map
{
    /**
     * Creates a rectangular map.
     * Centers on point X and extends as provided to the left, right, top and bottom.
     */
    public function __construct(
        int $left,
        int $right,
        int $top,
        int $bottom,
        HexOrientation $orientation,
    ) {
        if ($orientation === HexOrientation::POINTY_TOP) {
            for ($r = $top; $r <= $bottom; $r++) {
                $rOffset = (int) floor($r / 2);
                for ($q = $left - $rOffset; $q <= $right - $rOffset; $q++) {
                    $this->store(new Hex($q, $r, (-$q - $r)));
                }
            }
        } else {
            for ($q = $left; $q <= $right; $q++) {
                $qOffset = (int) floor($q / 2);
                for ($r = $top - $qOffset; $r <= $bottom - $qOffset; $r++) {
                    $this->store(new Hex($q, $r, (-$q - $r)));
                }
            }
        }
    }
}
