<?php

namespace App\Http\Controllers\Plugins\HelloWorld2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Log;

class HelloWorld2 extends Controller
{

	public function getUserNameById(Request $request)
	{
		$accountId = $request->input('accountId');
		Log::info('accountId = ' . $accountId);
		$response = Curl::to($this->apiUrl . "/ainvr/api/accounts/{$accountId}")
			->withHeader('x-auth-token:' . $this->token)
			->withOption('SSL_VERIFYHOST', false)
			->returnResponseObject()
			->get();

		if ($response->status != 200) {
			$libs = new CommonLib();
			return $libs->handelHttpReturnMessage($response->content);
		}

		//$ret['status'] = $response->status;
		$userName = json_decode($response->content)->name;

		return $userName;
	}
}
