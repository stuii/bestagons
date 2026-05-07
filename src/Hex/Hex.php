<?php

namespace Stui\Bestagons\Hex;

class Hex
{
    public int $q;

    public int $r;

    public int $s;

    public function __construct(
        int $q,
        int $r,
        ?int $s = null,
    ) {
        $this->q = $q;
        $this->r = $r;
        if ($s === null || $q + $r + $s !== 0) {
            $this->s = -$q - $r;
        }
    }

    public function __toString(): string
    {
        return sprintf('%d*%d*%d', $this->q, $this->r, $this->s);
    }

    public function equalTo(self $hex): bool
    {
        return $hex->q === $this->q
            && $hex->r === $this->r
            && $hex->s === $this->s;
    }

    public function add(self $hex): self
    {
        return new self(
            $this->q + $hex->q,
            $this->r + $hex->r,
            $this->s + $hex->s,
        );
    }

    public function subtract(self $hex): self
    {
        return new self(
            $this->q - $hex->q,
            $this->r - $hex->r,
            $this->s - $hex->s,
        );
    }

    public function multiply(int $k): self
    {
        return new self(
            $this->q * $k,
            $this->r * $k,
            $this->s * $k,
        );
    }

    public function length(): int
    {
        return (int) ((abs($this->q) + abs($this->r) + abs($this->s)) / 2);
    }

    public function distanceTo(self $hex): int
    {
        return $this->subtract($hex)->length();
    }

    /**
     * @param int<0,5> $direction
     */
    public function neighbor(int $direction): self
    {
        return $this->add(HexDirection::asHex($direction));
    }

    /**
     * @param int<0,5> $direction
     */
    public function diagonalNeighbor(int $direction): self
    {
        return $this->add(HexDirection::asDiagonalHex($direction));
    }

    public function rotateLeft(): self
    {
        return new self(
            -$this->s,
            -$this->q,
            -$this->r,
        );
    }

    public function rotateRight(): self
    {
        return new self(
            -$this->r,
            -$this->s,
            -$this->q,
        );
    }

    public function lerp(float | int $a, float | int $b, float $t): float
    {
        return $a * (1 - $t) + $b * $t;
    }

    public function lerpHex(self $to, float $t): FractionalHex
    {
        return new FractionalHex(
            $this->lerp($this->q, $to->q, $t),
            $this->lerp($this->r, $to->r, $t),
            $this->lerp($this->s, $to->s, $t),
        );
    }
}
