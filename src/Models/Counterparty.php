<?php

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Models;

use SergiyNezbritskiy\NovaPoshta\Connection;
use SergiyNezbritskiy\NovaPoshta\ModelInterface;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;

/**
 * Class Counterparty
 * This class is for managing your counterparties
 *
 * @see https://new.novaposhta.ua/dashboard/contacts
 */
class Counterparty implements ModelInterface
{
    public const COUNTERPARTY_PROPERTY_SENDER = 'Sender';
    public const COUNTERPARTY_PROPERTY_RECIPIENT = 'Recipient';
    public const COUNTERPARTY_PROPERTY_THIRD_PERSON = 'ThirdPerson';

    private const MODEL_NAME = 'Counterparty';

    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/0ae5dd75-8a5f-11ec-8ced-005056b2dbe1
     * @param array $counterparty Array containing the necessary params.
     * Example:
     * ```
     * $counterparty = [
     *   'FirstName'             => (string) First name. Required.
     *   'MiddleName'            => (string) Middle name. Required.
     *   'LastName'              => (string) Last name. Required.
     *   'Phone'                 => (string) Phone number. Required.
     *   'Email'                 => (string) Email. Required.
     *   'CounterpartyProperty'  => (string) Counterparty property. Required.
     * ];
     * ```
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function savePrivatePerson(array $counterparty): array
    {
        $counterparty['CounterpartyType'] = 'PrivatePerson';
        $result = $this->connection->post(self::MODEL_NAME, 'save', $counterparty);
        return array_shift($result);
    }

    /**
     * https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/bc3c44c7-8a8a-11ec-8ced-005056b2dbe1
     * @param array $counterparty Array containing the necessary params.
     *    $counterparty = [
     *      'EDRPOU'                => (string) EDRPOU. Required.
     *      'CounterpartyProperty'  => (string) Counterparty property. Optional.
     *    ];
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function saveOrganisation(array $counterparty): array
    {
        $counterparty['CounterpartyType'] = 'Organization';
        $result = $this->connection->post(self::MODEL_NAME, 'save', $counterparty);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/0ae5dd75-8a5f-11ec-8ced-005056b2dbe1
     * @param array $counterparty Array containing the necessary params.
     *    $counterparty = [
     *      'Ref'                   => (string) Identifier. Required.
     *      'FirstName'             => (string) First name. Required.
     *      'MiddleName'            => (string) Middle name. Required.
     *      'LastName'              => (string) Last name. Required.
     *      'Phone'                 => (string) Phone number. Optional.
     *      'Email'                 => (string) Email. Optional.
     *      'CounterpartyProperty'  => (string) Counterparty property. Required.
     *    ];
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function updatePrivatePerson(array $counterparty): array
    {
        $counterparty['CounterpartyType'] = 'PrivatePerson';
        $result = $this->connection->post(self::MODEL_NAME, 'update', $counterparty);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a2eb27e8-8512-11ec-8ced-005056b2dbe1
     * @param string $ref
     * @return void
     * @throws NovaPoshtaApiException
     */
    public function delete(string $ref): void
    {
        $this->connection->post(self::MODEL_NAME, 'delete', ['Ref' => $ref]);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a332efbf-8512-11ec-8ced-005056b2dbe1
     * @param string $ref
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCounterpartyOptions(string $ref): array
    {
        $result = $this->connection->post(self::MODEL_NAME, 'getCounterpartyOptions', ['Ref' => $ref]);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a3575a67-8512-11ec-8ced-005056b2dbe1
     * @param string $ref
     * @param int $page
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCounterpartyContactPersons(string $ref, int $page = 1): array
    {
        $params = [
            'Ref' => $ref,
            'Page' => $page
        ];
        return $this->connection->post(self::MODEL_NAME, 'getCounterpartyContactPersons', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a37a06df-8512-11ec-8ced-005056b2dbe1
     * @param string $counterpartyProperty
     * @param string $search
     * @param int $page
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCounterparties(string $counterpartyProperty, string $search = '', int $page = 1): array
    {
        $params = array_filter([
            'CounterpartyProperty' => $counterpartyProperty,
            'FindByString' => $search,
            'Page' => $page
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getCounterparties', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a28f4b04-8512-11ec-8ced-005056b2dbe1/method/a30dbb7c-8512-11ec-8ced-005056b2dbe1
     * @param string $counterpartyRef
     * @param string $counterpartyProperty
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCounterpartyAddresses(string $counterpartyRef, string $counterpartyProperty): array
    {
        $params = [
            'Ref' => $counterpartyRef,
            'CounterpartyProperty' => $counterpartyProperty,
        ];
        return $this->connection->post(self::MODEL_NAME, 'getCounterpartyAddresses', $params);
    }
}
