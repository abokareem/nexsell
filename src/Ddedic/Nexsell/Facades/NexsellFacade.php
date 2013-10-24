<?php namespace Ddedic\Nexsell\Facades;
 
use Illuminate\Support\Facades\Facade;
 
class NexsellFacade extends Facade {
 
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor() { return 'nexsell'; }
 
}