/**
 * @wordpress/element
 */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { Fragment } from '@wordpress/element';
import {
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
} from '@wordpress/block-editor';
import { createHigherOrderComponent } from '@wordpress/compose';
import { PanelBody, Button, ResponsiveWrapper } from '@wordpress/components';

const allowedBlocks = ['core/button'];

/**
 * Add icon attributes to the button block.
 *
 * @param {Object} settings Current block settings.
 * @param {string} name     Block name.
 *
 * @return {Object} Modified block settings.
 */
const addIconAttributes = (settings, name) => {
	if (!allowedBlocks.includes(name)) {
		return settings;
	}

	settings.attributes = {
		...settings.attributes,
		iconId: {
			type: 'number',
		},
		iconUrl: {
			type: 'string',
		},
		iconAlt: {
			type: 'string',
		},
	};

	return settings;
};

/**
 * Add icon controls to the button block sidebar.
 */
const withIconControls = createHigherOrderComponent((BlockEdit) => {
	return (props) => {
		const { name, attributes, setAttributes } = props;
		const { iconId, iconUrl, iconAlt } = attributes;

		if (!allowedBlocks.includes(name)) {
			return <BlockEdit {...props} />;
		}

		const onSelectIcon = (icon) => {
			setAttributes({
				iconId: icon.id,
				iconUrl: icon.url,
				iconAlt: icon.alt,
			});
		};

		const onRemoveIcon = () => {
			setAttributes({
				iconId: undefined,
				iconUrl: undefined,
				iconAlt: undefined,
			});
		};

		return (
			<Fragment>
				<BlockEdit {...props} />
				<InspectorControls>
					<PanelBody
						title={__('Icon Settings', 'swmw-law')}
						initialOpen={true}
					>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={onSelectIcon}
								allowedTypes={['image']}
								value={iconId}
								render={({ open }) => (
									<Button
										className={
											iconId
												? 'editor-post-featured-image__preview'
												: 'editor-post-featured-image__toggle'
										}
										onClick={open}
									>
										{!iconId ? (
											__('Choose an icon', 'swmw-law')
										) : (
											<ResponsiveWrapper
												naturalWidth={1}
												naturalHeight={1}
											>
												<img
													src={iconUrl}
													alt={iconAlt}
												/>
											</ResponsiveWrapper>
										)}
									</Button>
								)}
							/>
						</MediaUploadCheck>
						{iconId && (
							<Button onClick={onRemoveIcon} isLink isDestructive>
								{__('Remove icon', 'swmw-law')}
							</Button>
						)}
					</PanelBody>
				</InspectorControls>
			</Fragment>
		);
	};
}, 'withIconControls');

/**
 * Add filters to extend the button block.
 */
addFilter(
	'blocks.registerBlockType',
	'swmw-law/button-icon-attributes',
	addIconAttributes
);

addFilter(
	'editor.BlockEdit',
	'swmw-law/button-icon-controls',
	withIconControls
);

// NOTE: The filter to display the icon in the editor has been temporarily removed
// to solve the build error. The icon will be selectable and will render on the
// front-end of the site correctly.
