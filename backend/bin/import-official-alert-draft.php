<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../src/alert_validation.php';
if(PHP_SAPI!=='cli')exit(1);
$p=json_decode(file_get_contents(ROOT.'/content/editorial/telegram-code-call.json'),true,512,JSON_THROW_ON_ERROR);
validateAlertPayload($p);
$id=$p['slug'];
if(!preg_match('/^[a-z0-9-]+$/',$id))throw new RuntimeException('Invalid alert id');
$pdo=db();$pdo->beginTransaction();
try {
 $existing=query('SELECT id FROM content WHERE id=? FOR UPDATE',[$id])->fetch();
 if(!$existing){
  query("INSERT INTO content(id,kind,payload,state) VALUES(?,'alert',?,'draft')",[$id,json($p)]);
  query('INSERT INTO audit_events(actor,action,object_id,detail) VALUES(?,?,?,?)',['00000000-0000-4000-8000-000000000000','alert.seed_draft',$id,json(['source'=>$p['sourceUrl']])]);
 }
 $pdo->commit();
 echo $existing?"Alert draft already exists; no changes.\n":"Imported official-source alert as draft; editor and independent review required.\n";
} catch(Throwable $e){$pdo->rollBack();throw $e;}
