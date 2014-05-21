Custom CodeSniffer rules
========================

ControlStructures
-----------------

*SeparateBracketsSniff*

force newline before opening curly bracket of `if`, `else`, `elseif`, `foreach` and `for` blocks:

```php
if (...)
{
 	return TRUE;
}
```

Debug
-----

*ClassDebuggerCallSniff*

Warn if methods `dump`, `barDump`, `firelog` or `timer` are called on `Debugger` class.

*DebugFunctionCallSniff*

Warn if function `d`, `dd`, `de`, `dump`, `var_dump`, `error_log`, or `print_r` is called.

Formatting
----------

*UseInAlphabeticalOrderSniff*

*UseWithoutStartingSeparator*

Warn on `use \Foo\Bar;`, suggest `use Foo\Bar` instead.

MVC
---

*AbstractOrFinalPresenterSniff*

Nette Presenter classes must be either abstract or final.

Namespaces
----------

*UseDeclarationSniff*

Use declarations must be right after namespace declaration,
separated by exactly one empty line. There must be exactly
one use per declaration. There must be exactly two empty lines
after last use declaration.

Newlines
--------

*NamespaceNewlinesSniff*

Namespace declaration must be directly under php opening tag,
separated by exactly one empty line. There must be exactly one
empty line between namespace declaration and first use declaration.
If no use declaration follows, there must be two lines after
namespace declaration.

```php
<?php

namespace Foo;

use Bar;


class Quaz {}
```

*UseNewlinesSniff*

Use declarations must have no empty newlines in between.

PHP
---

*KeywordCaseSniff*

Checks that all constructs but logical operators are lowercase.
(e.g.: `foreach` instead of `ForEach`)

*UpperCaseBooleanConstantSniff*
*UpperCaseNullConstantSniff*

```php
TRUE && FALSE && NULL;
```

Strings
-------

*ConcatenationSpacingSniff*

Concatenation operator (`.`) must be separated by exacly one
space on both sides. 

Variables
---------

*VariableNameSniff*

Enforce camelCase and deny leading underscore.

WhiteSpace
----------

*CommaSpacingSniff*

Ensures there is no whitespaces before and exactly one space after each comma.
