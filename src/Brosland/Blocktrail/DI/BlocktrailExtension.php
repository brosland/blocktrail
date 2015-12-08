<?php

namespace Brosland\Blocktrail\DI;

use Brosland\Blocktrail\Blocktrail;

class BlocktrailExtension extends \Nette\DI\CompilerExtension
{

	/**
	 * @var array
	 */
	private static $DEFAULTS = [
		'account' => [],
		'wallet' => []
	];
	/**
	 * @var array
	 */
	private static $ACCOUNT_DEFAULTS = [
		'id' => NULL,
		'secret' => NULL,
		'network' => 'BTC',
		'testnet' => FALSE,
		'version' => 'v1',
		'endpoint' => NULL
	];
	/**
	 * @var array
	 */
	private static $WALLET_DEFAULTS = [
		'account' => Blocktrail::DEFAULT_NAME
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig(self::$DEFAULTS);

		$accounts = $this->loadAccounts($config['account']);
		$wallets = $this->loadWallets($config['wallet']);

		$builder->addDefinition($this->prefix('blocktrail'))
			->setClass(Blocktrail::class)
			->addSetup('setServiceLocator')
			->addSetup('setAccountsServiceMap', [$accounts])
			->addSetup('setWalletsServiceMap', [$wallets]);
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	private function loadAccounts($definitions)
	{
		if (isset($definitions['key']))
		{
			$definitions = [Blocktrail::DEFAULT_NAME => $definitions];
		}

		$builder = $this->getContainerBuilder();
		$accounts = [];

		foreach ($definitions as $name => $account)
		{
			$account = $this->mergeConfig($account, self::$ACCOUNT_DEFAULTS);
			$serviceName = $this->prefix('account.' . $name);

			$service = $builder->addDefinition($serviceName);
			$service->setClass(\Blocktrail\SDK\BlocktrailSDK::class, [
					$account['key'],
					$account['secret'],
					$account['network'],
					$account['testnet']
				])
				->setAutowired(empty($accounts));

			$accounts[$name] = $serviceName;
		}

		return $accounts;
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	private function loadWallets($definitions)
	{
		if (isset($definitions['id']))
		{
			$definitions = [Blocktrail::DEFAULT_NAME => $definitions];
		}

		$builder = $this->getContainerBuilder();
		$wallets = [];

		foreach ($definitions as $name => $wallet)
		{
			$wallet = $this->mergeConfig($wallet, self::$WALLET_DEFAULTS);
			$accountServiceName = $this->prefix('account.' . $wallet['account']);
			$serviceName = $this->prefix('wallet.' . $name);

			if (!$builder->hasDefinition($accountServiceName))
			{
				throw new \Nette\InvalidArgumentException("The account '{$wallet['account']}' not found in configuration.");
			}

			$service = $builder->addDefinition($serviceName);
			$service->setClass(\Blocktrail\SDK\Wallet::class)
				->setFactory('@' . $accountServiceName . '::initWallet', [
					$wallet['id'],
					$wallet['password']
				])
				->setAutowired(empty($wallets));

			$wallets[$name] = $serviceName;
		}

		return $wallets;
	}

	/**
	 * @param array $config
	 * @param array $defaults
	 * @return array
	 */
	private function mergeConfig($config, $defaults)
	{
		return \Nette\DI\Config\Helpers::merge(
				$config, $this->compiler->getContainerBuilder()->expand($defaults));
	}
}