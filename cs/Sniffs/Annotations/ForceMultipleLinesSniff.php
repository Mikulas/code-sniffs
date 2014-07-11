<?php

/**
 * Ensures control structures' blocks start on new line:
 *
 * Not allowed:
 * <code>
 * /** @property-read Foo * /
 * class Bar {}
 * </code>
 *
 * Allowed:
 * <code>
 * /**
 *  * @property-read Foo
 *  * /
 * class Bar {}
 * </code>
 */
class cs_Sniffs_Annotations_ForceMultipleLinesSniff implements PHP_CodeSniffer_Sniff
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

		$c = $tokens[$stackPtr]['content'];
		$multiline = preg_match('~^/\*\*[ \t]*$~m', $c);
		$continuation = !preg_match('~^\s*/\*\*~', $c);
		if ($multiline || $continuation)
		{
			return;
		}

		$next = $phpcsFile->findNext([T_WHITESPACE, T_DOC_COMMENT], ($stackPtr + 1), NULL, TRUE);
		if (in_array($tokens[$next]['code'], [T_CLASS, T_PRIVATE, T_PROTECTED, T_PUBLIC, T_ABSTRACT, T_CONST, T_STATIC]))
		{
			$phpcsFile->addError('Single line annotation is only alloved on variables', $next, 'SingleLine');
		}
	}

}
