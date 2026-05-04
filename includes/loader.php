<!-- includes/loader.php -->
<style>
    /* Instant CSS to prevent white flash */
    #page-loader {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white; /* Default Day */
        transition: opacity 0.5s ease;
    }

    /* Dark mode check immediately */
    .dark #page-loader { background-color: #0d1117; }

    .loader-container { display: flex; gap: 1rem; }

    .dot {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 9999px;
        animation: lottie-jump 1.2s ease-in-out infinite;
    }

    @keyframes lottie-jump {
        0%, 100% { transform: translateY(0); opacity: 0.5; }
        50% { transform: translateY(-20px); opacity: 1; }
    }

    /* Standard Lottie Colors */
    .dot-1 { background-color: #1111b1; animation-delay: 0ms; }
    .dot-2 { background-color: #00b080; animation-delay: 150ms; }
    .dot-3 { background-color: #56c1e9; animation-delay: 300ms; }
    .dot-4 { background-color: #ffb845; animation-delay: 450ms; }

    /* Stop body from scrolling while loading */
    body.loading-active { overflow: hidden; }
</style>

<div id="page-loader">
    <div class="loader-container">
        <div class="dot dot-1"></div>
        <div class="dot dot-2"></div>
        <div class="dot dot-3"></div>
        <div class="dot dot-4"></div>
    </div>
</div>

<script>
    // Prevent scrolling while loader is visible
    document.body.classList.add('loading-active');

    // When EVERYTHING (images, CDN, etc) is finished
    window.addEventListener('load', function() {
        const loader = document.getElementById('page-loader');
        loader.style.opacity = '0';
        
        setTimeout(() => {
            loader.style.display = 'none';
            document.body.classList.remove('loading-active');
        }, 500);
    });
</script>
