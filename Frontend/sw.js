// referenced from https://github.com/MarkJamesHoward/push
self.addEventListener('push', () => {
    self.registration.showNotification('Hello world!', options);
  });
  