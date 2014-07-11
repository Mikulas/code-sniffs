<?php

/**
 * Ensures '@inject' keyword is not on same line as '@var'
 * </code>
 */
class cs_Sniffs_Annotations_SeparateInjectSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [T_DOC_COMMENT];
	}


	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		if (preg_match('~@var.+@inject~is', $tokens[$stackPtr]['content']))
		{
			$phpcsFile->addError('@inject annotation must be on separate line', $stackPtr, 'SameLine');
		}
	}

}
