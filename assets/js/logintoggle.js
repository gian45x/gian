
const loginBtn = document.getElementById('loginBtn');
        const loginContainer = document.getElementById('loginContainer');

        function toggleLoginContainer() {
            loginContainer.classList.toggle('active');
        }

        loginBtn.addEventListener('click', toggleLoginContainer);
        loginContainer.addEventListener('click', (e) => {
            if (e.target.id === 'loginContainer') {
                toggleLoginContainer();
            }
        });