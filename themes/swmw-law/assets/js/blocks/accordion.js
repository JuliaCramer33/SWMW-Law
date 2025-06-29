/**
 * Accordion Block JS
 *
 * @package
 */

export class AccordionBlock {
	constructor() {
		this.init();
		// Use a more robust listener for editor updates
		if (window.acf) {
			window.acf.addAction('render_block_preview', (el) =>
				this.init(el[0])
			);
		}
	}

	init(context = document) {
		const accordions = context.querySelectorAll('.accordion-block');
		accordions.forEach((accordion) => this.setupAccordion(accordion));
	}

	setupAccordion(accordion) {
		const panels = accordion.querySelectorAll('.accordion-panel');

		panels.forEach((panel, index) => {
			const header = panel.querySelector('.accordion-panel-header');
			const content = panel.querySelector('.accordion-panel-content');

			if (
				!header ||
				!content ||
				header.classList.contains('js-accordion-initialized')
			) {
				return; // Exit if parts are missing or already initialized
			}
			header.classList.add('js-accordion-initialized');

			const isOpen = panel.classList.contains('is-open');
			header.setAttribute('aria-expanded', isOpen);
			header.setAttribute(
				'aria-controls',
				`panel-${accordion.id}-${index}`
			);
			content.setAttribute('id', `panel-${accordion.id}-${index}`);

			if (isOpen) {
				content.style.maxHeight = content.scrollHeight + 'px';
			}

			header.addEventListener('click', () => {
				const currentlyOpen = panel.classList.contains('is-open');

				// A simple accordion logic: close others if you want only one open at a time.
				// This example is a simple toggle.
				if (currentlyOpen) {
					panel.classList.remove('is-open');
					header.setAttribute('aria-expanded', 'false');
					content.style.maxHeight = null;
				} else {
					panel.classList.add('is-open');
					header.setAttribute('aria-expanded', 'true');
					content.style.maxHeight = content.scrollHeight + 'px';
				}
			});
		});

		// Recalculate height on window load for open-by-default panels
		window.addEventListener('load', () => {
			const openPanels = accordion.querySelectorAll(
				'.accordion-panel.is-open'
			);
			openPanels.forEach((panel) => {
				const content = panel.querySelector('.accordion-panel-content');
				if (content) {
					content.style.maxHeight = content.scrollHeight + 'px';
				}
			});
		});
	}
}

document.addEventListener('DOMContentLoaded', () => {
	new AccordionBlock();
});
