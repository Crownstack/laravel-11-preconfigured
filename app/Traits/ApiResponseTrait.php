<?php

namespace App\Traits;

trait ApiResponseTrait
{
	/**
	 * Base method to make a standard format for API response
	 *
	 * @param  string $status
	 * @param  string $message
	 * @param  array $data
	 * @param  array $errors
	 * @param  string $statusCode
	 * @return array $response
	 */
	public function sendResponse($status = 'ok', $message = '', $data = [], $statusCode = 200, $errorResponseCode = '' )
	{

		$response = [
			'status' => $status,
			'message' => $message,
		];

		$key = 'data';

		if($status == 'error')
		{
			$key= 'error';
			$response['errorResponseCode'] = $errorResponseCode;
		}

		$response[$key] = $data;
		
		return response()->json($response, $statusCode);
	}
}
