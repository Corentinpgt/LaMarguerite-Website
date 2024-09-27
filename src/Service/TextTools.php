<?php
//----------------------------------------------------------------------
// src/Service/TextTools.php
//----------------------------------------------------------------------
namespace App\Service;

class TextTools
{
    public function __construct()
    {
    }

	public function camelToSnakeCase($text)
	{
		return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $text));
	}
}
