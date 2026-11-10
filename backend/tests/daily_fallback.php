<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';

$pdo=db();
$pdo->beginTransaction();
try {
 $day=date('Y-m-d');
 query("INSERT INTO daily_bundles(day,scenario,lesson,question_id,state) VALUES(?,'missing-scenario','bank','q-bank','published') ON DUPLICATE KEY UPDATE scenario=VALUES(scenario),lesson=VALUES(lesson),question_id=VALUES(question_id),state='published'",[$day]);
 $fallback=daily();
 if(!$fallback['fallback']||!isset($fallback['mission']['slug'],$fallback['question']['id'],$fallback['lesson']['slug'])||!str_ends_with($fallback['bundle_id'],'-fallback'))throw new RuntimeException('Invalid scenario did not produce a usable, labelled fallback');
 query("UPDATE daily_bundles SET scenario='bank-sms',lesson='shopping',question_id='q-shopping' WHERE day=?",[$day]);
 if(!daily()['fallback'])throw new RuntimeException('Mismatched topic was published as a daily package');
 query("UPDATE daily_bundles SET scenario='bank-sms',lesson='bank',question_id='q-bank',theme='Kunlik maxsus mavzu',tip='Kunlik maxsus maslahat',promise='Kunlik maxsus va’da' WHERE day=?",[$day]);
 $validDaily=daily();
 if($validDaily['fallback']||$validDaily['theme']!=='Kunlik maxsus mavzu'||$validDaily['tip']!=='Kunlik maxsus maslahat'||$validDaily['promise']!=='Kunlik maxsus va’da')throw new RuntimeException('Valid package or custom theme/tip/promise was not returned');
 $alertPayload=['slug'=>'test-active-alert','title'=>'Faol rasmiy ogohlantirish','summary'=>'Qisqa izoh','sourceName'=>'UZCERT','sourceUrl'=>'https://csec.uz/uz/news/test','publishedAt'=>$day,'verifiedAt'=>$day,'activeFrom'=>$day,'activeUntil'=>$day,'steps'=>['1-qadam']];
 query("INSERT INTO content(id,kind,payload,state) VALUES('test-active-alert','alert',?,'published')", [json($alertPayload)]);
 $withAlert=daily();
 if(($withAlert['alert']['slug']??null)!=='test-active-alert')throw new RuntimeException('Active published alert was not attached to daily bundle');
 echo "PASS: Missing scenario and mismatched topic use labelled fallback; valid package, custom theme/tip/promise, and active alert are accepted.\n";
} finally {
 $pdo->rollBack();
}
