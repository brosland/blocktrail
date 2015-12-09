<?php

namespace Brosland\Blocktrail;

use Nette\Utils\DateTime;

class Address extends \Nette\Object
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
	 * @return Address
	 */
	public static function createFromArray(array $data)
	{
		$data['first_seen'] = DateTime::createFromFormat(DateTime::ATOM, $data['first_seen']);
		$data['last_seen'] = DateTime::createFromFormat(DateTime::ATOM, $data['last_seen']);

		$address = new Address();
		$address->data = $data;

		return $address;
	}

	/**
	 * A bitcoin address hash
	 * 
	 * @return string
	 */
	public function getAddress()
	{
		return $this->data['address'];
	}

	/**
	 * The Hash160 of the Bitcoin address.
	 * 
	 * @return string
	 */
	public function getHash160()
	{
		return $this->data['hash160'];
	}

	/**
	 * The confirmed balance of the address, displayed in Satoshi.
	 * 
	 * @return int
	 */
	public function getbBalance()
	{
		return $this->data['balance'];
	}

	/**
	 * The total confirmed amount received by the address, displayed in Satoshi.
	 * 
	 * @return int
	 */
	public function getReceived()
	{
		return $this->data['received'];
	}

	/**
	 * The total confirmed amount sent by the address, displayed in Satoshi.
	 * 
	 * @return int
	 */
	public function getSent()
	{
		return $this->data['sent'];
	}

	/**
	 * The total number of confirmed transactions related to the address
	 * 
	 * @return int
	 */
	public function getTransactions()
	{
		return $this->data['transactions'];
	}

	/**
	 * @return int
	 */
	public function getUtxos()
	{
		return $this->data['utxos'];
	}

	/**
	 * The current unconfirmed amount sent to the address, displayed in Satoshi.
	 * 
	 * @return int
	 */
	public function getUnconfirmedReceived()
	{
		return $this->data['unconfirmed_received'];
	}

	/**
	 * The total number of unconfirmed transactions for the address (both received and sent).
	 * 
	 * @return int
	 */
	public function getUnconfirmedSent()
	{
		return $this->data['unconfirmed_sent'];
	}

	/**
	 * The total number of unconfirmed transactions for the address (both received and sent).
	 * 
	 * @return int
	 */
	public function getUnconfirmedTransactions()
	{
		return $this->data['unconfirmed_transactions'];
	}

	/**
	 * @return int
	 */
	public function getUnconfirmedUtxos()
	{
		return $this->data['unconfirmed_utxos'];
	}

	/**
	 * The total number of confirmed transactions received by the address
	 * 
	 * @return int
	 */
	public function getTotalTransactionsIn()
	{
		return $this->data['total_transactions_in'];
	}

	/**
	 * The total number of confirmed transactions sent from the address
	 * 
	 * @return int
	 */
	public function getTotalTransactionsOut()
	{
		return $this->data['total_transactions_out'];
	}

	/**
	 * A category assigned to this address through Blocktrail's analysis of the network.
	 * 
	 * @return string
	 */
	public function getCategory()
	{
		return $this->data['category'];
	}

	/**
	 * A tag assigned to this address through Blocktrail's analysis of the network.
	 * 
	 * @return string
	 */
	public function getTag()
	{
		return $this->data['tag'];
	}

	/**
	 * @return DateTime
	 */
	public function getFirstSeen()
	{
		return $this->data['first_seen'];
	}

	/**
	 * @return DateTime
	 */
	public function getLastSeen()
	{
		return $this->data['last_seen'];
	}
}