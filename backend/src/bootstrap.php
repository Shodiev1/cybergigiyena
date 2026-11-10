<?php
declare(strict_types=1);
date_default_timezone_set('Asia/Tashkent');
const ROOT = __DIR__ . '/..';
ini_set('error_log', ROOT.'/var/error.log');
function config(): array {
 static $config;
 return $config ??= (is_file(ROOT.'/../.runtime/config.json') ? json_decode(file_get_contents(ROOT.'/../.runtime/config.json'),true,512,JSON_THROW_ON_ERROR) : []);
}
function db(): PDO {
 static $pdo;
 if (!$pdo) {
  $c=config();
  $pdo=new PDO(getenv('DB_DSN') ?: ($c['dsn'] ?? 'mysql:host=127.0.0.1;port=3308;dbname=cybergigiyena;charset=utf8mb4'),getenv('DB_USER') ?: ($c['user'] ?? 'cg_app'),getenv('DB_PASSWORD') ?: ($c['password'] ?? ''),[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);
  $pdo->exec("SET time_zone = '+00:00'");
 }
 return $pdo;
}
function query(string $sql,array $values=[]): PDOStatement { $s=db()->prepare($sql);$s->execute($values);return $s; }
function uuid(): string { $b=random_bytes(16);$b[6]=chr((ord($b[6])&15)|64);$b[8]=chr((ord($b[8])&63)|128);$h=bin2hex($b);return substr($h,0,8).'-'.substr($h,8,4).'-'.substr($h,12,4).'-'.substr($h,16,4).'-'.substr($h,20); }
function json(mixed $v): string { return json_encode($v,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR); }
final class ApiError extends RuntimeException {public function __construct(public int $status, public string $key,string $message){parent::__construct($message);}}
function fail(int $status,string $key,string $message): never {throw new ApiError($status,$key,$message);}
function textField(array $b,string $key,int $max=500,int $min=1): string {
 $v=$b[$key]??null;
 if(!is_string($v)||mb_strlen(trim($v))<$min||mb_strlen($v)>$max)fail(422,'VALIDATION_ERROR',"Maydonni to‘g‘ri to‘ldiring: $key");
 return trim($v);
}
function currentUser(): ?array {
 if(empty($_SESSION['user']))return null;
 $u=query('SELECT id,email,name,role,blocked,session_version FROM users WHERE id=?',[$_SESSION['user']])->fetch();
 if(!$u||$u['blocked']||(int)$u['session_version']!==($_SESSION['session_version']??0)){unset($_SESSION['user'],$_SESSION['mfa']);return null;}
 unset($u['blocked'],$u['session_version']); return $u;
}
function owner(): string {return ($u=currentUser()) ? 'u:'.$u['id'] : 'g:'.$_SESSION['guest'];}
function requireUser(): array {return currentUser()??fail(401,'UNAUTHENTICATED','Davom etish uchun hisobingizga kiring.');}
function requireAdmin(array $roles=['admin','author','reviewer','publisher','analyst']): array {
 $u=requireUser();
 if(!in_array($u['role'],$roles,true))fail(403,'FORBIDDEN','Bu bo‘limga kirish huquqi yo‘q.');
 if(empty($_SESSION['mfa'])||time()-$_SESSION['mfa']>1800)fail(403,'MFA_REQUIRED','Muharrir paneli uchun ikki bosqichli tasdiqlash kerak.');
 return $u;
}
function rateLimit(string $name,int $limit,int $seconds=60): void {
 $bucket=hash('sha256',$name.'|'.($_SERVER['REMOTE_ADDR']??'local')); $window=(int)(time()/$seconds);
 query('INSERT INTO rate_limits(bucket,window_start,hits) VALUES(?,?,1) ON DUPLICATE KEY UPDATE hits=hits+1',[$bucket,$window]);
 if((int)query('SELECT hits FROM rate_limits WHERE bucket=? AND window_start=?',[$bucket,$window])->fetchColumn()>$limit){header('Retry-After: '.$seconds);fail(429,'TOO_MANY_REQUESTS','Birozdan keyin qayta urinib ko‘ring.');}
}
function catalog(): array {
 static $catalog;
 if($catalog)return $catalog;
 $catalog=require ROOT.'/content/catalog.php';
 foreach(query("SELECT id,kind,payload,version,state FROM content")->fetchAll() as $r){
  if($r['state']==='archived'){
   if($r['kind']==='scenario')unset($catalog['scenarios'][$r['id']]);
   if($r['kind']==='lesson')$catalog['lessons']=array_values(array_filter($catalog['lessons'],fn($l)=>$l['slug']!==$r['id']));
   continue;
  }
  if($r['state']!=='published')continue;
  $p=json_decode($r['payload'],true);$p['version']=(int)$r['version'];
  if($r['kind']==='lesson'){foreach($catalog['lessons'] as &$l){if($l['slug']===$r['id'])$l=$p;}unset($l);}
  if($r['kind']==='scenario')$catalog['scenarios'][$r['id']]=$p;
 }
 return $catalog;
}
function publicQuestion(array $q): array {unset($q['correct'],$q['explanation']);return $q;}
function publicScenario(array $s): array {unset($s['scenes'],$s['start']);return $s;}
function publicScene(array $s): array {$s['choices']=array_map(fn($c)=>['id'=>$c['id'],'label'=>$c['label']],$s['choices']);return $s;}
function findLesson(string $slug): array {foreach(catalog()['lessons'] as $l)if($l['slug']===$slug)return $l;fail(404,'NOT_FOUND','Dars topilmadi.');}
function activeAlerts(): array {
 $alerts=[];
 foreach(query("SELECT payload FROM content WHERE kind='alert' AND state='published' ORDER BY updated_at DESC")->fetchAll() as $row){
  $a=json_decode($row['payload'],true);
  if(($a['activeFrom']??'')<=date('Y-m-d')&&($a['activeUntil']??'')>=date('Y-m-d'))$alerts[]=$a;
 }
 return $alerts;
}
function daily(): array {
 $c=catalog(); $day=date('Y-m-d');
 $row=query("SELECT * FROM daily_bundles WHERE day=? AND state='published'",[$day])->fetch();
 $lessons=array_column($c['lessons'],null,'slug');
 $s=$row ? ($c['scenarios'][$row['scenario']]??null) : null;
 $l=$row ? ($lessons[$row['lesson']]??null) : null;
 $q=$row ? ($c['questions'][$row['question_id']]??null) : null;
 $valid=$s && $l && $q && $s['lesson']===$l['slug'] && $q['lesson']===$l['slug'] && $q['topic']===$s['topic'];
 if(!$valid){
  $slugs=array_keys($c['scenarios']);
  if(!$slugs)fail(503,'CONTENT_UNAVAILABLE','Bugungi mashq vaqtincha mavjud emas. SOS yordamidan foydalanishingiz mumkin.');
  $index=((int)(new DateTimeImmutable($day))->diff(new DateTimeImmutable('2026-11-05'))->days)%count($slugs);
  $s=$c['scenarios'][$slugs[$index]];
  $l=$lessons[$s['lesson']]??null;
  $q=$l ? ($c['questions'][$l['questionId']]??null) : null;
  if(!$l||!$q)fail(503,'CONTENT_UNAVAILABLE','Bugungi mashq vaqtincha mavjud emas. SOS yordamidan foydalanishingiz mumkin.');
 }
 $theme=$valid&&!empty($row['theme'])?$row['theme']:$l['title'];
 $tip=$valid&&!empty($row['tip'])?$row['tip']:$l['rules'][0]['body'];
 $promise=$valid&&!empty($row['promise'])?$row['promise']:$l['action'];
 $alerts=activeAlerts();
 return ['date'=>$day,'timezone'=>'Asia/Tashkent','bundle_id'=>'UZ-'.$day.($valid?'-v'.$row['version']:'-fallback'),'version'=>$valid?(int)$row['version']:0,'fallback'=>!$valid,'theme'=>$theme,'mission'=>publicScenario($s),'lesson'=>$l,'question'=>publicQuestion($q),'tip'=>$tip,'promise'=>$promise,'alert'=>$alerts[0]??null];
}
function attemptView(array $a): array {
 $s=json_decode($a['snapshot'],true);$choices=json_decode($a['choices'],true);
 return ['id'=>$a['id'],'scenario'=>publicScenario($s),'step'=>count($choices),'finished'=>(bool)$a['finished'],'ready'=>$a['current_scene']==='end','scene'=>$a['current_scene']==='end'?null:publicScene($s['scenes'][$a['current_scene']]),'feedback'=>$choices,'lesson'=>$s['lesson']];
}
function ownedAttempt(string $id,bool $lock=false): array { $a=query('SELECT * FROM attempts WHERE id=? AND owner=?'.($lock?' FOR UPDATE':''),[$id,owner()])->fetch();return $a?:fail(404,'NOT_FOUND','Mashq topilmadi. Uni qaytadan boshlashingiz mumkin.'); }
function audit(string $action,string $object,array $detail=[]): void {query('INSERT INTO audit_events(actor,action,object_id,detail) VALUES(?,?,?,?)',[requireUser()['id'],$action,$object,json($detail)]);}
function totp(string $secret,string $code): bool {
 if(!preg_match('/^[0-9]{6}$/',$code))return false;
 $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';$bits='';foreach(str_split($secret) as $ch){$p=strpos($alphabet,$ch);if($p===false)return false;$bits.=str_pad(decbin($p),5,'0',STR_PAD_LEFT);}
 $key='';foreach(str_split($bits,8) as $byte)if(strlen($byte)===8)$key.=chr(bindec($byte));
 for($i=-1;$i<=1;$i++){ $tick=(int)floor(time()/30)+$i;$h=hash_hmac('sha1',pack('N2',0,$tick),$key,true);$offset=ord($h[19])&15;$n=unpack('N',substr($h,$offset,4))[1]&0x7fffffff;if(hash_equals(str_pad((string)($n%1000000),6,'0',STR_PAD_LEFT),$code))return true; }
 return false;
}
