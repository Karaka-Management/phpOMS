<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\DataStorage\Database\Query
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\Stdlib\Base\Enum;

/**
 * Query type enum.
 *
 * @package phpOMS\DataStorage\Database\Query
 * @license OMS License 1.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
abstract class JoinType extends Enum
{
    public const JOIN = 'JOIN';

    public const LEFT_JOIN = 'LEFT JOIN';

    public const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';

    public const LEFT_INNER_JOIN = 'LEFT INNER JOIN';

    public const RIGHT_JOIN = 'RIGHT JOIN';

    public const RIGHT_OUTER_JOIN = 'RIGHT OUTER JOIN';

    public const RIGHT_INNER_JOIN = 'RIGHT INNER JOIN';

    public const OUTER_JOIN = 'OUTER JOIN';

    public const INNER_JOIN = 'INNER JOIN';

    public const CROSS_JOIN = 'CROSS JOIN';

    public const FULL_JOIN = 'FULL JOIN';

    public const FULL_OUTER_JOIN = 'FULL OUTER JOIN';
}
