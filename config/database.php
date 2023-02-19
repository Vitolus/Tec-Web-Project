<?php

namespace Config;

final class Database
{
    /** @var string */
    public const HOST = 'localhost';

    /** @var int */
    public const PORT = 3306;

    /** @var string */
    public const USERNAME = 'root';

    /** @var string */
    public const PASSWORD = '';

    /** @var string */
    public const DATABASE = 'progettotecweb';

    /**
     * This class is not instanciable because it's only a support class containing the database configuration parameters
     */
    private function __construct()
    {
    }
}
