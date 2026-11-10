<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
if(PHP_SAPI!=='cli')exit(1);

$seed=require ROOT.'/content/catalog.php';
$slugs=array_keys($seed['scenarios']);
$rows=query('SELECT day,scenario,lesson,question_id,version,state FROM daily_bundles ORDER BY day')->fetchAll();
if(!$rows){echo "No calendar assignments found.\n";exit;}
$first=new DateTimeImmutable($rows[0]['day']);
$changed=0;
$pdo=db();
$pdo->beginTransaction();
try {
 foreach($rows as $row){
  $day=new DateTimeImmutable($row['day']);
  if($day<=new DateTimeImmutable('today')||$row['state']!=='published'||(int)$row['version']!==1)continue;
  $offset=(int)$first->diff($day)->format('%r%a');
  if($offset<0||$offset>=30)continue;
  $s=$seed['scenarios'][$slugs[$offset%count($slugs)]];
  if($row['scenario']!==$s['slug']||$row['lesson']!==$s['lesson']||$row['question_id']!=='q-'.$s['lesson'])continue;
  if(query("SELECT 1 FROM audit_events WHERE action='calendar.publish' AND object_id=? LIMIT 1",[$row['day']])->fetch())continue;
  query("UPDATE daily_bundles SET state='draft' WHERE day=? AND scenario=? AND lesson=? AND question_id=? AND version=1 AND state='published'",[$row['day'],$row['scenario'],$row['lesson'],$row['question_id']]);
  $changed++;
 }
 $pdo->commit();
 echo "Moved $changed untouched future rotation examples to draft. Reviewed days were preserved.\n";
} catch(Throwable $e) {$pdo->rollBack();throw $e;}
