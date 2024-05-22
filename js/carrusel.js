var slideIndex = 0;
var slides = document.getElementsByClassName("mySlides");
var myTimer;

function showSlides() {
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slideIndex++;
  if (slideIndex > slides.length) {
    slideIndex = 1;
  }
  slides[slideIndex - 1].style.display = "block";
  myTimer = setTimeout(showSlides, 6000);
}

function plusSlides(n) {
  clearTimeout(myTimer);
  slideIndex += n;
  if (slideIndex > slides.length) {
    slideIndex = 1;
  } else if (slideIndex < 1) {
    slideIndex = slides.length;
  }
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex - 1].style.display = "block";
  myTimer = setTimeout(showSlides, 6000);
}

function currentSlide(n) {
  clearTimeout(myTimer);
  slideIndex = n;
  if (slideIndex > slides.length) {
    slideIndex = 1;
  } else if (slideIndex < 1) {
    slideIndex = slides.length;
  }
  for (var i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  slides[slideIndex - 1].style.display = "block";
  myTimer = setTimeout(showSlides, 6000);
}

myTimer = setTimeout(showSlides, 6000);

window.addEventListener("load", function () {
  showSlides();
});
