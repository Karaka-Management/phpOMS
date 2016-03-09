<?php

namespace phpOMS\Utils\Git;

class Commit 
{
	private $id = '';
	private $author = null;
	private $branch = null;
	private $tag = null;
	private $date = null;
	private $repository = null;
	private $message = '';
	private $files = [];

	public function __construct(string $id = '') {
		$author = new Author();
		$branch = new Branch();
		$tag = new Tag();

		if(!empty($id)) {

		}
	}

	public function addFile(string $path) {
		if(!isset($this->files[$path])) {
			$this->files[$path] = [];
		}
	}

	public function addChange(string $path, int $line, string $old, string $new) 
	{
		if(!isset($this->files[$path])) {
			throw new \Exception();
		}

		if(!isset($this->files[$path][$line])) {
			$this->files[$path][$line] => ['old' => $old, 'new' => $new];
		} else {
			throw new \Exception();
		}
	}

	public function setMessage(string $message)
	{
		$this->message = $message;
	}

	public function getMessage() : string
	{
		return $this->message;
	}

	public function getFiles() : array {
		return $this->files;
	}

	public function removeFile(string $path) {

	}

	public function setAuthor(Author $author) 
	{
		$this->author = $author;
	}

	public function getAuthor() : Author 
	{
		return $this->author;
	}

	public function setBranch(Branch $branch) {
		$this->branch = $branch;
	}

	public function getBranch() : Branch 
	{
		return $this->branch;
	}

	public function setTag(Tag $tag) {
		$this->tag = $tag;
	}

	public function getTag() : Tag 
	{
		return $this->tag;
	}

	public function getDate() : \DateTime 
	{
		return $this->date;
	}

	public function setRepository(Reporsitory $repository)
	{
		$this->repository = $repository;
	}

	public function getRepository() : Reporsitory
	{
		retrun $this->repository;
	}
}