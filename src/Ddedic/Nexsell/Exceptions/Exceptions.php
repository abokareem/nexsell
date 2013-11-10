<?php namespace Ddedic\Nexsell\Exceptions;


class RequiredFieldsException extends \UnexpectedValueException {}

class InvalidFieldFormatException extends \UnexpectedValueException {}
class InvalidFromFieldException extends InvalidFieldFormatException {}
class InvalidToFieldException extends InvalidFieldFormatException {}
class InvalidDestinationException extends InvalidFieldFormatException{}

class InvalidRequestException extends \RuntimeException {}

class GatewayException extends \RuntimeException {}
class InvalidGatewayProviderException extends GatewayException {}
class InactiveGatewayProviderException extends GatewayException {}
class InvalidGatewayResponseException extends GatewayException {}

class PlanException extends \RuntimeException {}
class UnsupportedDestinationException extends PlanException {}

class InsufficientCreditsException extends \RuntimeException {}
class MessageFailedException extends \RuntimeException {}

