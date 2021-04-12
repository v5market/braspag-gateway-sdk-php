<?php

namespace Braspag\Gateway\Constants\Payment;

use Braspag\Gateway\Constants\AbstractEnum;

class Interest extends AbstractEnum
{
    public const INTEREST_MERCHANT = 'ByMerchant';
    public const INTEREST_ISSUER = 'ByIssuer';
}
