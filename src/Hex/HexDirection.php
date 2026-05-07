<?php

namespace Stui\Bestagons\Hex;

class HexDirection
{
    public const array DIRECTIONS = [
        [1,  0, -1],
        [1, -1,  0],
        [0, -1,  1],
        [-1,  0,  1],
        [-1,  1,  0],
        [0,  1, -1],
    ];

    public const array DIAGONAL_DIRECTIONS = [
        [2, -1, -1],
        [1, -2,  1],
        [-1, -1,  2],
        [-2,  1,  1],
        [-1,  2, -1],
        [1,  1, -2],
    ];

    /**
     * @param int<0,5> $direction
     */
    public static function asHex(int $direction): Hex
    {
        return new Hex(...self::DIRECTIONS[$direction]);
    }

    /**
     * @param int<0,5> $direction
     */
    public static function asDiagonalHex(int $direction): Hex
    {
        return new Hex(...self::DIAGONAL_DIRECTIONS[$direction]);
    }
}
