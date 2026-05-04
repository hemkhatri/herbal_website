document.addEventListener("DOMContentLoaded", function () {

    const images = document.querySelectorAll('.slide-img');
    const dots = document.querySelectorAll('.dot');
    const container = document.getElementById('container');
    const lens = document.getElementById('zoom-lens');
    const result = document.getElementById('zoom-result');

    let currentIndex = 0;

    window.changeSlide = function (dir) {
        goToSlide((currentIndex + dir + images.length) % images.length);
    }

    window.goToSlide = function (index) {
        images[currentIndex].classList.remove('slide-active');
        dots[currentIndex].classList.remove('active');

        currentIndex = index;

        images[currentIndex].classList.add('slide-active');
        dots[currentIndex].classList.add('active');
    }

    window.toggleDescription = function () {
        const content = document.getElementById('desc-content');
        const btn = document.getElementById('toggle-desc');

        content.classList.toggle('expanded');

        btn.innerText = content.classList.contains('expanded')
            ? 'Show Less'
            : 'Show Full Description';
    }

    /* ZOOM LOGIC */
    if (container) {
        container.addEventListener("mousemove", (e) => {
            if (window.innerWidth < 1024) return;

            const activeImg = images[currentIndex];
            const rect = activeImg.getBoundingClientRect();
            const contRect = container.getBoundingClientRect();

            let x = e.clientX - rect.left;
            let y = e.clientY - rect.top;

            if (x < 0 || y < 0 || x > rect.width || y > rect.height) {
                lens.style.display = "none";
                result.style.display = "none";
                return;
            }

            lens.style.display = "block";
            result.style.display = "block";

            let lx = x - (lens.offsetWidth / 2);
            let ly = y - (lens.offsetHeight / 2);

            lx = Math.max(0, Math.min(lx, rect.width - lens.offsetWidth));
            ly = Math.max(0, Math.min(ly, rect.height - lens.offsetHeight));

            lens.style.left = (lx + (rect.left - contRect.left)) + "px";
            lens.style.top = (ly + (rect.top - contRect.top)) + "px";

            const cx = result.offsetWidth / lens.offsetWidth;
            const cy = result.offsetHeight / lens.offsetHeight;

            let resultImg = result.querySelector('img');
            if (!resultImg) {
                resultImg = document.createElement('img');
                resultImg.style.position = 'absolute';
                resultImg.style.objectFit = 'contain';
                result.style.overflow = 'hidden';
                result.appendChild(resultImg);
            }

            result.style.backgroundImage = 'none';
            resultImg.src = activeImg.src;
            resultImg.style.width = `${rect.width * cx}px`;
            resultImg.style.height = `${rect.height * cy}px`;
            resultImg.style.left = `-${lx * cx}px`;
            resultImg.style.top = `-${ly * cy}px`;
        });

        container.addEventListener("mouseleave", () => {
            lens.style.display = "none";
            result.style.display = "none";
        });
    }

    /* Review animation */
    const reviewCard = document.querySelector('.lg\\:col-span-1');

    if (reviewCard) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.h-full').forEach(bar => {
                        const width = bar.style.width;
                        bar.style.width = '0';

                        setTimeout(() => {
                            bar.style.width = width;
                        }, 100);
                    });

                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(reviewCard);
    }

});