<?php

namespace Stui\Bestagons\Hex;

class FractionalHex
{
    public float $q;

    public float $r;

    public float $s;

    public function __construct(
        float $q,
        float $r,
        ?float $s = null,
    ) {
        $this->q = $q;
        $this->r = $r;

        if ($s === null || $q + $r + $s !== 0.0) {
            $this->s = -$q - $r;
        } else {
            $this->s = $s;
        }
    }

    public function round(): Hex
    {
        $q = (int) round($this->q);
        $r = (int) round($this->r);
        $s = (int) round($this->s);

        $qDiff = abs($q - $this->q);
        $rDiff = abs($r - $this->r);
        $sDiff = abs($s - $this->s);

        if ($qDiff > $rDiff && $qDiff > $sDiff) {
            $q = -$r - $s;
        } elseif ($rDiff > $sDiff) {
            $r = -$q - $s;
        } else {
            $s = $q - $r;
        }

        return new Hex($q, $r, $s);
    }
}
