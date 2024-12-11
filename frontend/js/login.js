const userData = localStorage.getItem('userData');
if (userData) {
    window.location.href = 'chat.html';
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('loginForm').addEventListener('submit', loginUser);
});

async function loginUser(event) {
    event.preventDefault(); // Previne o comportamento padrão do formulário

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (email !== "" && password !== "") {
        try {
            const response = await fetch('https://54a0-45-179-106-136.ngrok-free.app/api/v1/users/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, senha: password }),
            });

            const data = await response.json(); // Processa a resposta da API

            if (response.ok) {
                // Exibe mensagem de sucesso e redireciona
                document.getElementById('responseMessage').innerText = 'Login realizado com sucesso!';
                console.log('Dados do usuário:', data);

                localStorage.setItem('userData', JSON.stringify(data));
                window.location.href = 'chat.html';
            } else {
                // Exibe mensagem de erro retornada pela API
                document.getElementById('responseMessage').innerText = data.error || 'Erro ao realizar login.';
            }
        } catch (error) {
            console.error('Erro durante a requisição:', error);
            document.getElementById('responseMessage').innerText = 'Erro ao conectar ao servidor.';
        }
    } else {
        alert('Por favor, preencha todos os campos.');
    }
}