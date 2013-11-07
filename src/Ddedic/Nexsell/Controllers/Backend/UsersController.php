<?php namespace Ddedic\Nexsell\Controllers\Backend;

use Redirect, Input, Config, Controller, Request, Response, Nexsell, Api, Theme, Alert; 
use Ddedic\Nexsell\Clients\ClientInterface;

class UsersController extends BaseBackendController {


	protected $clients;


	public function __construct(ClientInterface $clients)
	{
		parent::__construct();

		$this->clients = $clients;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$data = array('users' => $this->clients->getAll());

		//var_dump("<pre>" . print_r($o, TRUE) . "</pre>");
		return $this->theme->watch('users.index', $data)->render();
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		die('show: ' . $id);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data = array('user' => $this->clients->findById($id));
		return $this->theme->watch('users.form', $data)->render();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	    $user = $this->clients->findById($id);


	    $user->update(Input::all());
	    



	    if($user->save())
	    {
	    	Alert::success('You have successfully edited user.')->flash();
	        return Redirect::route('backend.users.edit', $id)->withInput();

	    } else {

	        //return Response::json($user->errors()->all());
	        return Redirect::route('backend.users.edit', $id)->withInput()->withErrors($user->validationErrors);
	    }   
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		die('destroy: ' . $id);
	}

}