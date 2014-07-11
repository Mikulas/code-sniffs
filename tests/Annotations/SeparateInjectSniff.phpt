<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Annotations.SeparateInject';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class Bar
{
	/**
	 * @var Foo @inject
	 */
	public \$test;
}
CONTENT
);
Assert::same(['SameLine'], $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class Bar
{
	/**
	 * @var Foo
	 * @inject
	 */
	public \$test;
}
CONTENT
);
Assert::same(0, $cs->errors);
