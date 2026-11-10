<?php
declare(strict_types=1);

function validateAlertPayload(array $p, bool $publishing=false): void {
 foreach(['summary','sourceName','sourceUrl'] as $key)textField($p,$key,3000);
 $url=filter_var($p['sourceUrl'],FILTER_VALIDATE_URL);
 $parts=$url?parse_url($url):false;
 $host=is_array($parts)?($parts['host']??''):'';
 if(!$url||!$parts||strtolower($parts['scheme']??'')!=='https'||isset($parts['user'])||isset($parts['pass'])||isset($parts['port'])||filter_var($host,FILTER_VALIDATE_IP)||!filter_var($host,FILTER_VALIDATE_DOMAIN,FILTER_FLAG_HOSTNAME)||!str_contains($host,'.')||str_ends_with(strtolower($host),'.local'))fail(422,'VALIDATION_ERROR','Ochiq HTTPS asl manba havolasi kerak.');
 foreach(['publishedAt','verifiedAt','activeFrom','activeUntil'] as $key){
  textField($p,$key,10,10);
  $d=DateTimeImmutable::createFromFormat('!Y-m-d',$p[$key]);
  if(!$d||$d->format('Y-m-d')!==$p[$key])fail(422,'VALIDATION_ERROR','Sanani to‘g‘ri kiriting.');
 }
 $today=date('Y-m-d');
 if($p['publishedAt']>$p['verifiedAt']||$p['verifiedAt']>$today||$p['activeFrom']>$p['activeUntil']||$p['publishedAt']>$today)fail(422,'VALIDATION_ERROR','Sana oralig‘i noto‘g‘ri.');
 if($publishing&&($p['verifiedAt']<$today||$p['activeUntil']<$today))fail(422,'VALIDATION_ERROR','Nashr oldidan manbani bugun qayta tekshiring va amal muddatini yangilang.');
 if(!is_array($p['steps']??null)||count($p['steps'])<1||count($p['steps'])>8)fail(422,'VALIDATION_ERROR','Amaliy qadamlar kerak.');
 foreach($p['steps'] as $step)if(!is_string($step)||mb_strlen(trim($step))<1||mb_strlen($step)>1000)fail(422,'VALIDATION_ERROR','Qadam matni noto‘g‘ri.');
}
