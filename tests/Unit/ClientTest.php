<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Unit;

use Exception;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Client;
use SergiyNezbritskiy\NovaPoshta\Models\Address;

/**
 * Class ClientTest
 * Unit test for \SergiyNezbritskiy\NovaPoshta\Client
 * @see Client
 */
class ClientTest extends TestCase
{
    private Client $object;

    protected function setUp(): void
    {
        $this->object = new Client('some-key');
    }

    /**
     * @throws Exception
     */
    public function testAddressModelProperty(): void
    {
        $addressModel = $this->object->address;
        $this->assertInstanceOf(Address::class, $addressModel);
    }
}
