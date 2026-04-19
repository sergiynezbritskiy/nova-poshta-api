<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\Counterparty;

use Exception;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\ContactPerson;
use SergiyNezbritskiy\NovaPoshta\Models\Counterparty;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class SavePrivatePersonTest extends TestCase implements ConstantsInterface
{
    use UsesConnectionTrait;

    private Counterparty $model;
    private ContactPerson $contactPersonModel;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new Counterparty($connection);
        $this->contactPersonModel = new ContactPerson($connection);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testCounterpartyPrivatePersonCrud(): void
    {
        $sfx = $this->randomString(5);
        $counterparty = [
            'FirstName' => 'Іван' . $sfx,
            'MiddleName' => 'Іванович' . $sfx,
            'LastName' => 'Іванов' . $sfx,
            'Phone' => rand(380000000000, 380000999999),
            'Email' => 'ivan.ivanov@nova-poshta.test',
            'CounterpartyProperty' => 'Recipient',
        ];

        //create counterparty
        $actualResult = $this->model->savePrivatePerson($counterparty);
        $this->assertIsCounterparty($actualResult);

        //update counterparty
        $counterparty['Ref'] = $actualResult['Ref'];
        $counterparty['MiddleName'] = 'Петрович' . $sfx;
        $counterparty['CityRef'] = self::CITY_REF_KHARKIV;

        $actualResult = $this->model->updatePrivatePerson($counterparty);
        $this->assertSame($counterparty['MiddleName'], $actualResult['MiddleName']);

        //get counterparty options
        $counterpartyOptions = $this->model->getCounterpartyOptions($counterparty['Ref']);
        $this->assertIsCounterpartyOptions($counterpartyOptions);

        //get counterparty contact persons
        $contactPersons = $this->model->getCounterpartyContactPersons($counterparty['Ref']);
        $this->assertIsContactPerson($contactPersons[0]);

        //delete counterparty
        foreach ($contactPersons as $contactPerson) {
            $this->contactPersonModel->delete($contactPerson['Ref']);
        }
        $this->expectExceptionMessage('Counterparty PrivatePerson can\'t be deleted');
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
        $this->assertArrayHasKey('FirstName', $counterparty);
        $this->assertArrayHasKey('LastName', $counterparty);
        $this->assertArrayHasKey('Counterparty', $counterparty);
        $this->assertArrayHasKey('EDRPOU', $counterparty);
        $this->assertArrayHasKey('CounterpartyType', $counterparty);
    }

    /**
     * @param array $contactPerson
     * @return void
     */
    private function assertIsContactPerson(array $contactPerson): void
    {
        $this->assertArrayHasKey('Ref', $contactPerson);
        $this->assertArrayHasKey('Description', $contactPerson);
        $this->assertArrayHasKey('Phones', $contactPerson);
        $this->assertArrayHasKey('FirstName', $contactPerson);
        $this->assertArrayHasKey('LastName', $contactPerson);
        $this->assertArrayHasKey('MiddleName', $contactPerson);
        $this->assertArrayHasKey('Email', $contactPerson);
    }

    /**
     * @param array $counterpartyOptions
     * @return void
     */
    private function assertIsCounterpartyOptions(array $counterpartyOptions): void
    {
        $this->assertArrayHasKey('Debtor', $counterpartyOptions);
        $this->assertArrayHasKey('DebtorParams', $counterpartyOptions);
        $this->assertArrayHasKey('SecurePayment', $counterpartyOptions);
        $this->assertArrayHasKey('MainCounterparty', $counterpartyOptions);
    }

    /**
     * @throws Exception
     */
    private function randomString(int $length = 10): string
    {
        $characters = 'абвгґдеєжзиіїйклмнопрстуфхцчшщьюяАБВГҐДЕЄЖЗИІЇЙКЛМНОПРСТУФХЦЧШЩЬЮЯ';
        $charactersLength = mb_strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $symbolPosition = random_int(0, $charactersLength - 1);
            $randomString .= mb_substr($characters, $symbolPosition, 1);
        }
        return $randomString;
    }
}
