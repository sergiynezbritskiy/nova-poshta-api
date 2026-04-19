<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Address;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class SearchSettlementStreetsTest extends TestCase implements ConstantsInterface
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
    public function testSearchSettlementStreets(): void
    {
        $actualResult = $this->model->searchSettlementStreets('Чернишевського', self::CITY_REF_KHARKIV);
        $this->assertArrayHasKey('TotalCount', $actualResult);
        $this->assertArrayHasKey('Addresses', $actualResult);
    }
}
