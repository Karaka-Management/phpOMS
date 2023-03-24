<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   phpOMS\Utils\Git
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\Utils\Git;

/**
 * Gray encoding class
 *
 * @package phpOMS\Utils\Git
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Author
{
    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    public string $name = '';

    /**
     * Email.
     *
     * @var string
     * @since 1.0.0
     */
    private string $email = '';

    /**
     * Commit count.
     *
     * @var int
     * @since 1.0.0
     */
    private int $commitCount = 0;

    /**
     * Additions count.
     *
     * @var int
     * @since 1.0.0
     */
    private int $additionsCount = 0;

    /**
     * Removals count.
     *
     * @var int
     * @since 1.0.0
     */
    private int $removalsCount = 0;

    /**
     * Constructor
     *
     * @param string $name  Author name
     * @param string $email Author email
     *
     * @since 1.0.0
     */
    public function __construct(string $name = '', string $email = '')
    {
        $this->name  = $name;
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string
     *
     * @since 1.0.0
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * Get commit count
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getCommitCount() : int
    {
        return $this->commitCount;
    }

    /**
     * Set commit count
     *
     * @param int $count Commit count
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setCommitCount(int $count) : void
    {
        $this->commitCount = $count;
    }

    /**
     * Set additions count
     *
     * @param int $count Commit count
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setAdditionCount(int $count) : void
    {
        $this->additionsCount = $count;
    }

    /**
     * Get additions count
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getAdditionCount() : int
    {
        return $this->additionsCount;
    }

    /**
     * Set removals count
     *
     * @param int $count Commit count
     *
     * @return void
     *
     * @since 1.0.0
     */
    public function setRemovalCount(int $count) : void
    {
        $this->removalsCount = $count;
    }

    /**
     * Get removals count
     *
     * @return int
     *
     * @since 1.0.0
     */
    public function getRemovalCount() : int
    {
        return $this->removalsCount;
    }
}
