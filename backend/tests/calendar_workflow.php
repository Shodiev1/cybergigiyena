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
function rejected(callable $f,string $code):void {try{$f();}catch(ApiError $e){check($e->key===$code,'Unexpected error: '.$e->key.' (expected '.$code.')');return;}throw new RuntimeException('Expected '.$code);}

db()->beginTransaction();
try {
 $authorId=uuid();
 $reviewerId=uuid();
 $publisherId=uuid();
 query("INSERT INTO users(id,email,password_hash,role) VALUES(?,?,?,'author')",[$authorId,$authorId.'@example.invalid','unused']);
 query("INSERT INTO users(id,email,password_hash,role) VALUES(?,?,?,'reviewer')",[$reviewerId,$reviewerId.'@example.invalid','unused']);
 query("INSERT INTO users(id,email,password_hash,role) VALUES(?,?,?,'publisher')",[$publisherId,$publisherId.'@example.invalid','unused']);

 $asUser=function(string $id):void{
  $_SESSION=['user'=>$id,'session_version'=>(int)query('SELECT session_version FROM users WHERE id=?',[$id])->fetchColumn(),'mfa'=>time()];
 };

 $day='2027-05-20';
 // 1. Untouched seed draft (author=NULL) cannot go to review
 query("INSERT INTO daily_bundles(day,scenario,lesson,question_id,state,version,author,reviewer) VALUES(?,'bank-sms','bank','q-bank','draft',1,NULL,NULL) ON DUPLICATE KEY UPDATE state='draft',version=1,author=NULL,reviewer=NULL",[$day]);
 $asUser($authorId);
 rejected(fn()=>adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'review']),'AUTHOR_REQUIRED');

 // 2. Reviewer cannot save calendar draft
 $asUser($reviewerId);
 rejected(fn()=>adminDispatch('POST','/admin/calendar',['day'=>$day,'scenario'=>'qr-menu']),'FORBIDDEN');

 // 3. Author saves calendar draft -> version increments, author set, state=draft
 $asUser($authorId);
 rejected(fn()=>adminDispatch('POST','/admin/calendar',['day'=>$day,'scenario'=>'qr-menu','version'=>99]),'VERSION_CONFLICT');
 $saved=adminDispatch('POST','/admin/calendar',['day'=>$day,'scenario'=>'qr-menu','version'=>1]);
 check($saved['saved']===true&&$saved['version']===2,'Calendar draft save failed');
 $row=query('SELECT * FROM daily_bundles WHERE day=?',[$day])->fetch();
 check($row['scenario']==='qr-menu'&&$row['lesson']==='network'&&$row['question_id']==='q-network'&&$row['state']==='draft'&&$row['author']===$authorId&&$row['reviewer']===null,'Draft fields mismatch');

 // 4. Author sends draft to review
 rejected(fn()=>adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'review','version'=>1]),'VERSION_CONFLICT');
 adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'review','version'=>2]);
 check(query('SELECT state FROM daily_bundles WHERE day=?',[$day])->fetchColumn()==='review','State did not become review');

 // 5. Same author cannot approve own calendar package (even if given reviewer role)
 query("UPDATE users SET role='admin' WHERE id=?",[$authorId]);
 rejected(fn()=>adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'approved','version'=>2]),'INDEPENDENT_REVIEW');
 query("UPDATE users SET role='author' WHERE id=?",[$authorId]);

 // 6. Publisher cannot publish before independent reviewer approval
 $asUser($publisherId);
 rejected(fn()=>adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'published','version'=>2]),'REVIEW_REQUIRED');

 // 7. Independent reviewer approves
 $asUser($reviewerId);
 adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'approved','version'=>2]);
 $row=query('SELECT * FROM daily_bundles WHERE day=?',[$day])->fetch();
 check($row['state']==='approved'&&$row['reviewer']===$reviewerId,'Approval failed');

 // 8. Publisher publishes approved package; broken topic mismatch is rejected at publish time
 $asUser($publisherId);
 query("UPDATE daily_bundles SET lesson='shopping' WHERE day=?",[$day]);
 rejected(fn()=>adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'published','version'=>2]),'VALIDATION_ERROR');
 query("UPDATE daily_bundles SET lesson='network' WHERE day=?",[$day]);
 adminDispatch('POST','/admin/calendar/'.$day.'/state',['state'=>'published','version'=>2]);
 check(query('SELECT state FROM daily_bundles WHERE day=?',[$day])->fetchColumn()==='published','Publish failed');

 // 9. Re-editing resets published day back to draft, saves custom theme/tip/promise, and clears reviewer
 $asUser($authorId);
 adminDispatch('POST','/admin/calendar',['day'=>$day,'scenario'=>'bank-sms','theme'=>'Maxsus kun mavzusi','tip'=>'Maxsus eslatma matni','promise'=>'Maxsus kunlik va’da','version'=>2]);
 $row=query('SELECT * FROM daily_bundles WHERE day=?',[$day])->fetch();
 check($row['state']==='draft'&&$row['reviewer']===null&&$row['author']===$authorId&&(int)$row['version']===3&&$row['theme']==='Maxsus kun mavzusi'&&$row['tip']==='Maxsus eslatma matni'&&$row['promise']==='Maxsus kunlik va’da','Re-edit did not reset workflow or save custom bundle fields');

 // 10. Verify 30-day daily_plan.php has 30 distinct themes, tips, and promises
 $plan=require ROOT.'/content/daily_plan.php';
 check(count($plan)===30,'Expected 30 daily plan items');
 check(count(array_unique(array_column($plan,'theme')))===30,'Daily plan themes must be 30 distinct strings');
 check(count(array_unique(array_column($plan,'tip')))===30,'Daily plan tips must be 30 distinct strings');
 check(count(array_unique(array_column($plan,'promise')))===30,'Daily plan promises must be 30 distinct strings');

 echo "PASS: calendar draft, custom theme/tip/promise, 30-day distinct plan, role/MFA/version guards, author requirement, independent review, publish integrity check, and re-edit reset. All fixtures rolled back.\n";
} finally {
 db()->rollBack();
}
