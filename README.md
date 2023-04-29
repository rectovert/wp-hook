[![php](https://github.com/rectovert/wp-hook/actions/workflows/php.yml/badge.svg)](https://github.com/rectovert/wp-hook/actions/workflows/php.yml)

> WordPress hooks with OOP, inspired from [WordPress Plugin Boilerplate](https://wppb.me/).

## Why?

One common pitfall that I often encounter when managing a large complex site with the plugins and themes is that we could easily fall into nesting multiple hooks:

```php
add_action( 'init', 'initialise' );

function initialise(): void
{
	add_action( 'after_setup_theme', 'hello_world' );
}

function hello_world(): void
{
    echo "Hello World";
}
```

The problem with the above example is that WordPress may never execute the `hello_world` function since the `after_setup_theme` would have already done executing before the `init` hook. In some extreme cases, nested hooks [may cause an error](https://wordpress.stackexchange.com/questions/147505/wp-insert-posts-fatal-error-maximum-function-nesting-level-of-100-reached-ab).

This library aims to help minimising this pitfall.

## Usage

```php
namespace Rectovert\WP\Hook;

$hook = new Hook();
$hook->addAction( 'init', 'initialise' );
$hook->addFilter( 'after_setup_theme', 'hello_world' );
$hook->run();
```

## References

- [WordPress Plugin Boilerplate](https://wppb.me/)
- [Maximum function nesting level of '100' reached, aborting!](https://wordpress.stackexchange.com/questions/147505/wp-insert-posts-fatal-error-maximum-function-nesting-level-of-100-reached-ab)
- [PHP OOP: Introduction](https://phptherightway.com/#object-oriented-programming)
