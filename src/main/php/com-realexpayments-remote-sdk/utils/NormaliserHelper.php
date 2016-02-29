<?php


namespace com\realexpayments\remote\sdk\utils;


class NormaliserHelper {

	public static function GetClassName() {
		return __CLASS__;
	}

	public static  function filter_data($data)
	{
		if ($data == null)
		{
			return false;
		}

		if ($data == "")
		{
			return false;
		}

		return true;
	}

}