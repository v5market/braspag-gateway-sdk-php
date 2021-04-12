<?php

namespace Braspag\Gateway\Constants\FraudAnalysis;

use Braspag\Gateway\Constants\AbstractEnum;

class Shipping extends AbstractEnum
{
    public const METHOD_SAME_DAY = 'SameDay';
    public const METHOD_ONE_DAY = 'OneDay';
    public const METHOD_TWO_DAY = 'TwoDay';
    public const METHOD_THREE_DAY = 'ThreeDay';
    public const METHOD_LOW_COST = 'LowCost';
    public const METHOD_PICKUP = 'Pickup';
    public const METHOD_OTHER = 'Other';
    public const METHOD_NONE = 'None';

    public const METHODS = [
        self::METHOD_SAME_DAY,
        self::METHOD_ONE_DAY,
        self::METHOD_TWO_DAY,
        self::METHOD_THREE_DAY,
        self::METHOD_LOW_COST,
        self::METHOD_PICKUP,
        self::METHOD_OTHER,
        self::METHOD_NONE
    ];
}
