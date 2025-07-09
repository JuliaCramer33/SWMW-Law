wp.hooks.addFilter(
	'blocks.getSaveElement',
	`rb/core/columns/break-on-custom-breakpoint/breakpoint-styles`,
	function (element, block, attributes) {
		if (block.name !== 'core/columns') {
			return element;
		}

		if (attributes.isStackedOnCustom ?? false) {
			const css = `
            .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-${attributes.customBreakpoint} {
                flex-wrap: wrap !important;
            }
            @media (min-width: ${attributes.customBreakpoint}px) {
                .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-${attributes.customBreakpoint} {
                    flex-wrap: nowrap !important;
                }
            }
            @media (max-width: calc(${attributes.customBreakpoint}px - 1px)) {
                .wp-block-columns.is-not-stacked-on-mobile.is-stacked-on-custom-${attributes.customBreakpoint} > .wp-block-column {
                    flex-basis: 100% !important;
                }
            }
        `;

			return (
				<>
					{element}
					<style>{css}</style>
				</>
			);
		}

		return element;
	}
);
