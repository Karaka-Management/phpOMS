<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\tests\DataStorage\Database\TestModel;

use phpOMS\Contract\SerializableInterface;

class BaseModel
{
    public int $id = 0;

    public string $string = 'Base';

    public string $compress = 'Uncompressed';

    private string $pstring = 'Private';

    public string $conditional = '';

    public int $int = 11;

    public bool $bool = false;

    public float $float = 1.3;

    public $null = null;

    public \DateTime $datetime;

    public ?\DateTime $datetime_null = null;

    public array $hasManyDirect = [];

    public array $hasManyRelations = [];

    private array $hasManyDirectPrivate = [];

    private array $hasManyRelationsPrivate = [];

    public $ownsOneSelf = 0;

    public $belongsToOne = 0;

    private $ownsOneSelfPrivate = 0;

    private $belongsToOnePrivate = 0;

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

        $this->hasManyDirectPrivate = [
            new ManyToManyDirectModel(),
            new ManyToManyDirectModel(),
        ];

        $this->hasManyRelationsPrivate = [
            new ManyToManyRelModel(),
            new ManyToManyRelModel(),
        ];

        $this->ownsOneSelf  = new OwnsOneModel();
        $this->belongsToOne = new BelongsToModel();

        $this->serializable = new class() implements SerializableInterface {
            public $value = '';

            public function serialize() : string
            {
                return '123';
            }

            public function unserialize(mixed $data) : void
            {
                $this->value = $data;
            }
        };

        $this->jsonSerializable = new class() implements \JsonSerializable {
            public function jsonSerialize() : mixed
            {
                return [1, 2, 3];
            }
        };
    }

    public function getPString() : string
    {
        return $this->pstring;
    }

    public function getId() : int
    {
        return $this->id;
    }
}
