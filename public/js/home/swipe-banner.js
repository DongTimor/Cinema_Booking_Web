const swiper = new Swiper('.swiper-container', {
    loop: true, // Enable looping
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    speed: 700,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    slidesPerView: 'auto',
    centeredSlides: true,
    spaceBetween: 10,
});