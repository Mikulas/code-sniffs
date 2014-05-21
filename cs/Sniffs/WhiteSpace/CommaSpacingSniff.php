<?php

/**
 * Ensures no whitespaces and one whitespace is placed around each comma
 */
class cs_Sniffs_WhiteSpace_CommaSpacingSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * Returns an array of tokens this test wants to listen for.
	 *
	 * @return array
	 */
	public function register()
	{
		return [T_COMMA];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);

		if ($tokens[$next]['code'] !== T_WHITESPACE && ($next !== $stackPtr + 2))
		{
			// Last character in a line is ok.
			if ($tokens[$next]['line'] === $tokens[$stackPtr]['line'])
			{
				$error = 'Missing space after comma';
				$phpcsFile->addError($error, $next);
			}
		}

		$previous = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), NULL, TRUE);

		if ($tokens[$previous]['code'] !== T_WHITESPACE && ($previous !== $stackPtr - 1))
		{
			$error = 'Space before comma, expected none, though';
			$phpcsFile->addError($error, $next);
		}
	}

}
