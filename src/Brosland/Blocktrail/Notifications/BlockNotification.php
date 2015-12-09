<?php

namespace Brosland\Blocktrail\Notifications;

use Brosland\Blocktrail\Block;

class BlockNotification extends Notification
{

	/**
	 * @var Block
	 */
	private $block;


	/**
	 * @param array $data
	 * @return BlockNotification
	 */
	public static function createFromArray(array $data)
	{
		$notification = new BlockNotification();
		$notification->block = Block::createFromArray($data['data']);

		unset($data['data']);

		$notification->data = $data;

		return $notification;
	}

	/**
	 * @return Block
	 */
	public function getBlock()
	{
		return $this->block;
	}

	/**
	 * @return int
	 */
	public function getRetryCount()
	{
		return $this->data['retry_count'];
	}
}