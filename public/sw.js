self.addEventListener('push', (event) => {
    let payload = { title: 'Block L Portal', body: 'You have a new notification.' };
    try {
        if (event.data) {
            payload = { ...payload, ...event.data.json() };
        }
    } catch {
        payload.body = event.data?.text() ?? payload.body;
    }

    event.waitUntil(
        self.registration.showNotification(payload.title, {
            body: payload.body,
            icon: '/images/block-l-icon.svg',
            badge: '/images/block-l-badge.svg',
            data: payload.data ?? {},
        }),
    );
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    event.waitUntil(clients.openWindow('/notifications'));
});