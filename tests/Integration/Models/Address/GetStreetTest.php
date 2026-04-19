<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Address;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class GetStreetTest extends TestCase implements ConstantsInterface
{
    use UsesConnectionTrait;

    private Address $model;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new Address($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     */
    public function testGetStreet(): void
    {
        $actualResult = $this->model->getStreet(self::CITY_REF_KHARKIV, 'Черниш', 1, 10);
        $this->assertIsArray($actualResult);
        $this->assertIsStreet(array_shift($actualResult));
    }

    /**
     * @param array $street
     * @return void
     */
    private function assertIsStreet(array $street): void
    {
        $this->assertArrayHasKey('Description', $street);
        $this->assertArrayHasKey('Ref', $street);
        $this->assertArrayHasKey('StreetsTypeRef', $street);
        $this->assertArrayHasKey('StreetsType', $street);
    }
}
