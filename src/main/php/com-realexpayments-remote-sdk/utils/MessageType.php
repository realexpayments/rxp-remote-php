<?php


namespace com\realexpayments\remote\sdk\utils;


use SplEnum;

class MessageType extends SplEnum  {

	const __default = self::PAYMENT;

	const PAYMENT = "Payment";
	const THREE_D_SECURE = "3DS";
}