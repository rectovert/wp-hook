<?php

declare(strict_types=1);

namespace Rectovert\WP\Hook\Tests;

use ArgumentCountError;
use Rectovert\WP\Hook\Hook;

class HookTest extends TestCase
{
	private Hook $instance;

	public function setUp(): void
	{
		parent::setUp();

		$this->instance = new Hook();
	}

	public function testAddAction(): void
	{
		$func = static function (): bool {
			return true;
		};

		// No-run.
		$this->instance->addAction('wp', $func);
		$this->assertFalse(has_action('wp', $func));

		// Run.
		$this->instance->addAction('init', $func);
		$this->instance->run();

		$actual = has_action('init', $func);
		$expect = 10;

		$this->assertSame($expect, $actual);
	}

	public function testAddActionPriority(): void
	{
		$func = static function (): bool {
			return true;
		};

		$this->instance->addAction('init', $func, 100);
		$this->instance->run();

		$actual = has_action('init', $func);
		$expect = 100;

		$this->assertSame($expect, $actual);
	}

	public function testAddActionAcceptedArgs(): void
	{
		$this->instance->addAction('auth_cookie_malformed', static function ($cookie, $scheme): void {
			return;
		}, 100, 2);
		$this->instance->run();

		do_action('auth_cookie_malformed', '123', 'auth');

		$this->instance->addAction('auth_cookie_malformed', static function ($cookie, $scheme): void {
			return;
		}, 100);
		$this->instance->run();

		$this->expectException(ArgumentCountError::class);
		do_action('auth_cookie_malformed', '123', 'auth');
	}

	public function testAddFilter(): void
	{
		$func = static function ($value) {
			return $value;
		};

		// No-run.
		$this->instance->addFilter('the_content', $func);
		$this->assertFalse(has_filter('the_content', $func));

		// Run.
		$this->instance->addFilter('all_plugins', $func);
		$this->instance->run();

		$actual = has_filter('all_plugins', $func);
		$expect = 10;

		$this->assertSame($expect, $actual);
	}

	public function testAddFilterPriority(): void
	{
		$func = static function ($value) {
			return $value;
		};

		// Run.
		$this->instance->addFilter('all_plugins', $func, 100);
		$this->instance->run();

		$actual = has_filter('all_plugins', $func);
		$expect = 100;

		$this->assertSame($expect, $actual);
	}

	public function testAddFilterAcceptedArgs(): void
	{
		$this->instance->addFilter('allow_empty_comment', static function ($allowEmptyComment, $commentData) {
			return $allowEmptyComment;
		}, 100, 2);
		$this->instance->run();

		apply_filters('allow_empty_comment', false, []);

		$this->instance->addFilter('allow_empty_comment', static function ($allowEmptyComment, $commentData) {
			return $allowEmptyComment;
		}, 100);

		$this->instance->run();

		$this->expectException(ArgumentCountError::class);
		apply_filters('allow_empty_comment', false, []);
	}
}
