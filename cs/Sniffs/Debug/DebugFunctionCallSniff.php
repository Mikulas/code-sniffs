<?php

/**
 * Discourages the use of debug functions
 */
class cs_Sniffs_Debug_DebugFunctionCallSniff extends Generic_Sniffs_PHP_ForbiddenFunctionsSniff
{

	/**
	 * A list of forbidden functions with their alternatives.
	 *
	 * The value is NULL if no alternative exists. IE, the
	 * function should just not be used.
	 *
	 * @var array(string => string|NULL)
	 */
	protected $forbiddenFunctions = array(
		'd' => NULL,
		'dd' => NULL,
		'de' => NULL,
		'dump' => NULL,
		'var_dump' => NULL,
		'error_log' => NULL,
		'print_r' => NULL,
	);

	/**
	 * If true, an error will be thrown; otherwise a warning.
	 *
	 * @var bool
	 */
	public $error = TRUE;

}
