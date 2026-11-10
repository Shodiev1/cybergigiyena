# O‘zgarishlar

## 2026-11-10 — taqvim tahririy jarayoni, 30 kunlik reja va kengaytirilgan admin/UX namoyishi

- `daily_bundles` jadvaliga `author`, `reviewer`, `updated_at`, `theme`, `tip` va `promise` ustunlari qo‘shildi (`migrations/003_calendar_workflow.sql`, `migrations/004_daily_bundle_fields.sql`, `migrations/001_schema.sql`, `bin/setup.php`).
- `backend/content/daily_plan.php` orqali `rejam.md` 13.4 va 34-bo‘limlariga mos 30 ta takrorlanmas kunlik mavzu (`theme`), maslahat (`tip`) va va’da (`promise`) qoralamalari bazaga (`draft` holatida) kiritildi.
- `POST /admin/calendar` endi kunlik paketni darhol nashr qilmasdan `draft` holatida saqlaydi (`author` yoziladi, `reviewer=NULL`, `theme`/`tip`/`promise` saqlanadi, `version` to‘qnashuvi `409 VERSION_CONFLICT` bilan tekshiriladi va `calendar.draft` auditi yoziladi).
- `POST /admin/calendar/{Y-m-d}/state` orqali `draft -> review -> approved -> published` bosqichlari joriy etildi: muallif talabi (`AUTHOR_REQUIRED`), mustaqil tekshiruvchi talabi (`INDEPENDENT_REVIEW`), nashrdan oldin tekshiruvchi va kontent butunligi (`REVIEW_REQUIRED`, `VALIDATION_ERROR`) hamda qayta tahrirda `reviewer`ni tozalash.
- `daily()` va `GET /api/v1/inbox` faol nashr qilingan ogohlantirishni (`activeAlerts()`) qaytaradi; bosh sahifa (`/home`) tasdiqlangan faol `alert` mavjud bo‘lsa uni manbasi va sanasi bilan ko‘rsatadi.
- Admin `/admin/calendar` sahifasida holatlar bo‘yicha hisoblagich (`draft`, `review`, `approved`, `published`), keyingi kun nashr qilinmagani haqida ogohlantirish, `Ertangi kunni ko‘rish` (`Preview tomorrow`), `Kunlik paketni ko‘rish`, maxsus `theme`/`tip`/`promise` tahriri hamda qidiruv va holat filtri qo‘shildi.
- `scripts/check-web.ps1` Windows PowerShell 5.1 muhitida ham loopback proksisiz ishlashi uchun `curl.exe --noproxy '*'` orqali health-check qiladigan qilib tuzatildi.
- PHP/MySQL (`backend/tests/calendar_workflow.php`, `backend/tests/daily_fallback.php`) va Playwright (`web/tests/admin.spec.ts`, `web/tests/flows.spec.ts` — `320/360/393/430/480/768/1440` px, klaviatura skip-link fokus, faol alert banneri, ssenariyning barcha tarmoqlari va taqvim tahririy oqimi) testlari kengaytirildi: 20/20 E2E sinovi o‘tdi; tafsilotlar `QA-2026-11-06.md`da.

## 2026-11-06 — admin kontent tarixi

- Dars/ssenariy/ogohlantirish namoyishi, qidiruv va holat filtri.
- Tahrir tarixini ko‘rish va eski nusxani yangi qoralamaga tiklash API/UI.
- Tiklashda role/MFA, versiya to‘qnashuvi, qayta validatsiya, joriy nusxani saqlash va audit.
- PHP/MySQL regressiya va Edge UI testi; dalillar `QA-2026-11-06.md`da.

## 2026-11-05 — tugallanmagan tekshiruvlarni davom ettirish

- Flutter HTTP error callback’ida mavjud bo‘lmagan `isForMainFrame` olib
  tashlandi. Asosiy hujjat URI’si xato so‘rovi URI’si bilan solishtiriladi;
  xato rasm yoki API so‘rovi butun WebView’ni yopmaydi.
- Asosiy hujjat 4xx/5xx xatosi, subresource xatosi, noma’lum request va URL
  fragmentlari uchun regressiya testlari qo‘shildi.
- Kun savoli natijaga o‘tishdan avval progress yangilanishini kutadi.
- Onboarding query parametri va saqlangan dars bosqichi xavfsiz oraliqqa keltiriladi.
- Maslahat ulashish tugmasi Flutter bridge mavjud bo‘lsa `shareTip` yuboradi;
  odatiy brauzerda Web Share yoki nusxalash ishlaydi.
- Lokal API testlari loopback uchun tizim proksisini chetlab o‘tadi.
- Haqiqiy mahalliy backend bilan Playwright regressiya/ekran testlari qo‘shildi.

Sinov holati va tekshirilmagan talablar QA hisobotida alohida qayd etiladi.

- Kunlik API yaroqsiz ssenariy/dars/savol uchun belgilangan zaxira paket qaytaradi; brauzer yorlig‘i va MySQL rollback sinovi qo‘shildi.
- 30 avtomatik takror yozuvi yangi bazada draft. Mavjud bazadagi 29 kelgusi kun draftga o‘tkazildi; bugungi nashr saqlandi.
- Admin taqvimi qoralama/nashr sonini va aniq nashr amalini ko‘rsatadi.
- Alert manbasi, sanasi va amaliy qadamlari qat’iy tekshiriladi; nashr kuni qayta tekshiruv talab etiladi.
- UZCERTning Telegram kodi haqidagi 2026-07-07 rasmiy xabari CMSga auditli qoralama sifatida kiritildi. Muharrir va mustaqil reviewer tasdig‘isiz nashr etilmaydi.
