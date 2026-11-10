# CyberGigiyena

Mahalliy ishlab chiqish: Next.js + TypeScript + Tailwind, PHP 8.3 REST API,
MySQL 8.4 va Flutter WebView. Asosiy talablar va bajarilish holati: [rejam.md](rejam.md).

Bu repozitoriy hozir ishlab chiqish bosqichida. Production domen, HTTPS,
Android/iOS qurilma sinovlari va do‘kon nashri hali alohida qabul qilinadi.

## Mahalliy ishga tushirish

Buyruqlar loyiha papkasidan bajariladi. Uch xizmat bir vaqtda ishlashi kerak.

1. Loyihaning alohida MySQL nusxasi (mavjud `.runtime/mysql` ma’lumotlari):

   ```powershell
   & 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqld.exe' --no-defaults --basedir=C:/laragon/bin/mysql/mysql-8.4.3-winx64 --datadir=D:/Web_veiv/.runtime/mysql --port=3308 --bind-address=127.0.0.1 --mysqlx=OFF --log-error=D:/Web_veiv/.runtime/mysql.log
   ```

2. PHP API, boshqa terminalda:

   ```powershell
   php -S 127.0.0.1:8000 -t backend/public backend/public/index.php
   ```

3. Next.js, boshqa terminalda:

   ```powershell
   Set-Location web
   npm ci
   npm run dev
   ```

Sayt: `http://127.0.0.1:3000`. API shu origin orqali `/api/v1` ga yo‘naltiriladi.
Avtomatik 30 kunlik taqvim yozuvlari tahririy qoralama; kelgusi kunlar
o‘z-o‘zidan nashr qilinmaydi. Tasdiqlangan paket bo‘lmasa sayt buni oshkor
qilib, zaxira mashqni ko‘rsatadi.

Backendning sog‘lomlik manzili: `http://127.0.0.1:8000/api/v1/health`.
`API_ORIGIN` muhiti boshqa PHP manzilini tanlash uchun ishlatiladi.

`.runtime/config.json` va `.runtime/mysql-admin.json` maxfiy; ularni Gitga,
skrinshotga yoki xatolik hisobotiga qo‘shmang. Mavjud bazani qayta
`--initialize-insecure` qilmang. Yangi kompyuter uchun xavfsiz baza provisioning
va production migratsiya tartibi hali release vazifasi bo‘lib qoladi.

## Tekshiruvlar

Serverlar ishga tushgandan keyin:

```powershell
python backend/tests/integration.py
php backend/tests/daily_fallback.php
php backend/tests/alert_validation.php
php backend/tests/alert_draft.php
Set-Location web
npm run typecheck
npm run build
Set-Location ..
.\scripts\check-web.ps1 -Browser msedge
Set-Location flutter
flutter analyze
flutter test
```

Har buyruqning exit code’ini alohida tekshiring. Keyingi buyruqning muvaffaqiyati
oldingi xatoni yashirmasligi kerak. Playwright o‘rnatilgan Google Chrome’ni
headless rejimda ishlatadi. Natijalar `web/test-results/`, xatolardagi trace va
skrinshotlar shu papkada saqlanadi. Bu papkada test ma’lumotlari bo‘lishi mumkin.

Integratsiya testi alohida `example.invalid` hisob yaratadi va uni API orqali
o‘chiradi. Mehmon test yozuvlari mahalliy bazada qolishi mumkin; production
bazasiga testlarni yo‘naltirmang. `CG_TEST_URL` va `CG_WEB_URL` faqat ajratilgan
test muhiti uchun.

Windows sandbox `canonicalize ... Access is denied` xatosini bersa, bu build
o‘tdi degani emas: loyiha va SDK keshiga ruxsat berilgan mahalliy terminalda
buyruq qayta tekshirilishi kerak. Lokal API testi loopback uchun tizim HTTP
proksisidan foydalanmaydi.

## Rasmiy xabar qoralamasi

Rasmiy UZCERT xabari `backend/content/editorial/telegram-code-call.json` faylida
manba va sanalari bilan qoralama sifatida tayyorlangan. Mavjud bazaga
`php backend/bin/import-official-alert-draft.php` orqali kiritiladi; takroriy
ishga tushirish mavjud yozuvni o‘zgartirmaydi. Muharrir uni CMSda tahrirlaydi,
boshqa reviewer tekshiradi, publisher esa manbani nashr kuni qayta
tekshirgandan keyingina chiqaradi. Qoralama `/alerts` sahifasida ko‘rinmaydi.

## Flutter


Android emulyatori uchun (haqiqiy HTTP faqat debug holatda):

```powershell
flutter run --dart-define=APP_URL=http://10.0.2.2:3000
```

Eslatma: Next.js hozir faqat `127.0.0.1` ga bog‘lanadi. Emulyator yo‘li host
sozlamalariga bog‘liq va alohida tekshiriladi. Haqiqiy telefon va release uchun
o‘z HTTPS domeningizni `APP_URL` sifatida bering. Domen ko‘rsatilmasa ilova
soxta saytni ochmaydi; native SOS fallback ko‘rsatadi. Windows’da iOS IPA
yig‘ilmaydi: macOS/Xcode, signing va haqiqiy iPhone sinovi talab qilinadi.

Qobiqda bitta web navigatsiya, URL cheklovi, tashqi havola tasdig‘i, yuklanish
va xato holatlari, native SOS va ulashish bor. Kod mavjudligi Android/iOS’da
hammasi tekshirilganini anglatmaydi.

## Hujjatlar

- [Asosiy reja va checklist](rejam.md)
- [O‘zgarishlar](docs/CHANGELOG.md)
- [Tekshiruv dalillari va chegaralar](docs/QA-2026-11-05.md)
