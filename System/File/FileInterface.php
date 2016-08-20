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
namespace phpOMS\System\File;

/**
 * Filesystem class.
 *
 * Performing operations on the file system
 *
 * @category   Framework
 * @package    phpOMS\System\File
 * @author     OMS Development Team <dev@oms.com>
 * @author     Dennis Eichhorn <d.eichhorn@oms.com>
 * @license    OMS License 1.0
 * @link       http://orange-management.com
 * @since      1.0.0
 */
interface FileInterface
{
	public function getCount() : int;

	public function getSize() : int;

	public function getName() : string;

	public function getPath() : string;

	public function getParent() : FileInterface;

	public function createNode() : bool;

	public function copyNode() : bool;

	public function moveNode() : bool;

	public function deleteNode() : bool;

	public function putContent() : bool;

	public function getContent() : string;

	public function getCreatedAt() : \DateTime;

	public function getChangedAt() : \DateTime;

	public function getOwner() : int;

	public function getPermission() : string;

	public function index();

	public static function created(string $path) : \DateTime;

	public static function changed(string $path) : \DateTime;

	public static function owner(string $path) : int;

	public static function permission(string $path) : int;

	public static function parent(string $path) : string;

	public static function create(string $path) : bool;

	public static function delete(string $path) : bool;

	public static function copy(string $from, string $to, bool $overwrite = false) : bool;

	public static function move(string $from, string $to, bool $overwrite = false) : bool;

	public static function put(string $path, string $content, bool $overwrite = true) : bool;

	public static function get(string $path) : string;

	public static function size(string $path) : int;

	public static function exists(string $path) : bool;
}
