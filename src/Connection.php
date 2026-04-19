<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Utils;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Connection
 */
class Connection
{
    private const API_URI = 'https://api.novaposhta.ua/v2.0/json/';
    private const ERROR_MSG_TEMPLATE = 'Connection to Nova Poshta API failed: %s';
    private string $apiKey;
    private HttpClient $client;

    /**
     * @param string $apiKey
     * @param HttpClient $client
     */
    public function __construct(string $apiKey, HttpClient $client)
    {
        $this->apiKey = $apiKey;
        $this->client = $client;
    }

    /**
     * @param string $model
     * @param string $method
     * @param array $params
     * @return array
     * @throws NovaPoshtaApiException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function post(string $model, string $method, array $params = []): array
    {
        try {
            $request = array_filter([
                'apiKey' => $this->apiKey,
                'modelName' => $model,
                'calledMethod' => $method,
                'methodProperties' => $params
            ]);
            $response = $this->client->request('POST', self::API_URI, [
                RequestOptions::TIMEOUT => 10,
                RequestOptions::BODY => Utils::jsonEncode($request, JSON_UNESCAPED_UNICODE),
                RequestOptions::HEADERS => [
                    'content-type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
            if ($response->getStatusCode() !== 200) {
                throw new NovaPoshtaApiException(sprintf(self::ERROR_MSG_TEMPLATE, $response->getReasonPhrase()));
            }

            $body = $this->getResponseBody($response);

            if ($body['success'] === false) {
                $error = $body['errors'][0] ?? $body['warnings'][0] ?? 'Unknown error';
                $errorCode = $body['errorCodes'][0] ?? $body['warningCodes'][0] ?? 0;
                throw new NovaPoshtaApiException(sprintf(self::ERROR_MSG_TEMPLATE, $error), (int)$errorCode);
            }
            return $body['data'];
        } catch (GuzzleException $e) {
            throw new NovaPoshtaApiException(sprintf(self::ERROR_MSG_TEMPLATE, $e->getMessage()), $e->getCode(), $e);
        }
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws NovaPoshtaApiException
     */
    private function getResponseBody(ResponseInterface $response): array
    {
        $content = $response->getBody()->getContents();
        $result = json_decode($content, true);
        if (empty($result)) {
            throw new NovaPoshtaApiException('Invalid response from Nova Poshta API');
        }
        return $result;
    }
}
