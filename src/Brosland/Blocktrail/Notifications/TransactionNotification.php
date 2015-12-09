<?php

namespace Brosland\Blocktrail\Notifications;

use Brosland\Blocktrail\Transaction;

class TransactionNotification extends Notification
{

	/**
	 * @var Transaction
	 */
	private $transaction;


	/**
	 * @param array $data
	 * @return TransactionNotification
	 */
	public static function createFromArray(array $data)
	{
		$notification = new TransactionNotification();
		$notification->transaction = Transaction::createFromArray($data['data']);

		unset($data['data']);

		$notification->data = $data;

		return $notification;
	}

	/**
	 * @return Transaction
	 */
	public function getTransaction()
	{
		return $this->transaction;
	}

	/**
	 * @return bool
	 */
	public function isWalletNotification()
	{
		return isset($this->data['wallet']);
	}

	/**
	 * @return string
	 */
	public function getWalletId()
	{
		return $this->isWalletNotification() ? $this->data['wallet']['identifier'] : NULL;
	}

	/**
	 * @return array
	 */
	public function getWalletAddresses()
	{
		return $this->isWalletNotification() ? $this->data['wallet']['addresses'] : [];
	}

	/**
	 * @return int
	 */
	public function getWalletBalance()
	{
		return $this->isWalletNotification() ? $this->data['wallet']['balance'] : NULL;
	}
}