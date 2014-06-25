<?php

require __DIR__ . '/../bootstrap.php';

use Tester\Assert;

$sniff = 'cs.MVC.AbstractOrFinalPresenter';

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class FooPresenter extends Presenter {}
CONTENT
);
Assert::same(['NoPresenterModifier'], $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
class BasePresenter extends Presenter {}
CONTENT
);
Assert::same(['AbstractBasePresenter'], $cs->rules);

$cs = runPhpcs($sniff, <<<CONTENT
<?php
final class FooPresenter extends Presenter {}
CONTENT
);
Assert::same(0, $cs->errors);
