feather.replace();

/* Home Page */
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

const navbarPengaduanKehilanganMobile = document.querySelectorAll(".navbar-pengaduan-phonetablet");
const contentPengaduanKehilanganMobile = document.querySelectorAll(".navbar-dropdown-content-phonetablet");
const chevronNavbarMobile = document.querySelectorAll(".chevron-down-icon-mobile");
const menuNavbarMobileWrapper = document.querySelectorAll(".phonetablet-inner-wrapper");
const pengaduanKehilanganText = document.querySelectorAll(".pengaduan-kehilangan");
for (let i = 0; i < contentPengaduanKehilanganMobile.length; ++i) {
  navbarPengaduanKehilanganMobile[i].addEventListener("click", () => {
    contentPengaduanKehilanganMobile[i].classList.toggle("hidden");
    if (navbarPengaduanKehilanganMobile[i].classList.contains("text-white")) {
      navbarPengaduanKehilanganMobile[i].classList.remove("text-white");
      navbarPengaduanKehilanganMobile[i].classList.add("text-[#858585]");
      menuNavbarMobileWrapper[i].classList.remove("bg-[#266bda]");
      pengaduanKehilanganText[i].classList.remove("text-white");
      pengaduanKehilanganText[i].classList.add("text-[#858585]");
      chevronNavbarMobile[i].innerHTML = feather.icons["chevron-down"].toSvg();
    } else {
      navbarPengaduanKehilanganMobile[i].classList.add("text-white");
      navbarPengaduanKehilanganMobile[i].classList.remove("text-[#858585]");
      menuNavbarMobileWrapper[i].classList.add("bg-[#266bda]");
      pengaduanKehilanganText[i].classList.add("text-white");
      pengaduanKehilanganText[i].classList.remove("text-[#858585]");
      chevronNavbarMobile[i].innerHTML = feather.icons["chevron-up"].toSvg();
    }
  });
}
  
const menuNavbarIcon = document.querySelector(".menu-icon");
const navbarPengaduanKehilanganMobileWrapper = document.querySelector(".navbar-pengaduan-phonetablet-wrapper");
menuNavbarIcon.addEventListener("click", () => {
  if (navbarPengaduanKehilanganMobileWrapper.classList.contains("hidden")) {
    navbarPengaduanKehilanganMobileWrapper.classList.add("fixed");
    navbarPengaduanKehilanganMobileWrapper.classList.remove("hidden");
  } else {
    navbarPengaduanKehilanganMobileWrapper.classList.add("hidden");
    navbarPengaduanKehilanganMobileWrapper.classList.remove("fixed");
  }
});

const bellIcon = document.querySelector(".bell-icon");
const notificationFloat = document.querySelector("#notification-float");

document.addEventListener("click", (event) => {
  if (!bellIcon.contains(event.target) && !notificationFloat.contains(event.target)) {
    notificationFloat.style.opacity = '0';
    notificationFloat.style.transform = "translateY(-2.75rem)";
    notificationFloat.style.pointerEvents = "none";
  }
});

bellIcon.addEventListener("click", () => {
  if (notificationFloat.style.opacity === '0') {
    notificationFloat.style.opacity = '1';
    notificationFloat.style.transform = "translateY(0)";
    notificationFloat.style.transition = "all 0.4s ease-in-out";
    notificationFloat.style.pointerEvents = "all";
  } else {
    notificationFloat.style.opacity = '0';
    notificationFloat.style.transform = "translateY(-2.75rem)";
    notificationFloat.style.pointerEvents = "none";
  }
});

// Carousel Detail Unit Layanan


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
        breakpoint: 1350,
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

// Back button
const backButton = document.getElementById("back-default-display");
backButton.addEventListener("click", () => {
  window.history.back();
})