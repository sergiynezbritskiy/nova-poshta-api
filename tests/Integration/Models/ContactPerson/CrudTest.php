<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\ContactPerson;

use Exception;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\ContactPerson;
use SergiyNezbritskiy\NovaPoshta\Models\Counterparty;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

use function count;

class CrudTest extends TestCase
{
    use UsesConnectionTrait;

    private ContactPerson $model;
    private Counterparty $counterpartyModel;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new ContactPerson($connection);
        $this->counterpartyModel = new Counterparty($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     * @throws Exception
     */
    public function testContactPersonCrud(): void
    {
        $sfx = $this->randomString(5);
        $counterparty = $this->counterpartyModel->savePrivatePerson([
            'FirstName' => 'Іван' . $sfx,
            'MiddleName' => 'Іванович' . $sfx,
            'LastName' => 'Іванов' . $sfx,
            'Email' => 'ivan.ivanov.' . substr(uniqid(), 0, 5) . '@nova.poshta.test',
            'Phone' => rand(380000000000, 380000999999),
            'CounterpartyProperty' => 'Recipient',
        ]);
        if (filter_var(getenv('CLEAR_CONTACT_PERSONS'), FILTER_VALIDATE_BOOL)) {
            $this->clearContactPersons($counterparty);
        }

        //create counterparty
        $contactPersonData = [
            'FirstName' => 'Петро' . $sfx,
            'MiddleName' => 'Петрович' . $sfx,
            'LastName' => 'Петров' . $sfx,
            'Email' => 'petro.petrov.' . substr(uniqid(), 0, 5) . '@nova.poshta.test',
            'Phone' => rand(380000000000, 380000999999),
        ];
        $contactPerson = $this->model->save($counterparty['Ref'], $contactPersonData);
        $this->assertContactPerson($counterparty, $contactPerson, ['Email', 'MiddleName']);


        //update contact person
        $contactPersonData['Ref'] = $contactPerson['Ref'];
        $contactPersonData['MiddleName'] = 'Сидорович' . $sfx;
        $contactPersonUpdated = $this->model->update($counterparty['Ref'], $contactPersonData);
        $this->assertContactPerson($counterparty, $contactPersonUpdated, ['Ref', 'MiddleName', 'Email']);

        //delete contact person
        $this->model->delete($contactPerson['Ref']);
        $this->assertContactPersonMissing($counterparty['Ref']);

        //delete counterparty contact person
        $this->model->delete($contactPerson['Ref']);
    }

    /**
     * @param array $counterparty
     * @param array $expectedResult
     * @param array $compareAttributes
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function assertContactPerson(array $counterparty, array $expectedResult, array $compareAttributes): void
    {
        $page = 1;
        do {
            $contactPersons = $this->counterpartyModel->getCounterpartyContactPersons($counterparty['Ref'], $page++);
            foreach ($contactPersons as $contactPerson) {
                if ($this->ensureContactPerson($contactPerson, $expectedResult, $compareAttributes)) {
                    $this->assertTrue(true, 'Imitate that we did an assertion');
                    return;
                }
            }
        } while (count($contactPersons) > 0);
        $this->fail('Contact person not found');
    }

    /**
     * @param string $ref
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function assertContactPersonMissing(string $ref): void
    {
        $page = 1;
        do {
            $result = $this->counterpartyModel->getCounterpartyContactPersons($ref, $page++);
            foreach ($result as $contactPerson) {
                if ($this->ensureContactPerson($contactPerson, ['Ref' => $ref], ['Ref'])) {
                    $this->fail('Assert failed that counterparty `' . $ref . '` has been deleted');
                }
            }
        } while (count($result) > 0);
        $this->assertTrue(true, 'Imitate that we did an assertion');
    }

    /**
     * @param array $contactPerson
     * @param array $expectedResult
     * @param array $compareAttributes
     * @return bool
     */
    private function ensureContactPerson(array $contactPerson, array $expectedResult, array $compareAttributes): bool
    {
        foreach ($compareAttributes as $attribute) {
            if ($contactPerson[$attribute] !== $expectedResult[$attribute]) {
                return false;
            }
        }
        return true;
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

    /**
     * @param array $counterparty
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function clearContactPersons(array $counterparty): void
    {
        do {
            $result = $this->counterpartyModel->getCounterpartyContactPersons($counterparty['Ref']);
            foreach ($result as $contactPerson) {
                $this->model->delete($contactPerson['Ref']);
                $template = 'Contact person %s %s has been deleted' . PHP_EOL;
                echo sprintf($template, $contactPerson['FirstName'], $contactPerson['LastName']);
            }
        } while (count($result) > 0);
    }
}
