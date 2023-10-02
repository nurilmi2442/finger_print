<?php 
namespace App\Presentation;

class ResponseCreator {


	private $code;
    private $message;
    private $data;
    private $errors;

	function  __construct($code, $message, $data, $errors){
		$this->code = $code;
        $this->message = $message;
        $this->data = $data;
        $this->errors = $errors;
	} 

	public function getResponse()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
            'errors' => $this->errors
        ];
    }
}

?>