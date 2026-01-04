module.exports = {
	extends: ['plugin:@wordpress/eslint-plugin/recommended'],
	env: {
		browser: true,
		es6: true,
		jquery: true, // If you use jQuery
	},
	globals: {
		wp: 'readonly', // If you use WordPress global `wp` object
		swmwLawData: 'readonly', // Your localized script data
	},
	rules: {
		// You can add or override rules here if needed
		// e.g., 'no-console': 'warn',
	},
};
