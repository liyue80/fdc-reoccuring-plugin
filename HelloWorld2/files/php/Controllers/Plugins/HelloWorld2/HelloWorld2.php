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

	public function getObjectTypes(Request $request)
	{
		$response = Curl::to($this->apiUrl . "/ainvr/api/types")
			->withHeader('x-auth-token:' . $this->token)
			->withOption('SSL_VERIFYHOST', false)
			->returnResponseObject()
			->get();

		if ($response->status != 200) {
			$libs = new CommonLib();
			return $libs->handelHttpReturnMessage($response->content);
		}

		return json_decode($response->content);
	}

	public function getOptions(Request $request)
	{
		return Curl::to("http://127.0.0.1:5680/api/options")->get();
	}

	public function setOptions(Request $request)
	{
		$data = json_decode($request->getContent());
		// return array(
		// 	"result" => $request->isJson(),
		// 	"contentin" => $request->getContent()
		// );
		
		// $timewindow = $request->input('timewindow');
		// $threshold = $request->input('countthreshold');
		// // $targetobjects = $request->input('targetobjects');

		$response = Curl::to("http://127.0.0.1:5680/api/options")
			->withData($data)
			->asJson()
			->put();
		return $response;
	}
}
