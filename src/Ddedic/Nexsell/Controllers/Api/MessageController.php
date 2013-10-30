<?php namespace Ddedic\Nexsell\Controllers\Api;


use Teepluss\Api\Facades\Api;
use Input, Request, Response, Nexsell; 



class MessageController extends BaseApiController {


	protected $client;
	protected $message;
	protected $gateway;


	public function __construct()
	{
		parent::__construct();

		$this->_init_startup();

	}



	public function getIndex()
	{
		// Method not allowed
		return API::createResponse(null, 405);
	}

	public function postIndex()
	{
		// Method not allowed
		return API::createResponse(null, 405);
	}

	public function getSend()
	{
		// Method not allowed		
		return API::createResponse(null, 405);
	}



	// ----------------------------------------------


	private function _init_startup()
	{

		$apiKey = Input::get('api_key');
		$apiSecret = Input::get('api_secret');


		if ($client = Nexsell::authApi($apiKey, $apiSecret))
			$this->client = $client;
		else
			return API::createResponse(null, 401);

	}

	public function postClient()
	{

		$build = array();

		$build = $this->client->toArray();
		$client_messages = $this->client->getMessages;


		foreach ($client_messages as $message)
		{
			$build['messages'][$message->getMessageId()] = $message->toArray();
			$build['messages'][$message->getMessageId()]['parts'] = $message->getMessageParts->toArray();
		}


		return API::createResponse($build, 2);
		//die("<pre>" . print_r($build, TRUE) . "</pre>");
		
	}


	public function postDelivered()
	{

		$build = array();

		//die("<pre>" . print_r($this->client->__toString(), TRUE) . "</pre>");

		$build['client'] = $this->client->toArray();
		$client_messages = $this->client->getMessages;


		foreach ($client_messages as $message)
		{
			// messages
			$build['client']['messages'][$message->getMessageId()] = $message->toArray();
			$message_parts = $message->getMessageParts;

			// is delivered
			$build['client']['messages'][$message->getMessageId()]['is_delivered'] = $message->isDelivered();

			// message parts
			/*
			foreach ($message_parts as $part)
			{
				$build['client']['messages'][$message->getMessageId()]['parts'][$part->getPartId()] = $part->toArray();
				if ($part->getDeliveryReport)
					$build['client']['messages'][$message->getMessageId()]['parts'][$part->getPartId()]['delivery_report'] = $part->getDeliveryReport->toArray();
			}
			*/
			
		}

		return API::createResponse($build);
		//die("<pre>" . print_r($build, TRUE) . "</pre>");
		
	}	


	public function postSend()
	{

		$build = array();

		//die("<pre>" . print_r($this->client->__toString(), TRUE) . "</pre>");

		$build['client'] = $this->client->toArray();
		$client_messages = $this->client->getMessages;


		foreach ($client_messages as $message)
		{
			// messages
			$build['client']['messages'][$message->getMessageId()] = $message->toArray();
			$message_parts = $message->getMessageParts;

			// message parts
			foreach ($message_parts as $part)
			{
				$build['client']['messages'][$message->getMessageId()]['parts'][$part->getPartId()] = $part->toArray();
				if ($part->getDeliveryReport)
					$build['client']['messages'][$message->getMessageId()]['parts'][$part->getPartId()]['delivery_report'] = $part->getDeliveryReport->toArray();
			}

			
		}

		return API::createResponse($build);
		//die("<pre>" . print_r($build, TRUE) . "</pre>");
		
	}















}