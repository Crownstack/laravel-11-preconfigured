<?php

namespace App\Traits;

trait ApiResponse
{
	public function sendResponse($status = 'ok', $message = '', $data = [], $errors = [], $exceptions = [], $statusCode = 200){
		$response = ['status' => $status, 'message' => $message];
		switch ($status) {
			case  'ok': {
				$response['data'] = $data;
			}
			break;
			case 'error': {
				$response['errors'] = $errors;
			}
			break;
			case 'exception': {
				$response['exceptions'] = $exceptions;
			}
			break;
			default: {
				$response['data'] = [];
			}
		}
		return response()->json($response,$statusCode);
	}
}
