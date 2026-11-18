const badge = document.querySelector('[data-notification-count]');

async function refreshUnreadCount() {
    if (!badge) return;
    try {
        const response = await fetch('/notifications/unread-count', {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        const data = await response.json();
        const count = data.count ?? 0;
        badge.textContent = count > 99 ? '99+' : String(count);
        badge.classList.toggle('hidden', count === 0);
    } catch {
        // ignore transient errors
    }
}

setInterval(refreshUnreadCount, 60000);
refreshUnreadCount();