<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Schema\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Schema\Grammar;

/**
 * Database query grammar.
 *
 * @package phpOMS\DataStorage\Database\Schema\Grammar
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
class SqlServerGrammar extends Grammar
{
    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $systemIdentifierStart = '[';

    /**
     * System identifier.
     *
     * @var string
     * @since 1.0.0
     */
    protected string $systemIdentifierEnd = ']';
}
