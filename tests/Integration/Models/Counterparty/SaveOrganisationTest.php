<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Counterparty;

use Exception;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Counterparty;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class SaveOrganisationTest extends TestCase
{
    use UsesConnectionTrait;

    private Counterparty $model;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new Counterparty($connection);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testCounterpartyOrganisationCrud(): void
    {
        $counterparty = [
            'EDRPOU' => '42844961',
            'CounterpartyProperty' => 'Recipient',
        ];
        $counterparty = $this->model->saveOrganisation($counterparty);
        $this->assertIsCounterparty($counterparty);

        $this->model->delete($counterparty['Ref']);
    }

    /**
     * @param array $counterparty
     * @return void
     */
    private function assertIsCounterparty(array $counterparty): void
    {
        $this->assertArrayHasKey('Ref', $counterparty);
        $this->assertArrayHasKey('Description', $counterparty);
        $this->assertArrayHasKey('OwnershipForm', $counterparty);
        $this->assertArrayHasKey('OwnershipFormDescription', $counterparty);
        $this->assertArrayHasKey('Counterparty', $counterparty);
        $this->assertArrayHasKey('EDRPOU', $counterparty);
        $this->assertArrayHasKey('CounterpartyType', $counterparty);
    }
}
