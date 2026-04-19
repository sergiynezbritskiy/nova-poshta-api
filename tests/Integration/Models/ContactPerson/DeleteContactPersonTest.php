<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Integration\Models\ContactPerson;

use Exception;
use PHPUnit\Framework\TestCase;
use SergiyNezbritskiy\NovaPoshta\Models\ContactPerson;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;
use SergiyNezbritskiy\NovaPoshta\Tests\UsesConnectionTrait;

class DeleteContactPersonTest extends TestCase
{
    use UsesConnectionTrait;

    private ContactPerson $model;

    protected function setUp(): void
    {
        $connection = $this->getConnection();
        $this->model = new ContactPerson($connection);
    }

    /**
     * @return void
     * @throws NovaPoshtaApiException
     * @throws Exception
     */
    public function testDeleteNotExistingContactPerson(): void
    {
        $this->model->delete('not-existing-contact-person-ref');
        $this->assertTrue(true, 'No exception has been thrown, we are good');
    }
}
