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
class Commit 
{
	/**
     * Hash.
     *
     * @var string
     * @since 1.0.0
     */
	private $id = '';

	/**
     * Author.
     *
     * @var Author
     * @since 1.0.0
     */
	private $author = null;

	/**
     * Branch.
     *
     * @var Branch
     * @since 1.0.0
     */
	private $branch = null;

	/**
     * Tag.
     *
     * @var Tag
     * @since 1.0.0
     */
	private $tag = null;

	/**
     * Commit date.
     *
     * @var \DateTime
     * @since 1.0.0
     */
	private $date = null;

	/**
     * Repository.
     *
     * @var Repository
     * @since 1.0.0
     */
	private $repository = null;

	/**
     * Commit message.
     *
     * @var string
     * @since 1.0.0
     */
	private $message = '';

	/**
     * Files.
     *
     * @var string[]
     * @since 1.0.0
     */
	private $files = [];

	/**
     * Constructor
     *
     * @param string $id Commit hash
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function __construct(string $id = '') {
		$author = new Author();
		$branch = new Branch();
		$tag = new Tag();

		if (!empty($id)) {
			// todo: fill base info
		}
	}

	/**
     * Add file to commit.
     *
     * @param string $path File path
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function addFile(string $path) {
		if (!isset($this->files[$path])) {
			$this->files[$path] = [];
		}
	}

	/**
     * Add change.
     *
     * @param string $path File path
     * @param int $line Line number
     * @param string $old Old line
     * @param string $new New line
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	private function addChange(string $path, int $line, string $old, string $new) 
	{
		if (!isset($this->files[$path])) {
			throw new \Exception();
		}

		if (!isset($this->files[$path][$line])) {
			$this->files[$path][$line] = ['old' => $old, 'new' => $new];
		} else {
			throw new \Exception();
		}
	}

	/**
     * Set commit message.
     *
     * @param string $path File path
     *
     * @throws
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	/**
     * Get commit message.
     *
     * @return string
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getMessage() : string
	{
		return $this->message;
	}

	/**
     * Get files of this commit.
     *
     * @return string[]
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getFiles() : array 
	{
		return $this->files;
	}

	/**
     * Get files of this commit.
     *
     * @param string $path File path
     *
     * @return bool
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function removeFile(string $path) : bool
	{
		if (isset($this->files[$path])) {
			unset($this->files[$path]);

			return true;
		}

		return false;
	}

	/**
     * Set commit author.
     *
     * @param Author $author Commit author
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function setAuthor(Author $author) 
	{
		$this->author = $author;
	}

	/**
     * Get commit author.
     *
     * @return Author
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getAuthor() : Author 
	{
		return $this->author;
	}

	/**
     * Set commit branch.
     *
     * @param Branch $branch Commit branch
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function setBranch(Branch $branch) {
		$this->branch = $branch;
	}

	/**
     * Get commit branch.
     *
     * @return Branch
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getBranch() : Branch 
	{
		return $this->branch;
	}

	/**
     * Set commit tag.
     *
     * @param Repository $tag Commit tag
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function setTag(Tag $tag) {
		$this->tag = $tag;
	}

	/**
     * Get commit tag.
     *
     * @return Tag
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getTag() : Tag 
	{
		return $this->tag;
	}

	/**
     * Get commit date.
     *
     * @return \DateTime
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getDate() : \DateTime 
	{
		return $this->date ?? new \DateTime('now');
	}

	/**
     * Set commit repository.
     *
     * @param Repository $repository Commit repository
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function setRepository(Repository $repository)
	{
		$this->repository = $repository;
	}

	/**
     * Get commit repository.
     *
     * @return Repository
     *
     * @since  1.0.0
     * @author Dennis Eichhorn <d.eichhorn@oms.com>
     */
	public function getRepository() : Repository
	{
		return $this->repository;
	}
}
