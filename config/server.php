<?php

// -- ---------------------------------------------------------------------- -- //
// -- SMTP Server Configuration                                              -- //
// -- ---------------------------------------------------------------------- -- //

return
[
	/*
	 * ---------------------------------------------------------
	 * Hostname or IP Address
	 * ---------------------------------------------------------
	 */

	'host' => 'localhost',

	/*
	 * ---------------------------------------------------------
	 * Port                                   [ 25 | 465 | 587 ]
	 * ---------------------------------------------------------
	 */

	'port' => 25,

	/*
	 * ---------------------------------------------------------
	 * Encryption                       [ 'ssl' | 'tls' | null ]
	 * ---------------------------------------------------------
	 */

	'security' => null,

	/*
	 * ---------------------------------------------------------
	 * Authentication: Username & Password
	 * ---------------------------------------------------------
	 */

	'username' => null,
	'password' => null,
];