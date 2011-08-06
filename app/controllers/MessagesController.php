<?php

namespace app\controllers;

use app\models\Messages;

class MessagesController extends \lithium\action\Controller {

	public function index() {
		$messages = Messages::all();
		return compact('messages');
	}

	public function view() {
		$message = Messages::first($this->request->id);
		return compact('message');
	}

	public function add() {
		$message = Messages::create();

		if (($this->request->data) && $message->save($this->request->data)) {
			$this->redirect(array('Messages::view', 'args' => array($message->id)));
		}
		return compact('message');
	}

	public function edit() {
		$message = Messages::find($this->request->id);

		if (!$message) {
			$this->redirect('Messages::index');
		}
		if (($this->request->data) && $message->save($this->request->data)) {
			$this->redirect(array('Messages::view', 'args' => array($message->id)));
		}
		return compact('message');
	}

	public function delete() {
		if (!$this->request->is('post') && !$this->request->is('delete')) {
			$msg = "Messages::delete can only be called with http:post or http:delete.";
			throw new DispatchException($msg);
		}
		Messages::find($this->request->id)->delete();
		$this->redirect('Messages::index');
	}
}

?>