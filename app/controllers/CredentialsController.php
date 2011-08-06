<?php

namespace app\controllers;

use app\models\Credentials;

class CredentialsController extends \lithium\action\Controller {

	public function index() {
		$credentials = Credentials::all();
		return compact('credentials');
	}

	public function view() {
		$credential = Credentials::first($this->request->id);
		return compact('credential');
	}

	public function add() {
		$credential = Credentials::create();

		if (($this->request->data) && $credential->save($this->request->data)) {
			$this->redirect(array('Credentials::view', 'args' => array($credential->id)));
		}
		return compact('credential');
	}

	public function edit() {
		$credential = Credentials::find($this->request->id);

		if (!$credential) {
			$this->redirect('Credentials::index');
		}
		if (($this->request->data) && $credential->save($this->request->data)) {
			$this->redirect(array('Credentials::view', 'args' => array($credential->id)));
		}
		return compact('credential');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Credentials::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Credentials::find($this->request->id)->delete();
		$this->redirect('Credentials::index');
	}
}

?>