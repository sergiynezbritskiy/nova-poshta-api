<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Common;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Common;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\AssertEntityByPropertiesTrait;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class GetCargoDescriptionListTest extends TestCase
{
    use AssertEntityByPropertiesTrait;
    use UsesConnectionTrait;

    private Common $model;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new Common($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     */
    public function testGetCargoDescriptionList(): void
    {
        $actualResult = $this->model->getCargoDescriptionList();
        $this->assertNotEmpty($actualResult);
        $entity = array_shift($actualResult);
        $expectedKeys = [
            'Ref',
            'Description',
            'DescriptionRu',
        ];
        $this->assertEntity($entity, $expectedKeys);
    }
}
