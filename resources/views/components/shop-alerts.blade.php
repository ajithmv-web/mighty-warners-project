<!-- Alert Container - Fixed at top-right -->
<div id="alert-container" class="fixed top-4 right-4 z-50 space-y-2 max-w-md w-full px-4 sm:px-0 sm:w-96">
    <!-- Server-side Success Alert -->
    @if(session('success'))
        <div class="alert-item bg-green-50 border-l-4 border-green-500 rounded-lg shadow-lg p-4 flex items-start space-x-3 transition-all duration-300 animate-fade-in" role="alert" aria-live="polite" aria-atomic="true">
            <!-- Success Icon -->
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-label="Success">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <!-- Alert Message -->
            <div class="flex-1 text-sm text-green-800 font-medium">
                {{ session('success') }}
            </div>
            <!-- Close Button -->
            <button type="button" class="flex-shrink-0 ml-auto text-green-500 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 rounded transition-colors" onclick="dismissAlert(this.parentElement)" aria-label="Close alert">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Server-side Error Alert -->
    @if(session('error'))
        <div class="alert-item bg-red-50 border-l-4 border-red-500 rounded-lg shadow-lg p-4 flex items-start space-x-3 transition-all duration-300 animate-fade-in" role="alert" aria-live="polite" aria-atomic="true">
            <!-- Error Icon -->
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-label="Error">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <!-- Alert Message -->
            <div class="flex-1 text-sm text-red-800 font-medium">
                {{ session('error') }}
            </div>
            <!-- Close Button -->
            <button type="button" class="flex-shrink-0 ml-auto text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 rounded transition-colors" onclick="dismissAlert(this.parentElement)" aria-label="Close alert">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    @endif
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    .animate-fade-out {
        animation: fadeOut 0.3s ease-out;
    }
</style>

<script>
    function dismissAlert(alertElement) {
        alertElement.classList.remove('animate-fade-in');
        alertElement.classList.add('animate-fade-out');
        
        setTimeout(() => {
            alertElement.remove();
        }, 300);
    }
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-item');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                dismissAlert(alert);
            }, 5000);
        });
    });
    function showAlert(message, type = 'success') {
        const container = document.getElementById('alert-container');
        
        const alertHTML = `
            <div class="alert-item bg-${type === 'success' ? 'green' : 'red'}-50 border-l-4 border-${type === 'success' ? 'green' : 'red'}-500 rounded-lg shadow-lg p-4 flex items-start space-x-3 transition-all duration-300 animate-fade-in" role="alert" aria-live="polite" aria-atomic="true">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-${type === 'success' ? 'green' : 'red'}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        ${type === 'success' 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                        }
                    </svg>
                </div>
                <div class="flex-1 text-sm text-${type === 'success' ? 'green' : 'red'}-800 font-medium">
                    ${message}
                </div>
                <button type="button" class="flex-shrink-0 ml-auto text-${type === 'success' ? 'green' : 'red'}-500 hover:text-${type === 'success' ? 'green' : 'red'}-700 focus:outline-none focus:ring-2 focus:ring-${type === 'success' ? 'green' : 'red'}-500 focus:ring-offset-2 rounded transition-colors" onclick="dismissAlert(this.parentElement)" aria-label="Close alert">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', alertHTML);
        const newAlert = container.lastElementChild;
        setTimeout(() => {
            dismissAlert(newAlert);
        }, 5000);
    }
</script>
