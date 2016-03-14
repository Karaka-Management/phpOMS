<?php
/**
 * Orange Management
 *
 * PHP Version 7.0
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
     * Constructor
     *
     * @param string $name Author name
     * @param string $email Author email
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function __construct(string $name, string $email)
	{
		$this->name = $name;
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
		return $name;
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
		return $email;
	}
}