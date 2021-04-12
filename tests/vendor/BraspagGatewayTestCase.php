<?php
declare(strict_types=1);

namespace Braspag\Gateway\Test;

use PHPUnit\Framework\TestCase;

class BraspagGatewayTestCase extends TestCase
{
    protected $clientId = '';
    protected $clientSecret = '';
    protected $merchantKey = '';

    protected $env = null;
    protected $auth = null;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }
}
