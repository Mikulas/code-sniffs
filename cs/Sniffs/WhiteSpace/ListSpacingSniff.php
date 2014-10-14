<?php

/**
 * Ensures no whitespace after list keyword: list($a, $b)
 */
class cs_Sniffs_WhiteSpace_ListSpacingSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return array(T_LIST);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE)
		{
			$error = 'Space after list';
			$phpcsFile->addError($error, $stackPtr, 'SpaceAfter');
		}
	}

}
