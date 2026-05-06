<div>
    <h2 class="text-2xl font-bold mb-4 text-center">Login</h2>

    <form id="login-form" class="space-y-4">
        <input 
            type="email" 
            name="email" 
            placeholder="Email" 
            required
            class="w-full border p-3 rounded-lg"
        >

        <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            required
            class="w-full border p-3 rounded-lg"
        >

        <button 
            type="submit"
            class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700"
        >
            Login
        </button>

        <p id="login-error" class="text-red-500 text-sm text-center"></p>
    </form>
</div>

<script>
document.getElementById('login-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const errorBox = document.getElementById('login-error');

    errorBox.innerText = "Logging in...";

    const formData = new FormData(form);

    try {
        const res = await fetch('../../auth/login-process.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        if (data.success) {
            errorBox.classList.remove('text-red-500');
            errorBox.classList.add('text-green-600');
            errorBox.innerText = "Login successful!";

            setTimeout(() => {
                location.reload(); // or update UI dynamically
            }, 1000);

        } else {
            errorBox.innerText = data.message;
        }

    } catch (err) {
        errorBox.innerText = "Server error. Try again.";
    }
});
</script>