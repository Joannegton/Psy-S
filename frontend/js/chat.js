document.getElementById('DOMContentLoaded', () => {
     // Simulação de envio de mensagem
     document.getElementById('send-btn').addEventListener('click', function () {
        let input = document.getElementById('message-input');
        let message = input.value.trim();

        if (message !== "") {
            let messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'user-message');
            messageDiv.innerHTML = `<p><strong>Você:</strong> ${message}</p>`;
            document.querySelector('.messages').appendChild(messageDiv);
            input.value = ""; // Limpar campo de input
            input.focus();
        }
    });

    try {
        const response = await ('http://localhost:8080/api/users/list', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })

        const usuarios = response.json()

        document.getElementById('teste').innerHTML = usuarios 
    } catch (error) {
        alert(error)
    }
})