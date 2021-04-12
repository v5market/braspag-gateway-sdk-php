<?php

namespace Braspag\Gateway\Constants\FraudAnalysis;

use Braspag\Gateway\Constants\AbstractEnum;

class Passenger extends AbstractEnum
{
    public const STATUS_STANDARD = 'standard';
    public const STATUS_GOLD = 'gold';
    public const STATUS_PLATINUM = 'platinum';

    public const RATING_ADULT = 'adult';
    public const RATING_CHILD = 'child';
    public const RATING_INFANT = 'infant';

    public const STATUS = [
        self::STATUS_STANDARD,
        self::STATUS_GOLD,
        self::STATUS_PLATINUM
    ];

    public const RATING = [
        self::RATING_ADULT,
        self::RATING_CHILD,
        self::RATING_INFANT,
    ];
}
