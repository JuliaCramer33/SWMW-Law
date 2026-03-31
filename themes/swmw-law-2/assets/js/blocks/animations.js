/**
 * Block Animations Module
 * Handles scroll-triggered animations for Gutenberg blocks
 */

export function initBlockAnimations() {
  // Check if we're in the admin
  if (document.body.classList.contains('wp-admin')) {
    return;
  }

  // Check if Intersection Observer is supported
  if (!('IntersectionObserver' in window)) {
    // Fallback: animate all blocks immediately
    const blocks = document.querySelectorAll('[data-animate]');
    if (blocks.length > 0) {
      blocks.forEach(block => {
        block.classList.add('animated');
      });
    }
    return;
  }

  // Create intersection observer with error handling
  let observer;
  try {
    observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animated');
          // Once animated, stop observing
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1, // Trigger when 10% of the element is visible
      rootMargin: '0px 0px -50px 0px' // Start animation slightly before element is fully in view
    });
  } catch (error) {
    console.warn('SWMW: Could not create IntersectionObserver:', error);
    return;
  }

  // Observe all blocks with data-animate attribute
  const animatedBlocksToObserve = document.querySelectorAll('[data-animate]');

  if (animatedBlocksToObserve.length > 0) {
    animatedBlocksToObserve.forEach(block => {
      try {
        observer.observe(block);
      } catch (error) {
        console.warn('SWMW: Could not observe block:', error);
      }
    });
  }

  // Optional: Re-initialize animations when content is loaded via AJAX
  // This is useful for "Load More" functionality
  document.addEventListener('swmw:contentLoaded', () => {
    const newBlocks = document.querySelectorAll('[data-animate]:not(.animated)');
    newBlocks.forEach(block => {
      try {
        observer.observe(block);
      } catch (error) {
        console.warn('SWMW: Could not observe new block:', error);
      }
    });
  });

  // Optional: Add a method to manually trigger animations
  window.swmwTriggerAnimations = () => {
    const blocks = document.querySelectorAll('[data-animate]:not(.animated)');
    blocks.forEach(block => {
      block.classList.add('animated');
    });
  };
}

// Optional: Add a toggle for animations
export function toggleAnimations() {
  const body = document.body;
  const isAnimationsDisabled = body.classList.contains('no-animations');

  if (isAnimationsDisabled) {
    body.classList.remove('no-animations');
    // Re-initialize animations
    initBlockAnimations();
  } else {
    body.classList.add('no-animations');
  }
} 
