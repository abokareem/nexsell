<?php namespace Ddedic\Nexsell\Exceptions;


class RequiredFieldsException extends \UnexpectedValueException {}

class InvalidFieldFormatException extends \UnexpectedValueException {}
class InvalidFromFieldException extends InvalidFieldFormatException {}
class InvalidToFieldException extends InvalidFieldFormatException {}
class InvalidDestinationException extends InvalidFieldFormatException{}


class LoginRequiredException extends \UnexpectedValueException {}
class PasswordRequiredException extends \UnexpectedValueException {}
class UserAlreadyActivatedException extends \RuntimeException {}
class UserNotFoundException extends \OutOfBoundsException {}
class UserNotActivatedException extends \RuntimeException {}
class UserExistsException extends \UnexpectedValueException {}
class WrongPasswordException extends UserNotFoundException {}