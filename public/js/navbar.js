function handleLogout(logoutUrl){
    fetch(logoutUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (response.ok) {
            window.location.href = "{{ route('login') }}";
            return response.json(); // Parse JSON response
        } else {
            throw new Error('Failed to logout.');
        }
    })
    .then(data => {
        // Check if the response contains a success message
        if (data.success) {
            console.log("Logged out successfully");
        } else {
            throw new Error('Failed to logout.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}