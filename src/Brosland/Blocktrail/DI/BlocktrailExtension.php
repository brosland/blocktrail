<?php

namespace Brosland\Blocktrail\DI;

use Brosland\Blocktrail\Blocktrail,
	Nette\DI\CompilerExtension,
	Nette\DI\Config\Helpers,
	Nette\InvalidArgumentException;

class BlocktrailExtension extends CompilerExtension
{

	/**
	 * @var array
	 */
	private $defaults = [
		'curl' => [],
		'account' => [],
		'wallet' => [],
		'webhook' => []
	];
	/**
	 * @var array
	 */
	private $accountDefaults = [
		'key' => NULL,
		'secret' => NULL,
		'network' => 'BTC',
		'testnet' => FALSE,
		'version' => 'v3',
		'endpoint' => NULL
	];
	/**
	 * @var array
	 */
	private $walletDefaults = [
		'id' => NULL,
		'password' => NULL,
		'account' => Blocktrail::DEFAULT_NAME
	];


	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$accounts = $this->loadAccounts($config['account'], $config['curl']);
		$wallets = $this->loadWallets($config['wallet']);

		$builder->addDefinition($this->prefix('blocktrail'))
			->setClass(Blocktrail::class)
			->addSetup('setServiceLocator')
			->addSetup('setAccountsServiceMap', [$accounts])
			->addSetup('setWalletsServiceMap', [$wallets])
			->addSetup('setWebhooksMap', [$config['webhook']]);
	}

	/**
	 * @param array $definitions
	 * @param string $curl The CURL options.
	 * @return array
	 */
	private function loadAccounts(array $definitions, array $curl)
	{
		if (isset($definitions['key']))
		{
			$definitions = [Blocktrail::DEFAULT_NAME => $definitions];
		}

		$builder = $this->getContainerBuilder();
		$accounts = [];

		foreach ($definitions as $name => $account)
		{
			$account = $this->mergeConfig($account, $this->accountDefaults);
			$serviceName = $this->prefix('account.' . $name);

			$service = $builder->addDefinition($serviceName);
			$service->setClass(\Blocktrail\SDK\BlocktrailSDK::class, [
					$account['key'],
					$account['secret'],
					$account['network'],
					$account['testnet'],
					$account['endpoint']
				])
				->setAutowired(empty($accounts));

			foreach ($curl as $key => $value)
			{
				$service->addSetup('setCurlDefaultOption', [$key, $value]);
			}

			$accounts[$name] = $serviceName;
		}

		return $accounts;
	}

	/**
	 * @param array $definitions
	 * @return array
	 */
	private function loadWallets(array $definitions)
	{
		if (isset($definitions['id']))
		{
			$definitions = [Blocktrail::DEFAULT_NAME => $definitions];
		}

		$builder = $this->getContainerBuilder();
		$wallets = [];

		foreach ($definitions as $name => $wallet)
		{
			$wallet = $this->mergeConfig($wallet, $this->walletDefaults);
			$accountServiceName = $this->prefix('account.' . $wallet['account']);
			$serviceName = $this->prefix('wallet.' . $name);

			if (!$builder->hasDefinition($accountServiceName))
			{
				throw new InvalidArgumentException("The account '{$wallet['account']}' not found in configuration.");
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
		$builder = $this->compiler->getContainerBuilder();

		return Helpers::merge($config, $builder->expand($defaults));
	}
}