<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\Localization\Defaults
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\Localization\Defaults;

use phpOMS\DataStorage\Database\DataMapperAbstract;

/**
 * Mapper class.
 *
 * @package    phpOMS\Localization\Defaults
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class IbanMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, bool|string>>
     * @since 1.0.0
     */
    protected static $columns = [
        'iban_id'      => ['name' => 'iban_id', 'type' => 'int', 'internal' => 'id'],
        'iban_country' => ['name' => 'iban_country', 'type' => 'string', 'internal' => 'country'],
        'iban_chars'   => ['name' => 'iban_chars', 'type' => 'int', 'internal' => 'chars'],
        'iban_bban'    => ['name' => 'iban_bban', 'type' => 'string', 'internal' => 'bban'],
        'iban_fields'  => ['name' => 'iban_fields', 'type' => 'string', 'internal' => 'fields'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $table = 'iban';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $primaryField = 'iban_id';
}
