import { registerBlockExtension } from '@10up/block-components';
import ColumnsEdit from './edit';
import './save';

const additionalAttributes = {
  isStackedOnCustom: {
    type: 'boolean',
    default: false,
  },
  customBreakpoint: {
    type: 'string',
    default: '992',
  },
};

function generateClassName(attributes) {
  const { isStackedOnCustom } = attributes;
  let className = '';
  if (isStackedOnCustom) {
    className = `is-stacked-on-custom-${attributes.customBreakpoint}`;
  }

  return className;
}

registerBlockExtension('core/columns', {
  extensionName: 'break-on-custom-breakpoint',
  attributes: additionalAttributes,
  classNameGenerator: generateClassName,
  Edit: ColumnsEdit,
});
