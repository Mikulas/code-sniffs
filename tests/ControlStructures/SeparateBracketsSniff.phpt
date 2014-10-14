<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.ControlStructures.SeparateBrackets';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
if (TRUE) {
	return TRUE;
}
CONTENT
);
Assert::same(array('MissingNewline'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
if (TRUE)
{
	return TRUE;
}
CONTENT
);
Assert::same(0, $cs->errors);
