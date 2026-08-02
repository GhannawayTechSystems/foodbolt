// Sticky header shadow + small UX touches. No framework, vanilla JS.
(function () {
  var header = document.querySelector('.site-header');
  if (header) {
    var onScroll = function () {
      header.classList.toggle('scrolled', window.scrollY > 4);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // Auto-dismiss flash messages after 5s
  document.querySelectorAll('.flash-msg').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 400);
    }, 5000);
  });

  // Quantity steppers on cart page
  document.querySelectorAll('[data-qty]').forEach(function (form) {
    var input = form.querySelector('input[name="qty"]');
    form.querySelector('[data-qty-up]').addEventListener('click', function (e) {
      e.preventDefault();
      input.value = Math.max(0, parseInt(input.value || '0', 10) + 1);
      form.submit();
    });
    form.querySelector('[data-qty-down]').addEventListener('click', function (e) {
      e.preventDefault();
      input.value = Math.max(0, parseInt(input.value || '0', 10) - 1);
      form.submit();
    });
  });
})();
