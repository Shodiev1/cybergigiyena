<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../src/alert_validation.php';
// Exercise the actual dispatch/validation functions without running the HTTP entry point.
$source=file_get_contents(__DIR__.'/../public/index.php');
$start=strpos($source,'function adminDispatch(');
$end=strpos($source,"\ntry {",$start);
eval(substr($source,$start,$end-$start));
function check(bool $ok,string $message):void {if(!$ok)throw new RuntimeException($message);}
function rejected(callable $f,string $code):void {try{$f();}catch(ApiError $e){check($e->key===$code,'Unexpected error: '.$e->key);return;}throw new RuntimeException('Expected '.$code);}
db()->beginTransaction();
try {
 $uid=uuid();query("INSERT INTO users(id,email,password_hash,role) VALUES(?,?,?,'author')",[$uid,$uid.'@example.invalid','unused']);
 $_SESSION=['user'=>$uid,'session_version'=>(int)query('SELECT session_version FROM users WHERE id=?', [$uid])->fetchColumn(),'mfa'=>time()];
 $id='revision-test-'.substr(uuid(),0,8);
 $p=['slug'=>$id,'title'=>'Old title','summary'=>'Test summary','sourceName'=>'Test source','sourceUrl'=>'https://gov.uz/test','publishedAt'=>'2026-11-01','verifiedAt'=>'2026-11-06','activeFrom'=>'2026-11-01','activeUntil'=>'2026-12-01','steps'=>['Safe action']];
 query("INSERT INTO content(id,kind,payload,state,version,author,reviewer) VALUES(?,'alert',?,'approved',2,?,?)",[$id,json(array_replace($p,['title'=>'Current title'])),$uid,uuid()]);
 query('INSERT INTO content_revisions(content_id,actor,payload,version) VALUES(?,?,?,1)',[$id,$uid,json($p)]);$rid=(int)db()->lastInsertId();
 $path='/admin/content/'.$id;
 $history=adminDispatch('GET',$path.'/revisions',[]);check(count($history)===1&&$history[0]['payload']['title']==='Old title','History failed');
 rejected(fn()=>adminDispatch('POST',$path.'/restore',['revisionId'=>$rid,'version'=>1]),'VERSION_CONFLICT');
 rejected(fn()=>adminDispatch('POST',$path.'/restore',['revisionId'=>$rid+1,'version'=>2]),'NOT_FOUND');
 query("UPDATE users SET role='reviewer' WHERE id=?",[$uid]);
 rejected(fn()=>adminDispatch('POST',$path.'/restore',['revisionId'=>$rid,'version'=>2]),'FORBIDDEN');
 query("UPDATE users SET role='author' WHERE id=?",[$uid]);unset($_SESSION['mfa']);
 rejected(fn()=>adminDispatch('GET',$path.'/revisions',[]),'MFA_REQUIRED');$_SESSION['mfa']=time();
 $restored=adminDispatch('POST',$path.'/restore',['revisionId'=>$rid,'version'=>2]);
 $r=query('SELECT * FROM content WHERE id=?',[$id])->fetch();
 check($restored['version']===3&&$r['state']==='draft'&&$r['reviewer']===null&&$r['author']===$uid,'Workflow reset failed');
 check(json_decode($r['payload'],true)['title']==='Old title','Payload restore failed');
 check((int)query('SELECT COUNT(*) FROM content_revisions WHERE content_id=?',[$id])->fetchColumn()===2,'Current revision lost');
 check((int)query("SELECT COUNT(*) FROM audit_events WHERE object_id=? AND action='content.restore'",[$id])->fetchColumn()===1,'Audit missing');
 rejected(fn()=>adminDispatch('POST',$path.'/restore',['revisionId'=>$rid,'version'=>2]),'VERSION_CONFLICT');
 query("UPDATE users SET role='member' WHERE id=?",[$uid]);
 rejected(fn()=>adminDispatch('GET',$path.'/revisions',[]),'FORBIDDEN');
 echo "PASS: revision history, restore, role/MFA guards, stale versions, draft/reviewer reset, preserved current payload, audit. All fixtures rolled back.\n";
} finally {db()->rollBack();}

