<?php

namespace Brosland\Blocktrail;

class Input
{

	/**
	 * @var array
	 */
	private $data;


	private function __construct()
	{
		
	}

	/**
	 * @param array $data
	 * @return Input
	 */
	public static function createFromArray(array $data)
	{
		$input = new Input();
		$input->data = $data;

		return $input;
	}

	/**
	 * The position of the input in the array of inputs (zero indexed).
	 * 
	 * @return int
	 */
	public function getIndex()
	{
		return $this->data['index'];
	}

	/**
	 * The hash of the transaction containing the output used to create this input.
	 * This is null for coinbase transactions.
	 * 
	 * @return string
	 */
	public function getOutputHash()
	{
		return $this->data['output_hash'];
	}

	/**
	 * The position of the output in the array of outputs for the transaction from
	 * which the input was created.
	 * 
	 * @return int
	 */
	public function getOutputIndex()
	{
		return $this->data['output_index'];
	}

	/**
	 * The value of the input in Satoshi.
	 * 
	 * @return int
	 */
	public function getValue()
	{
		return $this->data['value'];
	}

	/**
	 * The address from which the input was transferred.
	 * 
	 * @return string
	 */
	public function getAddress()
	{
		return $this->data['address'];
	}

	/**
	 * The type of input - will be "coinbase" in the case of coinbase transactions.
	 * 
	 * @return string
	 */
	public function getType()
	{
		return $this->data['type'];
	}

	/**
	 * The type of multi-signature used (e.g. "1of2")
	 * 
	 * @return string
	 */
	public function getMultisig()
	{
		return $this->data['multisig'];
	}

	/**
	 * A list of the addresses involved in multi-signature transactions
	 * 
	 * @return array
	 */
	public function getMultisigAddresses()
	{
		return $this->data['multisig_addresses'];
	}

	/**
	 * The script signature used to unlock the input for use in the transaction.
	 * This is null for coinbase transactions.
	 * 
	 * @return string
	 */
	public function getScriptSignature()
	{
		return $this->data['script_signature'];
	}
}