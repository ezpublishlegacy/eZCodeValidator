<?php

class LogMessage {
	public $type;
	public $message;
	public $date;
	public $caller;

	public function __construct($params) {
		if(!is_array($params)) {
			throw new Exception('$params is expected to be an array');
		}

		$this->type		= isset($params['type']) ? $params['type'] : null;
		$this->message	= isset($params['message']) ? $params['message'] : null;
		$this->date		= isset($params['date']) ? $params['date'] : null;
		$this->caller	= isset($params['caller']) ? $params['caller'] : null;
	}
}