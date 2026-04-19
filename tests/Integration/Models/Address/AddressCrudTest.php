<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Address;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\Models\Counterparty;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class AddressCrudTest extends TestCase implements ConstantsInterface
{
    use UsesConnectionTrait;

    private Address $model;
    private Counterparty $counterpartyModel;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new Address($connection);
        $this->counterpartyModel = new Counterparty($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     */
    public function testAddressCrud(): void
    {
        $addressRef = $this->performAddressCreate();
        $this->performAddressUpdate($addressRef);
        $this->performAddressDelete($addressRef);
    }

    /**
     * @return string
     * @throws NovaPoshtaApiException
     */
    private function performAddressCreate(): string
    {
        $note = 'This address is for testing (created)';
        $flat = '111';
        $address = [
            'StreetRef' => self::STREET_REF,
            'BuildingNumber' => '68',
            'Flat' => $flat
        ];
        $actualResult = $this->model->save(self::COUNTERPARTY_REF, $address, $note);
        $this->assertAddress($actualResult, $flat, $note);
        return $actualResult['Ref'];
    }

    /**
     * @param string $addressRef
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function performAddressUpdate(string $addressRef): void
    {
        $note = 'This address is for testing (updated)';
        $flat = '333';
        $address = [
            'Ref' => $addressRef,
            'CounterpartyRef' => self::COUNTERPARTY_REF,
            'StreetRef' => self::STREET_REF,
            'BuildingNumber' => '68',
            'Flat' => $flat,
        ];
        $actualResult = $this->model->update($address, $note);

        $this->assertAddress($actualResult, $flat, $note);
    }

    /**
     * @param string $addressRef
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function performAddressDelete(string $addressRef): void
    {
        $this->model->delete($addressRef);
        $addresses = $this->counterpartyModel->getCounterpartyAddresses(self::COUNTERPARTY_REF, 'Sender');
        $this->assertAddressMissing($addressRef, $addresses);
    }

    /**
     * @param array $actualResult
     * @param string $flat
     * @param string $note
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function assertAddress(array $actualResult, string $flat, string $note): void
    {
        $this->assertArrayHasKey('Ref', $actualResult);
        $this->assertArrayHasKey('Description', $actualResult);
        $this->assertStringContainsString($flat, $actualResult['Description']);
        $this->assertStringContainsString($note, $actualResult['Description']);

        //ensure that address has been created
        $addresses = $this->counterpartyModel->getCounterpartyAddresses(self::COUNTERPARTY_REF, 'Sender');
        $this->assertAddressExists($actualResult['Ref'], $addresses);
    }

    /**
     * @param string $addressRef
     * @param array $addresses
     * @return void
     */
    private function assertAddressExists(string $addressRef, array $addresses): void
    {
        foreach ($addresses as $address) {
            if ($address['Ref'] === $addressRef) {
                return;
            }
        }
        $this->fail('Seems like address has not been created properly');
    }

    /**
     * @param string $addressRef
     * @param array $addresses
     * @return void
     */
    private function assertAddressMissing(string $addressRef, array $addresses): void
    {
        foreach ($addresses as $address) {
            if ($address['Ref'] === $addressRef) {
                $this->fail('Address has not been deleted.');
            }
        }
    }
}
