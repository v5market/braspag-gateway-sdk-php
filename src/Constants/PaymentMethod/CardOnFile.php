<?php

namespace Braspag\Gateway\Constants\PaymentMethod;

use Braspag\Gateway\Constants\AbstractEnum;

class CardOnFile extends AbstractEnum
{
    public const USAGE_FIRST = 'First';
    public const USAGE_USED = 'Used';

    public const REASON_RECURRING = 'Recurring';
    public const REASON_UNSCHEDULED = 'Unscheduled';
    public const REASON_INSTALLMENTS = 'Installments';
}
