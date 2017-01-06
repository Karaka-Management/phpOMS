<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @copyright  2013 Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
namespace phpOMS\Utils\Git;

/**
 * Gray encoding class
 *
 * @category   Framework
 * @package    phpOMS\Asset
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
class Author
{
    /**
     * Name.
     *
     * @var string
     * @since 1.0.0
     */
    private $name = '';

    /**
     * Email.
     *
     * @var string
     * @since 1.0.0
     */
    private $email = '';

    /**
     * Commit count.
     *
     * @var int
     * @since 1.0.0
     */
    private $commitCount = 0;

    /**
     * Additions count.
     *
     * @var int
     * @since 1.0.0
     */
    private $additionsCount = 0;

    /**
     * Removals count.
     *
     * @var int
     * @since 1.0.0
     */
    private $removalsCount = 0;

    /**
     * Constructor
     *
     * @param string $name  Author name
     * @param string $email Author email
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $name = '', string $email = '')
    {
        $this->name  = $name;
        $this->email = $email;
    }

    /**
     * Get name
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get email
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setCommitCount(int $count) /* : void */
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setAdditionCount(int $count) /* : void */
    {
        $this->additionsCount = $count;
    }

    /**
     * Get additions count
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
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
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setRemovalCount(int $count) /* : void */
    {
        $this->removalsCount = $count;
    }

    /**
     * Get removals count
     *
     * @return int
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getRemovalCount() : int
    {
        return $this->removalsCount;
    }
}