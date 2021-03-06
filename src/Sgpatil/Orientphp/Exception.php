<?php
namespace Sgpatil\Orientphp;

class Exception extends \Exception
{
	protected $headers;
	protected $data;

	public function __construct($message, $code=0, $headers=array(), $data=array())
	{
		$this->headers = $headers;
		$this->data = $data;

        $message = $message . $this->getTraceAsString();

		parent::__construct($message, $code);
	}

	/**
	 * Return response headers
	 * @return array Response headers
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * Return response data
	 * @return array Response data
	 */
	public function getData()
	{
		return $this->data;
	}
}
