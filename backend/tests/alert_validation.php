<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
require __DIR__.'/../src/alert_validation.php';

$today=date('Y-m-d');
$valid=[
 'summary'=>'Telegram orqali kod so‘ralganda mustaqil tekshiring.',
 'sourceName'=>'Gurlan tumani hokimligi / UZCERT',
 'sourceUrl'=>'https://gov.uz/oz/gurlan/news/view/189014',
 'publishedAt'=>'2026-07-07',
 'verifiedAt'=>$today,
 'activeFrom'=>$today,
 'activeUntil'=>$today,
 'steps'=>['Kod va parolni bermang.'],
];
validateAlertPayload($valid,true);
$bad=[
 ['sourceUrl'=>'https://user:secret@gov.uz/oz/gurlan/news/view/189014'],
 ['sourceUrl'=>'https://127.0.0.1/alert'],
 ['sourceUrl'=>'https://gov.uz:8443/alert'],
 ['sourceUrl'=>'http://gov.uz/alert'],
 ['verifiedAt'=>'2026-01-01'],
 ['publishedAt'=>'2026-13-01'],
 ['steps'=>['  ']],
];
foreach($bad as $case){
 try {validateAlertPayload(array_replace($valid,$case),true);} catch(ApiError $e){if($e->status===422)continue;}
 throw new RuntimeException('Invalid alert was accepted: '.json($case));
}
$stale=$valid;$stale['verifiedAt']='2026-07-07';
validateAlertPayload($stale);
try {validateAlertPayload($stale,true);throw new RuntimeException('Stale review was published');} catch(ApiError $e){if($e->status!==422)throw $e;}
echo "PASS: Alert source, dates, steps and publish freshness are validated.\n";
