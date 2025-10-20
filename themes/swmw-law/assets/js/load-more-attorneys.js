/**
 * Load More Attorneys (standalone module)
 * Uses page-based pagination to match server handler.
 */

export function initLoadMoreAttorneys() {
  const loadMoreButton = document.getElementById('load-more-attorneys');
  if (!loadMoreButton) return;

  let currentPage = 1; // initial page already rendered

  loadMoreButton.addEventListener('click', function () {
    currentPage += 1;
    const button = this;
    button.textContent = 'Loading...';
    button.disabled = true;

    const grid = document.querySelector('.attorney-grid');
    const offset = grid ? grid.querySelectorAll('.attorney-card-item').length : 0;
    // Debug: request details
    try {
      console.log('[LM] requesting', { page: currentPage, offset });
    } catch (e) { }

    const form = new FormData();
    form.append('action', 'load_more_attorneys');
    form.append('page', String(currentPage));
    form.append('offset', String(offset));
    // Also send an exclude list of current IDs for stability
    const ids = Array.from(document.querySelectorAll('.attorney-card-item')).map((n) => n.id.replace('post-', '')).filter(Boolean);
    form.append('exclude', ids.join(','));
    form.append('nonce', (window.swmwLawData && window.swmwLawData.load_more_attorneys_nonce) || '');

    fetch(window.swmwLawData && window.swmwLawData.ajaxUrl ? window.swmwLawData.ajaxUrl : '/wp-admin/admin-ajax.php', {
      method: 'POST',
      credentials: 'same-origin',
      body: form,
    })
      .then((r) => r.json())
      .then((response) => {
        if (response && response.success) {
          const grid = document.querySelector('.attorney-grid');
          if (grid && response.data && response.data.html) {
            // Append only new items, preserve server order
            const temp = document.createElement('div');
            temp.innerHTML = response.data.html;
            const incoming = Array.from(temp.querySelectorAll('.attorney-card-item'));
            // Debug incoming IDs
            try {
              console.log('[LM] incoming ids', incoming.map((n) => n.id));
            } catch (e) { }
            let appended = 0;
            incoming.forEach((el) => {
              const id = el.id || '';
              if (id && document.getElementById(id)) {
                return; // skip duplicates that are already in DOM
              }
              grid.appendChild(el);
              appended += 1;
            });
            // After append, normalize order across the whole grid: members first, then oldest start date
            const all = Array.from(grid.querySelectorAll('.attorney-card-item'));
            all.sort((a, b) => {
              const am = parseInt(a.getAttribute('data-is-member') || '1', 10);
              const bm = parseInt(b.getAttribute('data-is-member') || '1', 10);
              if (am !== bm) return am - bm;
              const ad = parseInt((a.getAttribute('data-start-date') || '99999999').replace(/[^0-9]/g, ''), 10);
              const bd = parseInt((b.getAttribute('data-start-date') || '99999999').replace(/[^0-9]/g, ''), 10);
              if (ad !== bd) return ad - bd;
              const at = (a.querySelector('.attorney-card-title a')?.textContent || '').trim();
              const bt = (b.querySelector('.attorney-card-title a')?.textContent || '').trim();
              if (at !== bt) return at.localeCompare(bt);
              const aid = a.id || '';
              const bid = b.id || '';
              return aid.localeCompare(bid);
            });
            // Re-render in sorted order
            const frag = document.createDocumentFragment();
            all.forEach((el) => frag.appendChild(el));
            grid.innerHTML = '';
            grid.appendChild(frag);
            // Debug: response meta and counts
            try {
              const count = grid.querySelectorAll('.attorney-card-item').length;
              console.log('[LM] success', {
                current_page: response.data.current_page,
                max_pages: response.data.max_pages,
                has_more: response.data.has_more,
                total_rendered: count,
              });
            } catch (e) { }
            // Disable immediately if server says no more, otherwise keep enabled
            const noMore = (response.data && response.data.has_more === false) ||
              (response.data && response.data.max_pages && response.data.current_page >= response.data.max_pages);
            if (noMore) {
              button.textContent = 'No More Attorneys';
              button.disabled = true;
            } else {
              button.textContent = 'Load More Attorneys';
              button.disabled = false;
            }
          } else {
            try { console.log('[LM] empty html'); } catch (e) { }
            button.textContent = 'No More Attorneys';
            button.disabled = true;
          }
        } else {
          try { console.warn('[LM] error response', response); } catch (e) { }
          button.textContent = 'Error - Try Again';
          button.disabled = false;
        }
      })
      .catch(() => {
        try { console.error('[LM] fetch error'); } catch (e) { }
        button.textContent = 'AJAX Error - Try Again';
        button.disabled = false;
      });
  });
}


