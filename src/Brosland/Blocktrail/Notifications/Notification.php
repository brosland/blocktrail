<?php

namespace Brosland\Blocktrail\Notifications;

abstract class Notification extends \Nette\Object
{

	const TYPE_ADDRESS = 'address-transactions', TYPE_BLOCK = 'block';


	/**
	 * @var array
	 */
	protected $data;


	protected function __construct()
	{
		
	}

	/**
	 * @return string
	 */
	public function getNetwork()
	{
		return $this->data['network'];
	}

	/**
	 * @return string self::TYPE_ADDRESS or self::TYPE_BLOCK
	 */
	public function getEventType()
	{
		return $this->data['event_type'];
	}

	/**
	 * @return array
	 */
	public function getAddresses()
	{
		return $this->data['addresses'];
	}
}