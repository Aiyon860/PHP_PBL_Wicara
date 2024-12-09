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