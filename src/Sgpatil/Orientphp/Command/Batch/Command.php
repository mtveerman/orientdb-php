<?php
namespace Sgpatil\Orientphp\Command\Batch;

use Sgpatil\Orientphp\Command as SingleCommand,
	Sgpatil\Orientphp\Client;

/**
 * A single command executed in a batch
 */
abstract class Command extends SingleCommand
{
	protected $base = null;
	protected $opId = null;
        
        //Added for Orientdb
        protected $type = null;
        protected $record = null;
        protected $language = null;
        protected $command = null;
        protected $script = null;

	/**
	 * Set the operation to drive the command
	 *
	 * @param Client $client
	 * @param SingleCommand $base
	 * @param integer $opId
	 */
	public function __construct(Client $client, SingleCommand $base, $opId)
	{
		parent::__construct($client);
		$this->base = $base;
		$this->opId = $opId;
	}

	/**
	 * Return the transport method to call
	 *
	 * @return string
	 */
	protected function getMethod()
	{
		return 'post';
	}

	/**
	 * Return the path to use
	 *
	 * @return string
	 */
	protected function getPath()
	{
		return '/batch';
	}

	/**
	 * Use the results
	 *
	 * @param integer $code
	 * @param array   $headers
	 * @param array   $data
	 * @return mixed
	 * @throws Exception on failure
	 */
	protected function handleResult($code, $headers, $data)
	{
		if ((int)($code / 100) != 2) {
			$this->throwException('Unable to commit batch', $code, $headers, $data);
		}

		foreach ($data as $result) {
			$this->handleSingleResult($result);
		}
		return true;
	}

	/**
	 * Handle a single result from the batch of results
	 *
	 * @param array $result
	 * @return mixed
	 * @throws Exception on failure
	 */
	protected function handleSingleResult($result)
	{
		$headers = array();
		if (isset($result['location'])) {
			$headers['Location'] = $result['location'];
		}
		return $this->base->handleResult(200, $headers, $result);
	}
}
