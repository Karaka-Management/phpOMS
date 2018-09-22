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
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\DataStorage\Database\Query\Column;
use phpOMS\DataStorage\Database\RelationType;

/**
 * Mapper class.
 *
 * @package    phpOMS\Localization\Defaults
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
class LanguageMapper extends DataMapperAbstract
{

    /**
     * Columns.
     *
     * @var array<string, array<string, string|bool>>
     * @since 1.0.0
     */
    protected static $columns = [
        'language_id'     => ['name' => 'language_id', 'type' => 'int', 'internal' => 'id'],
        'language_native' => ['name' => 'language_native', 'type' => 'string', 'internal' => 'name'],
        'language_639_1'  => ['name' => 'language_639_1', 'type' => 'string', 'internal' => 'native'],
        'language_639_2T' => ['name' => 'language_639_2T', 'type' => 'string', 'internal' => 'code2'],
        'language_639_2B' => ['name' => 'language_639_2B', 'type' => 'string', 'internal' => 'code3Native'],
        'language_639_3'  => ['name' => 'language_639_3', 'type' => 'string', 'internal' => 'code3'],
    ];

    /**
     * Primary table.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $table = 'language';

    /**
     * Primary field name.
     *
     * @var string
     * @since 1.0.0
     */
    protected static $primaryField = 'language_id';
}
