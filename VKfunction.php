<?php
$headers = array('accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8','content-type' => 'application/x-www-form-urlencoded','user-agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36');
$get_main_page = post('https://vk.com', array('headers' => array('accept: '.$headers['accept'], 'content-type: '.$headers['content-type'], 'user-agent: '.$headers['user-agent'])));
preg_match('/name=\"ip_h\" value=\"(.*?)\"/s', $get_main_page['content'], $ip_h);
preg_match('/name=\"lg_h\" value=\"(.*?)\"/s', $get_main_page['content'], $lg_h);
$post_auth = post('https://login.vk.com/?act=login', array('params' => 'act=login&role=al_frame&_origin='.urlencode('http://vk.com').'&ip_h='.$ip_h[1].'&lg_h='.$lg_h[1].'&email='.urlencode($login).'&pass='.urlencode($password),'headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $get_main_page['cookies']));
preg_match('/Location\: (.*)/s', $post_auth['headers'], $post_auth_location);
$post_auth_location[1]=trim(str_replace('Strict-Transport-Security: max-age=15768000', '', $post_auth_location[1]));
if(!preg_match('/\_\_q\_hash=/s', $post_auth_location[1])) {echo 'Не удалось авторизоваться <br /> <br />'.$post_auth['headers'];exit;}
$get_auth_location = post($post_auth_location[1], array('headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $post_auth['cookies']));
preg_match('/"uid"\:"([0-9]+)"/s', $get_auth_location['content'], $my_page_id);
$my_page_id = $my_page_id[1];
$get_my_page = getUserPage($my_page_id, $get_auth_location['cookies']);
if(preg_match('/act=security\_check/s', $get_my_page['headers'])) {
preg_match('/Location\: (.*)/s', $get_my_page['headers'], $security_check_location);
$security_check_location[1]=trim(str_replace('Strict-Transport-Security: max-age=0', '', $security_check_location[1]));
$get_security_check_page = post('https://vk.com'.$security_check_location[1], array('headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $get_auth_location['cookies']));
preg_match('/hash: \'(.*?)\'/s', $get_security_check_page['content'], $get_security_check_page_hash);
$post_security_check_code = post('https://vk.com/login.php', array('params' => 'act=security_check&code='.$security_check_code.'&al_page=2&hash='.$get_security_check_page_hash[1],'headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $get_auth_location['cookies']));
echo 'Запрошена проверка безопасности';
} else {
$get_user = getUserPage($public, $get_auth_location['cookies']);
$struser = iconv('windows-1251', 'utf-8', $get_user['content']);
preg_match('#<div id="group_u_rows_members" class="group_u_rows">(.+?)group_edit_more_members#is', $struser, $desk);
preg_match_all( '#<div class="group_u_name">(.+?)</div>#is', $desk[0], $matches);
$user_name1 = strip_tags($matches[0][0]);
$user_name2 = strip_tags($matches[0][1]);
$user_name3 = strip_tags($matches[0][2]);
preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $desk[0], $media);
$user_photo1 = str_replace('src="', '', $media[0][0]);
$user_photo2 = str_replace('src="', '', $media[0][1]);
$user_photo3 = str_replace('src="', '', $media[0][2]);
if($_GET['debug'] == 1) { echo ''.$user_name1.'<br><img src="'.$user_photo1.'"><br><br>'; echo ''.$user_name2.'<br><img src="'.$user_photo2.'"><br><br>'; echo ''.$user_name3.'<br><img src="'.$user_photo3.'"><br><br>'; }
require_once('PHPImage.php');
if(!copy($user_photo1, ''.$DROOT.'/tmp/1.jpg')) { copy('https://vk.com/images/camera_100.png', ''.$DROOT.'/tmp/1.jpg'); }
if(!copy($user_photo2, ''.$DROOT.'/tmp/2.jpg')) { copy('https://vk.com/images/camera_100.png', ''.$DROOT.'/tmp/2.jpg'); }
if(!copy($user_photo3, ''.$DROOT.'/tmp/3.jpg')) { copy('https://vk.com/images/camera_100.png', ''.$DROOT.'/tmp/3.jpg'); }
$profile1 = ''.$DROOT.'/tmp/1.jpg';
$profile2 = ''.$DROOT.'/tmp/2.jpg';
$profile3 = ''.$DROOT.'/tmp/3.jpg';
$image = new PHPImage();
$image->setDimensionsFromImage($bg);
$image->draw($bg);
$image->setTextColor($color);
$image->setFont($font);
if($position == 'center') {
$image->draw($profile1, '219', '55');
$image->draw($profile2, '344', '55');
$image->draw($profile3, '474', '55');
$image->text($user_name1, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 219,'y' => 165));
$image->text($user_name2, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 344,'y' => 165));
$image->text($user_name3, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 474,'y' => 165));
}
if($position == 'left') {
$image->draw($profile1, '19', '55');
$image->draw($profile2, '144', '55');
$image->draw($profile3, '274', '55');
$image->text($user_name1, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 19,'y' => 165));
$image->text($user_name2, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 144,'y' => 165));
$image->text($user_name3, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 274,'y' => 165));
}
if($position == 'right') {
$image->draw($profile1, '419', '55');
$image->draw($profile2, '544', '55');
$image->draw($profile3, '674', '55');
$image->text($user_name1, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 419,'y' => 165));
$image->text($user_name2, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 544,'y' => 165));
$image->text($user_name3, array('width' => 100,'fontSize' => 9,'alignHorizontal' => 'center','alignVertical' => 'center','x' => 674,'y' => 165));
}
if($show_time==1){$image->setTextColor($color_time);$image->text('Обновлено в '.date("H:i").'', array('width' => 150,'fontSize' => 8,'alignHorizontal' => 'left','alignVertical' => 'center','x' => 20,'y' => 183));}

$image->save(''.$DROOT.'/tmp/result.jpg');
$Result = request_photo_full('https://pu.vk.com/c'.$server.'/upload.php?act=owner_cover&oid=-'.$public.'&square=&mid='.$my_page_id.'&server='.$server.'&_origin=https%3A%2F%2Fvk.com&_sig='.$signature.'&');
if($_GET['debug'] == 1) { print_r($Result); echo '<br><br>'; }
$Result = request_nojson('https://pu.vk.com/c'.$server.'/upload.php?act=owner_cover_crop&_query='.$Result.'&_origin=https://vk.com&_full=0,0,795,200&_rot=0&_crop=0,0,795&_jsonp=0&_origin=https://vk.com');
if($_GET['debug'] == 1) { print_r($Result); echo '<br><br>'; }
$last = 'https://vk.com/al_page.php?_query='.$Result.'&act=owner_photo_save&al=1&from=groups_edit';
if($_GET['debug']==1) { echo $last; echo '<br><br>'; }
$get_save = SaveImg($last, $get_auth_location['cookies']);
if(strpos($get_save['content'], 'uploadOwnerCover') === FALSE){
if(strpos($get_save['content'], 'blocked') === FALSE){} else { echo '<h1>Аккаунт заблокирован</h1>'; exit; }
if(strpos($get_save['content'], 'ERR_UPLOAD_BAD_SIGNATURE') === FALSE){} else { echo '<h1>Не правильно взята переменная _sig</h1>'; exit; }
$expresp = explode('<!>',$get_save['content']);
if($expresp[5]=='6Le00B8TAAAAACHiybbHy8tMOiJhM5vh88JVtP4c') { echo '<h1>Нужно ввести капчу</h1>'; $need = 1; }
if($rucaptcha_key !== '' AND $rucaptcha_key !== NULL AND $need == 1){
$getc = file_get_contents('http://rucaptcha.com/in.php?key='.$rucaptcha_key.'&method=userrecaptcha&googlekey=6Le00B8TAAAAACHiybbHy8tMOiJhM5vh88JVtP4c&pageurl=vk.com&soft_id=1691');
$capcha_id = explode('|', $getc);
$capcha_id = $capcha_id[1];
sleep(10);
$counter = 0;
while ($counter < 12) 
{
$getc = file_get_contents('http://rucaptcha.com/res.php?key='.$rucaptcha_key.'&action=get&id='.$capcha_id.'');
$resp_c = explode('|', $getc);
if($resp_c[0] == 'OK') {
$counter=12;
$last = 'https://vk.com/al_page.php?_query='.$Result.'&act=owner_photo_save&al=1&from=groups_edit&recap&recaptcha='.$resp_c[1].'';
$get_save = SaveImg($last, $get_auth_location['cookies']);
if(strpos($get_save['content'], 'uploadOwnerCover') === FALSE){
echo 'Капча не прошла';
} else {
echo 'Капча прошла, фото опубликовано';
}
} else {
$counter++;
sleep(10);
}	
}
}
} else {
echo '<h1>Изображение опубликовалось</h1>'; 
}
if($_GET['debug']==1) { print_r($get_save); }
}
function getLastUser($id = null, $cookies = null) {
global $headers;
$get = post('https://vk.com/id'.$id, array('headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $cookies));return $get;}
function getUserPage($id = null, $cookies = null) {global $headers;$get = post('https://vk.com/club'.$id.'?act=users', array('headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $cookies));return $get;}
function SaveImg($url = null, $cookies = null) {global $headers;$get = post($url, array('headers' => array('accept: '.$headers['accept'],'content-type: '.$headers['content-type'],'user-agent: '.$headers['user-agent']),'cookies' => $cookies));return $get;}
function post($url = null, $params = null) {
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
if(isset($params['params'])) {curl_setopt($ch, CURLOPT_POST, 1);curl_setopt($ch, CURLOPT_POSTFIELDS, $params['params']);}
if(isset($params['headers'])) {curl_setopt($ch, CURLOPT_HTTPHEADER, $params['headers']);}
if(isset($params['cookies'])) {curl_setopt($ch, CURLOPT_COOKIE, $params['cookies']);}
$result = curl_exec($ch);
list($headers, $result) = explode("\r\n\r\n", $result, 4);
preg_match_all('|Set-Cookie: (.*);|U', $headers, $parse_cookies);
$cookies = implode(';', $parse_cookies[1]);
curl_close($ch);
return array('headers' => $headers, 'cookies' => $cookies, 'content' => $result);
}
function request_photo_full($url) {global $imagelast; 
$Curl = curl_init($url);
$data = array('photo' => '@'.$imagelast.'');
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($Curl, CURLOPT_SAFE_UPLOAD, false);
curl_setopt($Curl, CURLOPT_POSTFIELDS, $data);
$Result = curl_exec($Curl);
curl_close($Curl);
if ($Result) {return $Result;}return false;
}
function request_nojson($url) {
$Curl = curl_init($url);
curl_setopt($Curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($Curl, CURLOPT_SSL_VERIFYPEER, 0);
$Result = curl_exec($Curl);
curl_close($Curl);
if ($Result) {return $Result;}return false;
}
?>
