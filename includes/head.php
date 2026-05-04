<!-- includes/head.php -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Google Fonts -->
<link rel="preconnect" href="https://googleapis.com">
<link rel="preconnect" href="https://gstatic.com" crossorigin>
<link href="https://googleapis.com/css2?family=Italiana&family=Noto+Serif:ital,wght@0,100..900;1,100..900&family=Yatra+One&display=swap" rel="stylesheet">

<!-- Tailwind & Config -->
<style>
  /* This hides the whole page until Tailwind has processed the classes */
  [x-cloak] { display: none !important; }
  body { opacity: 0; transition: opacity 0.2s ease-in; }
  .tailwind-ready body { opacity: 1; }
</style>

<script>
  // Show the body immediately once the script is parsed
  document.addEventListener("DOMContentLoaded", function() {
    document.body.style.opacity = "1";
  });
</script>
