<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.Variables.VariableName';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
\$FooBar;
\$foo_bar;

class Foo
{
	public \$FooBar;
	public \$foo_bar;
	protected \$baz_qaz; // not public, ok
}
CONTENT
);

Assert::same(array('NotCamelCaps', 'NotCamelCaps', 'MemberVarNotCamelCaps', 'MemberVarNotCamelCaps'), $cs->rules);
