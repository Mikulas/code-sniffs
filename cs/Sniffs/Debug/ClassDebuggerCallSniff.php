<?php

/**
 * Warns about the use of debug methods
 */
class cs_Sniffs_Debug_ClassDebuggerCallSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return array(T_DOUBLE_COLON);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$className = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), NULL, TRUE);
		$method = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
		$methodName = $tokens[$method]['content'];

		$classes = array('debug', 'debugger');
		$methods = array('dump', 'bardump', 'firelog', 'timer');

		if (in_array(strtolower($tokens[$className]['content']), $classes)
			&& in_array(strToLower($methodName), $methods))
		{
			$error = 'Call to debug function %s::%s() should be removed';
			$data = array($tokens[$className]['content'], $methodName);
			$phpcsFile->addError($error, $stackPtr, 'Found', $data);
		}

	}

}
