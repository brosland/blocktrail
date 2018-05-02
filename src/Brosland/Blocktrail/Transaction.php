<?php

namespace Brosland\Blocktrail;

use DateTimeImmutable;
use Nette\Utils\DateTime;

class Transaction
{

	/**
	 * @var array
	 */
	private $data;
	/**
	 * @var Input[]
	 */
	private $inputs = [];
	/**
	 * @var Output[]
	 */
	private $outputs = [];


	private function __construct()
	{
		
	}

	/**
	 * @param array $data
	 * @return Transaction
	 */
	public static function createFromArray(array $data)
	{
		$transaction = new Transaction();

		foreach ($data['inputs'] as $key => $inputData)
		{
			$transaction->inputs[$key] = Input::createFromArray($inputData);
		}

		foreach ($data['outputs'] as $key => $outputData)
		{
			$transaction->outputs[$key] = Output::createFromArray($outputData);
		}

		unset($data['inputs']);
		unset($data['outputs']);

		if (isset($data['time']) && $data['time'] !== NULL)
		{
			$data['time'] = DateTimeImmutable::createFromFormat(
					DateTime::ATOM, $data['time']
			);
		}

		if (isset($data['block_time']) && $data['block_time'] !== NULL)
		{
			$data['block_time'] = DateTimeImmutable::createFromFormat(
					DateTime::ATOM, $data['block_time']
			);
		}

		$transaction->data = $data;

		return $transaction;
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->data['size'];
	}

	/**
	 * The hash of the transaction
	 * 
	 * @return string
	 */
	public function getHash()
	{
		return $this->data['hash'];
	}

	/**
	 * The UTC timestamp when the transaction was first seen by BlockTrail.
	 * 
	 * @return DateTimeImmutable
	 */
	public function getTime()
	{
		return $this->data['time'];
	}

	/**
	 * @return DateTimeImmutable
	 */
	public function getBlockTime()
	{
		return $this->data['block_time'];
	}

	/**
	 * The height of the block containing the transaction.
	 * The height is the distance from the first block in the chain, starting with height = 0.
	 * An unconfirmed transaction will have a null height.
	 * 
	 * @return int
	 */
	public function getBlockHeight()
	{
		return $this->data['block_height'];
	}

	/**
	 * The hash of the block containing the transaction.
	 * An unconfirmed transaction will have a null block_hash.
	 * 
	 * @return string
	 */
	public function getBlockHash()
	{
		return $this->data['block_hash'];
	}

	/**
	 * The total number of blocks that have been processed since the transaction,
	 * including the block containing the transaction.
	 * An unconfirmed transaction will have 0 confirmations.
	 * 
	 * @return int
	 */
	public function getConfirmations()
	{
		return $this->data['confirmations'];
	}

	/**
	 * Indicates if the transaction is a coinbase transaction,
	 * created by the miner who mined the block containing the transaction.
	 * 
	 * @return bool
	 */
	public function isCoinbase()
	{
		return $this->data['is_coinbase'];
	}

	/**
	 * The total value of all the inputs in Satoshi.
	 * 
	 * @return int
	 */
	public function getTotalInputValue()
	{
		return $this->data['total_input_value'];
	}

	/**
	 * The total value of all the outputs in Satoshi.
	 * 
	 * @return int
	 */
	public function getTotalOutputValue()
	{
		return $this->data['total_output_value'];
	}

	/**
	 * The total fees paid to the miner in Satoshi.
	 * 
	 * @return int
	 */
	public function getTotalFee()
	{
		return $this->data['total_fee'];
	}

	/**
	 * @return bool
	 */
	public function getOptInRbf()
	{
		return $this->data['opt_in_rbf'];
	}

	/**
	 * @return bool
	 */
	public function isDoubleSpend()
	{
		return isset($this->data['is_double_spend']) ?
			$this->data['is_double_spend'] : FALSE;
	}

	/**
	 * @return int|NULL
	 */
	public function getLockTimeTimestamp()
	{
		return $this->data['lock_time_timestamp'];
	}

	/**
	 * @return int|NULL
	 */
	public function getLockTimeBlockHeight()
	{
		return $this->data['lock_time_block_height'];
	}

	/**
	 * @return bool
	 */
	public function isSwTx()
	{
		return $this->data['is_sw_tx'];
	}

	/**
	 * @return int
	 */
	public function getWeight()
	{
		return $this->data['weight'];
	}

	/**
	 * @return string
	 */
	public function getWitnessHash()
	{
		return $this->data['witness_hash'];
	}

	/**
	 * @return int
	 */
	public function getLockTime()
	{
		return $this->data['lock_time'];
	}

	/**
	 * @return int
	 */
	public function getSigops()
	{
		return $this->data['sigops'];
	}

	/**
	 * @return int
	 */
	public function getVersion()
	{
		return $this->data['version'];
	}

	/**
	 * @return string
	 */
	public function getRaw()
	{
		return $this->data['raw'];
	}

	/**
	 * An array of all the inputs used in the transaction.
	 * 
	 * @return Input[]
	 */
	public function getInputs()
	{
		return $this->inputs;
	}

	/**
	 * An array of all the outputs used in the transaction.
	 * 
	 * @return Output[]
	 */
	public function getOutputs()
	{
		return $this->outputs;
	}

	/**
	 * @param string $address
	 * @return string
	 */
	public function getReceived($address)
	{
		$received = 0;

		foreach ($this->getOutputs() as $output)
		{
			if ($output->getAddress() == $address)
			{
				$received = bcadd($received, $output->getValue());
			}
		}

		return $received;
	}

	/**
	 * @param string $address
	 * @return string
	 */
	public function getSent($address)
	{
		$sent = 0;

		foreach ($this->getInputs() as $input)
		{
			if ($input->getAddress() == $address)
			{
				$sent = bcadd($sent, $input->getValue());
			}
		}

		return $sent;
	}
}