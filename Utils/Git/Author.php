<?php

namespace phpOMS\Utils\Git;

class Author
{
	private $name = '';
	private $email = '';

	public function __construct(string $name, string $email)
	{
		$this->name = $name;
		$this->email = $email;
	}

	public function getName() : string
	{
		return $name;
	}

	public function getEmail() : string
	{
		return $email;
	}
}