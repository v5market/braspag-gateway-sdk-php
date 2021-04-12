<?php

namespace Braspag\Gateway\Constants\Payment;

use Braspag\Gateway\Constants\AbstractEnum;

class Refund extends AbstractEnum
{
    public const RECEIVED = 1;
    public const SENT = 2;
    public const APPROVED = 3;
    public const DENIED = 4;
    public const REJECTED = 5;

    public const RECEIVED_NAME = 'Received';
    public const SENT_NAME = 'Sent';
    public const APPROVED_NAME = 'Approved';
    public const DENIED_NAME = 'Denied';
    public const REJECTED_NAME = 'Rejected';

    public const STATUS = [
        self::RECEIVED => self::RECEIVED_NAME,
        self::SENT => self::SENT_NAME,
        self::APPROVED => self::APPROVED_NAME,
        self::DENIED => self::DENIED_NAME,
        self::REJECTED => self::REJECTED_NAME,
    ];
}
