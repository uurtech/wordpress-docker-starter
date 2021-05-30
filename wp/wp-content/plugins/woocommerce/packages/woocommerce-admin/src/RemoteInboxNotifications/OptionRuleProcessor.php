<?php
/**
 * Rule processor that performs a comparison operation against an option value.
 */

namespace Automattic\WooCommerce\Admin\RemoteInboxNotifications;

defined( 'ABSPATH' ) || exit;

/**
 * Rule processor that performs a comparison operation against an option value.
 */
class OptionRuleProcessor implements RuleProcessorInterface {
	/**
	 * Performs a comparison operation against the option value.
	 *
	 * @param object $rule         The specific rule being processed by this rule processor.
	 * @param object $stored_state Stored state.
	 *
	 * @return bool The result of the operation.
	 */
	public function process( $rule, $stored_state ) {
		$default_value = 'contains' === $rule->operation ? array() : false;
		$default       = isset( $rule->default ) ? $rule->default : $default_value;
		$option_value  = get_option( $rule->option_name, $default );

		return ComparisonOperation::compare(
			$option_value,
			$rule->value,
			$rule->operation
		);
	}

	/**
	 * Validates the rule.
	 *
	 * @param object $rule The rule to validate.
	 *
	 * @return bool Pass/fail.
	 */
	public function validate( $rule ) {
		if ( ! isset( $rule->option_name ) ) {
			return false;
		}

		if ( ! isset( $rule->value ) ) {
			return false;
		}

		if ( ! isset( $rule->operation ) ) {
			return false;
		}

		return true;
	}
}
