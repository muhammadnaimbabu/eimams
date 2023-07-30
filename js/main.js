// Father icon
feather.replace();
const overlay = document.querySelector(".overlay");
const body = document.querySelector("body");
const menuBtn = document.querySelector(".menu-btn");
const menuItems = document.querySelector(".menu-items");
const expandBtn = document.querySelectorAll(".expand-btn");

function toggle() {
  // disable overflow body
  body.classList.toggle("overflow");
  // dark background
  overlay.classList.toggle("overlay--active");
  // add open class
  menuBtn.classList.toggle("open");
  menuItems.classList.toggle("open");
}

menuBtn.addEventListener("click", (e) => {
  e.stopPropagation();
  toggle();
});

window.onkeydown = function (event) {
  const key = event.key; // const {key} = event; in ES6+
  const active = menuItems.classList.contains("open");
  if (key === "Escape" && active) {
    toggle();
  }
};

document.addEventListener("click", (e) => {
  let target = e.target,
    its_menu = target === menuItems || menuItems.contains(target),
    its_hamburger = target === menuBtn,
    menu_is_active = menuItems.classList.contains("open");
  if (!its_menu && !its_hamburger && menu_is_active) {
    toggle();
  }
});

// mobile menu expand
expandBtn.forEach((btn) => {
  btn.addEventListener("click", () => {
    btn.classList.toggle("open");
  });
});

var swiper = new Swiper(".mySwiper", {
  centeredSlides: false,
  spaceBetween: 30,
  grabCursor: true,
  slidesPerGroupSkip: 1,
  loop: true,
  navigation: {
    nextEl: ".scholerNext__button",
    prevEl: ".scholerPrev__button",
  },
  breakpoints: {
    425: {
      slidesPerView: 1,
      spaceBetween: 0,
    },
    640: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 3,
      spaceBetween: 40,
    },
    1024: {
      slidesPerView: 5,
      spaceBetween: 50,
    },
  },
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
});

var employer = new Swiper(".endorsementM", {
  centeredSlides: true,
  grabCursor: true,
  slidesPerView: 3,
  slidesPerGroupSkip: 2,
  loop: true,
  navigation: {
    nextEl: ".scholerNext__button",
    prevEl: ".scholerPrev__button",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    320: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    425: {
      slidesPerView: 1,
      spaceBetween: 20,
    },
    640: {
      slidesPerView: 4,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 4,
      spaceBetween: 40,
    },
    1024: {
      slidesPerView: 5,
      spaceBetween: 20,
    },
  },
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
});

var employer = new Swiper(".top-employers", {
  // centeredSlides: true,
  grabCursor: true,
  slidesPerView: 3,
  slidesPerGroupSkip: 1,
  loop: true,
  navigation: {
    nextEl: ".emp__next__button",
    prevEl: ".emp__prev__button",
  },

  breakpoints: {
    375: {
      slidesPerView: 2,
      spaceBetween: 15,
    },
    425: {
      slidesPerView: 2,
      spaceBetween: 20,
    },
    640: {
      slidesPerView: 4,
      spaceBetween: 20,
    },
    768: {
      slidesPerView: 4,
      spaceBetween: 40,
    },
    1024: {
      slidesPerView: 5,
      spaceBetween: 50,
    },
  },
  // autoplay: {
  //   delay: 2500,
  //   disableOnInteraction: false,
  // },
});

var employer = new Swiper(".video1", {
  centeredSlides: true,
  grabCursor: true,
  slidesPerView: 3,
  slidesPerGroupSkip: 1,
  loop: true,
  // navigation: {
  //   nextEl: ".emp__next__button",
  //   prevEl: ".emp__prev__button",
  // },
  pagination: {
    el: ".max-pagination",
    clickable: true,
  },
  breakpoints: {
    375: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    425: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    640: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    768: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    1024: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
  },
  autoplay: {
    delay: 5000,
    disableOnInteraction: false,
  },
});

var employer = new Swiper(".video2", {
  centeredSlides: true,
  grabCursor: true,
  slidesPerView: 3,
  slidesPerGroupSkip: 1,
  loop: true,
  // navigation: {
  //   nextEl: ".emp__next__button",
  //   prevEl: ".emp__prev__button",
  // },
  pagination: {
    el: ".max-pagination",
    clickable: true,
  },
  breakpoints: {
    375: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    425: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    640: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    768: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
    1024: {
      slidesPerView: 1,
      spaceBetween: 5,
    },
  },
  autoplay: {
    delay: 4000,
    disableOnInteraction: false,
  },
});
