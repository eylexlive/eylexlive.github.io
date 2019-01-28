<?php

return [
	
	'type' => env('PAYMENT_TYPE'),

	'methods' => [

		'batihost' => [
			'id' => env('BATIHOST_ID'),
			'token' => env('BATIHOST_TOKEN')
		],

		'paywant' => [
			'key' => env('PAYWANT_KEY'),
			'secret' => env('PAYWANT_SECRET')
		]

	]

];