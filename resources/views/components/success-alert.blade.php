@if ($message)
    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <script>
        // Auto-dismiss success alert after 3 seconds
        setTimeout(function () {
            let alert = document.getElementById('success-alert');
            if (alert) {
                let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 3000);
    </script>
@endif
