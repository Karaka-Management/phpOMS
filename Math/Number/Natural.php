<?php

class Natural implements Number 
{
	public static function isNatural($value) : bool
	{
		return is_int($value) && $value >= 0;
	}
}