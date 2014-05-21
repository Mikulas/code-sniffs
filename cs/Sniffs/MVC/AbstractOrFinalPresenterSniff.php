<?php

/**
 * Ensures all presenter classes are either final or abstract
 */
class cs_Sniffs_MVC_AbstractOrFinalPresenterSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [T_CLASS];
	}

	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param integer $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$namePtr = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
		$class = $tokens[$namePtr]['content'];
		$match = [];
		if (!preg_match('~^(?P<name>(?P<base>Base)?.*?)Presenter$~', $class, $match))
		{
			// not a presenter class
			return;
		}

		$modifiers = [];
		$offset = 1;
		while (TRUE)
		{
			$token = $tokens[$stackPtr - $offset];
			if (!in_array($token['code'], [T_WHITESPACE, T_FINAL, T_ABSTRACT]))
			{
				break;
			}
			if ($token['code'] !== T_WHITESPACE)
			{
				$modifiers[] = $token['code'];
			}

			$offset++;
		}

		if (isset($match['base']) && !in_array(T_ABSTRACT, $modifiers))
		{
			$error = 'Base presenter %s must be defined abstract';
			$phpcsFile->addError($error, $stackPtr, 'AbstractBasePresenter', [$class]);

		}
		else if (!count($modifiers))
		{
			$error = 'Presenter %s must be either final or abstract';
			$phpcsFile->addError($error, $stackPtr, 'NoPresenterModifier', [$class]);
		}
	}

}
