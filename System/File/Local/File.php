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
namespace phpOMS\System\File\Local;
use phpOMS\System\File\ContainerInterface;
use phpOMS\System\File\FileInterface;
use phpOMS\System\File\PathException;

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
class File extends FileAbstract implements FileInterface
{

    /**
     * Constructor.
     *
     * @param string $path Path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function __construct(string $path)
    {
        parent::__construct($path);
        $this->count = 1;

        if (file_exists($this->path)) {
            $this->index();
        }
    }

    /**
     * Index file.
     *
     * @return void
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function index()
    {
        parent::index();

        $this->size = filesize($this->path);
    }

    /**
     * Save string to file.
     *
     * If the directory doesn't exist where the string should be saved it will be created
     * as well as potential subdirectories. The directories will be created with '0644'
     * permission.
     *
     * @param string $path Path to save the string to
     * @param string $content Content to save to file
     * @param bool $overwrite Should the file be overwritten if it already exists
     *
     * @example File::put('/var/www/html/test.txt', 'string'); // true
     * @example File::put('/var/www/html/test.txt', 'string', false); // false
     *
     * @return bool Returns true on successfule file write and false on failure
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function put(string $path, string $content, bool $overwrite = true) : bool
    {
        if ($overwrite || !file_exists($path)) {
            if (!Directory::exists(dirname($path))) {
                Directory::create(dirname($path), '0644', true);
            }

            file_put_contents($path, $content);

            return true;
        }

        return false;
    }

    /**
     * Get content of file.
     *
     * @param string $path Path to read from
     *
     * @example File::get('/var/www/html/test.txt');
     *
     * @return string The content of the file to read from.
     *
     * @throws PathException In case the file doesn't exist this exception gets thrown.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function get(string $path) : string
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return file_get_contents($path);
    }

    /**
     * Checks if a file exists.
     *
     * @param string $path Path of the file to check the existance for.
     *
     * @example File::exists('/var/www/html/test.txt');
     *
     * @return bool Returns true if the file exists and false if it doesn't.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function exists(string $path) : bool
    {
        return file_exists($path);
    }

    /**
     * Gets the parent directory path of the specified file.
     *
     * @param string $path Path of the file to get the parent directory for.
     *
     * @example File::parent('/var/www/html/test.txt'); // /var/www
     *
     * @return string Returns the parent full directory path.
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function parent(string $path) : string
    {
        return Directory::parent(dirname($path));
    }

    /**
     * Gets the date when the file got created.
     *
     * @param string $path Path of the file to get the date of creation for.
     *
     * @return \DateTime Returns the \DateTime of when the file was created.
     *
     * @throws PathException Throws this exception if the file to get the creation date for doesn't exist.
     *
     * @example File::created('/var/www/html/test.txt');
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function created(string $path) : \DateTime
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        $created = new \DateTime();
        $created->setTimestamp(filemtime($path));

        return $created;
    }

    /**
     * Gets the date when the file got changed the last time.
     *
     * @param string $path Path of the file to get the last date of change for.
     *
     * @return \DateTime Returns the \DateTime of when the file was last changed.
     *
     * @throws PathException Throws this exception if the file to get the last change date for doesn't exist.
     *
     * @example File::changed('/var/www/html/test.txt');
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public static function changed(string $path) : \DateTime
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        $changed = new \DateTime();
        $changed->setTimestamp(filectime($path));

        return $changed;
    }

    public static function size(string $path) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return filesize($path);
    }

    public static function owner(string $path) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return fileowner($path);
    }

    public static function permission(string $path) : int
    {
        if (!file_exists($path)) {
            throw new PathException($path);
        }

        return fileperms($path);
    }

    public static function dirname(string $path) : string
    {
        return dirname($path);
    }

    public static function copy(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!file_exists($from)) {
            throw new PathException($from);
        }

        if ($overwrite || !file_exists($to)) {
            if (!Directory::exists(dirname($to))) {
                Directory::create(dirname($to), '0644', true);
            }

            copy($from, $to);

            return true;
        }

        return false;
    }

    public static function move(string $from, string $to, bool $overwrite = false) : bool
    {
        if (!file_exists($from)) {
            throw new PathException($from);
        }

        if ($overwrite || !file_exists($to)) {
            if (!Directory::exists(dirname($to))) {
                Directory::create(dirname($to), '0644', true);
            }

            rename($from, $to);

            return true;
        }

        return false;
    }

    public static function delete(string $path) : bool
    {
        if (!file_exists($path)) {
            return false;
        }

        unlink($path);

        return true;
    }

    public function getDirName() : string
    {
        return basename(dirname($this->path));
    }

    public function getDirPath() : string
    {
        return dirname($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function createNode() : bool
    {
        return self::create($this->path);
    }

    public static function create(string $path) : bool
    {
        if (!file_exists($path)) {
            if (!Directory::exists(dirname($path))) {
                Directory::create(dirname($path), '0644', true);
            }

            touch($path);

            return true;
        }

        return false;
    }

    /**
     * Get file content.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function getContent() : string
    {
        return file_get_contents($this->path);
    }

    /**
     * Set file content.
     *
     * @param string $content Content to set
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
    public function setContent(string $content)
    {
        file_put_contents($this->path, $content);
    }

    public function getFileName() : string
    {
        return explode('.', $this->name)[0];
    }

    public function getExtension() : string
    {
        $extension = explode('.', $this->name);

        return $extension[1] ?? '';
    }

    public function getParent() : ContainerInterface
    {
        // TODO: Implement getParent() method.
    }

    public function copyNode() : bool
    {
        // TODO: Implement copyNode() method.
    }

    public function moveNode() : bool
    {
        // TODO: Implement moveNode() method.
    }

    public function deleteNode() : bool
    {
        // TODO: Implement deleteNode() method.
    }

    public function putContent() : bool
    {
        // TODO: Implement putContent() method.
    }
}