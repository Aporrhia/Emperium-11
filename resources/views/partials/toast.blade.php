<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastContainer = document.getElementById('toast-container');

        function showToast(message, type) {
            // Create toast element
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;

            // Add progress bar
            const progressBar = document.createElement('div');
            progressBar.className = 'progress-bar';
            toast.appendChild(progressBar);

            // Append toast to container
            toastContainer.appendChild(toast);

            // Show toast with animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto-remove toast after 4 seconds
            let timer = setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300); // Wait for animation to finish
            }, 4000);

            // Pause timer on hover
            toast.addEventListener('mouseenter', () => {
                clearTimeout(timer);
                progressBar.style.animationPlayState = 'paused';
            });

            // Resume timer on mouse leave
            toast.addEventListener('mouseleave', () => {
                const remainingTime = 4000 * (parseFloat(getComputedStyle(progressBar).width) / toast.offsetWidth);
                progressBar.style.animation = `progress ${remainingTime}ms linear forwards`;
                timer = setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, remainingTime);
            });
        }

        @if (session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif

        @if (session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
    });
</script>