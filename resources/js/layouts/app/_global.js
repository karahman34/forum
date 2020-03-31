window.validateForm = (errFields, fields) => {
  Object.keys(fields).map(field => {
    if (errFields.hasOwnProperty(field)) {
      fields[field] = errFields[field][0];
    } else {
      fields[field] = null;
    }
  });
};

window.addEventListener('load', () => {
  function toggleBurger() {
    let navBurgerActive = false;
    const navBurger = document.querySelector('.navbar-burger.burger');
    const navBarMenu = document.querySelector('.navbar-menu');
    navBurger.addEventListener('click', () => {
      if (!navBurgerActive) {
        navBurgerActive = true;

        navBurger.classList.add('is-active');
        navBarMenu.classList.add('is-active');
      } else {
        navBurgerActive = false;

        navBurger.classList.remove('is-active');
        navBarMenu.classList.remove('is-active');
      }
    });
  }

  toggleBurger();
});
