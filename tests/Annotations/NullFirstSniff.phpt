<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Annotations.NullFirst';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class Bar
{
	/**
	 * @var Foo|NULL
	 */
	public \$test;
}
CONTENT
);
Assert::same(array('NullNotFirst'), $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class Bar
{
	/**
	 * @var NULL|Foo
	 */
	public \$test;
}
CONTENT
);
Assert::same(0, $cs->errors);
