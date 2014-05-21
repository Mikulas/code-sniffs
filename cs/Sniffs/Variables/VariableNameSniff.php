<?php

/**
 * Checks the naming of variables and member variables.
 */
class cs_Sniffs_Variables_VariableNameSniff extends PHP_CodeSniffer_Standards_AbstractVariableSniff
{

	private $_ignore = array(T_WHITESPACE,T_COMMENT);

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	protected function processVariable(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens  = $phpcsFile->getTokens();
		$varName = ltrim($tokens[$stackPtr]['content'], '$');

		$phpReservedVars = [
			'_SERVER',
			'_GET',
			'_POST',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'_FILES',
			'GLOBALS',
			'http_response_header'
		];

		// If it's a php reserved var, then its ok.
		if (in_array($varName, $phpReservedVars) === TRUE)
		{
			return;
		}

		$objOperator = $phpcsFile->findNext([T_WHITESPACE], ($stackPtr + 1), NULL, TRUE);
		if ($tokens[$objOperator]['code'] === T_OBJECT_OPERATOR)
		{
			// Check to see if we are using a variable from an object.
			$var = $phpcsFile->findNext([T_WHITESPACE], ($objOperator + 1), NULL, TRUE);
			if ($tokens[$var]['code'] === T_STRING)
			{
				// Either a var name or a function call, so check for bracket.
				$bracket = $phpcsFile->findNext([T_WHITESPACE], ($var + 1), NULL, TRUE);

				if ($tokens[$bracket]['code'] !== T_OPEN_PARENTHESIS)
				{
					$objVarName = $tokens[$var]['content'];

					// There is no way for us to know if the var is public or private,
					// so we have to ignore a leading underscore if there is one and just
					// check the main part of the variable name.
					$originalVarName = $objVarName;
					if (substr($objVarName, 0, 1) === '_')
					{
						$objVarName = substr($objVarName, 1);
					}

					if (PHP_CodeSniffer::isCamelCaps($objVarName, FALSE, TRUE, FALSE) === FALSE)
					{
						$error = 'Variable "%s" is not in valid camel caps format';
						$data  = [$originalVarName];
						$phpcsFile->addError($error, $var, 'NotCamelCaps', $data);
					}
				}
			}
		}

		// There is no way for us to know if the var is public or private,
		// so we have to ignore a leading underscore if there is one and just
		// check the main part of the variable name.
		$originalVarName = $varName;
		if (substr($varName, 0, 1) === '_')
		{
			$objOperator = $phpcsFile->findPrevious([T_WHITESPACE], ($stackPtr - 1), NULL, TRUE);
			if ($tokens[$objOperator]['code'] === T_DOUBLE_COLON)
			{
				// The variable lives within a class, and is referenced like
				// this: MyClass::$_variable, so we don't know its scope.
				$inClass = TRUE;
			}
			else
			{
				$inClass = $phpcsFile->hasCondition($stackPtr, [T_CLASS, T_INTERFACE, T_TRAIT]);
			}

			if ($inClass === TRUE)
			{
				$varName = substr($varName, 1);
			}
		}

		if (PHP_CodeSniffer::isCamelCaps($varName, FALSE, TRUE, FALSE) === FALSE)
		{
			$error = 'Variable "%s" is not in valid camel caps format';
			$data  = [$originalVarName];
			$phpcsFile->addError($error, $stackPtr, 'NotCamelCaps', $data);
		}

	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	protected function processMemberVar(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();
		$varName = ltrim($tokens[$stackPtr]['content'], '$');
		$memberProps = $phpcsFile->getMemberProperties($stackPtr);
		$public = ($memberProps['scope'] === 'public');

		if ($public === TRUE)
		{
			if (substr($varName, 0, 1) === '_')
			{
				$error = 'Public member variable "%s" must not contain a leading underscore';
				$data  = [$varName];
				$phpcsFile->addError($error, $stackPtr, 'PublicHasUnderscore', $data);

				return;
			}
		}
		else
		{
			// private
			return;

			// if (substr($varName, 0, 1) !== '_') {
			// 	$scope = ucfirst($memberProps['scope']);
			// 	$error = '%s member variable "%s" must contain a leading underscore';
			// 	$data  = array(
			// 		$scope,
			// 		$varName,
			// 	);
			// 	$phpcsFile->addError($error, $stackPtr, 'PrivateNoUnderscore', $data);
			// 	return;
			// }
		}

		if (PHP_CodeSniffer::isCamelCaps($varName, FALSE, $public, FALSE) === FALSE)
		{
			$error = 'Variable "%s" is not in valid camel caps format';
			$data  = [$varName];
			$phpcsFile->addError($error, $stackPtr, 'MemberVarNotCamelCaps', $data);
		}

	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	protected function processVariableInString(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$phpReservedVars = [
			'_SERVER',
			'_GET',
			'_POST',
			'_REQUEST',
			'_SESSION',
			'_ENV',
			'_COOKIE',
			'_FILES',
			'GLOBALS',
			'http_response_header'
		];

		if (preg_match_all('|[^\\\]\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)|', $tokens[$stackPtr]['content'], $matches) !== 0)
		{
			foreach ($matches[1] as $varName)
			{
				// If it's a php reserved var, then its ok.
				if (in_array($varName, $phpReservedVars) === TRUE)
				{
					continue;
				}

				// There is no way for us to know if the var is public or private,
				// so we have to ignore a leading underscore if there is one and just
				// check the main part of the variable name.
				$originalVarName = $varName;
				if (substr($varName, 0, 1) === '_')
				{
					if ($phpcsFile->hasCondition($stackPtr, [T_CLASS, T_INTERFACE, T_TRAIT]) === TRUE)
					{
						$varName = substr($varName, 1);
					}
				}

				if (PHP_CodeSniffer::isCamelCaps($varName, FALSE, TRUE, FALSE) === FALSE)
				{
					$varName = $matches[0];
					$error = 'Variable "%s" is not in valid camel caps format';
					$data = [$originalVarName];
					$phpcsFile->addError($error, $stackPtr, 'StringVarNotCamelCaps', $data);
				}
			}
		}

	}

}
