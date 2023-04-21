<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Localization\LanguageDetection
 * @author    Patrick Schur <patrick_schur@outlook.de>
 * @copyright Patrick Schur
 * @license   https://opensource.org/licenses/mit-license.html MIT
 * @link      https://github.com/patrickschur/language-detection
 */
declare(strict_types = 1);

namespace phpOMS\Localization\LanguageDetection;

/**
 * Langauge match result
 *
 * @package phpOMS\Localization\LanguageDetection
 * @license https://opensource.org/licenses/mit-license.html MIT
 * @link    https://github.com/patrickschur/language-detection
 * @since   1.0.0
 */
class LanguageResult implements \JsonSerializable, \IteratorAggregate, \ArrayAccess
{
    /**
     * Match threshold
     *
     * @var int
     * @since 1.0.0
     */
    private const THRESHOLD = .025;

    /**
     * Match values per language
     *
     * @var float[]
     * @sicne 1.0.0
     */
    private array $result = [];

    /**
     * Constructor.
     *
     * @param array $result Langauge match results
     *
     * @since 1.0.0
     */
    public function __construct(array $result = [])
    {
        $this->result = $result;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return isset($this->result[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): ?float
    {
        return $this->result[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        if (null === $offset) {
            $this->result[] = $value;
        } else {
            $this->result[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        unset($this->result[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): array
    {
        return $this->result;
    }

    /**
     * Stringify
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function __toString(): string
    {
        return (string) \key($this->result);
    }

    /**
     * Only return whitelisted results
     *
     * @param \string[] ...$whitelist List of whitelisted languages
     *
     * @return LanguageResult
     *
     * @since 1.0.0
     */
    public function whitelist(string ...$whitelist): LanguageResult
    {
        return new LanguageResult(\array_intersect_key($this->result, \array_flip($whitelist)));
    }

    /**
     * Remove blacklisted languages
     *
     * @param \string[] ...$blacklist List of blacklist languages
     *
     * @return LanguageResult
     *
     * @since 1.0.0
     */
    public function blacklist(string ...$blacklist): LanguageResult
    {
        return new LanguageResult(\array_diff_key($this->result, \array_flip($blacklist)));
    }

    /**
     * Get languages results
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function close(): array
    {
        return $this->result;
    }

    /**
     * Get results based on internally defined threshold
     *
     * @return LanguageResult
     *
     * @since 1.0.0
     */
    public function bestResults(): LanguageResult
    {
        if (!\count($this->result)) {
            return new LanguageResult;
        }

        $first = \array_values($this->result)[0];

        return new LanguageResult(\array_filter($this->result, function ($value) use ($first) {
            return ($first - $value) <= self::THRESHOLD ? true : false;
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->result);
    }

    /**
     * Get results A to B
     *
     * @param int      $offset Zero indexed start value
     * @param null|int $length Number of results
     *
     * @return LanguageResult
     *
     * @since 1.0.0
     */
    public function limit(int $offset, int $length = null): LanguageResult
    {
        return new LanguageResult(\array_slice($this->result, $offset, $length));
    }
}
