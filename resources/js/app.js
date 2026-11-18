import './bootstrap';
import './push-notifications';
import './notification-poll';

document.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        document.getElementById('mobile-sidebar')?.classList.toggle('hidden');
    });
});

document.querySelectorAll('[data-enable-push]').forEach((btn) => {
    btn.addEventListener('click', async () => {
        if (!window.BlockLPush) return;
        const ok = await window.BlockLPush.requestPermissionAndSubscribe();
        btn.classList.toggle('hidden', ok);
        document.querySelector('[data-push-enabled]')?.classList.toggle('hidden', !ok);
    });
});