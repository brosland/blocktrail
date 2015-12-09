<?php

namespace Brosland\Blocktrail;

use Nette\DI\Container;

class Blocktrail extends \Nette\Object
{

	const DEFAULT_NAME = 'default';


	/**
	 * @var Container
	 */
	private $serviceLocator;
	/**
	 * @var array
	 */
	private $accountsServiceMap = [], $walletsServiceMap = [];


	/**
	 * @internal
	 * @param Container $serviceLocator
	 */
	public function setServiceLocator(Container $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}

	/**
	 * @internal
	 * @param array $accounts
	 */
	public function setAccountsServiceMap(array $accounts)
	{
		$this->accountsServiceMap = $accounts;
	}

	/**
	 * @internal
	 * @param array $wallets
	 */
	public function setWalletsServiceMap(array $wallets)
	{
		$this->walletsServiceMap = $wallets;
	}

	/**
	 * @param string $name
	 * @return \Blocktrail\SDK\BlocktrailSDK
	 * @throws \Nette\InvalidArgumentException
	 */
	public function getAccount($name = self::DEFAULT_NAME)
	{
		if (!isset($this->accountsServiceMap[$name]))
		{
			throw new \Nette\InvalidArgumentException("Unknown account '$name'.");
		}

		return $this->serviceLocator->getService($this->accountsServiceMap[$name]);
	}

	/**
	 * @param string $name
	 * @return \Blocktrail\SDK\Wallet
	 * @throws \Nette\InvalidArgumentException
	 */
	public function getWallet($name = self::DEFAULT_NAME)
	{
		if (!isset($this->walletsServiceMap[$name]))
		{
			throw new \Nette\InvalidArgumentException("Unknown wallet '$name'.");
		}

		return $this->serviceLocator->getService($this->walletsServiceMap[$name]);
	}
}