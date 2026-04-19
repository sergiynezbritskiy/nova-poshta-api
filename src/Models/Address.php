<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Models;

use RuntimeException;
use SergiyNezbritskiy\NovaPoshta\Connection;
use SergiyNezbritskiy\NovaPoshta\ModelInterface;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;

class Address implements ModelInterface
{
    private const MODEL_NAME = 'Address';
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a0eb83ab-8512-11ec-8ced-005056b2dbe1
     * @param string $cityName
     * @param int $page
     * @param int $limit
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function searchSettlements(string $cityName, int $page = 1, int $limit = PHP_INT_MAX): array
    {
        $params = array_filter([
            'CityName' => $cityName,
            'Page' => $page,
            'Limit' => $limit,
        ]);
        $result = $this->connection->post(self::MODEL_NAME, 'searchSettlements', $params);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1329635-8512-11ec-8ced-005056b2dbe1
     * @param string $streetName
     * @param string $settlementRef
     * @param int $limit
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function searchSettlementStreets(string $streetName, string $settlementRef, int $limit = 0): array
    {
        $params = array_filter([
            'StreetName' => $streetName,
            'SettlementRef' => $settlementRef,
            'Limit' => $limit,
        ]);
        $result = $this->connection->post(self::MODEL_NAME, 'searchSettlementStreets', $params);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a155d0d9-8512-11ec-8ced-005056b2dbe1
     * @param string $counterpartyRef
     * @param array $address
     * @param string|null $note
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function save(string $counterpartyRef, array $address, ?string $note = null): array
    {
        if ($note && strlen($note) > 40) {
            throw new RuntimeException('Note exceeds the limit of 40 symbols');
        }
        $params = array_filter([
            'CounterpartyRef' => $counterpartyRef,
            'StreetRef' => $address['StreetRef'],
            'BuildingNumber' => $address['BuildingNumber'],
            'Flat' => $address['Flat'],
            'Note' => $note
        ]);
        $result = $this->connection->post(self::MODEL_NAME, 'save', $params);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a19ba934-8512-11ec-8ced-005056b2dbe1
     * @param array $address
     * @param string $note
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function update(array $address, string $note = ''): array
    {
        if ($note && strlen($note) > 40) {
            throw new RuntimeException('Note exceeds the limit of 40 symbols');
        }
        $params = array_filter([
            'Ref' => $address['Ref'] ?? null,
            'CounterpartyRef' => $address['CounterpartyRef'] ?? null,
            'StreetRef' => $address['StreetRef'] ?? null,
            'BuildingNumber' => $address['BuildingNumber'] ?? null,
            'Flat' => $address['Flat'],
            'Note' => $note
        ]);
        $result = $this->connection->post(self::MODEL_NAME, 'update', $params);
        return array_shift($result);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a177069a-8512-11ec-8ced-005056b2dbe1
     * @param string $addressRef
     * @return void
     * @throws NovaPoshtaApiException
     */
    public function delete(string $addressRef): void
    {
        $this->connection->post(self::MODEL_NAME, 'delete', ['Ref' => $addressRef]);
    }

    /**
     * https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1c42723-8512-11ec-8ced-005056b2dbe1
     * @param array $filters
     * @param bool $warehouse
     * @param int $page
     * @param int $limit
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getSettlements(array $filters, bool $warehouse, int $page = 0, int $limit = 150): array
    {
        $params = array_filter([
            'AreaRef' => $filters['AreaRef'] ?? null,
            'Ref' => $filters['Ref'] ?? null,
            'RegionRef' => $filters['RegionRef'] ?? null,
            'FindByString' => $filters['FindByString'] ?? null,
            'Warehouse' => (int)$warehouse,
            'Page' => $page,
            'Limit' => $limit,
        ]);
        return $this->connection->post('AddressGeneral', 'getSettlements', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a1e6f0a7-8512-11ec-8ced-005056b2dbe1
     * @param int $page
     * @param int $limit
     * @param string $search
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCities(int $page = 1, int $limit = PHP_INT_MAX, string $search = ''): array
    {
        $params = array_filter([
            'Page' => (string)$page,
            'Limit' => (string)$limit,
            'FindByString' => $search,
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getCities', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a20ee6e4-8512-11ec-8ced-005056b2dbe1
     * @throws NovaPoshtaApiException
     */
    public function getAreas(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getAreas');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a2322f38-8512-11ec-8ced-005056b2dbe1
     * @param array $filters Available filters are: CityName, CityRef, TypeOfWarehouseRef, WarehouseId
     * @param int $page
     * @param int $limit
     * @param string $lang
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getWarehouses(array $filters = [], int $page = 1, int $limit = 1000, string $lang = 'UA'): array
    {
        $params = array_filter([
            'CityName' => $filters['CityName'] ?? null,
            'CityRef' => $filters['CityRef'] ?? null,
            'TypeOfWarehouseRef' => $filters['TypeOfWarehouseRef'] ?? null,
            'WarehouseId' => $filters['WarehouseId'] ?? null,
            'Page' => $page,
            'Limit' => $limit,
            'Language' => $lang,
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getWarehouses', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a2587b53-8512-11ec-8ced-005056b2dbe1
     * @throws NovaPoshtaApiException
     */
    public function getWarehouseTypes(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getWarehouseTypes');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a0cf0f5f-8512-11ec-8ced-005056b2dbe1/method/a27c20d7-8512-11ec-8ced-005056b2dbe1
     * @param string $cityRef
     * @param string $findByString
     * @param int $page
     * @param int $limit
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getStreet(string $cityRef, string $findByString, int $page = 0, int $limit = 0): array
    {
        $params = array_filter([
            'CityRef' => $cityRef,
            'FindByString' => $findByString,
            'Page' => $page,
            'Limit' => $limit,
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getStreet', $params);
    }
}
