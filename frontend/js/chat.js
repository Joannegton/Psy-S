document.addEventListener('DOMContentLoaded', function () {

    getMessages()

    document.getElementById('send-btn').addEventListener('click', sendMessage)

});

async function sendMessage() {
    let input = document.getElementById('message-input');
    let message = input.value.trim();

    if (message !== "") {

        try {
            const user_id = 1;
            const psy_id = 'psy172681618233837';

            const response = await fetch('http://localhost:8000/api/v1/interacoes/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    id_usuario: user_id,
                    id_terapeuta: psy_id,
                    mensagem: message
                })
            });

            if(!response.ok){
                throw new Error('Erro ao enviar mensagem');
            }

            // Adicionar mensagem do usuário na tela
            let messageDiv = document.createElement('div');
            messageDiv.classList.add('message', 'user-message');
            messageDiv.innerHTML = `<p>${message}</p>`;
            document.querySelector('.messages').appendChild(messageDiv);
            input.value = ""; // Limpar campo de input
            input.focus();

            const data = await response.json();
            const resposta = data.resposta;

            // Adicionar mensagem do terapeuta na tela
            let messageDivPsy = document.createElement('div');
            messageDivPsy.classList.add('message', 'bot-message')
            messageDivPsy.innerHTML = `<p>${resposta}</p>`
            document.querySelector('.messages').appendChild(messageDivPsy);

        } catch (error) {
            console.error(error);
            alert('Erro inesperado, tente novamente mais tarde!')
        }
    }
}

async function getMessages() {
    const user_id = 1;
    const psy_id = 'psy172681618233837';

    try {
        const response = await fetch(`http://localhost:8000/api/v1/interacoes/list?id_usuario=${user_id}&id_terapeuta=${psy_id}`)

        if(!response.ok){
            throw new Error('Erro ao carregar mensagens.')
        }

        const data = await response.json();
        
        console.log(data)

        data.forEach(element => {
            if(element.tipo === 'Terapeuta'){
                // Adicionar mensagem do terapeuta na tela
                let messageDivPsy = document.createElement('div');
                messageDivPsy.classList.add('message', 'bot-message')
                messageDivPsy.innerHTML = `<p>${element.mensagem}</p>`
                document.querySelector('.messages').appendChild(messageDivPsy);
            } else{
                // Adicionar mensagem do usuário na tela
                let messageDiv = document.createElement('div');
                messageDiv.classList.add('message', 'user-message');
                messageDiv.innerHTML = `<p>${element.mensagem}</p>`;
                document.querySelector('.messages').appendChild(messageDiv);
            }
        });            

    } catch (error) {
        console.error(error)
        alert(error)
    }

}