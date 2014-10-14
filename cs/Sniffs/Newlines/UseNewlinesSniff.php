<?php

class cs_Sniffs_Newlines_UseNewlinesSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return array(T_USE);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		if ($this->_shouldIgnoreUse($phpcsFile, $stackPtr))
		{
			return;
		}

		$nextUse = $phpcsFile->findNext(T_USE, ($stackPtr + 1), NULL, FALSE);
		if (!$nextUse || $this->_shouldIgnoreUse($phpcsFile, $nextUse))
		{
			return;
		}

		$newlines = 0;
		for ($ptr = $stackPtr + 2; $ptr < $nextUse; ++$ptr)
		{
			$space = $tokens[$ptr];
			if ($space['type'] !== 'T_WHITESPACE')
			{
				continue;
			}
			if (strpos("\n", $space['content']) !== FALSE)
			{
				$newlines++;
			}
		}

		if ($newlines !== 1)
		{
			$error = 'Extraneous newline after use';
			$phpcsFile->addError($error, $ptr, 'ExtraneousNewline');
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

		// use trait in class
		if ($phpcsFile->hasCondition($stackPtr, T_CLASS) === TRUE)
		{
			return TRUE;
		}

		// use trait in trait
		if ($phpcsFile->hasCondition($stackPtr, T_TRAIT) === TRUE)
		{
			return TRUE;
		}

		return FALSE;
	}

}
