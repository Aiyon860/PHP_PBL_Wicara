feather.replace();

// Navbar
const navbarPengaduanKehilangan = document.querySelectorAll(".navbar-pengaduan, .navbar-kehilangan");
const chevronNavbar = document.querySelectorAll(".chevron-down-icon");
for (let i = 0; i < navbarPengaduanKehilangan.length; ++i) {
  navbarPengaduanKehilangan[i].addEventListener("mouseenter", (event) => {
    chevronNavbar[i].innerHTML = feather.icons["chevron-up"].toSvg();
  })
  navbarPengaduanKehilangan[i].addEventListener("mouseleave", (event) => {
    chevronNavbar[i].innerHTML = feather.icons["chevron-down"].toSvg();
  })
}

/* Home Page */
// 'Wicara and its feature' Carousel
$(document).ready(function() {
  $(".carousel-wicara-wrapper").slick({
    initialSlide: 1,
    autoplay: true,
    accessibility: true,
    draggable: true,
    infinite: true,
    mobileFirst: true,
    swipeToSlide: true,
    arrows: false,
  });

  $(".cards-wrapper").slick({
    initialSlide: 1,
    slidesToShow: 3,
    autoplay: true,
    accessibility: true,
    draggable: true,
    infinite: true,
    mobileFirst: true,
    swipeToSlide: true,
    dots: true,
    responsive: [
      {
        breakpoint: 319,
        settings: {
          slidesToShow: 1,
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 2,
        }
      },
      {
        breakpoint: 1439,
        settings: {
          slidesToShow: 3,
        }
      },
      
    ]
  })
});

// Copyright
const copyrightYear = document.getElementById("copyright-year");
copyrightYear.innerText = new Date().getFullYear();