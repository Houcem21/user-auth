document.addEventListener('DOMContentLoaded', () => {
    const signupForm = document.getElementById('signupForm');
    const loginForm = document.getElementById('loginForm');
    const getProfileBtn = document.getElementById('getProfileBtn');
    const responseDiv = document.getElementById('response');
    const protectedContentDiv = document.getElementById('protected-content');

    // Handle Signup
    signupForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('signupUsername').value;
        const password = document.getElementById('signupPassword').value;

        const res = await fetch('signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const data = await res.json();
        responseDiv.textContent = JSON.stringify(data, null, 2);
    });

    // Handle Login
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('loginUsername').value;
        const password = document.getElementById('loginPassword').value;

        const res = await fetch('login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const data = await res.json();
        responseDiv.textContent = JSON.stringify(data, null, 2);

        if (data.token) {
            localStorage.setItem('authToken', data.token);
            protectedContentDiv.style.display = 'block';
        }
    });

    // Handle getting protected profile data
    getProfileBtn.addEventListener('click', async () => {
        const token = localStorage.getItem('authToken');

        if (!token) {
            responseDiv.textContent = 'You must be logged in to see your profile.';
            return;
        }

        const res = await fetch('profile.php', {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const data = await res.json();
        responseDiv.textContent = JSON.stringify(data, null, 2);
    });
});