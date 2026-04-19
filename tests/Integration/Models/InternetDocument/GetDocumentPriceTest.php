<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\InternetDocument;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\InternetDocument;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\AssertEntityByPropertiesTrait;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class GetDocumentPriceTest extends TestCase implements ConstantsInterface
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
    public function testGetDocumentPriceWithoutServiceType(): void
    {
        $params = [
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
            'ServiceType' => InternetDocument::SERVICE_TYPE_WAREHOUSE_WAREHOUSE,
            'Weight' => 34.4,
        ];
        $actualResult = $this->model->getDocumentPrice($params);
        $this->assertEntity($actualResult, ['Cost', 'AssessedCost']);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testGetWheelsPrice(): void
    {
        $params = [
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
            'Weight' => 34.4,
            'ServiceType' => 'WarehouseWarehouse',
            'Cost' => 100.00,
            'CargoType' => InternetDocument::CARGO_TYPE_TIRES_WHEELS,
            'CargoDetails' => [
                ['CargoDescription' => self::CARGO_TYPE_TIRES_WHEELS_DESCRIPTION, 'Amount' => 2]
            ],
            'SeatsAmount' => 2,
            'PackCount' => 2,
            'CargoDescription' => self::CARGO_TYPE_TIRES_WHEELS_DESCRIPTION,
        ];
        $actualResult = $this->model->getDocumentPrice($params);
        $this->assertEntity($actualResult, [
            'Cost',
            'AssessedCost',
        ]);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testGetPalletsPrice(): void
    {
        $params = [
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
            'Weight' => 0.1,
            'ServiceType' => 'WarehouseWarehouse',
            'Cost' => 150.00,
            'CargoType' => InternetDocument::CARGO_TYPE_PALLET,
            'CargoDetails' => [
                ['CargoDescription' => self::CARGO_TYPE_PALLETS_DESCRIPTION, 'Amount' => 2]
            ],
            'OptionsSeat' => [
                [
                    'weight' => 10,
                    'volumetricHeight' => 100,
                    'volumetricWidth' => 120,
                    'volumetricLength' => 120,
                ],
            ],
            'SeatsAmount' => 2,
            'PackCount' => 2,
            'CargoDescription' => self::CARGO_TYPE_PALLETS_DESCRIPTION,
        ];
        $actualResult = $this->model->getDocumentPrice($params);
        $this->assertEntity($actualResult, [
            'Cost',
            'AssessedCost',
        ]);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testGetCargoPrice(): void
    {
        $params = [
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
            'Weight' => 0.1,
            'ServiceType' => 'WarehouseWarehouse',
            'Cost' => 150.00,
            'CargoType' => InternetDocument::CARGO_TYPE_CARGO,
            'CargoDescription' => self::CARGO_TYPE_CARGO_DESCRIPTION,
        ];
        $actualResult = $this->model->getDocumentPrice($params);
        $this->assertEntity($actualResult, [
            'Cost',
            'AssessedCost',
        ]);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testGetDocumentsPrice(): void
    {
        $params = [
            'CitySender' => self::CITY_REF_KHARKIV,
            'CityRecipient' => self::CITY_REF_KYIV,
            'Weight' => 0.1,
            'ServiceType' => 'WarehouseWarehouse',
            'Cost' => 150.00,
            'CargoType' => InternetDocument::CARGO_TYPE_DOCUMENTS,
            'CargoDescription' => self::CARGO_TYPE_PALLETS_DESCRIPTION,
        ];
        $actualResult = $this->model->getDocumentPrice($params);
        $this->assertEntity($actualResult, [
            'Cost',
            'AssessedCost',
        ]);
    }
}
