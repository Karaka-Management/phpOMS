<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

class BaseModel
{
    public $id = 0;

    public $string = 'Base';

    public $conditional = '';

    public $int = 11;

    public $bool = false;

    public $float = 1.3;

    public $null = null;

    public $datetime = null;

    public $datetime_null = null;

    public $hasManyDirect = [];

    public $hasManyRelations = [];

    public $ownsOneSelf = 0;

    public $belongsToOne = 0;

    public $serializable = null;

    public $json = [1, 2, 3];

    public $jsonSerializable = null;

    public function __construct()
    {
        $this->datetime = new \DateTime('2005-10-11');

        $this->hasManyDirect = [
            new ManyToManyDirectModel(),
            new ManyToManyDirectModel(),
        ];

        $this->hasManyRelations = [
            new ManyToManyRelModel(),
            new ManyToManyRelModel(),
        ];

        $this->ownsOneSelf  = new OwnsOneModel();
        $this->belongsToOne = new BelongsToModel();

        $this->serializable = new class() implements \Serializable {
            public function serialize()
            {
                return '123';
            }

            public function unserialize($data) : void
            {

            }
        };

        $this->jsonSerializable = new class() implements \JsonSerializable {
            public function jsonSerialize()
            {
                return [1, 2, 3];
            }
        };
    }
}
