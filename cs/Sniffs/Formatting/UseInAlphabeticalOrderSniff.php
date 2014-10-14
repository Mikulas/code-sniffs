<?php

/**
 * Ensures all the use are in alphabetical order.
 */
class cs_Sniffs_Formatting_UseInAlphabeticalOrderSniff implements PHP_CodeSniffer_Sniff
{

	/** @var array */
	protected $_processed = array();

	public function register()
	{
		return array(T_USE);
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param integer $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		if (isset($this->_processed[$phpcsFile->getFilename()]))
		{
			return;
		}

		$tokens = $phpcsFile->getTokens();

		$isClosure = $phpcsFile->findPrevious(
			array(T_CLOSURE),
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

		// Only one USE declaration allowed per statement.
		$next = $phpcsFile->findNext(array(T_COMMA, T_SEMICOLON), ($stackPtr + 1));
		if ($tokens[$next]['code'] === T_COMMA)
		{
			$error = 'There must be one USE keyword per declaration';
			$phpcsFile->addError($error, $stackPtr, 'MultipleDeclarations');
		}

		$uses = array();

		$next = $stackPtr;
		while (TRUE)
		{
			$content = '';

			$end = $phpcsFile->findNext(array(T_SEMICOLON, T_OPEN_CURLY_BRACKET), $next);
			$useTokens = array_slice($tokens, $next, $end - $next, TRUE);
			$index = NULL;
			foreach ($useTokens as $index => $token)
			{
				if ($token['code'] === T_STRING || $token['code'] === T_NS_SEPARATOR)
				{
					$content .= $token['content'];
				}
			}

			// Check for class scoping on use. Traits should be
			// ordered independently.
			$scope = 0;
			if (!empty($token['conditions']))
			{
				$scope = key($token['conditions']);
			}
			$uses[$scope][$content] = $index;

			$next = $phpcsFile->findNext(T_USE, $end);
			if (!$next)
			{
				break;
			}
		}

		// Prevent multiple uses in the same file from entering
		$this->_processed[$phpcsFile->getFilename()] = TRUE;

		$ordered = TRUE;
		$failedIndex = NULL;
		foreach ($uses as $scope => $used)
		{
			$defined = $sorted = array_keys($used);

			natcasesort($sorted);
			$sorted = array_values($sorted);
			if ($sorted === $defined)
			{
				continue;
			}

			foreach ($defined as $i => $name)
			{
				if ($name !== $sorted[$i])
				{
					if ($failedIndex === NULL)
					{
						$failedIndex = $used[$name];
					}
					$ordered = FALSE;
				}
			}
		}
		if (!$ordered)
		{
			$error = 'Usings must be in alphabetical order';
			$phpcsFile->addError($error, $failedIndex, 'UseInAlphabeticalOrder', array());
		}
	}

}
