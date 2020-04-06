<?php

require 'config.php';

function output($id_chat) {
  $users = VKget('messages.getChat', "chat_id={$id_chat}&fields=status");
  if(isset($users['error'])) return "Код ошибки: {$users['error']['error_code']}\nСообщение: {$users['error']['error_msg']}";
  $users = $users['response']['users'];
  $final = array();

  for ($i=0; $i < count($users); $i++) {
    if(!isset($users[$i]['deactivated'])) array_push($final, "@id{$users[$i]['id']}"); // Проверка на удаленных и забаенных пользователей
  }

  return implode(',', $final);
}

function VKget($method, $args = array()) {
  return json_decode(file_get_contents("https://api.vk.com/method/{$method}?{$args}&access_token={$GLOBALS['token']}&v={$GLOBALS['v']}"), true);
}

if(isset($_GET['chatid']) && !empty($GLOBALS['token'])) {
  echo '<center>
          <textarea style="margin: 0px; width: 756px; height: 316px;">'.output($_GET['chatid']).'</textarea>
        </center>';
} else {
  echo '

  <form action="index.php" method="get">
   <p>CHAT ID: <input type="text" name="chatid" /></p>
   <p><input type="submit" /></p>
  </form>

  ';

  if(empty($GLOBALS['token'])) {
    echo 'ВВЕДИТЕ СВОЙ <strong>VK TOKEN</strong> В ФАЙЛЕ <strong>config.php</strong> | Получить токен можно <a href="https://oauth.vk.com/authorize?client_id=3116505&scope=1073737727&redirect_uri=https://oauth.vk.com/blank.html&display=page&response_type=token&revoke=1">ЗДЕСЬ</a></b>';
  }
}

 ?>
