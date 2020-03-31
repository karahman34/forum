// Use bulma toast
bulmaToast = require('bulma-toast').toast;
window.toast = options => {
  const defaultOptions = {
    closeOnClick: false,
    pauseOnHover: true,
    dismissible: true,
    position: 'bottom-left',
    duration: '3000',
  };

  const finalOptions = { ...defaultOptions, ...options };

  bulmaToast(finalOptions);
};
