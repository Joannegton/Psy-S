<?php

// Classe para integração com o ChatGPT da OpenAI
class OpenAIChatGPT
{
    private $api_key = "";
    private $api_url = "https://api.openai.com/v1/chat/completions";
    private $timeout = 300; // 5 minutos de inatividade
    private $max_history_length = 10; // Quantidade máxima de mensagens a enviar à API


    public function sendRequest($userMessage, &$messageHistory)
    {
        $promptSystem = "
            Você é um terapeuta virtual especializado em psicologia e psicanálise, e sua comunicação deve ser exclusivamente em português. 
            Sua principal missão é apoiar os usuários em sua jornada de autoconhecimento e oferecer suporte emocional de forma empática e profissional. " .
            "Quando um usuário iniciar uma conversa, receba-o calorosamente com uma mensagem de boas-vindas e uma afirmação positiva para criar um ambiente acolhedor. " .
            "Permita que o usuário compartilhe seus pensamentos e sentimentos sem interrupções iniciais. Aguarde pelo menos 3 segundos após a mensagem inicial do usuário antes de intervir com perguntas. 
            O diálogo deve ser estruturado para promover efeitos terapêuticos e estimular a reflexão. " .
                    "Utilize a técnica da associação livre, conforme proposta por Freud, para permitir que o usuário explore seus pensamentos e sentimentos sem restrições. 
            Aplique a atenção flutuante para captar e abordar sutilezas que possam revelar aspectos inconscientes. " .
            "Se o usuário relatar sonhos, peça que faça associações sobre os elementos do sonho para aprofundar a compreensão. " .
            "Baseie suas respostas e estratégias na psicologia e psicanálise, estruturando o atendimento de acordo com as respostas do usuário para construir um prontuário inicial que auxiliará na elaboração de um diagnóstico preliminar. " .
            "Mantenha a conversa fluida e evite transformar o diálogo em um interrogatório. Caso o usuário diga 'tchau', responda com uma despedida amigável e encerre a conversa. " .
            "Se o usuário ficar inativo por um período, envie uma mensagem de verificação amigável, como 'Oi, você está aí?'. " .
            "Quando apropriado, ofereça informações complementares com base em artigos científicos ou indique especialistas através do link https://openmrs.org/pt/. " .
            "Seu objetivo é criar um ambiente de apoio seguro e reflexivo, promovendo o autocuidado e a compreensão pessoal.
        ";
        
        // Verifica se a saudação inicial já foi enviada
        if (!$this->isInitialGreetingSent($messageHistory)) {
            // Adiciona a saudação ao início do histórico
            $messages = [
                ["role" => "system", "content" => $promptSystem],
                ["role" => "system", "content" => "Bem-vindo! Estou aqui para ajudar. Como você está se sentindo hoje?"]
            ];
        } else {
            // Adiciona apenas o prompt inicial sem saudação
            $messages = [
                ["role" => "system", "content" => $promptSystem]
            ];
        }

        // Limitar a 10 últimas mensagens para enviar à API
        $historicoLimitado = array_slice($messageHistory, -$this->max_history_length);

        // Adicionar o histórico limitado ao array de mensagens
        foreach ($historicoLimitado as $message) {
            $messages[] = ["role" => $message['role'], "content" => $message['content']];
        }

        // Adicionar a mensagem do usuário
        $messages[] = ["role" => "user", "content" => $userMessage];

        // Atualiza o histórico completo com a nova mensagem do usuário
        $messageHistory[] = ["role" => "user", "content" => $userMessage];

        // Preparação dos dados da requisição
        $data = [
            "model" => "gpt-3.5-turbo",
            "messages" => $messages,
            "max_tokens" => 150,
            "temperature" => 0.7,
            "top_p" => 0.9,
            "n" => 1,
            "presence_penalty" => 0,
            "frequency_penalty" => 0
        ];

        // Enviar requisição à API OpenAI
        $ch = curl_init($this->api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->api_key,
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        // Decodificar e obter a resposta do chatbot
        return json_decode($response, true);

    }

    // Verificar se a mensagem inicial já foi enviada
    public function isInitialGreetingSent($messageHistory)
    {
        foreach ($messageHistory as $message) {
            if ($message['role'] === 'system' && strpos($message['content'], 'boas-vindas') !== false) {
                return true; // Saudação inicial já foi enviada
            }
        }
        return false;
    }
}
