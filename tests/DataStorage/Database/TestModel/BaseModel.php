<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

class BaseModel
{
    protected int $id = 0;

    public string $string = 'Base';

    public string $conditional = '';

    public int $int = 11;

    public bool $bool = false;

    public float $float = 1.3;

    public $null = null;

    public \DateTime $datetime;

    public ?\DateTime $datetime_null = null;

    public array $hasManyDirect = [];

    public array $hasManyRelations = [];

    public $ownsOneSelf = 0;

    public $belongsToOne = 0;

    public ?object $serializable = null;

    public array $json = [1, 2, 3];

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

        $this->serializable = new class() {
            public $value = '';

            public function __serialize()
            {
                return ['123'];
            }

            public function __unserialize($data) : void
            {
                $this->value = $data;
            }
        };

        $this->jsonSerializable = new class() implements \JsonSerializable {
            public function jsonSerialize()
            {
                return [1, 2, 3];
            }
        };
    }

    public function getId() : int
    {
        return $this->id;
    }
}
