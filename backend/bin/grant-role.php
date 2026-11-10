<?php
declare(strict_types=1);
require __DIR__.'/../src/bootstrap.php';
if(PHP_SAPI!=='cli'||count($argv)!==3){fwrite(STDERR,"Usage: php backend/bin/grant-role.php email role (TOTP secret on stdin)\n");exit(1);}
[$script,$email,$role]=$argv;if(!in_array($role,['admin','author','reviewer','publisher','analyst'],true))exit(1);
$secret=trim(fgets(STDIN));if(!preg_match('/^[A-Z2-7]{32}$/',$secret)){fwrite(STDERR,"A 32-character Base32 authenticator secret is required on stdin.\n");exit(1);}
$u=query('SELECT id FROM users WHERE email=?',[$email])->fetch();if(!$u){fwrite(STDERR,"Register the account first.\n");exit(1);}
query('UPDATE users SET role=?,mfa_secret=? WHERE id=?',[$role,$secret,$u['id']]);echo "Role configured. MFA is required for all admin endpoints.\n";
