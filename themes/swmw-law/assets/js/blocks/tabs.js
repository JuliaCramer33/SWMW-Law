document.addEventListener('DOMContentLoaded', function () {
	const allTabsBlocks = document.querySelectorAll('.wp-block-acf-tabs');

	allTabsBlocks.forEach((tabsBlock) => {
		const tabsNav = tabsBlock.querySelector('.tabs-nav');
		const tabContent = tabsBlock.querySelector('.tab-content');

		// In the editor, InnerBlocks wraps panels in another div. On the front-end, they are direct children.
		// This handles both cases by searching for the panel class within the content area.
		const tabPanels = tabContent.querySelectorAll(
			'.wp-block-acf-tab-panel'
		);

		// Clear any server-side rendered nav items (or placeholders)
		tabsNav.innerHTML = '';

		// Build the nav on the client-side
		tabPanels.forEach((panel, index) => {
			const title = panel.dataset.tabTitle || `Tab ${index + 1}`;
			const navItem = document.createElement('div');
			navItem.classList.add('tab-nav-item');
			navItem.textContent = title;
			tabsNav.appendChild(navItem);
		});

		// Now that nav items are created, get them
		const tabNavItems = tabsNav.querySelectorAll('.tab-nav-item');

		// Function to switch tabs
		const switchTab = (activeIndex) => {
			tabNavItems.forEach((item, index) => {
				if (index === activeIndex) {
					item.classList.add('active');
				} else {
					item.classList.remove('active');
				}
			});

			tabPanels.forEach((panel, index) => {
				if (index === activeIndex) {
					panel.classList.add('active');
				} else {
					panel.classList.remove('active');
				}
			});
		};

		// Add click event listeners to the new nav items
		tabNavItems.forEach((item, index) => {
			item.addEventListener('click', () => {
				switchTab(index);
			});
		});

		// Set the first tab as active by default
		if (tabNavItems.length > 0) {
			switchTab(0);
		}
	});
});
