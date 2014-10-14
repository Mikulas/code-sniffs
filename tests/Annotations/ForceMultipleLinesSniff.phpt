<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Annotations.ForceMultipleLines';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
/** @property \$test */
class Foo {}
CONTENT
);
Assert::same(array('SingleLine'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class Foo {
	/** @var Bar */
	public \$bar;
}
CONTENT
);
Assert::same(array('SingleLine'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
/**
 * @property \$test
 */
class Foo {}
CONTENT
);
Assert::same(0, $cs->errors);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
/** @var Foo \$test */
\$test = 'foo';
CONTENT
);
Assert::same(0, $cs->errors);
