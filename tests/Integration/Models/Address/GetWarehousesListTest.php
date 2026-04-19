<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Address;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

/**
 * Class GetWarehousesListTest
 * Integration test for \SergiyNezbritskiy\NovaPoshta\Models\Address
 *
 * @see Address::getWarehouses
 */
class GetWarehousesListTest extends TestCase
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
    public function testGetWarehouses(): void
    {
        $actualResult = $this->model->getWarehouses([], 1, 10);
        $this->assertIsArray($actualResult);
        $this->assertIsWarehouse(array_shift($actualResult));
    }

    /**
     * @param array $warehouse
     * @return void
     */
    private function assertIsWarehouse(array $warehouse): void
    {
        $this->assertArrayHasKey('Ref', $warehouse);
        $this->assertArrayHasKey('Description', $warehouse);
        $this->assertArrayHasKey('CityRef', $warehouse);
        $this->assertArrayHasKey('WarehouseStatus', $warehouse);
        $this->assertArrayHasKey('CategoryOfWarehouse', $warehouse);
        $this->assertArrayHasKey('TypeOfWarehouse', $warehouse);
    }
}
