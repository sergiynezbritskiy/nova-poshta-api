<?php

declare(strict_types=1);

namespace SergiyNezbritskiy\NovaPoshta\Tests;

interface ConstantsInterface
{
    public const CITY_REF_KHARKIV = 'db5c88e0-391c-11dd-90d9-001a92567626';
    public const CITY_REF_KYIV = '8d5a980d-391c-11dd-90d9-001a92567626';

    /**
     * Counterparty Ref
     * Приватна Особа Іванов Іван Іванович
     */
    public const COUNTERPARTY_REF = '85d18dbb-b8fa-11ed-a60f-48df37b921db';

    /**
     * Вулиця Тимурiвцiв
     */
    public const STREET_REF = 'a7503d2c-8c06-11de-9467-001ec9d9f7b7';

    /**
     * Ідентифікатор типу відправлення Шина R-13, з довідника Види шин та дисків
     *
     * @see \SergiyNezbritskiy\NovaPoshta\Models\Common::getTiresWheelsList
     */
    public const CARGO_TYPE_TIRES_WHEELS_DESCRIPTION = 'd7c456cf-aa8b-11e3-9fa0-0050568002cf';

    /**
     * @see \SergiyNezbritskiy\NovaPoshta\Models\Common::getPalletsList()
     */
    public const CARGO_TYPE_PALLETS_DESCRIPTION = '627b0c26-d110-11dd-8c0d-001d92f78697';

    /**
     * @see \SergiyNezbritskiy\NovaPoshta\Models\Common::getCargoDescriptionList()
     */
    public const CARGO_TYPE_CARGO_DESCRIPTION = '223a10d1-33f5-11e3-b441-0050568002cf';
}
