<?php

declare(strict_types=1);

namespace Rectovert\WP\Hook;

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @phpstan-type WPHook array{hook: string, callback: callable, priority: int, accepted_args: int}
 */
final class Hook
{
	/**
	 * The array of actions registered with WordPress.
	 *
	 * @phpstan-var array<WPHook>
	 */
	private array $actions = [];

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @phpstan-var array<WPHook>
	 */
	private array $filters = [];

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @param string   $hook         The name of the WordPress action that is being registered.
	 * @param callable $callback     The name of the function to be called with Action hook.
	 * @param int      $priority     Optional. The priority at which the function should be fired. Default is 10.
	 * @param int      $acceptedArgs Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function addAction(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
	{
		$this->actions = $this->add($this->actions, $hook, $callback, $priority, $acceptedArgs);
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @param string   $hook         The name of the WordPress filter that is being registered.
	 * @param callable $callback     The name of the function to be called with Filter hook.
	 * @param int      $priority     Optional. The priority at which the function should be fired. Default is 10.
	 * @param int      $acceptedArgs Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function addFilter(string $hook, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
	{
		$this->filters = $this->add($this->filters, $hook, $callback, $priority, $acceptedArgs);
	}

	/**
	 * Register the filters and actions with WordPress.
	 */
	public function run(): void
	{
		foreach ($this->filters as $hook) {
			add_filter($hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args']);
		}

		foreach ($this->actions as $hook) {
			add_action($hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args']);
		}
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @param string   $hook         The name of the WordPress filter that is being registered.
	 * @param callable $callback     The name of the function to be called with hook.
	 * @param int      $priority     The priority at which the function should be fired.
	 * @param int      $acceptedArgs The number of arguments that should be passed to the $callback.
	 *
	 * @phpstan-param array<WPHook> $hooks The collection of hooks that is being registered (that is, actions or filters).
	 * @phpstan-return array<WPHook>
	 */
	private function add(array $hooks, string $hook, callable $callback, int $priority, int $acceptedArgs): array
	{
		$hooks[] = [
			'hook' => $hook,
			'callback' => $callback,
			'priority' => $priority,
			'accepted_args' => $acceptedArgs,
		];

		return $hooks;
	}
}
