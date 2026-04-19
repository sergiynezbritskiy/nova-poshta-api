<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta;

use GuzzleHttp\Client as HttpClient;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\Models\Common;
use SergiyNezbritskiy\NovaPoshta\Models\ContactPerson;
use SergiyNezbritskiy\NovaPoshta\Models\Counterparty;
use SergiyNezbritskiy\NovaPoshta\Models\InternetDocument;

/**
 * Class Client
 *
 * Class-connector with NovaPoshta API
 *
 * @see      https://developers.novaposhta.ua/documentation
 */
readonly class Client
{
    public Address $address;
    public Counterparty $counterparty;
    public ContactPerson $contactPerson;
    public Common $common;
    public InternetDocument $internetDocument;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $connection = new Connection($apiKey, new HttpClient());
        $this->address = new Address($connection);
        $this->counterparty = new Counterparty($connection);
        $this->contactPerson = new ContactPerson($connection);
        $this->common = new Common($connection);
        $this->internetDocument = new InternetDocument($connection);
    }
}
