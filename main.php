<?php

if (!isset($_REQUEST)) {return;}
$data = json_decode(file_get_contents('php://input'));

$token = '44dbd08180a5e89ea9c3cbf9d4933bad99835377a1fe78cad96b89deb69a3fd2ad240efdee9fb44831ef0';

$button = json_encode((object)[
    'one_time' => false,
    'buttons' => (array)[
        (array)[
            (object)[
                "action" => (object)[
                    "type" => "text",
                    "payload" => json_encode((object)["button" => "111"]),
                    "label" => "Информация"
                ],
                "color" => "positive"
            ],
            (object)[
                "action" => (object)[
                    "type" => "text",
                    "payload" => json_encode((object)["button" => "111"]),
                    "label" => "Инвайт"
                ],
                "color" => "negative"
            ],
            (object)[
                "action" => (object)[
                    "type" => "text",
                    "payload" => json_encode((object)["button" => "111"]),
                    "label" => "Текст"
                ],
                "color" => "primary"
            ],
            (object)[
                "action" => (object)[
                    "type" => "text",
                    "payload" => json_encode((object)["button" => "111"]),
                    "label" => "Текст"
                ],
                "color" => "secondary"
            ]
        ]
    ]
]);

switch ($data->type) {
    case 'message_reply':
        echo('ok');
        break;
    case 'message_new':
        $tempMess = mb_strtolower($data->object->body);
        if ($tempMess == "информация")
            $message = "Привет " .VkBot::getUserInfo($token, $data->object->user_id, 'first_name'). ".<br>Вот информация";
        elseif ( preg_match_all('/инвайт/m', $tempMess, $matches, PREG_SET_ORDER, 0) || ( preg_match_all('/фаму/m', $tempMess, $matches, PREG_SET_ORDER, 0) && ( ( preg_match_all('/прими/m', $tempMess, $matches, PREG_SET_ORDER, 0) || ( preg_match_all('/меня/m', $tempMess, $matches, PREG_SET_ORDER, 0))))))
            $message = "✅✅✅Информация про инвайт✅✅✅<br>Стоймость инвайта: 200руб.<br>Qiwi: +380958876335<br>При оплате указывайте ваш VK для связи.";
        elseif ($tempMess == "nokeyboard")
            VkBot::sendKeyBoardVk($token, $data->object->user_id, json_encode((object)['one_time' => false,'buttons' => (array)[]]), "Клавиатура: Отключена");
        elseif ($tempMess == "keyboard")
            VkBot::sendKeyBoardVk($token, $data->object->user_id, $button, "Клавиатура: Включена");
        else
            $message = "Привет " .VkBot::getUserInfo($token, $data->object->user_id, 'first_name'). ". Ваше сообщение не удалось распознть.<br>Я отправил его саппортам ожидайте.<br>Ваше сообщение: ". $data->object->body;

        $result = VkBot::sendMessageVk($token, $data->object->user_id, $message);

        echo('ok');

        break;

    case 'group_join':
        $message = "Добро пожаловать в нашу фаму Quinside, ".VkBot::getUserInfo($token, $data->object->user_id, 'first_name')."!<br>Успехов в игре!";

        $result = VkBot::sendMessageVk($token, $data->object->user_id, $message);
        $result = VkBot::sendKeyBoardVk($token, $data->object->user_id, $button);

        echo('ok');

        break;
}




class VkBot
{
    static function getUserInfo($token, $userId, $param)
    {
        return json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$userId}&v=5.50&access_token={$token}"), true)['response'][0][$param];
    }

    static function sendMessageVk($token, $userId, $message)
    {
       return file_get_contents('https://api.vk.com/method/messages.send?'. http_build_query(array('message' => $message,
                                                                                                    'user_id' => $userId, 
                                                                                                    'access_token' => $token,
                                                                                                    'v' => '5.50')));
    }
    static function sendKeyBoardVk($token, $userId, $keyBoard, $message)
    {
       return file_get_contents('https://api.vk.com/method/messages.send?'. http_build_query(array('message' => $message,
                                                                                                    'keyboard' => $keyBoard,
                                                                                                    'user_id' => $userId, 
                                                                                                    'access_token' => $token,
                                                                                                    'v' => '5.50')));
    }
    static function pinMessage($token, $userId, $keyBoard, $message)
    {
       return file_get_contents('https://api.vk.com/method/messages.pin?'. http_build_query(array('peer_id' => $message,
                                                                                                    'message_id' => $keyBoard,
                                                                                                    'access_token' => $token,
                                                                                                    'v' => '5.50')));
    }
}



?>
