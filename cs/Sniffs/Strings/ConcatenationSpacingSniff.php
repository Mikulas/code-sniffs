<?php

/**
 * Makes sure there are spaces between the concatenation operator (.) and
 * the strings being concatenated.
 */
class cs_Sniffs_Strings_ConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return array(T_STRING_CONCAT);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE)
		{
			$message = 'Expected 1 space before . string concatenation, but 0 found';
			$phpcsFile->addError($message, $stackPtr, 'MissingBefore');
		}
		else
		{
			$content = str_replace("\r\n", "\n", $tokens[($stackPtr - 1)]['content']);
			$spaces = strlen($content);
			if ($spaces > 1)
			{
				$message = 'Expected 1 space before . string concatenation, but %d found';
				$data = array($spaces);
				$phpcsFile->addError($message, $stackPtr, 'TooManyBefore', $data);
			}
		}

		if ($tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE)
		{
			$message = 'Expected 1 space after . string concatenation, but 0 found';
			$phpcsFile->addError($message, $stackPtr, 'MissingAfter');
		}
		else
		{
			$content = str_replace("\r\n", "\n", $tokens[($stackPtr + 1)]['content']);
			$spaces = strlen($content);
			if ($spaces > 1)
			{
				$message = 'Expected 1 space after . string concatenation, but %d found';
				$data = array($spaces);
				$phpcsFile->addError($message, $stackPtr, 'TooManyAfter', $data);
			}
		}
	}

}
