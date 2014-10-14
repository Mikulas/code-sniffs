<?php

/**
 * Checks that all uses of true and false are lowercase.
 */
class cs_Sniffs_PHP_UpperCaseBooleanConstantSniff implements PHP_CodeSniffer_Sniff
{

	/**
	 * A list of tokenizers this sniff supports.
	 *
	 * @var string[]
	 */
	public $supportedTokenizers = array('PHP', 'JS');

	public function register()
	{
		return array(
			T_TRUE,
			T_FALSE,
		);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		// Is this a member var name?
		$prevPtr = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), NULL, TRUE);
		if ($tokens[$prevPtr]['code'] === T_OBJECT_OPERATOR)
		{
			return;
		}

		// Is this a class name?
		if ($tokens[$prevPtr]['code'] === T_CLASS
			|| $tokens[$prevPtr]['code'] === T_EXTENDS
			|| $tokens[$prevPtr]['code'] === T_IMPLEMENTS
			|| $tokens[$prevPtr]['code'] === T_NEW
		)
		{
			return;
		}

		// Class or namespace?
		if ($tokens[($stackPtr - 1)]['code'] === T_NS_SEPARATOR)
		{
			return;
		}

		$keyword = $tokens[$stackPtr]['content'];
		if (strtoupper($keyword) !== $keyword)
		{
			$error = 'TRUE, FALSE must be uppercase; expected "%s" but found "%s"';
			$data  = array(strtoupper($keyword), $keyword);
			$phpcsFile->addError($error, $stackPtr, 'Found', $data);
		}
	}

}
