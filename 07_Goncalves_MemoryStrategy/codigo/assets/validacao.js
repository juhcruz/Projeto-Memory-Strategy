// codigo/assets/js/validacao.js
document.addEventListener('DOMContentLoaded', function() {
    const formLogin = document.getElementById('formLogin');
    const formRegisto = document.getElementById('formRegisto');

    if (formLogin) {
        formLogin.addEventListener('submit', function(event) {
            const email = document.getElementById('login_email').value.trim();
            const pass = document.getElementById('login_password').value;

            if (email === '' || pass === '') {
                alert('Por favor, preencha todos os campos do login.');
                event.preventDefault(); // Requisito: preventDefault()
            }
        });
    }

    if (formRegisto) {
        formRegisto.addEventListener('submit', function(event) {
            const email = document.getElementById('reg_email').value.trim();
            const pass = document.getElementById('reg_password').value;
            const termos = document.getElementById('termos').checked;

            if (email === '' || pass === '') {
                alert('Por favor, preencha todos os campos do registo.');
                event.preventDefault();
                return;
            }

            if (!termos) {
                alert('Deve aceitar os termos de privacidade para continuar[cite: 5].');
                event.preventDefault();
            }
        });
    }
});