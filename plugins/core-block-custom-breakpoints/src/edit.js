import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl, TextControl } from '@wordpress/components';

export default (props) => {
	const { attributes, setAttributes } = props;
	const { isStackedOnMobile, isStackedOnCustom, customBreakpoint } =
		attributes;

	const onChangeCustomStacking = (value) => {
		setAttributes({ isStackedOnCustom: value });
		if (isStackedOnMobile && value === true) {
			setAttributes({ isStackedOnMobile: false });
		}
	};

	return (
		<InspectorControls>
			<PanelBody title={__(' Custom Breakpoint')}>
				<ToggleControl
					label={__('Stack on Custom')}
					checked={isStackedOnCustom}
					onChange={onChangeCustomStacking}
				/>
				{isStackedOnCustom && (
					<TextControl
						label={__('Breakpoint in pixels')}
						value={customBreakpoint}
						onChange={(value) =>
							setAttributes({ customBreakpoint: value })
						}
					/>
				)}
			</PanelBody>
		</InspectorControls>
	);
};
