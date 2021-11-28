<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   phpOMS\DataStorage\Database\Mapper
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Mapper;

use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;
use phpOMS\DataStorage\Database\Query\OrderType;

/**
 * Mapper abstract.
 *
 * @package phpOMS\DataStorage\Database\Mapper
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
abstract class DataMapperAbstract
{
    protected DataMapperFactory $mapper;

    protected int $type = 0;

    protected array $with = [];

	protected array $sort = [];

	protected array $limit = [];

	protected array $where = [];

	/**
	 * Database connection.
	 *
	 * @var ConnectionAbstract
	 * @since 1.0.0
	 */
	protected ConnectionAbstract $db;

	public function __construct(DataMapperFactory $mapper, ConnectionAbstract $db)
	{
		$this->mapper = $mapper;
		$this->db     = $db;
    }

    // Only for relations, no impact on anything else
	public function with(string $member) : self
	{
		$split       = \explode('/', $member);
		$memberSplit = \array_shift($split);

		$this->with[$memberSplit ?? ''][] = [
			'child' => \implode('/', $split),
		];

		return $this;
	}

	public function sort(string $member, string $order = OrderType::DESC) : self
	{
		$split       = \explode('/', $member);
		$memberSplit = \array_shift($split);

		$this->sort[$memberSplit ?? ''][] = [
			'child' => \implode('/', $split),
			'order' => $order,
		];

		return $this;
	}

	public function limit(int $limit = 0, string $member = '') : self
	{
		$split       = \explode('/', $member);
		$memberSplit = \array_shift($split);

		$this->limit[$memberSplit ?? ''][] = [
			'child' => \implode('/', $split),
			'limit' => $limit,
		];

		return $this;
	}

	public function where(string $member, mixed $value, string $logic = '=', string $comparison = 'AND') : self
	{
		$split       = \explode('/', $member);
		$memberSplit = \array_shift($split);

		$this->where[$memberSplit ?? ''][] = [
			'child'      => \implode('/', $split),
			'value'      => $value,
			'logic'      => $logic,
			'comparison' => $comparison,
		];

		return $this;
    }

    public function createRelationMapper(self $mapper, string $member) : self
    {
        $relMapper = $mapper;

        if (isset($this->with[$member])) {
            foreach ($this->with[$member] as $with) {
                if ($with['child'] === '') {
                    continue;
                }

                $relMapper->with($with['child']);
            }
        }

        if (isset($this->sort[$member])) {
            foreach ($this->sort[$member] as $sort) {
                if ($sort['child'] === '') {
                    continue;
                }

                $relMapper->sort($sort['child'], $sort['order']);
            }
        }

        if (isset($this->limit[$member])) {
            foreach ($this->limit[$member] as $limit) {
                if ($limit['child'] === '') {
                    continue;
                }

                $relMapper->limit($limit['limit'], $limit['child']);
            }
        }

        if (isset($this->where[$member])) {
            foreach ($this->where[$member] as $where) {
                if ($where['child'] === '') {
                    continue;
                }

                $relMapper->where($where['child'], $where['value'], $where['logic'], $where['comparison']);
            }
        }

    	return $relMapper;
    }

    /**
     * Parse value
     *
     * @param string $type  Value type
     * @param mixed  $value Value to parse
     *
     * @return mixed
     *
     * @since 1.0.0
     */
    public function parseValue(string $type, mixed $value = null) : mixed
    {
        if ($value === null) {
            return null;
        } elseif ($type === 'int') {
            return (int) $value;
        } elseif ($type === 'string') {
            return (string) $value;
        } elseif ($type === 'float') {
            return (float) $value;
        } elseif ($type === 'bool') {
            return (bool) $value;
        } elseif ($type === 'DateTime' || $type === 'DateTimeImmutable') {
            return $value === null ? null : $value->format($this->mapper::$datetimeFormat);
        } elseif ($type === 'Json' || $value instanceof \JsonSerializable) {
            return (string) \json_encode($value);
        } elseif ($type === 'Serializable') {
            return $value->serialize();
        } elseif (\is_object($value) && \method_exists($value, 'getId')) {
            return $value->getId();
        }

        return $value;
    }

	abstract public function execute(...$options);
}
