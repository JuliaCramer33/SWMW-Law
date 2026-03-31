import { addFilter } from '@wordpress/hooks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment } from '@wordpress/element';

const ALLOWED_BLOCKS = ['core/column'];

/**
 * Adds a custom attribute to the allowed blocks.
 */
const addRecontainedAttribute = (settings, name) => {
  if (ALLOWED_BLOCKS.includes(name)) {
    settings.attributes = {
      ...settings.attributes,
      isRecontained: {
        type: 'boolean',
        default: false,
      },
    };
  }
  return settings;
};

/**
 * Adds a toggle control to the block inspector.
 */
const withRecontainedControl = createHigherOrderComponent((BlockEdit) => {
  return (props) => {
    const { name, attributes, setAttributes } = props;
    const { isRecontained } = attributes;

    if (!ALLOWED_BLOCKS.includes(name)) {
      return <BlockEdit {...props} />;
    }

    return (
      <Fragment>
        <BlockEdit {...props} />
        <InspectorControls>
          <PanelBody title="Layout" initialOpen={true}>
            <ToggleControl
              label="Re-contain this column"
              help="Adds standard padding to this column, for use inside a full-width container."
              checked={!!isRecontained}
              onChange={(value) =>
                setAttributes({ isRecontained: value })
              }
            />
          </PanelBody>
        </InspectorControls>
      </Fragment>
    );
  };
}, 'withRecontainedControl');

/**
 * Adds the 'is-recontained' class to the block's wrapper props.
 */
const applyRecontainedClass = (extraProps, blockType, attributes) => {
  const { isRecontained } = attributes;

  if (ALLOWED_BLOCKS.includes(blockType.name) && isRecontained) {
    extraProps.className = [extraProps.className, 'is-recontained']
      .filter(Boolean)
      .join(' ');
  }

  return extraProps;
};

addFilter(
  'blocks.registerBlockType',
  'swmw-law/add-recontained-attribute',
  addRecontainedAttribute
);

addFilter(
  'editor.BlockEdit',
  'swmw-law/with-recontained-control',
  withRecontainedControl
);

addFilter(
  'blocks.getSaveContent.extraProps',
  'swmw-law/apply-recontained-class',
  applyRecontainedClass
); 
