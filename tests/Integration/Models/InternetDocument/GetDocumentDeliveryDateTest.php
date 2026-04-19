<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\InternetDocument;

use DateTime;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\InternetDocument;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\AssertEntityByPropertiesTrait;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class GetDocumentDeliveryDateTest extends TestCase implements ConstantsInterface
{
    use AssertEntityByPropertiesTrait;
    use UsesConnectionTrait;

    private InternetDocument $model;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new InternetDocument($connection);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testGetDeliveryDateTest(): void
    {
        $actualResult = $this->model->getDocumentDeliveryDate([
            'DateTime' => (new DateTime())->format('d.m.Y'),
            'ServiceType' => 'WarehouseWarehouse',
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
        ]);
        $this->assertEntity($actualResult, ['date', 'timezone', 'timezone_type']);
    }
}
