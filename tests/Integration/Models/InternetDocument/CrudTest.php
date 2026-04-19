<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\InternetDocument;

use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\InternetDocument;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\AssertEntityByPropertiesTrait;
use SergiyNezbritskiy\NovaPoshta\Tests\ConstantsInterface;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class CrudTest extends TestCase implements ConstantsInterface
{
    use AssertEntityByPropertiesTrait;
    use UsesConnectionTrait;

    private InternetDocument $model;

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Kyiv');
        $connection = $this->getConnection();
        $this->model = new InternetDocument($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     */
    protected function tearDown(): void
    {
        if (filter_var(getenv('CLEAR_DOCUMENTS'), FILTER_VALIDATE_BOOL)) {
            foreach ($this->getAllDocuments() as $document) {
                $this->deleteDocument($document['Ref']);
            }
        }
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testCrudDocument(): void
    {
//        In order to get sender and recipient refs create a document in UI and fetch this document
//        $documents = $this->getAllDocuments();
//        'Sender' => '85d0d7a2-b8fa-11ed-a60f-48df37b921db',
//        'ContactSender' => 'e8253e73-cc91-11ed-a60f-48df37b921db',
//        'SenderAddress' => 'dbbc2098-25b3-11ee-a60f-48df37b921db',
//        'Recipient' => '85d18dbb-b8fa-11ed-a60f-48df37b921db',
//        'RecipientAddress' => '1ec09d88-e1c2-11e3-8c4a-0050568002cf',
//        'ContactRecipient' => '4184cb87-59ec-11f0-a1d5-48df37b921da',

        $senderRef = '85d0d7a2-b8fa-11ed-a60f-48df37b921db';
        $senderContactRef = 'e8253e73-cc91-11ed-a60f-48df37b921db';
        $senderAddressRef = 'dbbc2098-25b3-11ee-a60f-48df37b921db';

        $recipientRef = '85d18dbb-b8fa-11ed-a60f-48df37b921db';
        $recipientContactRef = '4184cb87-59ec-11f0-a1d5-48df37b921da';
        $recipientAddressRef = '1ec09d88-e1c2-11e3-8c4a-0050568002cf';

        //create document
        $params = [
            'Sender' => $senderRef,
            'CitySender' => self::CITY_REF_KHARKIV,
            'SenderAddress' => $senderAddressRef,
            'ContactSender' => $senderContactRef,
            'SendersPhone' => 380507778797,

            'Recipient' => $recipientRef,
            'CityRecipient' => self::CITY_REF_KYIV,
            'RecipientAddress' => $recipientAddressRef,
            'ContactRecipient' => $recipientContactRef,
            'RecipientsPhone' => 380507778797,

            'DateTime' => date('d.m.Y'),
            'CargoType' => InternetDocument::CARGO_TYPE_CARGO,
            'Weight' => '0.5',
            'SeatsAmount' => '1',
            'ServiceType' => InternetDocument::SERVICE_TYPE_DOORS_DOORS,
            'PayerType' => InternetDocument::PAYER_TYPE_RECIPIENT,
            'PaymentMethod' => InternetDocument::PAYMENT_TYPE_CASH,
            'Description' => 'ЦЕ ТЕСТОВЕ ЗАМОВЛЕННЯ, В ЖОДНОМУ РАЗІ НЕ ОБРОБЛЯТИ !!!'
        ];

        $actualResult = $this->model->save($params);
        $this->assertNotEmpty($actualResult['Ref']);

        $params['PayerType'] = InternetDocument::PAYER_TYPE_SENDER;
        $params['Ref'] = $actualResult['Ref'];

        $this->gulp();

        $actualResult = $this->model->update($params);

        $this->gulp();

        $document = $this->getDocumentByRef($actualResult['Ref']);
        $this->assertSame($params['PayerType'], $document['PayerType']);

        $this->gulp();

        //delete document
        $this->deleteDocument($actualResult['Ref']);

        $this->gulp();

        $this->assertDocumentDeleted($actualResult['Ref']);
    }

    /**
     * @param string $ref
     * @param int $attempt
     * @return void
     * @throws NovaPoshtaApiException
     * @SuppressWarnings(PHPMD.ElseExpression)
     */
    private function deleteDocument(string $ref, int $attempt = 1): void
    {
        try {
            $this->model->delete($ref);
        } catch (NovaPoshtaApiException $e) {
            $docNotCreatedYet = str_contains($e->getMessage(), 'No document changed DeletionMark');
            if (!$docNotCreatedYet) {
                throw $e;
            }
            $attemptsNotExceeded = $attempt <= 3;
            if ($attemptsNotExceeded) {
                printf(PHP_EOL . 'Attempt %d to delete document failed.' . PHP_EOL, $attempt);
                sleep(5 * $attempt);
                $this->deleteDocument($ref, ++$attempt);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param string $ref
     * @return void
     * @throws NovaPoshtaApiException
     */
    private function assertDocumentDeleted(string $ref): void
    {
        if ($this->getDocumentByRef($ref)) {
            $this->fail('Failed to delete document.');
        }
        $this->assertTrue(true, 'Just to suppress error that method doesn\'t do any assertions');
    }

    /**
     * @param string $ref
     * @return array|null
     * @throws NovaPoshtaApiException
     */
    public function getDocumentByRef(string $ref): ?array
    {
        $documents = $this->getAllDocuments();
        foreach ($documents as $document) {
            if ($document['Ref'] === $ref) {
                return $document;
            }
        }
        return null;
    }

    /**
     * @return array
     * @throws NovaPoshtaApiException
     */
    private function getAllDocuments(): array
    {
        return $this->model->getDocumentList([
            'DateTimeFrom' => date('d.m.Y', strtotime('-2 days')),
            'DateTimeTo' => date('d.m.Y', strtotime('+2 days')),
            'GetFullList' => 1,
            'DateTime' => date('d.m.Y'),
        ], 1);
    }

    /**
     * @return void
     */
    private function gulp(): void
    {
        $gulp = (int)getenv('GULP');

        if ($gulp) {
            sleep($gulp);
        }
    }
}
