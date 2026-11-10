<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
$id='alert-uzcert-telegram-code-20260707';
$row=query('SELECT kind,state,author,reviewer,payload FROM content WHERE id=?',[$id])->fetch();
if(!$row||$row['kind']!=='alert'||$row['state']!=='draft'||$row['author']!==null||$row['reviewer']!==null)throw new RuntimeException('Alert did not remain an unreviewed draft');
$payload=json_decode($row['payload'],true,512,JSON_THROW_ON_ERROR);
if($payload['sourceUrl']!=='https://gov.uz/oz/gurlan/news/view/189014')throw new RuntimeException('Official source mismatch');
$public=query("SELECT COUNT(*) FROM content WHERE id=? AND state='published'",[$id])->fetchColumn();
if((int)$public!==0)throw new RuntimeException('Draft appears in published content');
$events=query("SELECT COUNT(*) FROM audit_events WHERE action='alert.seed_draft' AND object_id=?",[$id])->fetchColumn();
if((int)$events!==1)throw new RuntimeException('Import is not idempotent or not audited');
echo "PASS: Official alert is a single audited, unreviewed draft and is not published.\n";
