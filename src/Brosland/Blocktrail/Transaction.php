<?php

namespace Brosland\Blocktrail;

use Nette\Utils\DateTime;

class Transaction extends \Nette\Object
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

		$data['first_seen_at'] = DateTime::createFromFormat(DateTime::ATOM, $data['first_seen_at']);
		$data['last_seen_at'] = DateTime::createFromFormat(DateTime::ATOM, $data['last_seen_at']);
		$data['block_time'] = DateTime::createFromFormat(DateTime::ATOM, $data['block_time']);

		$transaction->data = $data;

		return $transaction;
	}

	/**
	 * @return string
	 */
	public function getRaw()
	{
		return $this->data['raw'];
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
	 * @return DateTime
	 */
	public function getFirstSeenAt()
	{
		return $this->data['first_seen_at'];
	}

	/**
	 * The UTC timestamp when the transaction was last propagated through the network
	 * and seen by BlockTrail.
	 * 
	 * @return DateTime
	 */
	public function getLastSeenAt()
	{
		return $this->data['last_seen_at'];
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
	 * The UTC timestamp the block containing the transaction was created by the miner.
	 * An unconfirmed transaction will have a null block_time.
	 * 
	 * @return DateTime
	 */
	public function getBlockTime()
	{
		return $this->data['block_time'];
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
	 * The total estimated value of the transaction in Satoshi, calculated
	 * as the sum of all output values minus the estimated change amount.
	 * 
	 * @return int
	 */
	public function getEstimatedValue()
	{
		return $this->data['estimated_value'];
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
	 * The total estimated change for this transaction in Satoshi.
	 * 
	 * @return int
	 */
	public function getEstimatedChange()
	{
		return $this->data['estimated_change'];
	}

	/**
	 * The estimated change address for this transaction.
	 * 
	 * @return string
	 */
	public function getEstimatedChangeAddress()
	{
		return $this->data['estimated_change_address'];
	}

	/**
	 * Indicates if this transaction is considered "high priority"
	 * 
	 * @return bool
	 */
	public function hasHighPriority()
	{
		return $this->data['high_priority'];
	}

	/**
	 * Indicates if the transaction includes the minimum required fee according to the Bitcoin protocol.
	 * 
	 * @return bool
	 */
	public function hasEnoughFee()
	{
		return $this->data['enough_fee'];
	}

	/**
	 * Indicates if the transaction contains "dust" according to the Bitcoin protocol.
	 * 
	 * @return bool
	 */
	public function isContainsDust()
	{
		return $this->data['contains_dust'];
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
	 * @return bool
	 */
	public function isDoubleSpend()
	{
		return isset($this->data['is_double_spend']) ?
			$this->data['is_double_spend'] : FALSE;
	}

	/**
	 * @return array
	 */
	public function getDoubleSpendIn()
	{
		return isset($this->data['double_spend_in']) ?
			$this->data['double_spend_in'] : [];
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