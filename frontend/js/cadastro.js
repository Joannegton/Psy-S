document.getElementById('cadastroForm').addEventListener('submit', async function(event) {
    event.preventDefault();

    const nome = event.target.nome.value;
    const email = event.target.email.value;
    const senha = event.target.senha.value;

    try {
        const response = await fetch('http://localhost:8000/api/v1/users/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nome, email, senha })
        });

        if (!response.ok) {
            throw new Error('Erro ao cadastrar');
        }

        const data = await response.json();
        alert('Cadastro realizado com sucesso!');
        window.location.href = 'login.html';
    } catch (error) {
        alert('Erro: ' + error.message);
    }
});