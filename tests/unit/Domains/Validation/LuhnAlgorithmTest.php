<?php

use PHPUnit\Framework\TestCase;
use Braspag\Gateway\Domains\Validation\LuhnAlgorithm;

class LuhnAlgorithmTest extends Testcase
{
    /**
     * @test
     * @dataProvider providerValid
     */
    public function checkValidNumbers($cardNumber)
    {
        $this->assertTrue(LuhnAlgorithm::check($cardNumber));
    }

    /**
     * @test
     * @dataProvider providerInvalid
     */
    public function checkInvalidNumbers($cardNumber)
    {
        $this->assertFalse(LuhnAlgorithm::check($cardNumber));
    }

    public function providerValid()
    {
        return [
            ['341917500625952'],
            ['5348 8032 2629 4293'], // MasterCard
            ['4916 7244 2015 8784'], // Visa 16 dígitos
            ['4313514384257'],       // Visa 14 dígitos
            ['3728 809904 64682'],   // American Express
            ['3024 637511 0593'],    // Diners Club
            ['6011 7278 6232 3237'], // Discover
            ['2149 8350558 2501'],   // enRouter
            ['3575 1891 4354 2454'], // JCB
            ['86997 0369 47881 7'],  // Voyage
            ['6062 8288 1123 9686'], // Hipercard
            ['5034 3239 9082 6955'], // Aura
        ];
    }

    public function providerInvalid()
    {
        return [
            ['341917500625953'],
            ['5348 8032 2629 4290'], // MasterCard
            ['4916 7244 2015 8783'], // Visa 16 dígitos
            ['3728 809904 64684'],   // American Express
            ['3024 637511 0595'],    // Diners Club
            ['6011 7278 6232 3239'], // Discover
            ['2149 8350558 250'],   // enRouter
            ['3575 1891 4354 2453'], // JCB
            ['86997 0369 47881 1'],  // Voyage
            ['6062 8288 1123 9688'], // Hipercard
            ['5034 3239 9082 6954'], // Aura
            ['4111 1111 1111 1110'], // Visa
        ];
    }
}
