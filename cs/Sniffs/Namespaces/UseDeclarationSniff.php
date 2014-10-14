<?php

class cs_Sniffs_Namespaces_UseDeclarationSniff extends PSR2_Sniffs_Namespaces_UseDeclarationSniff
{

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		if ($this->_shouldIgnoreUse($phpcsFile, $stackPtr) === TRUE)
		{
			return;
		}

		$tokens = $phpcsFile->getTokens();

		// Only one USE declaration allowed per statement.
		$next = $phpcsFile->findNext(array(T_COMMA, T_SEMICOLON), ($stackPtr + 1));
		if ($tokens[$next]['code'] === T_COMMA)
		{
			$error = 'There must be one USE keyword per declaration';
			$phpcsFile->addError($error, $stackPtr, 'MultipleDeclarations');
		}

		// Make sure this USE comes after the first namespace declaration.
		$prev = $phpcsFile->findPrevious(T_NAMESPACE, ($stackPtr - 1));
		if ($prev !== FALSE)
		{
			$first = $phpcsFile->findNext(T_NAMESPACE, 1);
			if ($prev !== $first)
			{
				$error = 'USE declarations must go after the first namespace declaration';
				$phpcsFile->addError($error, $stackPtr, 'UseAfterNamespace');
			}
		}

		// Only interested in the last USE statement from here onwards.
		$nextUse = $phpcsFile->findNext(T_USE, ($stackPtr + 1));
		while ($this->_shouldIgnoreUse($phpcsFile, $nextUse) === TRUE)
		{
			$nextUse = $phpcsFile->findNext(T_USE, ($nextUse + 1));
			if ($nextUse === FALSE)
			{
				break;
			}
		}

		if ($nextUse !== FALSE)
		{
			return;
		}

		$end  = $phpcsFile->findNext(T_SEMICOLON, ($stackPtr + 1));
		$next = $phpcsFile->findNext(T_WHITESPACE, ($end + 1), NULL, TRUE);
		$diff = ($tokens[$next]['line'] - $tokens[$end]['line'] - 1);
		if ($diff !== 2)
		{
			if ($diff < 0)
			{
				$diff = 0;
			}

			$error = 'There must be two blank lines after the last USE statement; %s found;';
			$data  = array($diff);
			$phpcsFile->addError($error, $stackPtr, 'SpaceAfterLastUse', $data);
		}

	}

	/**
	 * Check if this use statement is part of the namespace block.
	 *
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return bool
	 */
	private function _shouldIgnoreUse(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		// keyword inside closure
		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
		if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS)
		{
			return TRUE;
		}

		// trait
		if ($phpcsFile->hasCondition($stackPtr, T_CLASS) === TRUE)
		{
			return TRUE;
		}

		return FALSE;
	}

}
