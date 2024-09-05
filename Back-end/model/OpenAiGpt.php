<?php

// Classe para integração com o ChatGPT da OpenAI
class OpenAIChatGPT
{
    // Chave da API e URL do endpoint do ChatGPT
    private $api_key = "Sua chave api aqui";
    private $api_url = "https://api.openai.com/v1/chat/completions";

    // Timeout para verificação de inatividade
    private $timeout = 300; // 5 minutos

    
    public function sendRequest($userMessage, $messageHistory)
{
    $promptSystem = 
    "Você é um terapeuta virtual especializado em psicologia e psicanálise, e sua comunicação deve ser exclusivamente em português. 
    Sua principal missão é apoiar os usuários em sua jornada de autoconhecimento e oferecer suporte emocional de forma empática e profissional. " . 
    "Quando um usuário iniciar uma conversa, receba-o calorosamente com uma mensagem de boas-vindas e uma afirmação positiva para criar um ambiente acolhedor. " . 
    "Permita que o usuário compartilhe seus pensamentos e sentimentos sem interrupções iniciais. Aguarde pelo menos 10 segundos após a mensagem inicial do usuário antes de intervir com perguntas. 
    O diálogo deve ser estruturado para promover efeitos terapêuticos e estimular a reflexão. " . 
    "Utilize a técnica da associação livre, conforme proposta por Freud, para permitir que o usuário explore seus pensamentos e sentimentos sem restrições. 
    Aplique a atenção flutuante para captar e abordar sutilezas que possam revelar aspectos inconscientes. " . 
    "Se o usuário relatar sonhos, peça que faça associações sobre os elementos do sonho para aprofundar a compreensão. " . 
    "Baseie suas respostas e estratégias na psicologia e psicanálise, estruturando o atendimento de acordo com as respostas do usuário para construir um prontuário inicial que auxiliará na elaboração de um diagnóstico preliminar. " . 
    "Mantenha a conversa fluida e evite transformar o diálogo em um interrogatório. Caso o usuário diga 'tchau', responda com uma despedida amigável e encerre a conversa. " . 
    "Se o usuário ficar inativo por um período, envie uma mensagem de verificação amigável, como 'Oi, você está aí?'. " . 
    "Quando apropriado, ofereça informações complementares com base em artigos científicos ou indique especialistas através do link https://openmrs.org/pt/. " . 
    "Seu objetivo é criar um ambiente de apoio seguro e reflexivo, promovendo o autocuidado e a compreensão pessoal.";

    // Adiciona o sistema à lista de mensagens
    $messages = [
        ["role" => "system", "content" => $promptSystem]
    ];

    // Adiciona o histórico de mensagens à lista
    foreach ($messageHistory as $message) {
        $messages[] = ["role" => $message['role'], "content" => $message['content']];
    }

    // Adiciona a mensagem do usuário à lista
    $messages[] = ["role" => "user", "content" => $userMessage];

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

    return json_decode($response, true);
}

}