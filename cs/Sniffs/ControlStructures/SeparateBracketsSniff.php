<?php

/**
 * Ensures control structures' blocks start on new line:
 *
 * Not allowed:
 * <code>
 * if (...) {
 *  return TRUE;
 * }
 * </code>
 *
 * Allowed:
 * <code>
 * if (...)
 * {
 *   return TRUE;
 * }
 * </code>
 */
class cs_Sniffs_ControlStructures_SeparateBracketsSniff implements PHP_CodeSniffer_Sniff
{

	public function register()
	{
		return [T_IF, T_ELSE, T_ELSEIF, T_FOREACH, T_FOR];
	}


	/**
	 * @param PHP_CodeSniffer_File $phpcsFile
	 * @param int $stackPtr
	 * @return void
	 */
	public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
	{
		$tokens = $phpcsFile->getTokens();

		$next = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), NULL, TRUE);
		$isInline = TRUE;
		$offset = 0;
		$showCondition = FALSE;
		// skip condition of IF and ELSE IF
		if ($tokens[$next]['code'] === T_OPEN_PARENTHESIS)
		{
			$showCondition = TRUE;
			$depth = 1;
			$offset = 1;
			while ($depth > 0) {
				if ($tokens[$next + $offset]['code'] === T_OPEN_PARENTHESIS)
				{
					$depth++;
				}
				else if ($tokens[$next + $offset]['code'] === T_CLOSE_PARENTHESIS)
				{
					$depth--;
				}
				$offset++;
			}
		}
		$blockOrStatement = $phpcsFile->findNext(T_WHITESPACE, ($next + $offset + 1), NULL, TRUE);
		if ($tokens[$blockOrStatement]['code'] === T_OPEN_CURLY_BRACKET)
		{
			$isInline = FALSE;
		}

		if ($isInline)
		{
			// inline block such as
			// if (...) throw
			return;
		}

		$hasNewline = FALSE;
		$return = 1;
		$spacing = '';
		while (TRUE) {
			$whitespace = $tokens[$blockOrStatement - $return];
			$return++;

			/**
			 * <code>
			 * }
			 * else // comment
			 * {
			 * </code>
			 */
			if ($whitespace['code'] === T_COMMENT)
			{
				if ($whitespace['content'] !== '/*')
				{
					// comment until end of the line, this sniff must be fulfilled
					return;
				}
				else
				{
					throw new Exception('not implemented');
				}
			}
			if ($whitespace['code'] !== T_WHITESPACE)
			{
				break;
			}
			$spacing .= $whitespace['content'];
		};

		if ($hasNewline = strpos($spacing, "\n") === FALSE)
		{
			$error = 'Structure missing newline before bracket; found "%s';
			if ($showCondition)
			{
				$error .= ' (...)';
			}
			$error .= ' {"';
			$data = [$tokens[$stackPtr]['content']];
			$phpcsFile->addError($error, $blockOrStatement - $return, 'MissingNewline', $data);
		}
	}

}
