<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Models;

use SergiyNezbritskiy\NovaPoshta\Connection;
use SergiyNezbritskiy\NovaPoshta\ModelInterface;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;

class Common implements ModelInterface
{
    private const MODEL_NAME = 'Common';
    private Connection $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a56d5c1c-8512-11ec-8ced-005056b2dbe1
     * @param string $recipientCityRef
     * @param string $dateTime
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getTimeIntervals(string $recipientCityRef, string $dateTime = ''): array
    {
        $params = array_filter([
            'RecipientCityRef' => $recipientCityRef,
            'DateTime' => $dateTime,
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getTimeIntervals', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a5912a1e-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCargoTypes(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getCargoTypes');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a5b46873-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getBackwardDeliveryCargoTypes(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getBackwardDeliveryCargoTypes');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a5dd575e-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getPalletsList(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getPalletsList');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a6247f2f-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getTypesOfPayersForRedelivery(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getTypesOfPayersForRedelivery');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a6492db4-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getPackList(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getPackList');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a66fada0-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getTiresWheelsList(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getTiresWheelsList');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a697db47-8512-11ec-8ced-005056b2dbe1
     * @param string $findByString
     * @param int $page
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getCargoDescriptionList(string $findByString = '', int $page = 0): array
    {
        $params = array_filter([
            'FindByString' => $findByString,
            'Page' => $page,
        ]);
        return $this->connection->post(self::MODEL_NAME, 'getCargoDescriptionList', $params);
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a697db47-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getMessageCodeText(): array
    {
        return $this->connection->post('CommonGeneral', 'getMessageCodeText');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a6e189f7-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getServiceTypes(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getServiceTypes');
    }

    /**
     * @see https://developers.novaposhta.ua/view/model/a55b2c64-8512-11ec-8ced-005056b2dbe1/method/a754ff0d-8512-11ec-8ced-005056b2dbe1
     * @return array
     * @throws NovaPoshtaApiException
     */
    public function getOwnershipFormsList(): array
    {
        return $this->connection->post(self::MODEL_NAME, 'getOwnershipFormsList');
    }
}
