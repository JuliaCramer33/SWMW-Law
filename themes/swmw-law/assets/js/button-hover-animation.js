function initializeButtonHoverAnimation() {
  const buttons = document.querySelectorAll(
    '.wp-block-button:not(.is-style-outline) .wp-block-button__link, ' +
    '.wp-block-button.is-style-outline .wp-block-button__link'
  );

  buttons.forEach((button) => {
    let originalInlineTextColor = button.style.color; // Store only current inline style for restoration
    const computedInitialTextColor = window.getComputedStyle(button).color;

    const computedButtonStyle = window.getComputedStyle(button);
    const initialButtonBackgroundColor =
      computedButtonStyle.backgroundColor;

    // Set initial border color based on button type
    if (button.closest('.wp-block-button.is-style-outline')) {
      button.style.borderColor = computedInitialTextColor; // Outline border matches its text color
    } else {
      button.style.borderColor = initialButtonBackgroundColor; // Fill button border matches its background
    }
  });
}

// Check if the script is being run in a browser environment before trying to add the event listener.
// This also makes the function testable in non-browser environments if needed.
if (typeof window !== 'undefined' && typeof document !== 'undefined') {
  document.addEventListener(
    'DOMContentLoaded',
    initializeButtonHoverAnimation
  );
}

export default initializeButtonHoverAnimation;
