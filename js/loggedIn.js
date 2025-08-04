document.querySelectorAll('a[href="#"]').forEach(function (link) {
    // Add event listener to each link
    link.addEventListener('click', function (event) {// Prevent default behavior of anchor tag
      event.preventDefault();
      // Show popup dialog
      Swal.fire({
        title: "<strong>Food Hub | Centro Cuyapo</strong>",
        icon: "info",
        html: `
  You are not yet Logged In. Please log in 
  and Enjoy!
`,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: `
        <a href="login/index.php" class="text-white"><i class="fa fa-arrow-right"></i> Sign In!</a>
`,
        confirmButtonAriaLabel: "Arrow-left, Sign In!",
        cancelButtonText: `
  <i class="">Later</i>
`,
        cancelButtonAriaLabel: "Thumbs down"
      });
    });
  });