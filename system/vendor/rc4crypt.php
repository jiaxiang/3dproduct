<?php

// Class Made By Mukul Sabharwal [mukulsabharwal@yahoo.com]
// http://www.devhome.net/php/
// On October 21, 2000

function r4crypt ($pwd, $data, $case='en') {

	if ($case == 'de')
	{

		$data = base64_decode($data);

	}

	$key[] = "";
	$box[] = "";
	$temp_swap = "";
	$pwd_length = 0;

	$pwd_length = strlen($pwd);

	for ($i = 0; $i < 255; $i++) {

		$key[$i] = ord(substr($pwd, ($i % $pwd_length)+1, 1));
		$box[$i] = $i;

	}

	$x = 0;

	for ($i = 0; $i < 255; $i++) {

		$x = ($x + $box[$i] + $key[$i]) % 256;
		$temp_swap = $box[$i];

		$box[$i] = $box[$x];
		$box[$x] = $temp_swap;

	}

	$temp = "";
	$k = "";

	$cipherby = "";
	$cipher = "";

	$a = 0;
	$j = 0;

	for ($i = 0; $i < strlen($data); $i++) {

		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;

		$temp = $box[$a];
		$box[$a] = $box[$j];

		$box[$j] = $temp;

		$k = $box[(($box[$a] + $box[$j]) % 256)];
		$cipherby = ord(substr($data, $i, 1)) ^ $k;

		$cipher .= chr($cipherby);

	}

	if ($case == 'de')
	{

		$cipher = base64_decode(base64_encode($cipher));

	}
	else
	{

		$cipher = base64_encode($cipher);

	}

	return $cipher;

}

?>