<?php

namespace Brosland\Blocktrail;

class Output extends \Nette\Object
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
	 * @return Output
	 */
	public static function createFromArray(array $data)
	{
		$output = new Output();
		$output->data = $data;

		return $output;
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
	 * The value of the output in Satoshi.
	 * 
	 * @return int
	 */
	public function getValue()
	{
		return $this->data['value'];
	}

	/**
	 * The address receiving the output value.
	 * 
	 * @return string
	 */
	public function getAddress()
	{
		return $this->data['address'];
	}

	/**
	 * The type of output (e.g. pubkeyhash).
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
	 * The script of the output.
	 * 
	 * @return string
	 */
	public function getScript()
	{
		return $this->data['script'];
	}

	/**
	 * The script of the output as hexadecimal.
	 * 
	 * @return string
	 */
	public function getScriptHex()
	{
		return $this->data['script_hex'];
	}

	/**
	 * The hash of the transaction where this output has subsequently been spent.
	 * This is null for unspent outputs.
	 * 
	 * @return string
	 */
	public function getSpentHash()
	{
		return $this->data['spent_hash'];
	}

	/**
	 * The position of this output in the array of inputs for the transaction
	 * that subsequently spends this output.
	 * 
	 * @return int
	 */
	public function getSpentIndex()
	{
		return $this->data['spent_index'];
	}
}