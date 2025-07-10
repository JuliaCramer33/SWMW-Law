/**
 * Block Animations Module
 * Handles scroll-triggered animations for Gutenberg blocks
 */

export function initBlockAnimations() {
  console.log('SWMW: Initializing block animations...');

  // Check if we're in the admin
  if (document.body.classList.contains('wp-admin')) {
    console.log('SWMW: In admin, skipping animations');
    return;
  }

  // Test: Check if any blocks have the data attribute
  const allBlocks = document.querySelectorAll('.wp-block');
  console.log('SWMW: Total blocks found:', allBlocks.length);

  const animatedBlocks = document.querySelectorAll('[data-animate]');
  console.log('SWMW: Blocks with data-animate attribute:', animatedBlocks.length);

  // Log the first few blocks to see their structure
  allBlocks.forEach((block, index) => {
    if (index < 5) {
      console.log('SWMW: Block', index, 'classes:', block.className, 'data-animate:', block.getAttribute('data-animate'));
    }
  });

  // Check if Intersection Observer is supported
  if (!('IntersectionObserver' in window)) {
    console.log('SWMW: IntersectionObserver not supported, using fallback');
    // Fallback: animate all blocks immediately
    const blocks = document.querySelectorAll('[data-animate]');
    console.log('SWMW: Found', blocks.length, 'blocks to animate');
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
          console.log('SWMW: Animating block:', entry.target, 'with animation:', entry.target.getAttribute('data-animate'));
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
  console.log('SWMW: Found', animatedBlocksToObserve.length, 'blocks with data-animate attribute');

  if (animatedBlocksToObserve.length > 0) {
    animatedBlocksToObserve.forEach(block => {
      try {
        observer.observe(block);
        console.log('SWMW: Observing block:', block, 'with animation:', block.getAttribute('data-animate'));
      } catch (error) {
        console.warn('SWMW: Could not observe block:', error);
      }
    });
  } else {
    console.log('SWMW: No blocks found with data-animate attribute');
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
