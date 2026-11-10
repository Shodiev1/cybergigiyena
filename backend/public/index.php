<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../src/alert_validation.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');
$requestId=uuid();
$production=getenv('APP_ENV')==='production';
ini_set('display_errors','0');
ini_set('session.use_strict_mode','1');
ini_set('session.use_only_cookies','1');
ini_set('session.gc_maxlifetime','604800');
session_save_path(ROOT.'/var/sessions');
session_name($production?'__Host-cg':'cg_local');
session_set_cookie_params(['lifetime'=>604800,'path'=>'/','secure'=>$production,'httponly'=>true,'samesite'=>'Lax']);
session_start();
$_SESSION['guest']??=uuid(); $_SESSION['csrf']??=bin2hex(random_bytes(32));
if(isset($_SESSION['last_seen'])&&time()-$_SESSION['last_seen']>604800){unset($_SESSION['user'],$_SESSION['mfa']);}
$_SESSION['last_seen']=time();
$method=$_SERVER['REQUEST_METHOD'];
$path=substr(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),strlen('/api/v1')) ?: '/';
$body=[];$idem=false;$actor='';$eventKey='';

function progress(): array {
 $o=owner();$attempts=query('SELECT * FROM attempts WHERE owner=? ORDER BY created_at DESC LIMIT 100',[$o])->fetchAll();
 $questions=query('SELECT * FROM question_attempts WHERE owner=? ORDER BY created_at DESC LIMIT 200',[$o])->fetchAll();
 $skills=[];
 foreach(catalog()['topics'] as $t){
  $q=[];$s=[];$missed=0;
  foreach($questions as $a)if($a['topic']===$t['slug']&&!isset($q[$a['question_id']])){$q[$a['question_id']]=(bool)$a['correct'];}
  foreach($attempts as $a){$snap=json_decode($a['snapshot'],true);if($a['finished']&&$snap['topic']===$t['slug']&&!isset($s[$a['scenario']])){$decisions=json_decode($a['choices'],true);$s[$a['scenario']]=count(array_filter($decisions,fn($x)=>!$x['safe']))===0;}}
  $enough=count($q)>=3||count($s)>=2; $values=array_merge(array_values($q),array_values($s));
  $skills[]=['topic'=>$t['slug'],'title'=>$t['title'],'evidence'=>count($values),'enough'=>$enough,'percent'=>$enough?(int)round(count(array_filter($values))/count($values)*100):null,'needsPractice'=>in_array(false,$values,true)];
 }
 return ['lessons'=>query('SELECT slug,step,completed,updated_at FROM lesson_progress WHERE owner=?',[$o])->fetchAll(),'attempts'=>array_map(fn($a)=>array_merge(attemptView($a),['createdAt'=>$a['created_at']]),$attempts),'questions'=>$questions,'promises'=>query('SELECT * FROM promises WHERE owner=? ORDER BY day DESC LIMIT 100',[$o])->fetchAll(),'skills'=>$skills];
}
function dispatch(string $method,string $path,array $b): mixed {
 if($method==='GET'&&$path==='/health'){query('SELECT 1');return ['status'=>'ok','database'=>'mysql','version'=>1];}
 if($method==='GET'&&$path==='/session')return ['csrf'=>$_SESSION['csrf'],'user'=>currentUser(),'importAvailable'=>!empty($_SESSION['import_guest'])];
 if($method==='GET'&&$path==='/app-config')return ['locale'=>'uz-Latn-UZ','features'=>['push'=>false,'guest'=>true],'version'=>1];
 if($method==='GET'&&$path==='/catalog'){
  $c=catalog();unset($c['questions']);$c['scenarios']=array_values(array_map('publicScenario',$c['scenarios']));return $c;
 }
 if($method==='GET'&&$path==='/daily'){
  $data=daily();$etag='"'.hash('sha256',json($data)).'"';header('ETag: '.$etag);
  if(($_SERVER['HTTP_IF_NONE_MATCH']??'')===$etag){http_response_code(304);exit;}
  return $data;
 }
 if($method==='GET'&&$path==='/topics')return catalog()['topics'];
 if($method==='GET'&&$path==='/glossary')return catalog()['glossary'];
 if($method==='GET'&&$path==='/scenarios')return array_values(array_map('publicScenario',catalog()['scenarios']));
 if($method==='GET'&&preg_match('~^/scenarios/([a-z0-9-]+)$~',$path,$m))return publicScenario(catalog()['scenarios'][$m[1]]??fail(404,'NOT_FOUND','Mashq topilmadi.'));
 if($method==='GET'&&$path==='/lessons/search'){
  $q=mb_strtolower(mb_substr((string)($_GET['q']??''),0,100));
  return array_values(array_filter(catalog()['lessons'],fn($l)=>str_contains(mb_strtolower($l['title'].' '.$l['summary'].' '.$l['topic']),$q)));
 }
 if($method==='GET'&&preg_match('~^/lessons/([a-z0-9-]+)$~',$path,$m))return findLesson($m[1]);
 if($method==='GET'&&preg_match('~^/questions/([a-z0-9-]+)$~',$path,$m))return publicQuestion(catalog()['questions'][$m[1]]??fail(404,'NOT_FOUND','Savol topilmadi.'));
 if($method==='POST'&&$path==='/question-attempts'){
  $q=catalog()['questions'][textField($b,'questionId',100)]??fail(404,'NOT_FOUND','Savol topilmadi.');
  $answer=textField($b,'answer',20);if(!in_array($answer,array_column($q['options'],'id'),true))fail(422,'VALIDATION_ERROR','Javobni tanlang.');
  $id=uuid();$correct=$answer===$q['correct'];
  query('INSERT INTO question_attempts(id,owner,question_id,topic,answer,correct,explanation) VALUES(?,?,?,?,?,?,?)',[$id,owner(),$q['id'],$q['topic'],$answer,(int)$correct,$q['explanation']]);
  return ['id'=>$id,'correct'=>$correct,'explanation'=>$q['explanation'],'lesson'=>$q['lesson'],'answer'=>$answer];
 }
 if($method==='POST'&&$path==='/scenario-attempts'){
  $slug=textField($b,'scenario',80);$s=catalog()['scenarios'][$slug]??fail(404,'NOT_FOUND','Mashq topilmadi.');$id=uuid();
  query('INSERT INTO attempts(id,owner,scenario,snapshot,current_scene,choices) VALUES(?,?,?,?,?,?)',[$id,owner(),$slug,json($s),$s['start'],'[]']);
  return attemptView(ownedAttempt($id));
 }
 if(preg_match('~^/scenario-attempts/([a-f0-9-]{36})(?:/(choices|finish))?$~',$path,$m)){
  $a=ownedAttempt($m[1],$method==='POST'); $s=json_decode($a['snapshot'],true);$action=$m[2]??'';
  if($method==='GET'&&$action==='')return attemptView($a);
  if($method==='POST'&&$action==='choices'){
   if($a['finished']||$a['current_scene']==='end')fail(409,'VERSION_CONFLICT','Mashq yakunlangan. Natijani ko‘ring.');
   if(($b['scene']??'')!==$a['current_scene'])fail(409,'VERSION_CONFLICT','Sahna yangilangan. Mashqni davom ettiring.');
   $choiceId=textField($b,'choice',30);$choice=null;
   foreach($s['scenes'][$a['current_scene']]['choices'] as $c)if($c['id']===$choiceId)$choice=$c;
   if(!$choice)fail(422,'VALIDATION_ERROR','Tanlov mavjud emas.');
   $choices=json_decode($a['choices'],true);$choices[]=['scene'=>$a['current_scene'],'choice'=>$choiceId,'label'=>$choice['label'],'safe'=>$choice['safe'],'why'=>$choice['why']];
   query('UPDATE attempts SET current_scene=?,choices=? WHERE id=?',[$choice['next'],json($choices),$a['id']]);
   return attemptView(ownedAttempt($a['id']));
  }
  if($method==='POST'&&$action==='finish'){
   if($a['current_scene']!=='end')fail(409,'INCOMPLETE','Avval mashq bosqichlarini tugating.');
   query('UPDATE attempts SET finished=1 WHERE id=?',[$a['id']]);return attemptView(ownedAttempt($a['id']));
  }
 }
 if($method==='POST'&&$path==='/lesson-progress'){
  $l=findLesson(textField($b,'slug',80));$step=$b['step']??0;
  if(!is_int($step)||$step<0||$step>5)fail(422,'VALIDATION_ERROR','Bosqich noto‘g‘ri.');
  $complete=$step===5;
  if($complete&&!query('SELECT id FROM question_attempts WHERE owner=? AND question_id=? LIMIT 1',[owner(),$l['questionId']])->fetch())fail(409,'INCOMPLETE','Dars savolini bajaring.');
  query('INSERT INTO lesson_progress(owner,slug,step,completed) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE step=GREATEST(step,?),completed=GREATEST(completed,?),updated_at=CURRENT_TIMESTAMP',[owner(),$l['slug'],$step,(int)$complete,$step,(int)$complete]);return ['saved'=>true];
 }
 if($method==='GET'&&in_array($path,['/progress','/me/progress','/me/history','/me/weaknesses'],true))return progress();
 if($method==='POST'&&$path==='/promises'){
  $d=daily();$id=uuid();
  query('INSERT INTO promises(id,owner,day,lesson,title) VALUES(?,?,?,?,?) ON DUPLICATE KEY UPDATE id=id',[$id,owner(),$d['date'],$d['lesson']['slug'],$d['promise']]);
  return query('SELECT * FROM promises WHERE owner=? AND day=?',[owner(),$d['date']])->fetch();
 }
 if($method==='PATCH'&&preg_match('~^/promises/([a-f0-9-]{36})$~',$path,$m)){
  $state=$b['state']??'';if(!in_array($state,['selected','done','later'],true))fail(422,'VALIDATION_ERROR','Holat noto‘g‘ri.');
  if(!query('SELECT id FROM promises WHERE id=? AND owner=?',[$m[1],owner()])->fetch())fail(404,'NOT_FOUND','Va’da topilmadi.');
  query('UPDATE promises SET state=? WHERE id=? AND owner=?',[$state,$m[1],owner()]);return ['state'=>$state];
 }
 if($method==='GET'&&$path==='/me/promises')return progress()['promises'];
 if($method==='GET'&&$path==='/me')return requireUser();
 if($method==='GET'&&$path==='/preferences'){
  $p=query('SELECT payload FROM preferences WHERE owner=?',[owner()])->fetchColumn();return $p?json_decode($p,true):[];
 }
 if($method==='PATCH'&&$path==='/me/preferences'){
  $p=[];if(isset($b['theme'])){if(!in_array($b['theme'],['system','light','dark'],true))fail(422,'VALIDATION_ERROR','Ko‘rinish noto‘g‘ri.');$p['theme']=$b['theme'];}
  if(isset($b['fontScale'])){if(!in_array($b['fontScale'],[1,1.25,1.5,2],true))fail(422,'VALIDATION_ERROR','Shrift o‘lchami noto‘g‘ri.');$p['fontScale']=$b['fontScale'];}
  $p['reducedMotion']=(bool)($b['reducedMotion']??false);$p['checklist']=array_values(array_intersect((array)($b['checklist']??[]),['password','telegram','apps','backup','privacy']));
  query('INSERT INTO preferences(owner,payload) VALUES(?,?) ON DUPLICATE KEY UPDATE payload=?',[owner(),json($p),json($p)]);return $p;
 }
 if($method==='POST'&&in_array($path,['/auth/register','/auth/login'],true)){
  rateLimit('auth',12,900);$email=mb_strtolower(textField($b,'email',254));$password=$b['password']??'';
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||!is_string($password)||strlen($password)>1024)fail(422,'VALIDATION_ERROR','Email yoki parol formati noto‘g‘ri.');
  if($path==='/auth/register'){
   if(mb_strlen($password)<12)fail(422,'VALIDATION_ERROR','Parol kamida 12 belgidan iborat bo‘lsin.');
   if(($b['consent']??false)!==true)fail(422,'VALIDATION_ERROR','Maxfiylik ma’lumotlari bilan tanishing.');
   if(query('SELECT id FROM users WHERE email=?',[$email])->fetch())fail(409,'ACCOUNT_EXISTS','Bu email bilan hisob ochib bo‘lmadi. Kirish yoki tiklashni sinab ko‘ring.');
   $id=uuid();$name=isset($b['name'])?textField($b,'name',80,0):'';
   query('INSERT INTO users(id,email,name,password_hash) VALUES(?,?,?,?)',[$id,$email,$name,password_hash($password,PASSWORD_ARGON2ID)]);
  }else{
   $u=query('SELECT * FROM users WHERE email=?',[$email])->fetch();
   $hash=$u['password_hash']??'$2y$12$wQyhvH0zhC9a97/3JCS4kuY.wvdGJYr8IwaqaNU.38QJvcygA6GJq';
   if(!password_verify($password,$hash)||!$u||$u['blocked'])fail(401,'INVALID_CREDENTIALS','Email yoki parol noto‘g‘ri.');
   $id=$u['id'];
  }
  $guest='g:'.$_SESSION['guest'];session_regenerate_id(true);$_SESSION['user']=$id;$_SESSION['session_version']=(int)query('SELECT session_version FROM users WHERE id=?',[$id])->fetchColumn();$_SESSION['import_guest']=$guest;unset($_SESSION['mfa']);$_SESSION['csrf']=bin2hex(random_bytes(32));
  return ['user'=>currentUser(),'csrf'=>$_SESSION['csrf'],'importAvailable'=>true];
 }
 if($method==='POST'&&$path==='/auth/logout'){
  $_SESSION=[];session_regenerate_id(true);$_SESSION['guest']=uuid();$_SESSION['csrf']=bin2hex(random_bytes(32));return ['csrf'=>$_SESSION['csrf']];
 }
 if($method==='POST'&&$path==='/auth/recover'){
  rateLimit('recover',5,900);$email=mb_strtolower(textField($b,'email',254));if(!filter_var($email,FILTER_VALIDATE_EMAIL))fail(422,'VALIDATION_ERROR','Emailni to‘g‘ri kiriting.');
  if(getenv('MAIL_ENABLED')!=='1')fail(503,'MAIL_UNAVAILABLE','Parolni tiklash xizmati hozir ulanmagan. Keyinroq qayta urinib ko‘ring.');
  $u=query('SELECT id FROM users WHERE email=?',[$email])->fetch();
  if($u){$token=bin2hex(random_bytes(32));query('INSERT INTO password_resets(token_hash,user_id,expires_at) VALUES(?,?,DATE_ADD(UTC_TIMESTAMP(),INTERVAL 30 MINUTE))',[hash('sha256',$token),$u['id']]);$origin=getenv('APP_ORIGIN');if(!$origin||!str_starts_with($origin,'https://'))fail(503,'MAIL_UNAVAILABLE','Tiklash xizmati sozlanmagan.');if(!mail($email,'CyberGigiyena: parolni tiklash',$origin.'/auth/reset?token='.$token))fail(503,'MAIL_UNAVAILABLE','Xabar yuborilmadi. Keyinroq qayta urinib ko‘ring.');}
  return ['message'=>'Agar bu emailga hisob bog‘langan bo‘lsa, tiklash ko‘rsatmasi yuboriladi.'];
 }
 if($method==='POST'&&$path==='/auth/reset'){
  rateLimit('reset',8,900);$token=textField($b,'token',64,64);$password=textField($b,'password',1024,12);
  $r=query('SELECT * FROM password_resets WHERE token_hash=? AND expires_at>UTC_TIMESTAMP() FOR UPDATE',[hash('sha256',$token)])->fetch();
  if(!$r)fail(422,'INVALID_TOKEN','Tiklash havolasi yaroqsiz yoki muddati o‘tgan.');
  query('UPDATE users SET password_hash=?,session_version=session_version+1 WHERE id=?',[password_hash($password,PASSWORD_ARGON2ID),$r['user_id']]);query('DELETE FROM password_resets WHERE user_id=?',[$r['user_id']]);return ['reset'=>true];
 }
 if($method==='POST'&&$path==='/progress/sync'){
  requireUser();$from=$_SESSION['import_guest']??null;$to=owner();
  if(!$from)return ['imported'=>false];
  if(($b['accept']??false)!==true){unset($_SESSION['import_guest']);return ['imported'=>false];}
  foreach(['attempts','question_attempts','feedback'] as $table)query("UPDATE $table SET owner=? WHERE owner=?",[$to,$from]);
  foreach(query('SELECT slug,step,completed FROM lesson_progress WHERE owner=?',[$from])->fetchAll() as $lesson){query('INSERT INTO lesson_progress(owner,slug,step,completed) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE step=GREATEST(step,?),completed=GREATEST(completed,?)',[$to,$lesson['slug'],$lesson['step'],$lesson['completed'],$lesson['step'],$lesson['completed']]);}
  query('INSERT IGNORE INTO promises(id,owner,day,lesson,title,state) SELECT UUID(),?,day,lesson,title,state FROM promises WHERE owner=?',[$to,$from]);
  query('DELETE FROM lesson_progress WHERE owner=?',[$from]);query('DELETE FROM promises WHERE owner=?',[$from]);unset($_SESSION['import_guest']);return ['imported'=>true];
 }
 if($method==='GET'&&$path==='/me/export')return ['exportedAt'=>gmdate(DATE_ATOM),'user'=>currentUser(),'progress'=>progress(),'preferences'=>dispatch('GET','/preferences',[])];
 if($method==='DELETE'&&$path==='/me'){
  $u=requireUser();$row=query('SELECT password_hash FROM users WHERE id=?',[$u['id']])->fetch();
  if(($b['confirm']??'')!=='O‘CHIRISH'||!password_verify((string)($b['password']??''),$row['password_hash']))fail(422,'CONFIRMATION_REQUIRED','Parol va O‘CHIRISH tasdig‘i kerak.');
  foreach(['attempts','question_attempts','lesson_progress','promises','preferences','feedback','idempotency_keys'] as $t)query("DELETE FROM $t WHERE owner=?",[owner()]);
  query('DELETE FROM users WHERE id=?',[$u['id']]);$_SESSION=[];session_regenerate_id(true);$_SESSION['guest']=uuid();$_SESSION['csrf']=bin2hex(random_bytes(32));return ['deleted'=>true,'csrf'=>$_SESSION['csrf']];
 }
 if($method==='POST'&&$path==='/feedback'){
  rateLimit('feedback',5,3600);$category=textField($b,'category',30);if(!in_array($category,['content','bug','idea'],true))fail(422,'VALIDATION_ERROR','Turini tanlang.');
  query('INSERT INTO feedback(id,owner,category,message) VALUES(?,?,?,?)',[uuid(),owner(),$category,textField($b,'message',2000,10)]);return ['received'=>true];
 }
 if($method==='GET'&&($path==='/alerts'||str_starts_with($path,'/alerts/'))){
  $alerts=activeAlerts();
  if($path==='/alerts')return $alerts;
  foreach($alerts as $a)if($a['slug']===substr($path,8))return $a;
  fail(404,'NOT_FOUND','Ogohlantirish mavjud emas yoki muddati o‘tgan.');
 }
 if($method==='GET'&&$path==='/inbox')return activeAlerts();
 if($method==='POST'&&$path==='/admin/mfa'){
  $u=requireUser();if($u['role']==='member')fail(403,'FORBIDDEN','Ruxsat yo‘q.');rateLimit('mfa',6,300);
  $mfaRow=query('SELECT mfa_secret,mfa_last_tick FROM users WHERE id=? FOR UPDATE',[$u['id']])->fetch();$secret=$mfaRow['mfa_secret'];
  if(!$secret||!totp($secret,textField($b,'code',6,6)))fail(403,'INVALID_MFA','Tasdiqlash kodi noto‘g‘ri yoki sozlanmagan.');
  $tick=(int)floor(time()/30);if((int)$mfaRow['mfa_last_tick']>=$tick)fail(409,'MFA_REPLAY','Yangi kodni kuting.');query('UPDATE users SET mfa_last_tick=? WHERE id=?',[$tick,$u['id']]);$_SESSION['mfa']=time();return ['verified'=>true];
 }
 if(str_starts_with($path,'/admin'))return adminDispatch($method,$path,$b);
 fail(404,'NOT_FOUND','So‘ralgan ma’lumot topilmadi.');
}

function adminDispatch(string $method,string $path,array $b): mixed {
 $u=requireAdmin();
 if($method==='GET'&&$path==='/admin/feedback'){requireAdmin(['admin','author','reviewer']);return query('SELECT id,category,message,created_at FROM feedback ORDER BY created_at DESC LIMIT 100')->fetchAll();}
 if($method==='GET'&&$path==='/admin/overview')return ['lessons'=>count(catalog()['lessons']),'scenarios'=>count(catalog()['scenarios']),'feedback'=>(int)query('SELECT COUNT(*) FROM feedback')->fetchColumn(),'today'=>daily(),'roles'=>['author','reviewer','publisher','admin'],'user'=>$u];
 if($method==='GET'&&$path==='/admin/content')return array_map(function($r){$r['payload']=json_decode($r['payload'],true);return $r;},query('SELECT * FROM content ORDER BY kind,id')->fetchAll());
 if($method==='GET'&&preg_match('~^/admin/content/([a-z0-9-]+)/revisions$~',$path,$m)){
  requireAdmin(['admin','author','reviewer','publisher']);
  if(!query('SELECT id FROM content WHERE id=?',[$m[1]])->fetchColumn())fail(404,'NOT_FOUND','Kontent topilmadi.');
  return array_map(function($r){$r['payload']=json_decode($r['payload'],true);return $r;},query('SELECT id,version,payload,created_at FROM content_revisions WHERE content_id=? ORDER BY id DESC',[$m[1]])->fetchAll());
 }
 if($method==='POST'&&preg_match('~^/admin/content/([a-z0-9-]+)/restore$~',$path,$m)){
  requireAdmin(['admin','author']);
  $row=query('SELECT * FROM content WHERE id=? FOR UPDATE',[$m[1]])->fetch();if(!$row)fail(404,'NOT_FOUND','Kontent topilmadi.');
  if(($b['version']??0)!==(int)$row['version'])fail(409,'VERSION_CONFLICT','Boshqa muharrir tahrir qildi. Yangilab ko‘ring.');
  if(!is_int($b['revisionId']??null)||$b['revisionId']<1)fail(422,'VALIDATION_ERROR','Tahrir nusxasini tanlang.');
  $revision=query('SELECT payload FROM content_revisions WHERE id=? AND content_id=?',[$b['revisionId'],$m[1]])->fetch();if(!$revision)fail(404,'NOT_FOUND','Tahrir nusxasi topilmadi.');
  $p=json_decode($revision['payload'],true);validateContent($row['kind'],$m[1],$p);
  query('INSERT INTO content_revisions(content_id,actor,payload,version) VALUES(?,?,?,?)',[$m[1],$u['id'],$row['payload'],$row['version']]);
  query("UPDATE content SET payload=?,state='draft',author=?,reviewer=NULL,version=version+1,updated_at=CURRENT_TIMESTAMP WHERE id=?",[json($p),$u['id'],$m[1]]);
  audit('content.restore',$m[1]);return ['saved'=>true,'version'=>(int)$row['version']+1];
 }
 if($method==='GET'&&$path==='/admin/calendar')return query('SELECT * FROM daily_bundles ORDER BY day LIMIT 90')->fetchAll();
 if($method==='GET'&&$path==='/admin/audit'){requireAdmin(['admin']);return query('SELECT id,actor,action,object_id,created_at FROM audit_events ORDER BY id DESC LIMIT 100')->fetchAll();}
 if($method==='GET'&&$path==='/admin/users'){requireAdmin(['admin']);return query('SELECT id,name,role,blocked,created_at FROM users ORDER BY created_at DESC LIMIT 100')->fetchAll();}
 if($method==='PATCH'&&preg_match('~^/admin/users/([a-f0-9-]{36})$~',$path,$m)){
  requireAdmin(['admin']);if($m[1]===$u['id'])fail(422,'VALIDATION_ERROR','O‘zingizni bloklay olmaysiz.');query('UPDATE users SET blocked=? WHERE id=?',[(int)(bool)($b['blocked']??false),$m[1]]);audit('user.status',$m[1]);return ['saved'=>true];
 }
 if($method==='GET'&&$path==='/admin/analytics'){
  requireAdmin(['admin','analyst']);$count=(int)query('SELECT COUNT(DISTINCT owner) FROM attempts')->fetchColumn();
  return ['sampleEnough'=>$count>=10,'participants'=>$count>=10?$count:null,'completed'=>$count>=10?(int)query('SELECT COUNT(*) FROM attempts WHERE finished=1')->fetchColumn():null];
 }
 if($method==='POST'&&$path==='/admin/calendar'){
  requireAdmin(['admin','author']);$day=textField($b,'day',10,10);$d=DateTimeImmutable::createFromFormat('!Y-m-d',$day);if(!$d||$d->format('Y-m-d')!==$day)fail(422,'VALIDATION_ERROR','Sana noto‘g‘ri.');
  $existing=query('SELECT * FROM daily_bundles WHERE day=? FOR UPDATE',[$day])->fetch();
  if(isset($b['version'])&&$existing&&(!is_int($b['version'])||$b['version']!==(int)$existing['version']))fail(409,'VERSION_CONFLICT','Boshqa muharrir tahrir qildi. Yangilab ko‘ring.');
  $scenario=catalog()['scenarios'][textField($b,'scenario',80)]??fail(422,'VALIDATION_ERROR','Mashq topilmadi.');$l=findLesson($scenario['lesson']);
  $theme=isset($b['theme'])?textField($b,'theme',220):(!empty($existing['theme'])?$existing['theme']:$l['title']);
  $tip=isset($b['tip'])?textField($b,'tip',1000):(!empty($existing['tip'])?$existing['tip']:$l['rules'][0]['body']);
  $promise=isset($b['promise'])?textField($b,'promise',1000):(!empty($existing['promise'])?$existing['promise']:$l['action']);
  query("INSERT INTO daily_bundles(day,scenario,lesson,question_id,theme,tip,promise,state,author,reviewer) VALUES(?,?,?,?,?,?,?,'draft',?,NULL) ON DUPLICATE KEY UPDATE scenario=VALUES(scenario),lesson=VALUES(lesson),question_id=VALUES(question_id),theme=VALUES(theme),tip=VALUES(tip),promise=VALUES(promise),version=version+1,state='draft',author=VALUES(author),reviewer=NULL,updated_at=CURRENT_TIMESTAMP",[$day,$scenario['slug'],$l['slug'],$l['questionId'],$theme,$tip,$promise,$u['id']]);audit('calendar.draft',$day,['scenario'=>$scenario['slug'],'theme'=>$theme]);return ['saved'=>true,'version'=>$existing?(int)$existing['version']+1:1];
 }
 if($method==='POST'&&preg_match('~^/admin/calendar/([0-9]{4}-[0-9]{2}-[0-9]{2})/state$~',$path,$m)){
  $row=query('SELECT * FROM daily_bundles WHERE day=? FOR UPDATE',[$m[1]])->fetch();if(!$row)fail(404,'NOT_FOUND','Kunlik paket topilmadi.');
  if(isset($b['version'])&&(!is_int($b['version'])||$b['version']!==(int)$row['version']))fail(409,'VERSION_CONFLICT','Boshqa muharrir tahrir qildi. Yangilab ko‘ring.');
  $state=$b['state']??'';
  if($state==='review'){requireAdmin(['admin','author']);if($row['state']!=='draft')fail(409,'VERSION_CONFLICT','Avval draft kerak.');if(!$row['author'])fail(409,'AUTHOR_REQUIRED','Qoralamani muharrir tahrirlashi kerak.');}
  elseif($state==='approved'){requireAdmin(['admin','reviewer']);if($row['state']!=='review'||$row['author']===$u['id'])fail(409,'INDEPENDENT_REVIEW','Boshqa muharrirning mustaqil tekshiruvi kerak.');query('UPDATE daily_bundles SET reviewer=? WHERE day=?',[$u['id'],$m[1]]);}
  elseif($state==='published'){requireAdmin(['admin','publisher']);if($row['state']!=='approved'||!$row['reviewer'])fail(409,'REVIEW_REQUIRED','Avval mustaqil tekshiruv kerak.');$c=catalog();$s=$c['scenarios'][$row['scenario']]??null;$l=null;foreach($c['lessons'] as $item)if($item['slug']===$row['lesson'])$l=$item;$q=$c['questions'][$row['question_id']]??null;if(!$s||!$l||!$q||$s['lesson']!==$l['slug']||$q['lesson']!==$l['slug']||$q['topic']!==$s['topic'])fail(422,'VALIDATION_ERROR','Kunlik paket tarkibi yaroqsiz.');}
  elseif($state==='draft'){requireAdmin(['admin','author','publisher']);query('UPDATE daily_bundles SET reviewer=NULL WHERE day=?',[$m[1]]);}
  else fail(422,'VALIDATION_ERROR','Holat noto‘g‘ri.');
  query('UPDATE daily_bundles SET state=?,updated_at=CURRENT_TIMESTAMP WHERE day=?',[$state,$m[1]]);audit('calendar.'.$state,$m[1]);return ['saved'=>true];
 }
 if($method==='PUT'&&preg_match('~^/admin/content/([a-z0-9-]+)$~',$path,$m)){
  requireAdmin(['admin','author']);$row=query('SELECT * FROM content WHERE id=? FOR UPDATE',[$m[1]])->fetch();if(!$row)fail(404,'NOT_FOUND','Kontent topilmadi.');
  if(($b['version']??0)!==(int)$row['version'])fail(409,'VERSION_CONFLICT','Boshqa muharrir tahrir qildi. Yangilab ko‘ring.');
  $p=$b['payload']??[];if(!is_array($p))fail(422,'VALIDATION_ERROR','Kontent noto‘g‘ri.');
  validateContent($row['kind'],$m[1],$p);
  query('INSERT INTO content_revisions(content_id,actor,payload,version) VALUES(?,?,?,?)',[$m[1],$u['id'],$row['payload'],$row['version']]);
  query("UPDATE content SET payload=?,state='draft',author=?,reviewer=NULL,version=version+1,updated_at=CURRENT_TIMESTAMP WHERE id=?",[json($p),$u['id'],$m[1]]);audit('content.edit',$m[1]);return ['saved'=>true];
 }
 if($method==='POST'&&$path==='/admin/alerts'){
  requireAdmin(['admin','author']);$id='alert-'.substr(uuid(),0,8);$p=$b;$p['slug']=$id;validateContent('alert',$id,$p);
  query("INSERT INTO content(id,kind,payload,author) VALUES(?,'alert',?,?)",[$id,json($p),$u['id']]);audit('alert.create',$id);return ['id'=>$id];
 }
 if($method==='POST'&&preg_match('~^/admin/content/([a-z0-9-]+)/state$~',$path,$m)){
  $row=query('SELECT * FROM content WHERE id=? FOR UPDATE',[$m[1]])->fetch();if(!$row)fail(404,'NOT_FOUND','Kontent topilmadi.');$state=$b['state']??'';
  if($state==='review'){requireAdmin(['admin','author']);if($row['state']!=='draft')fail(409,'VERSION_CONFLICT','Avval draft kerak.');if(!$row['author'])fail(409,'AUTHOR_REQUIRED','Qoralamani muharrir tahrirlashi kerak.');}
  elseif($state==='approved'){requireAdmin(['admin','reviewer']);if($row['state']!=='review'||$row['author']===$u['id'])fail(409,'INDEPENDENT_REVIEW','Boshqa muharrirning mustaqil tekshiruvi kerak.');query('UPDATE content SET reviewer=? WHERE id=?',[$u['id'],$m[1]]);}
  elseif($state==='published'){requireAdmin(['admin','publisher']);if($row['state']!=='approved'||!$row['reviewer'])fail(409,'REVIEW_REQUIRED','Avval mustaqil tekshiruv kerak.');$payload=json_decode($row['payload'],true);validateContent($row['kind'],$row['id'],$payload);if($row['kind']==='alert')validateAlertPayload($payload,true);}
  elseif($state==='archived'){requireAdmin(['admin','publisher']);}
  else fail(422,'VALIDATION_ERROR','Holat noto‘g‘ri.');
  query('UPDATE content SET state=? WHERE id=?',[$state,$m[1]]);audit('content.'.$state,$m[1]);return ['saved'=>true];
 }
 fail(404,'NOT_FOUND','Muharrir amali topilmadi.');
}
function validateContent(string $kind,string $id,array $p): void {
 textField($p,'title',220);if(($p['slug']??'')!==$id)fail(422,'VALIDATION_ERROR','Kontent identifikatori o‘zgarmasin.');
 if($kind==='lesson'){
  $old=findLesson($id);if(($p['topic']??'')!==$old['topic']||($p['questionId']??'')!==$old['questionId'])fail(422,'VALIDATION_ERROR','Dars mavzusi yoki savoli noto‘g‘ri.');
  foreach(['summary','story','action'] as $key)textField($p,$key,3000);
  if(!isset($p['rules'])||count($p['rules'])!==3)fail(422,'VALIDATION_ERROR','Uchta qoida kerak.');foreach($p['rules'] as $r){textField($r,'title',200);textField($r,'body',2000);}
 }
 if($kind==='scenario'){
  if(!isset($p['scenes'][$p['start']??'']))fail(422,'VALIDATION_ERROR','Boshlang‘ich sahna kerak.');findLesson($p['lesson']??'');
  if(count($p['scenes'])>20)fail(422,'VALIDATION_ERROR','Sahnalar haddan tashqari ko‘p.');
  $walk=function(string $id,array $seen)use(&$walk,$p):void{if($id==='end')return;if(isset($seen[$id])||!isset($p['scenes'][$id]))fail(422,'VALIDATION_ERROR','Sahnalarda aylana yoki uzilish bor.');$seen[$id]=true;$s=$p['scenes'][$id];textField($s,'body',3000);if(count($s['choices']??[])<2||count($s['choices'])>4)fail(422,'VALIDATION_ERROR','2–4 ta tanlov kerak.');$ids=[];foreach($s['choices'] as $c){textField($c,'label',300);textField($c,'why',2000);if(isset($ids[$c['id']])||!is_bool($c['safe']??null))fail(422,'VALIDATION_ERROR','Tanlov identifikatori yoki bahosi noto‘g‘ri.');$ids[$c['id']]=true;$walk($c['next'],$seen);}};
  $walk($p['start'],[]);
 }
 if($kind==='alert'){
  validateAlertPayload($p);
 }
 if(strlen(json($p))>50000)fail(413,'PAYLOAD_TOO_LARGE','Kontent juda katta.');
}

try {
 if(!str_starts_with($_SERVER['REQUEST_URI'],'/api/v1/'))fail(404,'NOT_FOUND','API yo‘li mavjud emas.');
 if((int)($_SERVER['CONTENT_LENGTH']??0)>65536)fail(413,'PAYLOAD_TOO_LARGE','So‘rov juda katta.');
 if($method!=='GET'){
  $origin=$_SERVER['HTTP_ORIGIN']??'';$expected=getenv('APP_ORIGIN')?:'http://127.0.0.1:3000';
  $extraOrigin=is_file(ROOT.'/../.runtime/public-origin.txt')?trim((string)file_get_contents(ROOT.'/../.runtime/public-origin.txt')):'';
  $renderOrigin=rtrim((string)(getenv('RENDER_EXTERNAL_URL')?:''),'/');
  $allowedOrigins=array_filter([$expected,$extraOrigin,$renderOrigin,...($production?[]:['http://localhost:3000'])]);
  if($origin!==''&&!in_array($origin,$allowedOrigins,true))fail(403,'ORIGIN_REJECTED','So‘rov manbasiga ruxsat yo‘q.');
  if(!hash_equals($_SESSION['csrf'],$_SERVER['HTTP_X_CSRF_TOKEN']??''))fail(403,'CSRF_INVALID','Sessiyani yangilab, qayta urinib ko‘ring.');
  if(!str_starts_with($_SERVER['CONTENT_TYPE']??'','application/json'))fail(415,'JSON_REQUIRED','JSON so‘rov kerak.');
  $raw=file_get_contents('php://input');try{$body=json_decode($raw,true,64,JSON_THROW_ON_ERROR);}catch(JsonException){fail(400,'INVALID_JSON','So‘rov formati noto‘g‘ri.');}
  if(!is_array($body)||array_is_list($body)&&$body!==[])fail(400,'INVALID_JSON','Obyekt ko‘rinishidagi so‘rov kerak.');
  rateLimit('mutate',180,60);
  $idem=!str_starts_with($path,'/auth')&&!str_starts_with($path,'/admin/mfa')&&$path!=='/me';
  if($idem){
   $eventKey=$_SERVER['HTTP_IDEMPOTENCY_KEY']??'';if(!preg_match('/^[a-zA-Z0-9-]{16,80}$/',$eventKey))fail(422,'IDEMPOTENCY_REQUIRED','Amal identifikatori kerak.');
   $actor=owner();$hash=hash('sha256',$method.$path.json($body));db()->beginTransaction();
   query('INSERT IGNORE INTO idempotency_keys(owner,event_key,request_hash) VALUES(?,?,?)',[$actor,$eventKey,$hash]);
   $existing=query('SELECT * FROM idempotency_keys WHERE owner=? AND event_key=? FOR UPDATE',[$actor,$eventKey])->fetch();
   if($existing['request_hash']!==$hash)fail(409,'IDEMPOTENCY_CONFLICT','Bu amal identifikatori boshqa so‘rovga tegishli.');
   if($existing['response']!==null){$result=json_decode($existing['response'],true);db()->commit();echo json(['ok'=>true,'data'=>$result,'meta'=>['request_id'=>$requestId,'version'=>1,'server_time'=>gmdate(DATE_ATOM)]]);exit;}
  }else{db()->beginTransaction();}
 }
 $result=dispatch($method,$path,$body);
 if($idem)query('UPDATE idempotency_keys SET response=? WHERE owner=? AND event_key=?',[json($result),$actor,$eventKey]);
 if(db()->inTransaction())db()->commit();
 echo json(['ok'=>true,'data'=>$result,'meta'=>['request_id'=>$requestId,'server_time'=>gmdate(DATE_ATOM),'version'=>1]]);
} catch(Throwable $e){
 try{if(db()->inTransaction())db()->rollBack();}catch(Throwable){}
 $status=$e instanceof ApiError?$e->status:503;$key=$e instanceof ApiError?$e->key:'SERVICE_UNAVAILABLE';
 http_response_code($status);if(!$e instanceof ApiError)error_log('['.$requestId.'] '.$e->getMessage());
 echo json(['ok'=>false,'error'=>['code'=>$key,'message'=>$e instanceof ApiError?$e->getMessage():'Xizmatga ulanishda muammo. Qayta urinib ko‘ring.'],'meta'=>['request_id'=>$requestId]]);
}
