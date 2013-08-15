<?php

class Trims_Core {
	static public function run($array)
	{
		return is_array($array) ? array_map('Trims::run', $array) : trim($array);
	}
}