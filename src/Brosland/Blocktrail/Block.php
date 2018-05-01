<?php

namespace Brosland\Blocktrail;

use DateTimeImmutable;
use Nette\Utils\DateTime;

class Block
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
	 * @return Block
	 */
	public static function createFromArray(array $data)
	{
		$data['block_time'] = DateTimeImmutable::createFromFormat(
			DateTime::ATOM, $data['block_time']
		);
		$data['arrival_time'] = DateTimeImmutable::createFromFormat(
			DateTime::ATOM, $data['arrival_time']
		);

		$block = new Block();
		$block->data = $data;

		return $block;
	}

	/**
	 * The block hash.
	 * 
	 * @return string
	 */
	public function getHash()
	{
		return $this->data['hash'];
	}

	/**
	 * The distance of this block from the first block in the chain (starting with a height = 0).
	 * 
	 * @return int
	 */
	public function getHeight()
	{
		return $this->data['height'];
	}

	/**
	 * The UTC timestamp when the block was created by the miner.
	 * 
	 * @return DateTimeImmutable
	 */
	public function getBlockTime()
	{
		return $this->data['block_time'];
	}

	/**
	 * The UTC timestamp when the block was first received by our server.
	 * 
	 * @return DateTimeImmutable
	 */
	public function getArrivalTime()
	{
		return $this->data['arrival_time'];
	}

	/**
	 * The nonce used for finding the block hash.
	 * 
	 * @return int
	 */
	public function getNonce()
	{
		return $this->data['nonce'];
	}

	/**
	 * The network difficulty for finding the block hash.
	 * 
	 * @return int
	 */
	public function getDifficulty()
	{
		return $this->data['difficulty'];
	}

	/**
	 * The top of the Merkle tree, which can be used to verify that a given
	 * transaction was included in the block.
	 * 
	 * @return string
	 */
	public function getMerkleroot()
	{
		return $this->data['merkleroot'];
	}

	/**
	 * Indicates if the block is in the main chain or is an orphan.
	 * 
	 * @return bool
	 */
	public function isOrphan()
	{
		return $this->data['is_orphan'];
	}

	/**
	 * The hash of the previous block in the chain.
	 * 
	 * @return string
	 */
	public function getPrevBlock()
	{
		return $this->data['prev_block'];
	}

	/**
	 * The hash of the next block in the chain.
	 * 
	 * @return string
	 */
	public function getNextBlock()
	{
		return $this->data['next_block'];
	}

	/**
	 * The size of the block in bytes.
	 * 
	 * @return int
	 */
	public function getByteSize()
	{
		return $this->data['byte_size'];
	}

	/**
	 * The number of blocks that have been processed since the previous block
	 * (including the block itself).
	 * 
	 * @return int
	 */
	public function getConfirmations()
	{
		return $this->data['confirmations'];
	}

	/**
	 * The total number of transactions included in this block.
	 * 
	 * @return int
	 */
	public function getTransactions()
	{
		return $this->data['transactions'];
	}

	/**
	 * The total value of all the transactions included in this block.
	 * 
	 * @return int
	 */
	public function getValue()
	{
		return $this->data['value'];
	}

	/**
	 * The name of the mining pool detected to be the creator of the block.
	 * 
	 * @return string
	 */
	public function getMiningpoolName()
	{
		return $this->data['miningpool_name'];
	}

	/**
	 * The url of the mining pool detected to be the creator of the block.
	 * 
	 * @return string
	 */
	public function getMiningpoolUrl()
	{
		return $this->data['miningpool_url'];
	}

	/**
	 * The slugified name of the mining pool detected to be the creator of the block.
	 * 
	 * @return string
	 */
	public function getMiningpoolSlug()
	{
		return $this->data['miningpool_slug'];
	}
}