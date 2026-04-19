<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Address;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class GetWarehouseTypesTest extends TestCase
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
    public function testGetWarehouseTypes(): void
    {
        $actualResult = $this->model->getWarehouseTypes();
        $this->assertNotEmpty($actualResult);
        $this->assertIsWarehouseType(array_shift($actualResult));
    }

    /**
     * @param array $type
     * @return void
     */
    private function assertIsWarehouseType(array $type): void
    {
        $expectedKeys = [
            'Ref',
            'DescriptionRu',
            'Description',
        ];
        $actualKeys = array_keys($type);
        sort($actualKeys);
        sort($expectedKeys);
        $this->assertSame($expectedKeys, $actualKeys);
    }
}
