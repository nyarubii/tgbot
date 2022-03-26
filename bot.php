<?php

    define('BOT_TOKEN', 'token');
    define('API_URL', 'https://api.telegram.org/bot' . BOT_TOKEN . '/');

    function apiRequestWebhook($method, $parameters) {
        if (!is_string($method)) {
            error_log("Method name must be a string\n");
            return false;
        }
        if (!$parameters) {
            $parameters = [];
        } else {
            if (!is_array($parameters)) {
                error_log("Parameters must be an array\n");
                return false;
            }
        }
        $parameters["method"] = $method;
        $payload = json_encode($parameters);
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($payload));
        echo $payload;
        return true;
    }

    if ($_GET['hook'] == 'true') {
        define('WEBHOOK_URL', 'https://example.come/bot.php');
        $url = API_URL . 'setWebhook?' . http_build_query(['url' => WEBHOOK_URL]);
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        curl_exec($handle);
        echo "<span style='color: green;'>Телеграм бот подготовлен к работе!</span><br>";
    }

    $content = file_get_contents("php://input");
    $update = json_decode($content, true);

    if (!$update) {
        exit;
    }
    if (isset($update["message"])) {
        $chat_id = $update["message"]['chat']['id'];
        if (isset($update["message"]['text'])) {
            $text = $update["message"]['text'];
            apiRequestWebhook("sendMessage", ['chat_id' => $chat_id, "text" => $text]);
        }
    }
