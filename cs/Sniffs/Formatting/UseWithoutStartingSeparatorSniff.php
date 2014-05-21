<?php

/**
 * Disalows:
 * <code>
 * use \Foo\Bar;
 * </code>
 *
 * Expects:
 * <code>
 * use Foo\Bar;
 * </code>
 */
class cs_Sniffs_Formatting_UseWithoutStartingSeparatorSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [T_USE];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param integer $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$isClosure = $phpcsFile->findPrevious(
			[T_CLOSURE],
			($stackPtr - 1),
			NULL,
			FALSE,
			NULL,
			TRUE
		);
		if ($isClosure)
		{
			return;
		}

		if ($tokens[$stackPtr + 1]['code'] === T_WHITESPACE && $tokens[$stackPtr + 2]['code'] === T_NS_SEPARATOR)
		{
			$error = 'Usings must not start with opening ns separator';
			$phpcsFile->addError($error, $stackPtr, 'NotAllowed', []);
		}
	}

}
