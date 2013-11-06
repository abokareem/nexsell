<?php namespace Ddedic\Nexsell\Controllers\Api;


use Nexsell, Api;
use Input, Request, Response; 



class MessageController extends BaseApiController {


	



	public function __construct()
	{
		parent::__construct();

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


	public function postTest()
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






	public function postSend()
	{


		try {


			$from = Input::get('from');
			$to = Input::get('to');
			$text = Input::get('text');

			if (Nexsell::sendMessage($this->client, $from, $to, $text))
			{
				return API::createResponse('Message succesfully sent', 20);
			}

			
		}

		catch (\Ddedic\Nexsell\Exceptions\RequiredFieldsException $e) {

			return API::createResponse(null, 41);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\InvalidFromFieldException $e) {

			return API::createResponse(null, 43);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\InvalidToFieldException $e) {

			return API::createResponse(null, 44);
			
		}	

		catch (\Ddedic\Nexsell\Exceptions\InvalidDestinationException $e) {

			return API::createResponse(null, 45);
			
		}				

		catch (\Ddedic\Nexsell\Exceptions\InactiveGatewayProviderException $e) {

			return API::createResponse(null, 47);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\InvalidGatewayProviderException $e) {

			return API::createResponse(null, 46);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\InvalidRequestException $e) {

			return API::createResponse(null, 48);
			
		}		

		catch (\Ddedic\Nexsell\Exceptions\UnsupportedDestinationException $e) {

			return API::createResponse(null, 49);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\InsufficientCreditsException $e) {

			return API::createResponse(null, 50);
			
		}

		catch (\Ddedic\Nexsell\Exceptions\MessageFailedException $e) {

			return API::createResponse($e->getMessage(), 51);
			
		}		



	}









}