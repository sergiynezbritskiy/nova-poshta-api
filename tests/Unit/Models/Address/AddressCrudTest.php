<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests\Unit\Models\Address;

use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SergiyNezbritskiy\NovaPoshta\Connection;
use SergiyNezbritskiy\NovaPoshta\Models\Address;
use SergiyNezbritskiy\NovaPoshta\NovaPoshtaApiException;

class AddressCrudTest extends TestCase
{
    private Address $model;

    /**
     * @return void
     * @throws Exception
     */
    protected function setUp(): void
    {
        $connection = $this->createMock(Connection::class);
        $this->model = new Address($connection);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testCreateAddressTooLongNote(): void
    {
        $note = 'This is the note that exceeds forty symbols - the limit from Nova Poshta API';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Note exceeds the limit of 40 symbols');
        $this->model->save('', [], $note);
    }

    /**
     * @throws NovaPoshtaApiException
     */
    public function testUpdateAddressTooLongNote(): void
    {
        $note = 'This is the note that exceeds forty symbols - the limit from Nova Poshta API';
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Note exceeds the limit of 40 symbols');
        $this->model->update([], $note);
    }
}
