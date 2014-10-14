<?php

/**
 * Ensures NULL keyword is always first type
 *
 * Not allowed:
 * <code>
 * /** @property-read Foo|NULL * /
 * class Bar {}
 * </code>
 *
 * Allowed:
 * <code>
 * /**
 *  * @property-read NULL|Foo
 *  * /
 * class Bar {}
 * </code>
 */
class cs_Sniffs_Annotations_NullFirstSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return array(T_DOC_COMMENT);
	}


	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		if (preg_match('~\|NULL~i', $tokens[$stackPtr]['content']))
		{
			$phpcsFile->addError('NULL must be first defined type', $stackPtr, 'NullNotFirst');
		}
	}

}
