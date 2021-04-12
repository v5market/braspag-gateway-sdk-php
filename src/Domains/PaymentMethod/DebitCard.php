<?php

namespace Braspag\Gateway\Domains\PaymentMethod;

use Braspag\Gateway\Constants\PaymentMethod\DebitCardBrand as Brand;

class DebitCard extends AbstractCard
{
    /**
     * {@inheritDoc}
     */
    public function isCreditCard(): bool
    {
        return false;
    }
}
