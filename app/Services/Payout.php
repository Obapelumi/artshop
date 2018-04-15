<?php

namespace App\Services;

// use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class Payout
{
	protected $P_KEY;
	protected $S_KEY;
	protected $URL;

	public function __construct() {
		// $this->P_KEY = 'pk_test_63fbe44f64ec22de32b7ad8e1ed84c1375d50f7c';
		$this->P_KEY = config('app.paystackPK');
		// $this->S_KEY = 'sk_test_5d6122c1135b280b393fa16bd7c07155f33f32bc';
		$this->S_KEY = config('app.paystackSK');
		$this->URL = config('app.paystackURL');
	}

	public function createTransferRecipient ($data) {
        $URL = $this->URL. '/transferrecipient';
        $name = $data['brand_name'];
        $accNumber = $data['acc_no'];
        $bankCode = $data['bank_code'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \n   \"type\": \"nuban\",\n   \"name\": \"$name\",\n   \"description\": \"Vendor\",\n   \"account_number\": \"$accNumber\",\n   \"bank_code\": \"$bankCode\",\n   \"currency\": \"NGN\",\n   \"metadata\": {\n      \"job\": \"Vendor\"\n    }\n }");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function listTransferRecipient () {
		$URL = $this->URL . '/transferrecipient';

		$ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function checkBalance() {
		$URL = $this->URL . '/balance';
		$ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");


        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function disableOTP() {
		$URL = $this->URL . '/transfer/disable_otp';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function finalizeDisableOTP($data) {
		$URL = $this->URL . '/transfer/disable_otp_finalize';
        $otp = $data['otp'];

	    curl_setopt($ch, CURLOPT_URL, "$URL");
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"otp\": \"$otp\"}");
	    curl_setopt($ch, CURLOPT_POST, 1);

	    $headers = array();
	    $headers[] = "Authorization: Bearer $this->S_KEY";
	    $headers[] = "Content-Type: application/json";
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	    $result = curl_exec($ch);
	    if (curl_errno($ch)) {
	        return curl_error($ch);
	    }
	    curl_close ($ch);

        return json_decode($result);
	}

	public function enableOTP() {
		$URL = $this->URL . '/transfer/enable_otp';

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function makePayment ($data) {
		$URL = $this->URL . '/transfer';
        $recipientCode = $data['recipient_code'];
        $reason = $data['reason'];
        $amount = $data['amount'];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "$URL");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\": \"balance\", \"reason\": \"$reason\", \"amount\":$amount, \"recipient\": \"$recipientCode\"}");
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Authorization: Bearer $this->S_KEY";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close ($ch);

        return json_decode($result);
	}

	public function finalizePayment ($data) {

	}

}