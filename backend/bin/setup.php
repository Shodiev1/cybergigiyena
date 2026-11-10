<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
if(PHP_SAPI!=='cli')exit(1);
if(!is_file(ROOT.'/../.runtime/config.json')){
 $root=new PDO('mysql:host=127.0.0.1;port=3308;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
 $pass=bin2hex(random_bytes(24));$rootPass=bin2hex(random_bytes(24));
 $root->exec('CREATE DATABASE IF NOT EXISTS cybergigiyena CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
 $root->exec("CREATE USER IF NOT EXISTS 'cg_app'@'127.0.0.1' IDENTIFIED BY '$pass'");
 $root->exec("GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, REFERENCES ON cybergigiyena.* TO 'cg_app'@'127.0.0.1'");
 $root->exec("ALTER USER 'root'@'localhost' IDENTIFIED BY '$rootPass'");
 file_put_contents(ROOT.'/../.runtime/config.json',json(['dsn'=>'mysql:host=127.0.0.1;port=3308;dbname=cybergigiyena;charset=utf8mb4','user'=>'cg_app','password'=>$pass]));
 file_put_contents(ROOT.'/../.runtime/mysql-admin.json',json(['password'=>$rootPass]));
}
$sql=file_get_contents(ROOT.'/migrations/001_schema.sql');foreach(explode(';',$sql) as $stmt)if(trim($stmt)!=='')db()->exec($stmt);
if(!query("SHOW COLUMNS FROM users LIKE 'session_version'")->fetch())db()->exec('ALTER TABLE users ADD COLUMN session_version INT NOT NULL DEFAULT 1');
if(!query("SHOW COLUMNS FROM users LIKE 'mfa_last_tick'")->fetch())db()->exec('ALTER TABLE users ADD COLUMN mfa_last_tick BIGINT NOT NULL DEFAULT 0');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'author'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN author CHAR(36) NULL');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'reviewer'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN reviewer CHAR(36) NULL');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'updated_at'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'theme'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN theme VARCHAR(220) NULL');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'tip'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN tip TEXT NULL');
if(!query("SHOW COLUMNS FROM daily_bundles LIKE 'promise'")->fetch())db()->exec('ALTER TABLE daily_bundles ADD COLUMN promise TEXT NULL');
$seed=require ROOT.'/content/catalog.php';
$plan=require ROOT.'/content/daily_plan.php';
foreach($seed['lessons'] as $l)query("INSERT IGNORE INTO content(id,kind,payload,state) VALUES(?,'lesson',?,'published')",[$l['slug'],json($l)]);
foreach($seed['scenarios'] as $s)query("INSERT IGNORE INTO content(id,kind,payload,state) VALUES(?,'scenario',?,'published')",[$s['slug'],json($s)]);
// 30-day distinct editorial plan: seeded as unreviewed drafts until editor + independent reviewer approve.
foreach(range(0,29) as $i){
 $day=(new DateTimeImmutable('today'))->modify('+'.$i.' days')->format('Y-m-d');
 $entry=$plan[$i%count($plan)];
 $s=$seed['scenarios'][$entry['scenario']];
 query("INSERT IGNORE INTO daily_bundles(day,scenario,lesson,question_id,theme,tip,promise,state) VALUES(?,?,?,?,?,?,?,'draft')",[$day,$s['slug'],$s['lesson'],'q-'.$s['lesson'],$entry['theme'],$entry['tip'],$entry['promise']]);
 query("UPDATE daily_bundles SET scenario=?,lesson=?,question_id=?,theme=?,tip=?,promise=? WHERE day=? AND version=1 AND author IS NULL AND reviewer IS NULL AND theme IS NULL",[$s['slug'],$s['lesson'],'q-'.$s['lesson'],$entry['theme'],$entry['tip'],$entry['promise'],$day]);
}
echo "MySQL schema and seed ready: 12 lessons, 5 scenarios, 30 distinct daily plan drafts.\n";
