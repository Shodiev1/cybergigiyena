# CYBERGIGIYENA — WEB + FLUTTER WEBVIEW MASTER PLAN 4.0

> **Asosiy qaror:** **Veb-sayt: Next.js (App Router, TypeScript) + Tailwind CSS**. **Mobil ilova qobig‘i: Flutter (Dart) + `webview_flutter`** — Androidda WebView, iOS'da WKWebView. **Backend: avvalgi reja bo‘yicha PHP REST API + MySQL**.  
> **Maqsad:** bitta haqiqiy ishlaydigan onlayn sayt, shu saytni Android va iOS ichida ochuvchi sifatli mobil ilova.  
> **Versiya:** 4.0 — 2026-11-05. **Holat:** ishlab chiqishga mo‘ljallangan mahsulot/UX/API/native integratsiya texnik topshirig‘i, hozir tayyor dastur emas.  
> **Avvalgi manba:** `CyberGigiyena_WEB_FIRST_MASTER_PLAN_v3.md` (2026-11-05). Undagi **53 ta ekran kontrakti**, kunlik reja, 20 simulyatsiya, o‘quv, SOS, va'da, zaif tomonlar, CMS va QA **saqlangan**.  
> **Qat'iy stack:** `Next.js + TypeScript + Tailwind CSS + Flutter WebView + PHP API + MySQL`. `React/Vite SPA`, `TWA` yoki `Capacitor` — **asosiy variant EMAS**. Next.js React'ga tayanadi, ammo ilova o‘z marshrutlarini **Next.js App Router** orqali boshqaradi.  
> **Muhim realizm:** Android APK va iOS IPA bir xil veb saytdan foydalanadi, lekin platformadagi login, bildirishnoma, tashqi havola, saqlash, navigatsiya va ilova do‘koni tekshiruvlari alohida ishlab chiqiladi.

## TEZKOR NAVIGATSIYA — MUHIM QISMLAR

| Kim? / Nima qilinadi? | Qayerga qaraladi? |
|---|---|
| Mahsulot qoidalari, foydalanuvchi maqsadi | 0–1 |
| **Next.js + Flutter + PHP arxitekturasi** | **2, 35–37** |
| To‘rt sahifa, ekran xaritasi va tugmalar | 3, 5–12, 20–21 |
| **Apple ruhidagi telefon dizayni, Safe Area, responsivlik** | **4, 33, 36** |
| Kunlik kontent, 30 kunlik dastur | 13, 19, 34 |
| API va MySQL | 14–16, 37 |
| **Offline, cache, sinxronlash** | **17, 38** |
| Xavfsizlik va Apple/Google talablariga moslik | 18, 23, 31, 39 |
| Flutter WebView kod skeleti va native bridge | **23, 35, 37** |
| Papkalar, hosting, CI/CD | 25–26, 40 |
| Har bir sahifa vazifalari / 53 ekran | 20 |
| QA, turli qurilmalar, UX qabul testlari | 27, 30, 39 |
| Ish boshlash ketma-ketligi va vazifalar | 29, 32, 41 |

**Hujjatni loyihaga joylashtirish:** `CyberGigiyena/docs/CYBERGIGIYENA_MASTER_PLAN_WEB_FLUTTER_V4.md`. Barcha ishlab chiquvchi va dizaynerlar aynan shu hujjat bo‘yicha ishlaydi. Har yangilanish `docs/CHANGELOG.md`da yoziladi.

---

---

## 0. QAT'IY QARORLAR — BU QOIDALAR O‘ZGARMAYDI

1. Pastki navigatsiya **4 ta**: **Asosiy**, **Bilimlar**, **Simulyatsiya**, **Profil**. `SOS` barcha bosh ekranlarda header orqali va `/sos` route bilan mavjud.
2. **XP, reyting, medal, leaderboard, majburiy ketma-ket kirish** yo‘q. Haqiqiy o‘rganish, xatolar tahlili, amaliy odatlar va ko‘nikmalar rivoji bor.
3. Mehmon sifatida saytni ko‘rib, dars va simulyatsiyalarni boshlash **mumkin**; hisob ochish faqat qurilmalararo sinxronlash va shaxsiy tarix uchun.
4. Har kun **missiya, savol, mikro-dars, foydali maslahat, va’da** server boshqaruvidagi yagona mavzuli paketdan keladi; kecha bajargani ertaga yo‘qolmaydi.
5. O‘zbekiston kiberogohlantirishi sanasi, tekshiruv sanasi va **asl manbasiz** dolzarb sifatida chiqarilmaydi.
6. Simulyatsiyada **haqiqiy parol, OTP, bank karta raqami** kiritilmaydi; real zararli havola yoki APK yuklanmaydi. Xavfli vaziyatlar ilova ichida yasama va nazoratli.
7. “Faol seanslarni tekshirish” — foydalanuvchiga rasmli yo‘riqnoma, **telefon/app ichiga ruxsatsiz kirib skanerlash emas**.
8. **Apple'ga o‘xshash his**: tiniq oq fon, katta sarlavha, nozik material qatlamlari, sokin animatsiya, nafis soyalar, aniq tipografika; Apple logotipi, tizim ekranining aynan nusxasi yoki litsenziyasiz shriftlar emas.
9. Oddiy arzon Android telefoni, zaif internet, kattalashtirilgan matn va vertikal ekran **birinchi darajali talab**.
10. Sayt ishga tushganda **haqiqiy, foydalanuvchiga foyda keltiruvchi mahsulot** bo‘ladi; bo‘sh tugmalar, `coming soon` karta-ko‘rinishlar MVPda ko‘rsatilmaydi.

---

## 1. MAXSULOTNI KIM VA NIMA UCHUN ISHLATADI?

| Segment | Birinchi muammo | Ilovadagi haqiqiy yechim | Uzoq muddatdagi foyda |
|---|---|---|---|
| Oddiy smartfon egasi | Bank SMSi yoki Telegram linki haqiqiymi? | Simulyatsiya + sababini tushuntirish | Firibgar xabarni tanishga o‘rganadi |
| Katta yoshli inson | Telefon qo‘ng‘irog‘ida shoshiltirish | Katta tugmalar, qisqa audio (keyin), SOS | Shoshmasdan tekshirish odati |
| Talaba | QR, Wi-Fi, ijtimoiy tarmoq akkaunti | Mini-dars + vaziyatli interaktiv mashq | Xavfsiz raqamli tanlov |
| Onlayn xaridor/sotuvchi | Soxta to‘lov, avans, skrinshot | Savdo ssenariylari | Pul yo‘qotish xavfini kamaytirish |
| Oila a'zosi | Yaqinlariga tushuntirish qiyin | “Bilimingizni ulashing” kartasi | Oila miqyosida bilim tarqalishi |
| Ishchi/kichik biznes | Rahbar nomidan xabar, soxta invoice | Ish joyi bo‘limi (2-versiya) | Ish muhitidagi xatolar kamayishi |

**Foydalanuvchi va'dasi:** `2 daqiqa bilib oling → 3 daqiqa vaziyatni hal qiling → bir hayotiy qadamni bajaring`. Testni noto‘g‘ri qilish uyat emas; interfeys sabrli va hurmatli bo‘ladi.

**Til:** v1 `uz-Latn-UZ`, arxitektura boshidan i18n. v1.1 `uz-Cyrl-UZ`, `ru-RU` faqat sifatli tarjima tayyor bo‘lganda. *Har bir* matn tarjima kalitida: `home.mission.title`, `practice.feedback.why` va h.k.

---

## 2. ARXITEKTURA — NEXT.JS SAYT + FLUTTER WEBVIEW (ANDROID/IOS)

### 2.1. Stack va javobgarlik chegaralari

| Qatlam | Tanlov | Mas'uliyat | Nimalarni qilmaydi? |
|---|---|---|---|
| Veb UI va marshrutlar | **Next.js App Router + TypeScript** | Asosiy, Bilimlar, Simulyatsiya, Profil, SOS; server-rendered shell; SEO landing | Android/iOS native ruxsatini o‘zi boshqarmaydi |
| Veb dizayn | **Tailwind CSS + CSS custom properties** | Light/dark, safe area, 320–tablet layout, accessibility | Native status-bar/ruxsat oynasining o‘rnini bosmaydi |
| Web state | TanStack Query (faqat kerakli client qismlar), React state | API bilan uzluksiz UX, optimistic UI faqat idempotent amalda | Maxfiy session tokenlarni `localStorage`ga yozmaydi |
| Web lokal storage | IndexedDB (minimal Dexie), `Cache Storage` tanlab | Public dars va mehmon natijasi; offline rejalash | WebViewda 100% doimiy offline kafolat bermaydi |
| Veb hosting | **Node.js runtime / Next.js server** | SSR, App Router, static assets | PHP hosting ichidagi oddiy statik fayllarga teng EMAS |
| Biznes backend | **PHP 8.3+ REST API** | Foydalanuvchi, mashqlar, natija, daily paket, admin va auth | Next.js bilan bir endpointga ikki marta biznes mantiq yozmaydi |
| Ma'lumotlar bazasi | **MySQL 8+** | Progress, kontent, audit, jadvallar | WebView bevosita DBga ulanmaydi |
| Edge / reverse proxy | **Nginx yoki mos gateway** | Bitta domen, HTTPS, `/api/v1`ni PHPga, qolganini Next'ga yo‘naltirish | CORSga keraksiz yechim yaratmaydi |
| Native qobiq | **Flutter (Dart) + `webview_flutter`** | Android WebView / iOS WKWebView, native splash, offline fallback, linklar, share, opt-in push | Biznes mantiqni ikki marta yozmaydi |
| Kontent tahriri | Next.js'dagi `/admin` + PHP API | Dars, simulyatsiya, alert fact-check, kalendar | Har kontent o‘zgarganda APK yangilamaydi |

**Birinchi kundan TypeScript tanlang:** `Next.js (JavaScript/TypeScript)` deganingdagi ikkidan birini belgilash kerak bo‘lsa, shu miqyos uchun **TypeScript** afzal. Runtime validatsiya uchun Zod yoki shu vazifani bajaruvchi kutubxonadan foydalaning. UI Tailwind utility + mustaqil dizayn tokenlariga bo‘linadi; barcha ekranlar bitta komponent tizimidan foydalanadi.

### 2.2. Qabul qilingan joylashuv

```text
                          BIR DOMEN: https://APP_DOMEN
                                 │
                ┌────────────────┴────────────────┐
                │                                 │
            Browser / PWA                  Flutter Android / iOS
             Next.js UI                     WebView -> shu HTTPS URL
                └────────────────┬────────────────┘
                                 │
                          HTTPS NGINX
               ┌─────────────────┴─────────────────┐
               │                                   │
       /, /home, /learn, ...                /api/v1/*
          Next.js Node                      PHP-FPM REST API
               │                                   │
               └── UI shell / client API calls ────┤
                                                   │
                                                  MySQL
                                                   │
                                              background jobs/CMS
```

**Aniq kelishuv:** masalan `https://app.example.uz` — faqat **namuna**, domen band qilingan deyilmaydi. `GET /home` Next.js, `GET /api/v1/daily` PHP. Nginx `/api/v1/`ni PHPga yo‘naltiradi. Agar Next.js'da `app/api/` route handlers ham ishlatilsa, ular `/api/v1/*` bilan **to‘qnashmasligi** kerak. API uchun bitta yakuniy contract va bitta owner belgilanadi.

### 2.3. Birlamchi UX qanday ochiladi?

- Brauzer: `app.example.uz/home` — `Next.js` ishlaydi; barcha kontent va navigatsiya oddiy webda to‘liq foydali.
- Android: Flutter APK/AAB `WebViewWidget` orqali **shu HTTPS domenni** ochadi; Android WebView ilovaning ichida render qiladi.
- iOS: Flutter IPA o‘z `WKWebView`i orqali **shu HTTPS domenni** ochadi.
- Web'dagi kunlik mashq, darslar, ogohlantirishlar, profil va CMS nashri — **serverdagi yangilanish orqali** aks etadi; odatda mobil binary qayta tarqatish shart emas.
- Flutter qobig‘i ruxsatlar, native funksiyalar, splash, app ikon, App Store/Play Store metadata va platforma kodidagi o‘zgarishlar uchun **ilova yangilanishi** talab qiladi.

### 2.4. Nimani qat'iyan aralashtirmaymiz?

1. `Next.js`ni faqat statik `next export` bilan chiqarish **asosiy variant emas**: Next server rendering, App Router va dynamic sahifalar uchun Node runtime kerak. Static export faqat mustaqil tekshirilib, server xususiyatlaridan voz kechilganda mumkin.
2. Flutter `webview_flutter` — Next.jsga **alternativa emas**, u tayyor HTTPS saytingni ekranda ko‘rsatadi.
3. WebView qobig‘i haqiqiy koddagi UX va server xavfsizligini avtomatik ta'minlamaydi.
4. Android va iOS cookie storage odatda **o‘z app konteynerida**. Brauzerda login bo‘lsa, appda avtomatik login bo‘lishi shart emas; **bitta akkaunt** orqali natijalar serverda sinxronlashadi.
5. **PHP/MySQL saqlanadi**, chunki oldingi rejada shu backend tanlangan. Keyinchalik alohida backend refaktori qaror qilinsa, API kontrakti va jadvallar boshqariladigan tarzda ko‘chiriladi, hozir ikki backend yaratmaymiz.

### 2.5. Ish oqimida roziliklar va xavfsizlik

- Sayt ochilmasa Flutter **native offline/error** ekrani ko‘rsatadi; `Qayta urinish`, `Saqlangan yo‘riqnoma`, `SOS` har doim mavjud.
- Begona domen WebView ichida avtomatik ochilmaydi. HTTPS tashqi ishonchli havolalar **tizim brauzeriga**, telefon va email havolalari esa foydalanuvchi bosganda mos ilovaga yo‘naltiriladi.
- Kamera, kontaktlar, SMS, Accessibility, fondagi mikrofon ruxsatlari **MVPda so‘ralmaydi**. Foydalanuvchi real OTP yoki bank karta raqamini kiritmasligi kerak.
- Apple App Store **4.2 Minimum Functionality** sharti tufayli Flutter qobiq foydalanuvchiga haqiqiy qo‘shimcha qiymat beradi: native SOS offline yordam, push opt-in, ulashish, real native loading/error, deep linklar. Bu tasdiq kafolati emas; release review talablarini qayta tekshiring.


---

## 3. SITE MAP: 4 TAB VA QAYERGA O‘TADI?

```text
/                                  → birinchi kirish bo‘lsa /welcome; qaytgan foydalanuvchi /home
/welcome                            → 3 ekranli onboarding (skip mumkin)
/auth                               → mehmon yoki kirish
/auth/login                         → kirish
/auth/register                      → akkaunt ochish
/auth/recover                       → parol tiklash
/diagnostic                         → 5 savolli boshlang‘ich sinov (ixtiyoriy)
/home                               → ASOSIY TAB
  /mission/today                    → bugungi missiya intro
  /question/today                   → kun savoli
  /question/today/result            → sabab va tavsiya
  /alerts                           → barcha tasdiqlangan ogohlantirishlar
  /alerts/:slug                     → bitta manbali ogohlantirish
  /inbox                            → shaxsiy bildirishnomalar
  /share/today                      → maslahat ulashish
  /promise/today                    → bugungi va'da
/learn                              → BILIMLAR TAB
  /learn/topic/:slug                → mavzu va darslar ro‘yxati
  /learn/lesson/:slug               → dars
  /learn/lesson/:slug/check         → mini-test
  /learn/lesson/:slug/finish        → dars natijasi
  /learn/review                     → takrorlash
  /learn/search                     → qidiruv
  /learn/glossary                   → lug‘at
/practice                           → SIMULYATSIYA TAB
  /practice/:slug/intro             → vaziyat haqida ma'lumot
  /practice/:slug/play              → 1-5 bosqichli simulyatsiya
  /practice/:slug/result            → qaror, xato, izoh
  /practice/:slug/action            → haqiqiy hayotda bajariladigan qadam
/profile                            → PROFIL TAB
  /profile/skills                   → mavzular bo‘yicha ko‘nikmalar
  /profile/weaknesses               → mustahkamlash kerak bo‘lgan mavzular
  /profile/history                  → o‘tilgan dars va mashqlar
  /profile/promises                 → va'dalar tarixi
  /profile/checklist                → xavfsizlik amaliy cheklisiti
  /profile/preferences              → til, tema, shrift, motion
  /profile/notifications            → eslatma sozlamalari
  /profile/privacy                  → maxfiylik markazi
  /profile/export                   → foydalanuvchi ma'lumotini eksport qilish
  /profile/delete                   → akkauntni va ma'lumotni o‘chirish
/sos                                → favqulodda yordam (global)
/sos/:kind                          → vaziyatga mos xavfsiz qadamlar
/feedback                           → xato yoki foydali taklif berish
/admin                              → muharrir paneli (faqat rolli ruxsat)
/admin/content                      → dars/missiya/savol/va'da tahrirlash
/admin/scenarios                    → simulyatsiya sahna grafigi
/admin/alerts                       → rasmiy xabar tekshiruvi
/admin/calendar                     → kunlik paket jadvali
/admin/users                        → minimal hisob boshqaruvi
/admin/audit                        → o‘zgartirishlar tarixi
/admin/analytics                    → anonimlashtirilgan faoliyat ko‘rsatkichlari
```

**Tab bar faqat** `/home`, `/learn`, `/practice`, `/profile` va ularning keng ro‘yxatlarda mavjud; darsning chuqur rejimida yoki simulyatsiyaning `play` qismida pastki panel yashiriladi. Har qaysi tab foydalanuvchi scroll pozitsiyasini eslab qoladi. Brauzer back — oldingi kontentga qaytaradi; Android back/gesture shu mantiqqa mos keladi. Agar mini-testda tanlov bor va foydalanuvchi chiqayotgan bo‘lsa, `Natija saqlansinmi?` dialogi.

---

## 4. “APPLE'GA O‘XSHASH” DIZAYN TIZIMI — O‘Z BRENDIMIZ BILAN

Apple HIG uslubidan olinadigan tamoyillar: **vizual ierarxiya, bo‘sh joy, konsistent boshqaruv, tabiiy transition, ravshan o‘qilish, foydalanishga yaqin tugmalar**. Maxsus mahsulotimizning identifikatsiyasi esa CyberGigiyena: ko‘k, safir, och moviy va to‘q matn.

### 4.1. Figma bosqichlari va asosiy frame'lar

- Figma Pages: `00-Foundations`, `01-Components`, `02-Mobile-Home`, `03-Learn`, `04-Practice`, `05-Profile`, `06-SOS-Alerts`, `07-Auth-Onboarding`, `08-Admin-Tablet`, `09-Flows-and-States`, `10-Developer-Handoff`.
- 4 frame kengliklari: **320 / 360 / 393 / 430 CSS px**; qo‘shimcha **600/768/1024/1440 px**. CSS px dizayn birligi; telefonning fizik pikseli bilan almashtirmang.
- Har muhim ekran **light/dark**, kamida **default/loading/empty/error/offline/success**, font 100%/200%, UzLotin/Ru variantlari bilan tekshiriladi.
- Component variantlar: button primary/secondary/destructive/disabled/loading, list row, alert notice, chips, radio, bottom sheet, nav, progress, simulation header, lesson step, toast, snackbar, skeleton, input, modal, tab.

### 4.2. Tokenlar — Next.js `globals.css` va Figma variables

| Token | Light | Dark | Qo‘llanish |
|---|---|---|---|
| `--bg` | `#F5F7FB` | `#0C1220` | Sahifa foni |
| `--surface` | `#FFFFFF` | `#182235` | Oq kartalar |
| `--surface-subtle` | `#EFF4FB` | `#223047` | Ikkinchi qatlam |
| `--text` | `#101828` | `#F4F7FC` | Asosiy matn |
| `--muted` | `#5B6475` | `#B6C3D5` | Izoh va meta |
| `--primary` | `#1677F2` | `#6DAAFF` | CTA, focus |
| `--primary-deep` | `#1057C1` | `#4F8EF0` | Gradient, active |
| `--success` | `#14846C` | `#57D7B6` | Xavfsiz qaror |
| `--warning` | `#B76D00` | `#FFD06D` | Ehtiyot belgisi |
| `--danger` | `#C53344` | `#FF8391` | Tasdiqlangan jiddiy xavf |
| `--border` | `#DFE5F0` | `#34425A` | Karta ajratuvchi |
| `--focus` | `#1769E9` | `#B3D7FF` | Klaviatura focus |

**Muhim:** rang natijani bildiruvchi yagona signal emas; ikon + matn birga ishlaydi. Muhim qizil alert doim qizil fonda katta ochilmaydi. Qizil `danger` faqat aniq xavfga.

**Radiuslar:** `r-xs=8`, `r-sm=12`, `r-md=16`, `r-lg=20`, `r-xl=24`, `r-hero=28`, `r-pill=999` CSS px.  
**Spacing:** `4, 8, 12, 16, 20, 24, 32, 40, 48` CSS px; asosiy sahifa yon padding 16 (320 px ekranda 12).  
**Soya:** `0 8px 24px rgba(17,42,76,.06)` karta; kuchli blur faqat dekorativ qatlam, matn ustida emas.  
**Gradient:** hero `linear-gradient(135deg,#1A8DFF,#155BD5)`. Qolgan kartalar oq.  
**Ikonlar:** Lucide 20–24 px, stroke 1.8–2; matnsiz ikon albatta `aria-label` oladi.

### 4.3. Tipografika — haqiqiy o‘qiluvchanlik

- **Asosiy:** `Inter`, `system-ui`, `Arial`, `sans-serif`. `SF Pro` faqat tizimda mavjud bo‘lsa fallback sifatida; Apple shriftini boshqa platformaga ruxsatsiz tarqatmang.
- Display 28/34 semibold (mobil), H1 24/30, H2 20/26, H3 18/24, body 16/24, secondary 14/20, caption 12/18; **asosiy savol** kamida 16/24.
- Oddiy matn kengligi 60–70 belgi oralig‘ida, qo‘lda 3-4 qatorli qiyin paragraflar bo‘linadi.
- O‘zbek apostroflarini (`o‘`, `g‘`) izchil ishlatish; monospace faqat URL/domain misollarida, ularda satrga ko‘chirish imkoniyati bo‘lsin.
- HTML `font-size` foydalanuvchi sozlamalarini hurmat qiladi: 200% matnda kesilish bo‘lmasin.

### 4.4. To‘rt asosiy komponentning o‘lchamlari

| UI elementi | Mobil tavsiya | UX shart |
|---|---:|---|
| Header | 56–64px | Profil/brand + SOS + bildirishnoma; notch/punch-hole xavfsiz |
| Bottom navigation | 64–72px + `safe-area-inset-bottom` | 4 ta tab, label doim ko‘rinadi, kontentni yopmaydi |
| Primary button | **min-height 48px** | Bosh barmoq bilan bosish, loading payti ikki marta yubormaslik |
| Touch icon hit area | **min 44×44 CSS px** | Apple uslubidagi qulay tegish nishoni; WCAG AA talabidan kattaroq dizayn maqsadi |
| Card | padding 16px; radius 20–24px | Kartani sarlavha, izoh, actionga bo‘lish |
| Mission hero | mobil 210–270px mazmunga qarab | Asosiy CTA va bugungi mavzu qat'iy ko‘rinadi |
| Alert card | 108–160px, kontentga mos | Sana, manba, `Batafsil`, `Mashq qilish` |
| Answer option | min-height 54px; gap 10px | Butun satr bosiladi, radio label bor |
| Bottom sheet | max-height `calc(100dvh - 24px)` | Scroll ichkarida, orqaga/esc yopadi |
| Dialog | min 280px, max 420px | Tugma tavsifi aniq; destructive harakatda tasdiq |

### 4.5. Responsive va buklanadigan ekranlar

| CSS viewport | Layout | Nav | Karta ustunlari | Yon bo‘shliq |
|---|---|---|---|---|
| 320–359 | compact phone | pastki 4 tab | 1 | 12px |
| 360–479 | standard phone | pastki 4 tab | 1 | 16px |
| 480–599 | large phone | pastki 4 tab | 1 yoki 2 kichik karta | 20px |
| 600–767 | small tablet/fold | pastki 4 tab yoki balandroq tab | 2 | 24px |
| 768–1023 | tablet | yon navigatsiya | 2 | 24–32px |
| 1024+ | desktop/web | 220–256px sidebar | 2–3 | kontent max-width 1200px |

- `viewport-fit=cover`, CSS `env(safe-area-inset-top/bottom/left/right)` bilan notch va home-indicator saqlanadi.
- Mobil bodyni gorizontal scroll qildirmang; domen, uzun nomlar `overflow-wrap:anywhere` bilan o‘raladi.
- Klaviatura ochilganda input yopilib qolmasin; `dvh` / `visualViewport` asosida test qiling, ekran umuman qayta sakramasin.
- `prefers-reduced-motion: reduce` animatsiyani qisqartiradi/o‘chiradi; `prefers-color-scheme` uchun `system/light/dark` rejimi.
- Orientatsiya bloklanmaydi; 320×568, 360×740, 393×852, 430×932, 768×1024, 1024×768 sinov.

### 4.6. Animatsiya / “premium” hissi

- Tab switch / button state: 120–180ms; card appear: 180–240ms; modal: 180–220ms (loyiha o‘lchov mezoni).
- Harakatdan oldin action darhol feedback beradi; “to‘g‘ri”/“xato” faqat foydalanuvchi tanlagandan keyin.
- Blur juda ko‘p bo‘lmasin: `backdrop-filter` bo‘lmasa oddiy oq/yengil shaffof fon fallback.
- Yuklash spinneri o‘rniga oldindan o‘lchami berilgan skeleton; keraksiz og‘ir 3D/lottie videolar birinchi ekranda ishlatilmaydi.
- Barcha animatsiyalar `transform/opacity`ga asoslanadi, `width/top` kabi qimmat layout o‘zgarishlaridan qochiladi.

### 4.7. Web UI uchun boshlang‘ich CSS token namunasi

```css
:root {
  font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  color-scheme: light;
  --bg: #F5F7FB; --surface: #FFF; --text: #101828;
  --muted: #5B6475; --primary: #1677F2; --border: #DFE5F0;
  --space: 16px; --radius-card: 22px;
}
* { box-sizing: border-box; }
body { margin: 0; background: var(--bg); color: var(--text); }
button { min-height: 48px; cursor: pointer; }
button:focus-visible, a:focus-visible { outline: 3px solid var(--primary); outline-offset: 3px; }
.app-shell { min-height: 100dvh; padding-bottom: calc(76px + env(safe-area-inset-bottom)); }
.content { width: min(100%, 1200px); margin-inline: auto; padding-inline: 16px; }
.bottom-nav { position: fixed; inset-inline: 0; bottom: 0; padding-bottom: env(safe-area-inset-bottom); }
@media (min-width: 768px) { .content { padding-inline: 32px; } .bottom-nav { display: none; } }
@media (prefers-reduced-motion: reduce) { *, *::before, *::after { animation-duration: .01ms !important; transition-duration: .01ms !important; } }
```

---

## 5. ASOSIY SAHIFA: REAL OQIM, KARTA-KARTA, QAYSI JOYDA?

**Yo‘l:** `/home` (4 tabning 1-si). Bu sahifa marketing afishasi emas, foydalanuvchini har kuni birinchi foydali harakatgacha olib boruvchi ish stoli.

### 5.1. 393×852 mobil telefon — yuqoridan pastga

```text
┌─────────────────────────────────────────┐
│  CyberGigiyena        [SOS]  [🔔]       │  Header + safe area
│  Xayrli kech, do‘stim!                  │
│  Bugun o‘zingizni bir xavfdan asrang.  │
│ ┌─────────────────────────────────────┐ │
│ │ BUGUNGI MISSIYA  •  4 daqiqa       │ │
│ │ Faol seanslarni tekshirish          │ │  Blue gradient hero
│ │ Qaysi qurilmalar kirganini bilasiz? │ │
│ │ [Missiyani boshlash    →]           │ │
│ └─────────────────────────────────────┘ │
│ ┌─────────────────────────────────────┐ │
│ │ ⚠ O‘zbekistonda kiberogohlantirish │ │  Verified alert
│ │ Soxta Telegram havolalari...        │ │
│ │ Manba • sana  [Batafsil]            │ │
│ └─────────────────────────────────────┘ │
│ Kun savoli                              │
│ ┌─────────────────────────────────────┐ │
│ │ Qaysi manzil shubhali?              │ │
│ │ ( ) ... ( ) ...   [Javob berish]    │ │
│ └─────────────────────────────────────┘ │
│ Bugungi bilim   [2 daqiqa o‘qish →]     │
│ Zaif tomonlarim [Mustahkamlash →]       │
│ Bilimingizni ulashing [Nusxa/Ulashish] │
│ Bugun o‘zingizga va'da [Tanlash →]     │
│─────────────────────────────────────────│
│  Asosiy   Bilimlar  Simulyatsiya Profil│  Sticky tab
└─────────────────────────────────────────┘
```

### 5.2. Bloklarning qat'iy kontenti, UI holati va bosilishi

| Tartib | Element | Nimani ko‘rsatadi? | Bosilganda | Agar data bo‘lmasa |
|---|---|---|---|---|
| 1 | Header | Ism yoki `Salom!`, SOS, notification count | `/sos`, `/inbox` | Mehmon salomi |
| 2 | Bugungi missiya | Bugungi lokal sana, mavzu, 3–5 min, holat | `/mission/today` | Oxirgi keshlangan missiya va `Kecha` yorlig‘i |
| 3 | Rasmiy ogohlantirish | Sarlavha, tasdiqlangan manba, sana | `/alerts/:slug` | `So‘nggi ogohlantirishlar` yoki evergreen maslahat; hech qachon yolg‘on `bugun` |
| 4 | Kun savoli | Qisqa savol, 2–4 radio | `Tekshirish` → natija | Lokal tasdiqlangan savol banki |
| 5 | Bugungi bilim | Nom, 1–2 minut, illyustratsiya | `/learn/lesson/:slug` | Oxirgi ochiq dars |
| 6 | Zaif tomonlarim | Birinchi 1–2 mustahkamlash mavzusi | `/profile/weaknesses` | `Ko‘proq mashq bajaring` (foiz uydirilmaydi) |
| 7 | Bilimingizni ulashing | 1 aniq tavsiya; nusxa/ulashish | `/share/today` / Web Share API | Saqlangan maslahat |
| 8 | Va'da bering | Dars bilan bog‘langan amaliy harakat | `/promise/today` | Kunning oddiy cheklisti |
| 9 | Tab bar | 4 ikon + label | tab yo‘nalishi | Har doim mavjud |

**Birlamchi ko‘rinishda** faqat header + missiya + rasmiy xabarning boshi yetarli; pastga scroll qilish tabiiy. Bu ekran `hamma narsani bir ekranga sig‘dirish` uchun kichraytirilmaydi. Home o‘ng/chapga surilgan carousel bilan asosiy kontentni yashirmaydi.

### 5.3. Dinamik home holatlari

- Yangi kiruvchi: bugun missiya + `Birinchi mashqingizni boshlang`, zaiflik blokida dastlab `Hali yetarli ma'lumot yo‘q`.
- Bugunni tugatgan: hero `Bugungi mashq bajarildi` va `Izohni yana ko‘rish`; keyingi tavsiya optional, kun yana boshlangandek ko‘rsatilmaydi.
- Offline: bitta neytral banner `Saqlangan darslar ochiq; yangi ogohlantirishlar yangilanmaydi`.
- Qayta kirgan: yarim qolgan dars `Davom etish` bilan ochiladi; scroll pozitsiya saqlanadi.
- Muhim alert chiqqan: tasdiqlangan critical alert 24–72 soat (editor bergan muddat) davomida missiyadan oldin tor banner; server sanasi to‘g‘ri.
- Foydalanuvchi 30 kun kelmagan: aybdorlikka asoslangan tekst yo‘q; `Bugundan qayta boshlaymiz`.

---

## 6. BILIMLAR — MIKRO-DARS, IZOHLAR, TAKRORLASH

**Yo‘l:** `/learn`. Katalog: yuqorida qidiruv + `Boshlang‘ich/O‘rta/Barchasi` filtr + `Sizga tavsiya` + mavzu kartalari. Har modul ochilganda qisqa kirish, o‘qish vaqti, darslar, o‘zlashtirilganlik. Qo‘rqitadigan “qiyin sertifikat” o‘rniga sodda bilim.

### 6.1. Boshlang‘ich 12 modul va minimum kontent

| # | Modul | Darslar v1 | Amaliy misol / hayotiy qadam |
|---|---|---:|---|
| 01 | Kiberxavfsizlik asoslari | 5 | Shubhali vaziyatda to‘xtash va tekshirish |
| 02 | Parol va ikki bosqichli himoya | 6 | 2FAni yoqish bo‘yicha yo‘riqnoma |
| 03 | Phishing va soxta havolalar | 8 | Domenni solishtirish, URL belgilarini tanish |
| 04 | Telegram va messenjerlar | 6 | Faol sessiya, begona login, soxta ovoz berish |
| 05 | Bank kartasi va mobil to‘lov | 6 | OTP, PIN, karta ma'lumoti, soxta bank SMS |
| 06 | Zararli APK va telefon | 5 | Ruxsatlar, noma'lum o‘rnatish manbalari |
| 07 | Wi-Fi, QR va umumiy tarmoq | 5 | Public hotspot, QR havola xususiyatlari |
| 08 | Ijtimoiy muhandislik | 5 | Qo‘rqitish, shoshiltirish, soxta vakil |
| 09 | Onlayn savdo va yetkazib berish | 5 | Soxta chek, avans, yetkazish linki |
| 10 | Ijtimoiy tarmoq va maxfiylik | 5 | Profil sozlamalari, ruxsatlar, joylashuv |
| 11 | Qurilma yo‘qolishi va zaxira nusxa | 4 | Backup, ekran qulf, tiklash rejasi |
| 12 | Kiberhodisada birinchi yordam | 5 | Parolni almashtirish, rasmiy qo‘llab-quvvatlash |

**Jami rejalashtirilgan v1: 65 mikro-dars.** Bu reja uchun son; kontent yozilishi va mustaqil tekshirilishi talab etiladi. MVP uchun avval 12 mavzuning har birida 1 asosiy dars (12 ta dars) va ular bilan bog‘langan 5 ta simulyatsiya yetadi; keyin 65 darsli to‘liq v1 kontentiga kengaytiriladi. Bo‘sh mavzularni `tayyor` deb chiqarmang.

### 6.2. Darsning aniq qadamli UX shabloni

1. **Kirish:** `Nimani o‘rganasiz?` + `2 daqiqa`; keyingi tugma.
2. **Real voqea** (to‘liq uydirma bo‘lmagan, ammo shaxsiy ma'lumotsiz va simulyatsiya yorlig‘i bilan) — rasm, 3–4 satr.
3. **3 asosiy qoida** — kartalar, har birida misol.
4. **Mini-tekshiruv** — 1–3 savol; javobdan keyin sababini ko‘rsatish.
5. **Hayotiy qo‘llash** — 1 aniq xavfsiz qadam, foydalanuvchi tekshirishi mumkin.
6. **Tugatish** — `Takrorlashga saqlash` / `Simulyatsiyaga o‘tish`.

**Har bosqichda:** `1/5`, keyingi/orqaga, saqlab chiqish, screen reader; 200% font bilan kontent o‘qiladi. Darsda real tashkilot brendi yoki screenshots eskirishi mumkin: video/rasmga `tekshirilgan sana` yoziladi.

### 6.3. Qidiruv: foydali natija

- qidiriladigan maydonlar `sarlavha`, `teg`, `qisqa izoh`, `atama` (`2FA`, `OTP`, `Telegram`, `phishing/fishing`, `karta`).
- matn 2 belgidan keyin 250ms debounce; `qidiruv bo‘sh` = eng mashhur 6 mavzu; `natija yo‘q` = shunga o‘xshash 3 atama.
- offline bo‘lsa faqat keshlangan darslar qidiriladi, `offline qidiruv` izohi ko‘rinadi.
- ahamiyati yuqori: mobil klaviatura ochilganda qidiruv input va natijalar ko‘rinadi.

---

## 7. SIMULYATSIYA: REAL MUHIT, LEKIN 100% XAVFSIZ

**Yo‘l:** `/practice`. Katalog filtrlar: `Tavsiya`, `Yangi`, `Phishing`, `Telegram`, `Karta`, `Ilovalar`, `Savdo`, `Tarmoq`. Har karta: mavzu, vaqt (3–6 min), qiyinlik, ko‘nikma, `Boshlash`. **Noto‘g‘ri javob uchun minus ball yo‘q.**

### 7.1. Har bir ssenariy 7 bosqichda

```text
Katalog → Intro / xavfsiz mashq ogohlantirishi
→ Voqea muhiti (soxta SMS, ekran, xat, qo‘ng‘iroq)
→ Qaror (2–4 real tanlov)
→ Tanlovga mos 2-bosqichli o‘zgarish (scene graph)
→ Foydalanuvchi xulosasi
→ Natija + nima uchun + qaysi belgilarga qarash
→ Hayotiy qadam + takrorlash/bo‘lishish (xavfsiz maslahat)
```

### 7.2. UI maket: chat/email/fake-site/card

- Teparoqda `SIMULYATSIYA • 2/4`; haqiqiy banking UI bilan adashtirmaslik uchun mashq kontekstida doim belgi mavjud.
- Real ko‘rinishli **soxta SMS**: yuboruvchi, xabar va `linkni tekshirish` kartasi; havola hech qachon tashqi domenni ochmaydi.
- **Soxta sayt** maketi: domen yozuvi, aniq fikrlash uchun belgilarga bosish; real Google yoki bank login sahifasining chalkashtiradigan nusxasi emas.
- **Qo‘ng‘iroq** maketi: telefon ekraniga o‘xshash, `Qo‘ng‘iroqni yakunlash`, `Tasdiqlash`, `Tekshirish uchun vaqt so‘rash` kabi tanlovlar.
- **Zararli APK**: `Ilovani o‘rnatish` haqiqiy tugmasi yo‘q; faqat `O‘rnatmaslik` / `Fayl manbasini tekshirish` qarori.
- **QR** maketi: rasm bosilganda ilova ichidagi xavfsiz simulyatsiya URL preview; kamera yoki real QR skaneri majburiy emas.

### 7.3. Bir simulyatsiya uchun ma'lumot kontrakti

```json
{
  "id": "sim-phishing-001",
  "version": 3,
  "title": "Soxta bank SMS xabari",
  "topic_id": "phishing",
  "difficulty": "beginner",
  "duration_seconds": 240,
  "status": "published",
  "start_scene_id": "scene1",
  "scenes": [
    {
      "id": "scene1",
      "type": "sms_mock",
      "sender": "NamunaXizmat",
      "body": "Hisobingiz vaqtincha to‘xtatildi. Tekshiruv uchun example.invalid sahifasiga kiring.",
      "choices": [
        {"id": "c1", "label": "Havolani bosaman", "next_scene_id": "scene2"},
        {"id": "c2", "label": "Bankning rasmiy ilovasidan tekshiraman", "next_scene_id": "scene3"}
      ]
    }
  ],
  "skills": ["url-check", "social-engineering"],
  "review_status": "approved"
}
```

Bu JSON **shartnoma namunasi**, yakuniy to‘liq ssenariy emas. **Public clientga `risk`/score/answer key uzatilmaydi** — tanlov bahosi serverdagi maxsus jadvalda saqlanadi. Editor real scene'lar ketma-ketligini yaratadi; backend har tanlovda faqat ishonilgan `id`larni qabul qiladi, HTMLni sanitizatsiya qiladi. `example.invalid` rezerv test domeni; hech qaysi zararli tashqi domen simulyatsiyada avtomatik ochilmasin.

### 7.4. Simulyatsiya tugallangach

- `Siz nimani to‘g‘ri angladingiz?` va `E'tibor berish kerak bo‘lgan belgilar`; hech qachon `siz xavfsizsiz` kabi asossiz global xulosa emas.
- `Bu gal o‘tkazib yuborilgan 2 belgi` — foydalanuvchining haqiqiy tanlovlariga asoslanadi.
- `O‘rganish` linki — tegishli darsga; `O‘sha vaziyatni qayta sinash` → variantli yangi ssenariy, javob yodlashni kamaytiradi.
- `Natijani ulashish` shaxsiy ball/savol/ism bilan emas, faqat umumiy maslahat kartasi.
- `Qayta ko‘rib chiqish` statistikani ikkilamchi baholamasin; har urinish `attempt_id`, idempotency key bilan yoziladi.

### 7.5. Boshlang‘ich 20 ssenariy banki

| ID | Nomi | Voqea | Asosiy qaror |
|---|---|---|---|
| SIM01 | Soxta bank SMS | Hisob bloklangani aytiladi | Rasmiy ilovadan mustaqil tekshirish |
| SIM02 | Telegram ovoz berish | Do‘stning ismiga o‘xshash bot | OTP bermaslik |
| SIM03 | Faol seanslar | Begona qurilma haqidagi belgi | Sessiyalarni ilovadan tekshirish |
| SIM04 | Soxta yetkazib berish | Buyurtma uchun qo‘shimcha to‘lov | Rasmiy saytdan izlash |
| SIM05 | QR menyu | QR manzil boshqa saytga olib boradi | Domenni tekshirish |
| SIM06 | Bepul Wi-Fi | Parol talab qilmaydigan noma'lum hotspot | Maxfiy operatsiyani cheklash |
| SIM07 | “Rasmda sizmisiz?” APK | Notanish fayl keladi | O‘rnatmaslik |
| SIM08 | Firibgar qo‘ng‘iroq | Bank xodimidek gapiradi | Qo‘ng‘iroqni yakunlash va rasmiy kanal |
| SIM09 | Soxta Instagram login | O‘xshash domen | Adresni tekshirish |
| SIM10 | Soxta cashback | Hozir havolaga kiring deyishadi | Shoshmasdan mustaqil tekshirish |
| SIM11 | Wi-Fi captive portal | Begona profilni o‘rnatish talabi | Rad etish |
| SIM12 | Soxta ish taklifi | Avval to‘lov qilish | Dalilni tekshirish |
| SIM13 | QR to‘lov almashtirilgan | Terminal ustiga yopishtirilgan kod | Savdogardan aniqlash |
| SIM14 | Soxta chek | Faqat chek skrinshoti | Bank ilovasidagi balansni tekshirish |
| SIM15 | “Rahbar” xabari | Zudlik bilan pul o‘tkazish | Boshqa kanaldan tasdiqlash |
| SIM16 | Noma'lum USB | Begona qurilma | Ulamaslik |
| SIM17 | Yaqin nomdagi domen | `example.uz`ga o‘xshash | Domenning haqiqiy qismini topish |
| SIM18 | Tiklash kodi | Kodek yordamchisi deb so‘rash | Kodni hech kimga bermaslik |
| SIM19 | Parol qayta ishlatilgan | 2 xizmatda bir xil parol | Unikal parol tanlash |
| SIM20 | Yo‘qolgan telefon | Telefon topilmadi | Rasmiy bloklash va tiklash qadamlari |

**MVPda** SIM01–SIM05 to‘liq interaktiv; qolganlari nashr jadvaliga tayyorlanadi. Barcha 20 tasini chizib, ishlamaydigan karta qilib chiqarish taqiqlanadi.

---

## 8. PROFIL: NAZORAT EMAS, SHAXSIY RIVOJLANISH

**Yo‘l:** `/profile`. Sarlavha, ism/`Mehmon`, bugungi faoliyat holati; `Mening ko‘nikmalarim`, `Zaif tomonlarim`, `Tarix`, `Va’dalarim`, `Xavfsizlik checklisti`, `Bildirishnomalar`, `Til va ko‘rinish`, `Maxfiylik`, `Yordam`.

### 8.1. Ko‘nikma foizini to‘g‘ri hisoblash

- Har bir `skill` uchun **faqat yetarli dalil** bo‘lganda nisbiy tayyorgarlik ko‘rsatkichi ko‘rsating. Misol reja: kamida **3 mustaqil savol yoki 2 turli ssenariy**, aks holda `Hali ma'lumot yetarli emas`.
- Oxirgi 4 haftadagi muvaffaqiyatli mustaqil qarorlar og‘irlik bilan; 1 savolga 100% degan noto‘g‘ri tasavvur yo‘q.
- Shaxsiy tarixni serverga yuborish ixtiyoriy; mehmon uchun faqat qurilmada.
- `Zaif tomonlarim`: tartib eng ko‘p xato qilingan va ehtimoliy ta'siri yuqori mavzular bo‘yicha; `Mashq qilish` → shu mavzudan yangi simulyatsiya.
- Foydalanuvchiga qattiq `siz zaifsiz` deyilmaydi: `Mustahkamlashga arziydigan mavzular`.

### 8.2. Va'dalarim

- `Yoqaman` / `Bajardim` / `Keyinroq` kabi real holatlar; har kuni qayta bajarilganini so‘rash avtomatik faqat foydalanuvchi roziligi bilan.
- Bir kunda ko‘pi bilan 1 asosiy va'da tavsiya qilinadi. 5 ta checkbox bilan charchatib yubormang.
- Va'da `to‘g‘ri bajarildi` degan natijani ilova mustaqil tekshirgan deb ko‘rsatmaydi; foydalanuvchining o‘zi tasdiqlagan harakat ekanini ko‘rsating.
- Bajarilmagan va'da uchun salbiy banner, jarima yoki majburiy streak yo‘q.

---

## 9. O‘ZBEKISTON KIBEROGOHLANTIRISHLARI: FAKT-CHECK BILAN

### 9.1. Har alert uchun zarur maydonlar

`id, slug, title_uz, summary_uz, topic_id, severity, source_name, source_url, source_published_at, verified_at, editorial_status, active_from, active_until, region_scope, actionable_steps, related_scenario_id, last_reviewed_by`.

**Ekran:** sarlavha → `Manba` → `E'lon qilingan sana` → `Tekshirildi` → nima bo‘lyapti → belgilar → nima qilish → `Shuni mashq qilaman` → asli manbani ochish. `Tasdiqlangan` degani xabar tahririyatda tekshirilgan, hodisa ayni damda davom etyapti degani emas.

**Mantiq:** `draft → factcheck → approved → scheduled → published → expired → archived`. Moderatordan boshqa hech kim `published` qilolmaydi. Manba havolasi ishlamasa `review_needed`; muddati o‘tgan critical alert `archived`ga tushadi. Inson moderator ruxsatisiz sun'iy intellekt xabarni `dolzarb hujum` deb yubormaydi.

### 9.2. Alertlarning mavzulari

- Bank karta va SMS-kod firibgarligi.
- Telegram akkauntini egallash; soxta `ovoz bering` linklari.
- Zararli APK va noma'lum fayl o‘rnatish.
- Onlayn savdoda soxta chek/avans.
- Soxta davlat idorasi nomidan qo‘ng‘iroq.
- Soxta domenlar va phishing.

**Ogohlantirish importi:** manba URLlarini qo‘lda kiritish (MVP) → keyin RSS/API mavjud bo‘lsa ruxsatli import → editor tekshiradi → nashr. IIV/UZCERT kontentini avtomatik olib, ishonchliligini tekshirmasdan translyatsiya qilish yo‘q.

---

## 10. SOS — ENG MUHIM “REAL DUNYO” SAHIFASI

**Yo‘l:** `/sos`, ommaviy ochiq, kirishsiz va asosiy statik qismi oflayn ham ishlaydi.

Birinchi ekranda 5 ta katta tanlov:

1. `Akkauntimga begona kirdi` → avval rasmiy xizmatdan sessiyalarni yopish, parol/2FA, tiklash imkoniyati, yordam markazi.
2. `Karta yoki pul xavf ostida` → bankning **rasmiy** raqamiga o‘zi aloqaga chiqish, karta bloklash yo‘li, tranzaksiya dalilini saqlash.
3. `Shubhali havolani ochdim` → ma'lumot kiritganmi/yo‘qmi farqlash, sessiyalar, parol va qurilmani tekshirish.
4. `Noma'lum APK o‘rnatdim` → internet/ilova ruxsatlari haqida tushuntirish, xavfsiz o‘chirish/tahlil, ekspert yordami.
5. `Telefonim yo‘qoldi` → rasmiy qurilma topish/masofadan himoya funksiyalariga yo‘naltirish.

**Ehtiyot qoidasi:** rasmiy yordam raqamlari tekshirilmagan bo‘lsa telefon raqami uydirilmaydi. Ilova o‘zini bank, militsiya yoki favqulodda davlat xizmati sifatida tutmaydi. `SOS` sahifasi moliyaviy/legal kafolat bermaydi, birinchi xavfsiz choralarni ko‘rsatadi. Maxfiy dalil yoki karta raqamini ilovaga kiritishni so‘ramang.

---

## 11. BILIMNI ULASHISH: BEZARAR VA QULAY

`/share/today` → umumiy xavfsizlik maslahatidan 1 dona kartochka → `Ulashish` bosilsa, qo‘llab-quvvatlasa `navigator.share`; aks holda `Nusxalash` / `Rasmni saqlash`. Kontent o‘zbekcha, imlo to‘g‘ri, har kimga tushunarli. Hech qanday foydalanuvchining xatolari, darajasi, emaili yoki telefon raqami avtomatik qo‘shilmaydi. Foydalanuvchi qaysi ilovaga va kimga yuborishni **o‘zi** tanlaydi. Har kuni uyushtirilgan spamga yo‘l qo‘yilmaydi.

---

## 12. VA'DA BERING: XAVFSIZ ODAT MODULI

`/promise/today`: 1 mavzuga mos bir harakat: `Bugun Telegramimdagi qurilmalarni tekshiraman`. Uch holat `Tanladim` → `Bajardim` / `Keyinroq` va `Bajarganimni qanday tekshiraman?` yo‘riqnomasi. Bir foydalanuvchi bir kunda 1 tasini primary qiladi, boshqa tavsiyalar ichki ro‘yxatda. Bajarish natijasi **foydalanuvchi bildirgan**, avtomatik haqiqiy tekshiruv emas. Keyingi kun yengil eslatma va ixtiyoriy qayta ko‘rish. Maxfiylik/ruhiy bosimni oshiradigan `12 kun ketma-ket bajarilmadi` sharmandali UI yo‘q.

---

## 13. KUNLIK DINAMIK KONTENT: HAQIQATDAN HAR KUNI BOSHQA NARSA

### 13.1. Bir kunlik paket — API kontrakti

```json
{
  "date": "2026-11-05",
  "timezone": "Asia/Tashkent",
  "bundle_id": "UZ-2026-11-05-general-v2",
  "bundle_version": 2,
  "theme": {"topic_id": "account-security", "title": "Begona seansni aniqlash"},
  "mission": {"scenario_id": "SIM03", "estimated_minutes": 5},
  "question": {"question_id": "Q071"},
  "lesson": {"lesson_id": "L-telegram-04"},
  "tip": {"tip_id": "TIP08"},
  "promise": {"promise_id": "PROM12"},
  "alert": {"alert_id": null, "freshness": "none"},
  "fallback": false,
  "generated_at": "2026-11-04T18:00:00Z",
  "expires_at": "2026-11-05T18:59:59Z"
}
```

**Muhim:** `date` — `Asia/Tashkent` bo‘yicha kun; `Z` timestamp UTC. Paket bir kunga pin qilinadi; telefon soatini o‘zgartirib kun savolini sun'iy yangilatib bo‘lmasin. Shaxsiy tavsiya server/klient `cohort` va oxirgi natijalarga qarab nazoratli variantni olishi mumkin. O‘zbekiston ichida turli hudud uchun alohida kontent bo‘lsa region aniq tanlansin, GPS shart emas.

### 13.2. Selection engine — qadamlar

1. `published=true`, `active_from <= today < active_until`, `locale=uz-Latn-UZ`, to‘liq tahrir qilingan `daily_bundle`larni olish.
2. Bugungi global mavzu moderator taqvimidan olinadi. Kundalik savol/missiya/dars/va'da **bir mavzuda**, lekin turli shaklda bo‘lsin.
3. Shaxsiylashtirish uchun oxirgi 14 kunlik **takrorlangan qiyin mavzular**ga ruxsat etilgan variant tanlash; `bundle_id`, `variant_id`, `assignment_date` saqlanadi.
4. Bir kunda qayta ochilsa *shu paket* chiqadi; backend o‘rtada darsni yangilasa, eski bajarilgan attempt o‘z `content_version`iga bog‘lanib qoladi.
5. Paket offline bo‘lsa eng so‘nggi saqlangan `bundle` mavjud; sarlavhada eski sana va `yangilanmagan` ko‘rsatiladi. Soxta “bugungi” yorlig‘i ishlatilmaydi.
6. Muhim alert tasdiqlanib chiqqan bo‘lsa u dinamik kartada qo‘shiladi, lekin missiya IDni majburan almashtirmaydi.
7. `unpublish` qilingan xato kontentga kirish tezda to‘xtatiladi; offline keshlangan xavfli/eskirgan kontentni `revoked_content_ids` ro‘yxati orqali keyingi online paytda tozalash.
8. Yangi versiya serverda tayyor bo‘lmasa public home `Bugungi mashq tayyorlanmoqda` deb bo‘sh qoldirmaydi: 30 kunlik tasdiqlangan mavzu bankidan deterministic fallback.

### 13.3. Kunlik refresh

- Serverning maqsadli vaqt zonasi: `Asia/Tashkent` (O‘zbekiston uchun yagona). Paket mahalliy `00:00` da almanadi; cron ertangi paketlarni **bir kun oldin** build qiladi.
- Frontend `GET /api/v1/daily?date=YYYY-MM-DD&locale=...` orqali ETag bilan tekshiradi, qayta kirganda background refresh qiladi.
- Frontend local taymer vaqt zonasi almashganida `today`ni qayta hisoblaydi; haddan ortiq polling emas: initial, visibility change, manual refresh, kundalik chegara.
- `draft/scheduled/published/archived`; editor uchun `Preview tomorrow` va “published bundle missing?” monitoring.
- Kontent original tahrirlangan sanalari va `reviewed_by` auditga yoziladi.

### 13.4. Birinchi 14 kun — ishlab chiqishga tayyor tahririy reja

| Kun | Missiya | Kun savoli | Mikro-dars | Va'da |
|---:|---|---|---|---|
| 1 | Faol seanslar | Begona login belgisi | Telegram qurilmalar | Sessiyalarni tekshiraman |
| 2 | Bank SMS | SMSdagi havola | Phishing URL | SMS-kod bermayman |
| 3 | Telegram ovoz berish | OTP qachon talab qilinadi? | Telegram 2FA | 2FAni yoqaman |
| 4 | Zararli APK | `.apk` nimani bildiradi? | Ilova manbalari | Noma'lum o‘rnatishni o‘chiraman |
| 5 | Soxta yetkazish | To‘lov talabi | Online savdo | Rasmiy do‘kondan tekshiraman |
| 6 | Soxta Wi-Fi | Public Wi-Fi xavfi | Tarmoq xavfsizligi | Ochiq Wi-Fi'da to‘lov qilmayman |
| 7 | QR xavfi | QR domeni | QR va redirect | QRdan keyin URLga qarayman |
| 8 | Firibgar qo‘ng‘iroq | Ovozdagi shoshirish | Social engineering | Rasmiy raqamga o‘zim qo‘ng‘iroq qilaman |
| 9 | Soxta chek | Chek skrinshoti | Bank ilovasi tasdig‘i | Pul kelganini ilovadan ko‘raman |
| 10 | Parol takrori | Qayta ishlatilgan parol | Parol menejeri | 1 muhim parolni yangilayman |
| 11 | Soxta login | Domen farqi | URL qismlari | Sayt adresini mustaqil ochaman |
| 12 | Telefon yo‘qolishi | Qurilmani qidirish | Zaxira va screen lock | Qulfni tekshiraman |
| 13 | Soxta vakil | “Davlat xodimi” deb kod so‘rash | Tekshiruv usullari | Kodni aytmayman |
| 14 | Tiklash xabari | Reset link kimdan? | Recovery | Tiklash kontaktlarini ko‘rib chiqaman |

**30-kundan keyin:** bir xil kontent 1:1 takrorlanmaydi. Mavzu takrorlanishi mumkin, lekin ssenariy boshqa (`3-bosqich phishing`, `do‘st akkauntidan kelgan link`, `ish stoli emaili`). Spaced repetition: 1, 7, 21, 45 kun variantlari; noto‘g‘ri qaror bo‘lsa ertaroq, to‘g‘ri bo‘lsa kechroq. “Yangi mavzu” va “takrorlash” aniq yorliqlanadi.

---

## 14. API: BIRINCHI KUNDAN ANIQ ENDPOINT SHARTNOMASI

**Baza:** `/api/v1`. Har javob `Content-Type: application/json; charset=utf-8`, standart xato shakli. GET endpointlar idempotent; POST mutate endpointlar CSRF bilan himoyalangan. Response uchun server `request_id`, `server_time`, `content_version` qaytaradi. API versiya o‘zgarishi backward compatibility nazorati bilan.

### 14.1. Endpoint matritsasi

| Method | Route | Guest | Nima qiladi / natija |
|---|---|---|---|
| GET | `/app-config` | Ha | supported locales, version, feature flags |
| GET | `/daily` | Ha | bugungi yagona paket; `ETag` |
| GET | `/alerts?topic=&page=` | Ha | tasdiqlangan va yangiligi tekshirilgan alertlar |
| GET | `/alerts/:slug` | Ha | manbali alert batafsil |
| GET | `/topics` | Ha | mavzular ro‘yxati |
| GET | `/topics/:slug` | Ha | mavzu tafsiloti |
| GET | `/lessons/:slug` | Ha | sanitizatsiyadan o‘tgan dars JSON bloklari |
| GET | `/lessons/search?q=` | Ha | paginated qidiruv |
| GET | `/glossary?q=` | Ha | atamalar |
| GET | `/questions/:id` | Ha | savol va javob variantlari; **javob kaliti yo‘q** |
| POST | `/question-attempts` | Ha* | javob qabul qilish; correct + explanation server qaytaradi |
| GET | `/scenarios?topic=&level=` | Ha | katalog va tavsiyalar |
| GET | `/scenarios/:slug` | Ha | scene graph display data, **scoring secret yo‘q** |
| POST | `/scenario-attempts` | Ha* | attempt boshlash, ID olish |
| POST | `/scenario-attempts/:id/choices` | Ha* | bitta tanlov; server next scene/feedback beradi |
| POST | `/scenario-attempts/:id/finish` | Ha* | yakuniy hisob, tavsiyalar |
| POST | `/progress/sync` | Yo‘q | lokal progressni serverga idempotent yuborish |
| GET | `/me` | Yo‘q | profil |
| PATCH | `/me/preferences` | Yo‘q | til, tema, accessibility, notifications |
| GET | `/me/progress` | Yo‘q | mavzu progressi |
| GET | `/me/weaknesses` | Yo‘q | yetarli sample bo‘lsa zaif mavzular |
| GET | `/me/history` | Yo‘q | mashqlar tarixi |
| POST | `/promises` | Ha* | va'dani saqlash |
| PATCH | `/promises/:id` | Ha* | bajarildi/keyinroq |
| GET | `/me/promises` | Yo‘q | serverdagi va'dalar |
| POST | `/auth/register` | Ha | ro‘yxatdan o‘tish |
| POST | `/auth/login` | Ha | sessiya |
| POST | `/auth/logout` | Yo‘q | sessiya tugatish |
| POST | `/auth/recover` | Ha | parolni tiklash xabari, neytral response |
| POST | `/auth/reset` | Ha | token bo‘yicha yangi parol |
| GET | `/me/export` | Yo‘q | ma'lumot eksportini xavfsiz boshqarish |
| DELETE | `/me` | Yo‘q | akkauntni o‘chirish (qayta tasdiq bilan) |
| POST | `/feedback` | Ha | xato / taklif / alert tuzatish |
| GET | `/inbox` | Yo‘q | opt-in xabarlari |
| POST | `/push/subscription` | Yo‘q | ruxsat berilgan push subscription |
| DELETE | `/push/subscription` | Yo‘q | pushni o‘chirish |

`Ha*`: mehmonning anonim lokal attemptlari brauzerda ishlaydi; serverga jo‘natishda **anonymous ephemeral session** / rate-limited guest token ishlatiladi yoki offline lokal scoring schema tanlanadi. Har ikki yo‘lning privacy va manipulyatsiya tradeoffi hujjatlashtiriladi. Muhim narsa: guest imkoniyatlari login ekraniga majburan olib bormaydi.

### 14.2. Response va xato namunasi

```json
{
  "ok": true,
  "data": {"lesson": {"id": "L-telegram-04", "title": "Faol seanslar"}},
  "meta": {"request_id": "req_7e9", "server_time": "2026-11-05T08:12:00Z", "version": 1}
}
```

```json
{
  "ok": false,
  "error": {"code": "CONTENT_NOT_AVAILABLE", "message": "Bu mashq hozir mavjud emas", "field_errors": {}},
  "meta": {"request_id": "req_a3f"}
}
```

**Standart xato kodlar:** `VALIDATION_ERROR 422`, `UNAUTHENTICATED 401`, `FORBIDDEN 403`, `NOT_FOUND 404`, `VERSION_CONFLICT 409`, `TOO_MANY_REQUESTS 429`, `SERVER_ERROR 500`, `CONTENT_REVOKED 410`. Frontend har biri uchun odamlarcha izoh beradi; xatoda raw PHP stack trace yo‘q.

### 14.3. URL va API o‘rtasidagi yodda qoladigan qoida

- Frontend `/learn/lesson/:slug` = sahifa; server `/api/v1/lessons/:slug` = JSON data. Ikkisini bir marshrutga aralashtirmang.
- `read` endpoint cache ETag/Last-Modified bilan; `private progress` `Cache-Control: private, no-store` bilan.
- So‘rovda `Authorization` yoki cookie — tanlangan platforma modeliga bog‘liq; client kodida auth tokenlarni URL query orqali uzatmang.
- Sahna tanlovlarining **to‘g‘riligini va xavf qiymatini client JSdan ishonib qabul qilmang**; serverda canonical scoring.
- API request idempotency: `Idempotency-Key` / `event_id` yozib, ikki marta bosilganda progress ikki marta ko‘paymasin.

---

## 15. MYSQL 8+ — MA'LUMOTLAR BAZASINING ANIQ YADROSI

### 15.1. Jadval guruhlari

| Guruh | Jadvallar | Asosiy kalit/indeks |
|---|---|---|
| Accounts | `users`, `sessions`, `password_resets`, `user_preferences`, `consents` | `users.email` unique; `sessions.user_id` |
| Learning | `topics`, `lessons`, `lesson_blocks`, `question_bank`, `question_options`, `lesson_progress` | `lessons.slug` unique; `topic_id,status,locale` |
| Practice | `scenarios`, `scenario_versions`, `scenario_scenes`, `scene_choices`, `scenario_attempts`, `attempt_choices` | `(scenario_id, version)` unique; `attempt_id,scene_id` |
| Daily | `daily_bundles`, `daily_assignments`, `tips`, `promises`, `user_promises` | `(date,locale,cohort)` unique |
| Alerts | `alerts`, `alert_sources`, `alert_reviews` | `status,active_from,active_until`; source URL |
| Progress | `question_attempts`, `skill_evidence`, `user_skill_summaries` | `user_id,skill_id` |
| Admin | `admin_roles`, `user_roles`, `content_revisions`, `audit_events`, `feature_flags` | actor/time/event indexes |
| Ops | `jobs`, `outbox_events`, `idempotency_keys`, `failed_jobs` | dedupe id, retry indexes |

### 15.2. SQL migratsiyasi (asosiy jadval namunasi, *to‘liq migratsiya fayllariga ajratiladi*)

```sql
CREATE TABLE topics (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,
  title_uz VARCHAR(220) NOT NULL,
  difficulty ENUM('beginner','intermediate','advanced') NOT NULL DEFAULT 'beginner',
  is_published BOOLEAN NOT NULL DEFAULT FALSE,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE lessons (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  topic_id BIGINT UNSIGNED NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  title_uz VARCHAR(220) NOT NULL,
  estimated_minutes TINYINT UNSIGNED NOT NULL DEFAULT 3,
  content_version INT UNSIGNED NOT NULL DEFAULT 1,
  state ENUM('draft','review','published','archived') NOT NULL DEFAULT 'draft',
  published_at DATETIME NULL,
  FOREIGN KEY (topic_id) REFERENCES topics(id),
  KEY ix_lesson_lookup (topic_id,state,published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE daily_bundles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  calendar_date DATE NOT NULL,
  locale VARCHAR(24) NOT NULL DEFAULT 'uz-Latn-UZ',
  cohort VARCHAR(40) NOT NULL DEFAULT 'general',
  theme_topic_id BIGINT UNSIGNED NOT NULL,
  mission_scenario_id BIGINT UNSIGNED NOT NULL,
  lesson_id BIGINT UNSIGNED NOT NULL,
  question_id BIGINT UNSIGNED NOT NULL,
  tip_id BIGINT UNSIGNED NOT NULL,
  promise_id BIGINT UNSIGNED NOT NULL,
  state ENUM('draft','approved','scheduled','published','archived') NOT NULL DEFAULT 'draft',
  version INT UNSIGNED NOT NULL DEFAULT 1,
  UNIQUE KEY ux_day (calendar_date,locale,cohort),
  KEY ix_bundles_publish (state,calendar_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE scenario_attempts (
  id CHAR(36) PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  guest_session_id CHAR(36) NULL,
  scenario_id BIGINT UNSIGNED NOT NULL,
  scenario_version INT UNSIGNED NOT NULL,
  state ENUM('started','in_progress','finished','abandoned') NOT NULL DEFAULT 'started',
  started_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  finished_at DATETIME NULL,
  CHECK (user_id IS NOT NULL OR guest_session_id IS NOT NULL),
  KEY ix_attempt_user (user_id,started_at),
  KEY ix_attempt_scenario (scenario_id,scenario_version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**SQL alohida qaror:** haqiqiy `daily_bundles` uchun lesson/scenario/question/tip/promise jadvallarining FKlari, `users`, `alerts` va barcha migration rollbacklari yoziladi. Yuqoridagi SQL — arxitektura ko‘rsatish uchun qisman namuna; to‘g‘ridan-to‘g‘ri productionga yetarli emas.

### 15.3. Privacy va ma'lumot saqlash

- `users`: minimal email (ixtiyoriy) va parol xeshi; ismlar optional, telefon majburiy emas.
- Parol `password_hash()` bilan Argon2id imkon bo‘lsa; `password_verify()`; parol yoki reset token loglarga tushmaydi.
- Sirli JWTlarni `localStorage`da saqlamang. **Brauzer**: same-origin `Secure`, `HttpOnly`, `SameSite=Lax/Strict` session cookie + CSRF. **Flutter WebView**: mustaqil cookie storage va sessiya muddati haqiqiy Android/iOS qurilmada sinovdan o‘tadi; veb sessiya modelini o‘zboshimchalik bilan JWTga almashtirmang.
- Saqlash muddatlari siyosati: guest offline progress brauzer clear qilmaguncha; auth session ma'lum TTL; auditning kerakli ma'lumoti aniq belgilangan muddat; analytics aggregatsiya (retention yuridik bahoga bog‘liq).
- Export: `.json` file, download link max 10 min va bir martalik; delete: foydalanuvchi qayta tasdiqlaydi, account darhol bloklanadi, ma'lumotni o‘chirish queue orqali izchil boradi.
- MySQL avtomatik backup shifrlanadi, tiklash mashqlari har oy, backupda maxfiy kalit alohida.

---

## 16. BACKEND: PHP SERVISLAR, QOTMAYDIGAN SO‘ROVLAR

### 16.1. Service qatlamlari

```text
Controller         HTTP parsing, auth check, JSON response
Request Validator  format, size, type, content constraints
Service            business rules / daily assignment / scoring
Repository         PDO prepared statements, transactions
Domain Models      typed values, enum statuses, versioning
Events/Jobs        outbox, scheduled publishing, notifications
Middleware         rate limit, CSRF, CORS, role, audit
Infra              DB, cache, mail, files, cron, observability
```

**Har endpoint:** bounded payload, input validation, prepared SQL, pagination, max timeout, structured error; og‘ir statistikani real-time request paytida qayta hisoblamang — oldindan aggretatsiya qiling.

### 16.2. PHP backend non-functional mezonlari

| Ko‘rsatkich | Maqsad (reja, fakt emas) | Qanday tekshiramiz |
|---|---|---|
| Oddiy API p95 | <= 300ms server tarafida (test trafik) | k6 + real monitoring |
| `/daily` p95 | <= 250ms (warm cache) | 100 concurrent sinov |
| DB query | N+1 yo‘q; `EXPLAIN` bilan indexes | profiling |
| Bir API response | oddiy payload <= 100 KB gzipdan oldin (rasmlar alohida) | bundle analyzer |
| Open connection | PDO pool PHP-FPM resurslari bilan cheklangan | load test |
| Xato ehtimoli | 5xx < 0.5% maqsad | error dashboard |
| Backup | har kun, muvaffaqiyat xabari | restore sinovi |

### 16.3. Muhim endpointlarning backend logikasi

**`GET /daily`**: timezone → datega mapping → published bundle → if user logged in: `assignment` pin → bounded JOIN data → `ETag` → JSON. Database tushsa oldindan statik/public keshlangan generic daily pack yordam beradi; stale ekanini yashirmaydi.

**`POST /scenario-attempts/:id/choices`**: auth/guest identity → idempotency → attempt active? → scene edge allowed? → content version mavjud? → choice risk canonical score → transition → DB transaction → response. Bir vaqtning o‘zida double-submit kelsa biri `200 existing`, biri takror yaratilmaydi.

**`POST /promises`**: date + user/guest + promise ID → unique key → ikkinchi POST bo‘lsa oldingi yozuv → response. Bir kunlik bitta aktiv va'da biznes qoida.

**`POST /auth/recover`**: email qaysi bo‘lishidan qat'i nazar neytral `Agar bu manzil ro‘yxatda bo‘lsa, ko‘rsatma yuboriladi`; token random, DBda hash, TTL, rate-limit va bir martalik.

---

## 17. OFFLINE, CACHE VA SINXRONLASHTIRISH — BRAUZER/WEBVIEW HALOL TAQSIMOTI

**Tizimning uch muhiti:** (1) brauzer yoki ixtiyoriy PWA, (2) Android WebView, (3) iOS WKWebView. Service Worker/IndexedDB qo‘llab-quvvatlanishi va storage'ning umrini **real qurilmada** sinang. “Veb sayt offline 100% ishlaydi” degan kafolat berilmaydi.

| Funksiya | Onlayn browser | Onlayn Flutter | Offline browser | Offline Flutter |
|---|---|---|---|---|
| AppShell/4 tab | To‘liq | To‘liq | Cache bo‘lsa | Native fallback; cached web bo‘lsa ochishi mumkin |
| Oldin saqlangan dars | To‘liq | To‘liq | IndexedDB/cache | WebView storage yoki native read-only cache |
| SOS bazaviy yo‘riqnoma | To‘liq | To‘liq | Offline nusxa | **Flutter asset sifatida doim bor** |
| Kunlik yangi alert | Server | Server | Yo‘q, eski sana bilan | Yo‘q, eski sana bilan |
| Simulyatsiya | Server tasdiqli | Server tasdiqli | Maxsus offline practice | Maxsus offline practice faqat sinovdan keyin |
| Profil sync | To‘liq | To‘liq | Navbatda | Navbatda / server qaytganda |
| Push | Browser imkoniyatiga qarab | Flutter FCM/APNs | Kelmaydi | Internet bo‘lmasa yetmaydi |

### 17.1. Xotirada nimalar saqlanadi?

- **Public cache**: tekshirilgan dars matni, ikonka, kechagi maslahat, SOS yo‘riqnoma; har bir itemda `contentVersion`, `savedAt`, `lastVerifiedAt`.
- **Private**: ehtiyotkorlik bilan; email, cookie, token, maxfiy profil server cache/CDN'da `public` bo‘lmasin. Login session `Secure, HttpOnly, SameSite` cookie bilan.
- **Progress queue**: faqat tasodifiy `attemptId` (UUID), `contentVersion`, `answers` (sanitizatsiya qilingan), `createdAt`, `syncStatus`; backend idempotency.
- **Guest -> user merge**: foydalanuvchiga preview, dublikat qayta sanalmaydi. Importni rad etish mumkin.

### 17.2. Sinxronlash algoritmi

```text
Client completes action
  -> local event UUID + payload persisted (if supported)
  -> POST /api/v1/attempts or /progress/sync with Idempotency-Key
  -> server validates actor, scenario version, accepted choices
  -> 2xx: mark synced; 409: reconcile by eventId/version; 401: login banner
  -> retry ONLY after backoff and when connection restored
```

**Online scoring serverda.** Offline `practice` alohida "offline o‘quv mashqi" deb belgilanadi; server tasdiqsiz malaka darajasi yangilangan deb ko‘rsatilmaydi. Savol javoblari client bundle'da oldindan oshkor qilinmaydi (offline demo faqat o‘quv uchun istisno).

### 17.3. Service Worker va WebView muammolari

- Next.js runtime shell va versiyali statik resurslarni boshqaradi; Next RSC/Flight response hamda shaxsiy data noto‘g‘ri keshlanmasin.
- PWA service worker **majburiy emas**: agar qo‘shilsa Android Chrome va iOS Safari hamda WebViewda alohida test; eski build qayta paydo bo‘lib loginni buzmasligi kerak.
- Android/iOSdagi `storage` tozalanishi, app reinstall, qurilma xotira cheklovi — test ssenariysi. Foydalanuvchining server hisobidagi progressi yo‘qolmasin.
- Flutter offline SOS matni ilovaning lokal assets'ida minimal versiyada saqlanadi. Hamma joyda sana / offline ekanligi ochiq ko‘rsatiladi.


---

## 18. XAVFSIZLIK: CYBER TA'LIM ILOVASI O‘ZI HAM NAMUNA BO‘LSIN

### 18.1. Xavf modeli

| Xavf | Amaliy himoya |
|---|---|
| XSS (kontent editor orqali) | HTML sanitizatsiya + JSON typed blocks + CSP + escaping |
| SQL injection | PDO prepared statements, server validation |
| CSRF (cookie auth) | CSRF token + SameSite + origin check |
| IDOR (boshqa user progressi) | Har endpointda object-level authorization |
| Brute force | Login, reset, guest attempt rate limit, anti-automation |
| Session theft | HttpOnly Secure cookie, refresh/rotate, sign out everywhere |
| Fake news / soxta alert | Editor+factcheck+publish roles, URL va sana, audit |
| Simulyatsiyada real zarar | Sintetik ma'lumot, inert link, credentials yig‘maslik |
| PWA cache data leak | Private API `no-store`, logoutda local privacy reset policy |
| Adminni egallash | MFA, separate role, least privilege, audit, IP/risk monitoring |
| Supply chain | `composer audit`, `npm audit`, lockfiles, dependabot |
| WebView bridge risk | Androidga o‘tishda untrusted URLni web/native bridgega kiritmaslik |

**Muhim talab:** OWASP ASVS 5.0 dan API/auth/validation/logging sessiyalariga mos verifikatsiya; OWASP MASVS Android bosqichida. Security tests faqat o‘z test muhitida. Ruxsatsiz real xizmatlarga hujum testlari rejaga kirmaydi.

### 18.2. Yuridik/maxfiylik (tekshiriladigan ishlar)

- O‘zbekistondagi shaxsga doir ma'lumotlar bo‘yicha amaldagi qonun, saqlash/joylashtirish, rozilik va voyaga yetmagan foydalanuvchilar masalalarini yuridik maslahatchi bilan **release oldidan** tekshirish. Hujjat yuridik xulosa bermaydi.
- Foydalanuvchi uchun `Maxfiylik siyosati`, `Foydalanish shartlari`, `Aloqa`, `Ma'lumotni eksport qilish`, `O‘chirish` haqiqiy ishlaydi.
- Analitika: shakllantirilgan anonim eventlar; shaxsiy xabar va simulyatsiya matnini tashqi analyticsga jo‘natmang.
- Email yuborish xavfsizligi: SPF, DKIM, DMARC, password reset link domain tekshiruvlari.
- Push opt-in, istalgan vaqtda o‘chiriladi; uyqu/tungi vaqt himoyasi.
- Ochiq kontentni tashqaridan qayta nashr qilganda manba va mualliflik huquqini tekshirish.

---

## 19. ADMIN CMS: HAR KUNI DASTURCHI KODSIZ YANGILAY OLISHI KERAK

### 19.1. Admin sahifalari

| Panel | Nima qiladi? | Kim? | Qabul mezoni |
|---|---|---|---|
| `/admin` | kunlik paket, status, versiya, nosozlik | Admin/editor | ertangi paket ko‘rinadi |
| `/admin/content` | dars, savol, maslahat, va'da CRUD | Editor | draft/preview va validation |
| `/admin/scenarios` | sahnalar, tarmoqlanish, score, preview | Editor + reviewer | no dead-end scene, no live link |
| `/admin/alerts` | manba, sana, severity, review | Fact-check + publisher | sources/expiration majburiy |
| `/admin/calendar` | 30–90 kun oldindan mavzu jadvali | Editor | duplicate/bo‘sh kun warning |
| `/admin/users` | hisoblar, bloklash/appeal | Admin only | minimal PII, audit |
| `/admin/analytics` | signup, lesson complete, sim outcomes | Analyst | privacy-friendly aggregates |
| `/admin/audit` | kim qachon nimani o‘zgartirdi | Admin | append-only events |

### 19.2. Kontent workflow

1. Muallif draft yaratadi: sarlavha, maqsad, tinglovchi, media alt-text, dars izi, teglar.
2. `Content lint` tekshiruvi: imlo, uzunlik, mashq javobi, buzilgan link, vizual kontrast, real parol/OTP so‘rovi yo‘qligi.
3. **Mustaqil tekshiruvchi** xavfsizlik, oddiy til va faktlarni tekshiradi.
4. Preview: 320/393/768, dark/light, Uzbek/Til; scene graph validator ishlaydi.
5. Publisher tasdiqlaydi, vaqtini belgilaydi; `active_from/active_until`.
6. Cron ertangi `daily_bundle`ni tuzadi; `missing content` alert admin dashboardga.
7. Nashr qilingandan keyin feedback monitor; xato aniqlansa `rollback` va `revoked`.

**Ruxsatlar:** `author` kontent kiritsa ham `alert publish` qila olmaydi; `reviewer` tasdiqlaydi; `publisher` nashr etadi; `admin` foydalanuvchi va role boshqaradi. Moderatorning shaxsiy qaydlarida foydalanuvchi ma'lumotlari bo‘lmaydi.

---

## 20. HAR BIR SAHIFA — FOCUS/TUGMA/API/QAYTISH KONTRAKTI

Quyidagi ekranlar **public foydalanishga yaroqli web mahsulot** uchun topshiriladi. Tugmani bosganda hech narsa bo‘lmasligi mumkin emas; asosiy funksiya ishlamasa UI disabled holatini sababi bilan ko‘rsatadi. Har ekran `loading / empty / error / offline / success`, `browser back`, `keyboard/touch`, `320px` va `200%` shrift bilan tekshiriladi. `admin` ekranlari odatiy mobil navigatsiyaga qo‘shilmaydi.


### W01. Birinchi kirish — `/`

- **Maqsad:** Yo‘naltirish.
- **Ekranning tarkibi:** Brend belgisi va tez holat tekshiruvi.
- **Bosilish/navigatsiya:** Avval kirilmagan → /welcome; qaytgan → /home.
- **Data/server:** app-config; local onboarding flag.
- **Alohida holatlar:** Server uzilsa ham mehmon home uchun statik kontent ochiladi.
- **Qabul mezoni:** Cheksiz spinner bo‘lmasin, 2.5sdan keyin fallback; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W02. Onboarding — `/welcome`

- **Maqsad:** Ilovaning foydasini 3 slaydda tushuntirish.
- **Ekranning tarkibi:** Sarlavha, 1 rasm, 1–2 gap, keyingi va o‘tkazish.
- **Bosilish/navigatsiya:** Keyingi → keyingi slayd; O‘tkazish → /auth.
- **Data/server:** localized static copy.
- **Alohida holatlar:** Brauzer back avvalgi slaydga qaytadi.
- **Qabul mezoni:** Ekran reader, swipe bo‘lmasa tugmalar orqali yurish; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W03. Mehmon yoki hisob — `/auth`

- **Maqsad:** Ro‘yxatdan o‘tishga majburlamaslik.
- **Ekranning tarkibi:** Mehmon, Kirish, Ro‘yxatdan o‘tish, siyosat.
- **Bosilish/navigatsiya:** Mehmon → /home; Kirish → /auth/login.
- **Data/server:** guest local ID; auth service.
- **Alohida holatlar:** Server offline bo‘lsa mehmon yo‘li ishlaydi.
- **Qabul mezoni:** Mehmon primary variant bo‘lsin; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W04. Ro‘yxatdan o‘tish — `/auth/register`

- **Maqsad:** Ixtiyoriy hisob ochish.
- **Ekranning tarkibi:** Email, parol, qayta ko‘rish, rozilik.
- **Bosilish/navigatsiya:** Ro‘yxatdan o‘tish → email tasdiq/tasdiqlash.
- **Data/server:** POST /auth/register.
- **Alohida holatlar:** 409 email used; 422 xato; 429 limit.
- **Qabul mezoni:** Parol logga tushmasin, kesilgan input bo‘lmasin; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W05. Kirish — `/auth/login`

- **Maqsad:** Hisob progressini ochish.
- **Ekranning tarkibi:** Email, parol, ko‘rsat/yashir, tiklash.
- **Bosilish/navigatsiya:** Kirish → original protected route yoki /profile.
- **Data/server:** POST /auth/login.
- **Alohida holatlar:** 401 neytral, 429 urinish cheklangan.
- **Qabul mezoni:** Cookie Secure HttpOnly, CSRF ishlaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W06. Parolni tiklash — `/auth/recover`

- **Maqsad:** Hisobni xavfsiz tiklash.
- **Ekranning tarkibi:** Email input, submit, umumiy tasdiq.
- **Bosilish/navigatsiya:** Yuborish → vaqt va emailga ko‘rsatma izohi.
- **Data/server:** POST /auth/recover.
- **Alohida holatlar:** Email bor/yo‘qligini oshkor qilmaslik.
- **Qabul mezoni:** Token 1 martalik, TTL, rate-limit; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W07. Boshlang‘ich diagnostika — `/diagnostic`

- **Maqsad:** Foydalanuvchiga mos mavzuni topish.
- **Ekranning tarkibi:** 5 ta savol, skip, 1/5 progress.
- **Bosilish/navigatsiya:** Natija → tavsiya va /home.
- **Data/server:** question bank, local attempt.
- **Alohida holatlar:** O‘tkazib yuborish doimo bor.
- **Qabul mezoni:** 5 savoldan qat’iy baho bermaslik; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W08. Asosiy — `/home`

- **Maqsad:** Har kuni bir foydali ish.
- **Ekranning tarkibi:** Header, mission, verified alert, Q, lesson, weakness, share, promise.
- **Bosilish/navigatsiya:** Har karta o‘z routeiga; tablar ishlaydi.
- **Data/server:** GET /daily; GET /me/progress.
- **Alohida holatlar:** Guest / offline / completed / alertless.
- **Qabul mezoni:** 320pxda asosiy CTA va alert sanasi ko‘rinadi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W09. Bugungi missiya — `/mission/today`

- **Maqsad:** Bugungi vaziyatni kontekstga solish.
- **Ekranning tarkibi:** Sarlavha, vaqt, maqsad, mashq ogohlantirishi.
- **Bosilish/navigatsiya:** Boshlash → /practice/:slug/intro.
- **Data/server:** GET /daily.
- **Alohida holatlar:** Tugatilgan bo‘lsa “Qayta ko‘rish”.
- **Qabul mezoni:** Haqiqiy xizmatga ulanishni da’vo qilmaslik; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W10. Kun savoli — `/question/today`

- **Maqsad:** Bitta sodda savolda o‘rganish.
- **Ekranning tarkibi:** Savol, 2–4 large answer rows, tekshirish.
- **Bosilish/navigatsiya:** Tekshirish → /question/today/result.
- **Data/server:** GET /questions/:id; POST /question-attempts.
- **Alohida holatlar:** Bitta javob, double submit yo‘q.
- **Qabul mezoni:** Javob kaliti oldindan clientda oshkor emas; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W11. Kun savoli natijasi — `/question/today/result`

- **Maqsad:** Nima uchunligini tushuntirish.
- **Ekranning tarkibi:** To‘g‘ri/noto‘g‘ri, izoh, xavf belgisi, dars.
- **Bosilish/navigatsiya:** Darsni o‘rganish → /learn/lesson/:slug.
- **Data/server:** Attempt result.
- **Alohida holatlar:** Refresh → oldingi attempt ko‘rinadi.
- **Qabul mezoni:** Xato uchun uyaltiradigan til ishlatilmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W12. Ogohlantirishlar — `/alerts`

- **Maqsad:** So‘nggi rasmiy xabarlar.
- **Ekranning tarkibi:** Filtr, topic, date, cards, source tags.
- **Bosilish/navigatsiya:** Karta → /alerts/:slug.
- **Data/server:** GET /alerts.
- **Alohida holatlar:** Alert yo‘q → doimiy maslahatlar.
- **Qabul mezoni:** “Bugun” so‘zi tekshirilgan data bilan; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W13. Alert tafsiloti — `/alerts/:slug`

- **Maqsad:** Xavfni tushunish va ehtiyot chorasi.
- **Ekranning tarkibi:** Sana, manba URL, tekshirilgan sana, qadamlar.
- **Bosilish/navigatsiya:** Manbani ochish yoki simulyatsiya → /practice.
- **Data/server:** GET /alerts/:slug.
- **Alohida holatlar:** Expired → tarix yorlig‘i; link unavailable.
- **Qabul mezoni:** Ekranda source_url ko‘rinadi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W14. Bildirishnomalar — `/inbox`

- **Maqsad:** Ruxsat bilan kelgan xabarlar tarixi.
- **Ekranning tarkibi:** O‘qilgan/o‘qilmagan, category.
- **Bosilish/navigatsiya:** Xabar bosilganda to‘g‘ri route.
- **Data/server:** GET /inbox.
- **Alohida holatlar:** Guest = public alert page; permission denied alohida.
- **Qabul mezoni:** Push ruxsati sukut bo‘yicha berilmagan; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W15. Bilimlar katalogi — `/learn`

- **Maqsad:** Mavzularni tez tanlash.
- **Ekranning tarkibi:** Qidiruv, filtr, 12 topic kartasi, tavsiya.
- **Bosilish/navigatsiya:** Topic → /learn/topic/:slug.
- **Data/server:** GET /topics.
- **Alohida holatlar:** Offline cached topics; qidiruv bo‘sh.
- **Qabul mezoni:** Kartalardagi dars soni real nashrga mos; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W16. Mavzu tafsiloti — `/learn/topic/:slug`

- **Maqsad:** Darslar tartibi va o‘rganish yo‘li.
- **Ekranning tarkibi:** Qisqa tavsif, darslar, progres, misol.
- **Bosilish/navigatsiya:** Darsni ochish → /learn/lesson/:slug.
- **Data/server:** GET /topics/:slug.
- **Alohida holatlar:** Darslar yo‘q = published topic ko‘rsatilmaydi.
- **Qabul mezoni:** Har published lesson navigatsiya ishlaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W17. Dars o‘qish — `/learn/lesson/:slug`

- **Maqsad:** Bitta ko‘nikmani tushunish.
- **Ekranning tarkibi:** 1/5 bosqich, rasmlar, alt-text, keyingi.
- **Bosilish/navigatsiya:** Davom → keyingi; saqlash → history.
- **Data/server:** GET /lessons/:slug; local progress.
- **Alohida holatlar:** Offline oldindan keshlangan; tahrir v2.
- **Qabul mezoni:** 200% matn, klaviatura, progress saqlanishi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W18. Dars mini-test — `/learn/lesson/:slug/check`

- **Maqsad:** Bilimni mustahkamlash.
- **Ekranning tarkibi:** 1–3 savol, radio, feedback.
- **Bosilish/navigatsiya:** Tekshirish → /learn/lesson/:slug/finish.
- **Data/server:** POST /question-attempts.
- **Alohida holatlar:** Offline local result marker.
- **Qabul mezoni:** Tahlilni tanlovdan oldin chiqarmaslik; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W19. Dars yakuni — `/learn/lesson/:slug/finish`

- **Maqsad:** Nima o‘rganilganini yakunlash.
- **Ekranning tarkibi:** 2 bullet xulosa, qayta ko‘rish, mashq.
- **Bosilish/navigatsiya:** Simulyatsiya → shu mavzu catalog.
- **Data/server:** Progress record.
- **Alohida holatlar:** Offline sync pending banner.
- **Qabul mezoni:** Bir marta yoziladi, refresh duplicate emas; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W20. Bilim qidiruvi — `/learn/search`

- **Maqsad:** Atama yoki dars topish.
- **Ekranning tarkibi:** Input, mashhur atamalar, natija filtrlari.
- **Bosilish/navigatsiya:** Natija → dars/glossary.
- **Data/server:** GET /lessons/search.
- **Alohida holatlar:** Offline local search.
- **Qabul mezoni:** 2 belgi, debounce, uzun matn wrap; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W21. Atamalar lug‘ati — `/learn/glossary`

- **Maqsad:** Texnik atamani sodda izohlash.
- **Ekranning tarkibi:** Search, alifbo, misol, dars link.
- **Bosilish/navigatsiya:** Atama → anchor; misol → lesson.
- **Data/server:** GET /glossary.
- **Alohida holatlar:** No result → izlash variantlari.
- **Qabul mezoni:** O‘zbekcha atama + inglizcha sinonim; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W22. Takrorlash — `/learn/review`

- **Maqsad:** Unutilayotgan bilimga qaytish.
- **Ekranning tarkibi:** 3 tavsiya, nega chiqdi izohi.
- **Bosilish/navigatsiya:** Takrorlashni boshlash → dars yoki practice.
- **Data/server:** Progress/scheduler.
- **Alohida holatlar:** Yetarli data bo‘lmasa tasodifiy mos dars.
- **Qabul mezoni:** Shaxsiy zaiflikni uydirmaslik; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W23. Simulyatsiyalar katalogi — `/practice`

- **Maqsad:** Real hayot vaziyatini tanlash.
- **Ekranning tarkibi:** Category chips, card list, vaqt, qiyinlik.
- **Bosilish/navigatsiya:** Karta → /practice/:slug/intro.
- **Data/server:** GET /scenarios.
- **Alohida holatlar:** Guest + offline cached + empty.
- **Qabul mezoni:** Karta faqat published bo‘lsa ko‘rinadi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W24. Simulyatsiya kirish — `/practice/:slug/intro`

- **Maqsad:** Xavfsiz o‘yin qoidalarini tushuntirish.
- **Ekranning tarkibi:** Scenario summary, 3–6 min, “SIMULYATSIYA”.
- **Bosilish/navigatsiya:** Boshlash → /practice/:slug/play.
- **Data/server:** GET /scenarios/:slug.
- **Alohida holatlar:** Offline supported yoki info.
- **Qabul mezoni:** Real OTP/parol so‘ralmasligi ko‘rinadi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W25. Simulyatsiya o‘yin — `/practice/:slug/play`

- **Maqsad:** Tanlovli vaziyatni hal qilish.
- **Ekranning tarkibi:** Mock SMS/call/page, 1/N, 2–4 choices.
- **Bosilish/navigatsiya:** Tanlash → next scene; yakun → result.
- **Data/server:** POST /scenario-attempts/.../choices.
- **Alohida holatlar:** Exit confirmation, offline queue, stale version.
- **Qabul mezoni:** Scene graphda dead-end; double-submit bo‘lmasin; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W26. Natija va tahlil — `/practice/:slug/result`

- **Maqsad:** Sabab, belgi va tavsiyani anglash.
- **Ekranning tarkibi:** Qaror tahlili, o‘tkazilgan signallar, navbatdagi amal.
- **Bosilish/navigatsiya:** Darsga / yangi ssenariy / action.
- **Data/server:** POST /scenario-attempts/:id/finish.
- **Alohida holatlar:** Natija yo‘q bo‘lsa safe restart.
- **Qabul mezoni:** Tashqi bank xizmatiga o‘xshamaydigan maket; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W27. Hayotiy amaliy qadam — `/practice/:slug/action`

- **Maqsad:** O‘rganganini real hayotda qo‘llash.
- **Ekranning tarkibi:** Bitta checklist, rasmli yo‘riqnoma.
- **Bosilish/navigatsiya:** Bajardim/Keyinroq → /promise/today.
- **Data/server:** Promise service/local.
- **Alohida holatlar:** Yordam linki, no external auth automation.
- **Qabul mezoni:** Bajarildini foydalanuvchi tanlagani qayd; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W28. Profil — `/profile`

- **Maqsad:** O‘z holatini va sozlamani ko‘rish.
- **Ekranning tarkibi:** Guest/account, skills, weaknesses, history, promise, settings.
- **Bosilish/navigatsiya:** Har row tafsilotiga.
- **Data/server:** GET /me; local guest.
- **Alohida holatlar:** Guestdagi “akkauntni bog‘lash” ixtiyoriy.
- **Qabul mezoni:** Profil data noto‘g‘ri odamga chiqmasin; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W29. Ko‘nikmalarim — `/profile/skills`

- **Maqsad:** Mavzularni o‘zlashtirishni ko‘rish.
- **Ekranning tarkibi:** Topic list, evidence count, recommendation.
- **Bosilish/navigatsiya:** Mashq → /practice filtered.
- **Data/server:** GET /me/progress.
- **Alohida holatlar:** < minimum sample → “data yetarli emas”.
- **Qabul mezoni:** Foiz aniqligi tushuntirilgan; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W30. Zaif tomonlarim — `/profile/weaknesses`

- **Maqsad:** Mustahkamlashga arziydigan mavzular.
- **Ekranning tarkibi:** 2–5 topic, dalil soni, qayta mashq.
- **Bosilish/navigatsiya:** Mashq → mos simulyatsiya.
- **Data/server:** GET /me/weaknesses.
- **Alohida holatlar:** 3 savol yo‘q: sample data yetarli emas.
- **Qabul mezoni:** Xavfli uyaltiruvchi so‘zlar yo‘q; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W31. Tarix — `/profile/history`

- **Maqsad:** O‘qigan va mashq qilganlarini ko‘rish.
- **Ekranning tarkibi:** Date grouped lessons/scenarios, filter.
- **Bosilish/navigatsiya:** Item → o‘sha izoh/lesson.
- **Data/server:** GET /me/history.
- **Alohida holatlar:** Guest IndexedDB; empty friendly.
- **Qabul mezoni:** Ruxsatsiz boshqa account tarixi chiqmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W32. Va’dalarim — `/profile/promises`

- **Maqsad:** Har kuni o‘zimga bergan va’dalarni boshqarish.
- **Ekranning tarkibi:** Date, selected/done/later, edit.
- **Bosilish/navigatsiya:** Promise → /promise/:id yoki today.
- **Data/server:** GET /me/promises.
- **Alohida holatlar:** Offline guest lokal saqlaydi.
- **Qabul mezoni:** O‘tmishda noto‘g‘ri streak bosimi yo‘q; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W33. Xavfsizlik checklist — `/profile/checklist`

- **Maqsad:** O‘z qurilmasini nazorat qilish.
- **Ekranning tarkibi:** 2FA, password, apps, backup, sessions.
- **Bosilish/navigatsiya:** Har item → rasmli guide.
- **Data/server:** Local checklist / optional sync.
- **Alohida holatlar:** Ish bajarilmagan bo‘lsa label honest.
- **Qabul mezoni:** Haqiqiy skanerlangan degan da’vo yo‘q; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W34. Til va ko‘rinish — `/profile/preferences`

- **Maqsad:** O‘qishga qulay sozlama.
- **Ekranning tarkibi:** UzLotin, light/dark/system, shrift, motion.
- **Bosilish/navigatsiya:** Tanlash → darhol preview/saqlash.
- **Data/server:** PATCH /me/preferences; local.
- **Alohida holatlar:** Offline darhol ishlaydi.
- **Qabul mezoni:** 200% matn bilan butun app test; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W35. Bildirishnoma sozlamalari — `/profile/notifications`

- **Maqsad:** Eslatmani foydalanuvchi nazorat qiladi.
- **Ekranning tarkibi:** Opt-in toggle, sokin vaqt, mavzular.
- **Bosilish/navigatsiya:** Yoqish → permission prompt faqat shundan so‘ng.
- **Data/server:** Notification/subscription.
- **Alohida holatlar:** Browser denied = izoh, qayta so‘rash spam emas.
- **Qabul mezoni:** Bir kunda ko‘p notification yo‘q; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W36. Maxfiylik — `/profile/privacy`

- **Maqsad:** Ma’lumot kimda ekanini tushuntirish.
- **Ekranning tarkibi:** Qaysi data saqlanadi, cookie, exports, delete.
- **Bosilish/navigatsiya:** Export/delete mos route.
- **Data/server:** GET /me, legal pages.
- **Alohida holatlar:** Guest uchun lokal ma’lumotni tozalash.
- **Qabul mezoni:** Tushunarli o‘zbekcha siyosat; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W37. Ma’lumot eksporti — `/profile/export`

- **Maqsad:** O‘z data nusxasini olish.
- **Ekranning tarkibi:** JSON format, yaratish, yuklab olish.
- **Bosilish/navigatsiya:** Download → tasdiqlangan fayl.
- **Data/server:** GET /me/export.
- **Alohida holatlar:** Retry/expired link.
- **Qabul mezoni:** Begona foydalanuvchi kira olmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W38. Akkaunt o‘chirish — `/profile/delete`

- **Maqsad:** Hisob va ma’lumotni olib tashlash.
- **Ekranning tarkibi:** Oqibatlar, qayta tasdiq, cancel.
- **Bosilish/navigatsiya:** Tasdiq → DELETE /me → logout.
- **Data/server:** DELETE /me.
- **Alohida holatlar:** Re-auth kerak, online-only.
- **Qabul mezoni:** Cancel osongina, delete ikki bosqich; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W39. Maslahat ulashish — `/share/today`

- **Maqsad:** O‘rgangan xavfsizlik qoidasini ulashish.
- **Ekranning tarkibi:** Share card, preview, share, copy.
- **Bosilish/navigatsiya:** Share sheet yoki copy fallback.
- **Data/server:** GET /daily tip.
- **Alohida holatlar:** Web Share API yo‘q = copy.
- **Qabul mezoni:** Private user natijalari yuborilmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W40. Maslahatlar arxivi — `/share/archive`

- **Maqsad:** Avvalgi maslahatlarni ko‘rish.
- **Ekranning tarkibi:** Date, topic, copy/share.
- **Bosilish/navigatsiya:** Tip → /share/today?tip=.
- **Data/server:** GET /tips.
- **Alohida holatlar:** No result = today tip.
- **Qabul mezoni:** Eski tip “bugungi” deyilmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W41. Bugungi va’da — `/promise/today`

- **Maqsad:** Bitta foydali odat tanlash.
- **Ekranning tarkibi:** 1 action, nima uchun, 3 holat.
- **Bosilish/navigatsiya:** Tanladim/Bajardim/Keyinroq.
- **Data/server:** POST /promises; PATCH /promises/:id.
- **Alohida holatlar:** Offline queue; 409 sync conflict.
- **Qabul mezoni:** Bir kunlik primary bitta; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W42. Va’da tafsiloti — `/promise/:id`

- **Maqsad:** Va’dani qanday bajarishni ko‘rsatish.
- **Ekranning tarkibi:** Qadamlar, manba yoki ichki guide.
- **Bosilish/navigatsiya:** Bajardim → local/server status.
- **Data/server:** GET/PATCH /promises/:id.
- **Alohida holatlar:** Expired? tarixda ko‘rish mumkin.
- **Qabul mezoni:** Foydalanuvchi bajardim degani aniq; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W43. SOS tanlash — `/sos`

- **Maqsad:** Shoshilinch xavfda soddalik.
- **Ekranning tarkibi:** 5 katta muammo, tez qadam.
- **Bosilish/navigatsiya:** Tanlov → /sos/:kind.
- **Data/server:** Static guide.
- **Alohida holatlar:** Offline ham ochiladi.
- **Qabul mezoni:** Kirishsiz 2 tap ichida yo‘riqnoma; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W44. SOS tafsilot — `/sos/:kind`

- **Maqsad:** Xavf bo‘yicha birinchi harakatlar.
- **Ekranning tarkibi:** Hozir, keyin, rasmiy aloqa, eslatma.
- **Bosilish/navigatsiya:** Qadamni ochish / source → tashqi link.
- **Data/server:** Static checked guide + CMS sources.
- **Alohida holatlar:** Offline source tekshirilmadi disclaimer.
- **Qabul mezoni:** Hech qanday karta/parol/OTP yig‘ilmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### W45. Fikr bildirish — `/feedback`

- **Maqsad:** Kontent xatosi yoki taklif.
- **Ekranning tarkibi:** Topic, category, message (optional email).
- **Bosilish/navigatsiya:** Yuborish → tasdiq.
- **Data/server:** POST /feedback.
- **Alohida holatlar:** 429 cooldown, offline draft.
- **Qabul mezoni:** Har foydalanuvchi shaxsiy data yubormasligi haqida eslatma; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A01. Admin boshqaruv — `/admin`

- **Maqsad:** Kunlik operatsion holat.
- **Ekranning tarkibi:** Bugun/ertaga, published alerts, errors.
- **Bosilish/navigatsiya:** Paneldan tegishli editor.
- **Data/server:** Role-gated admin APIs.
- **Alohida holatlar:** 401 login; 403 no permission.
- **Qabul mezoni:** Admin MFA majburiy; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A02. Kontent tahrir — `/admin/content`

- **Maqsad:** Dars/savol/maslahat tayyorlash.
- **Ekranning tarkibi:** Form, validation, preview, revisions.
- **Bosilish/navigatsiya:** Draft → review → publish.
- **Data/server:** CRUD content APIs.
- **Alohida holatlar:** Undo/revision restore.
- **Qabul mezoni:** Publish role ajratilgan; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A03. Simulyatsiya editor — `/admin/scenarios`

- **Maqsad:** Scene graph tuzish.
- **Ekranning tarkibi:** Node canvas/form fallback, branch check.
- **Bosilish/navigatsiya:** Preview, approve, schedule.
- **Data/server:** CRUD scenario versions.
- **Alohida holatlar:** Dead-end/invalid risk rejasi bloklanadi.
- **Qabul mezoni:** Real zararli linkni publish qilish mumkin emas; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A04. Alert faktcheck — `/admin/alerts`

- **Maqsad:** Manbali xabar nashri.
- **Ekranning tarkibi:** Title, source URL, date, editor, expiration.
- **Bosilish/navigatsiya:** Approve → schedule/publish.
- **Data/server:** CRUD alerts / audit.
- **Alohida holatlar:** Muddati yo‘q critical alert blok.
- **Qabul mezoni:** Oldingi alert rollback qilinadi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A05. Kunlik kalendar — `/admin/calendar`

- **Maqsad:** 30–90 kun oldindan mavzu tartibi.
- **Ekranning tarkibi:** Date grid, health check, variant.
- **Bosilish/navigatsiya:** Erta/kecha preview, publish.
- **Data/server:** CRUD daily bundles.
- **Alohida holatlar:** Bo‘sh paket warning/fallback.
- **Qabul mezoni:** Bir sana bir cohort bitta bundle; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A06. Hisoblar boshqaruvi — `/admin/users`

- **Maqsad:** Ruxsat va minimal support.
- **Ekranning tarkibi:** Search user ID/email, status.
- **Bosilish/navigatsiya:** Disable/re-enable only role.
- **Data/server:** role-gated users API.
- **Alohida holatlar:** PII minimum, action audit.
- **Qabul mezoni:** Foydalanuvchi tarixini ommaga ochmaydi; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A07. Admin audit — `/admin/audit`

- **Maqsad:** Kim nima qilganini bilish.
- **Ekranning tarkibi:** Actor, action, time, before/after ID.
- **Bosilish/navigatsiya:** Filter/export.
- **Data/server:** immutable audit events.
- **Alohida holatlar:** Sensitive fields mask.
- **Qabul mezoni:** Append-only log; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

### A08. Admin analitika — `/admin/analytics`

- **Maqsad:** Mahsulot foydasini o‘lchash.
- **Ekranning tarkibi:** Aggregated activation/completion/safety metrics.
- **Bosilish/navigatsiya:** Filter by range/topic.
- **Data/server:** analytics aggregate API.
- **Alohida holatlar:** Small cohort privacy threshold.
- **Qabul mezoni:** PII raw log yo‘q; umumiy loading/empty/error/offline, 320px/200% shrift, klaviatura, screen reader va deep-link testlari bajariladi.

---

## 21. FOYDALANUVCHI OQIMLARI — “2 TAPDA FOYDA” TAMOYILI

### 21.1. Birinchi kelgan odam (onboarding bilan)

```text
Google/Telegram tavsiya havolasi → /welcome
→ 3 qisqa slayd (skip mumkin)
→ Mehmon bo‘lib davom etish
→ /home → bugungi missiya
→ /practice/:slug/intro → /play
→ /result (nima uchun?)
→ /action (bitta amal)
→ /promise/today (ixtiyoriy)
```

**Tekshiruv:** ro‘yxatdan o‘tmasdan 2–3 minut ichida birinchi foydani olish mumkin. Agar onboarding uzun bo‘lsa, birinchi sahifadayoq `Darhol boshlash` ko‘rinadi. Brauzerda yangi oynaga tashlab yubormang.

### 21.2. Har kuni qaytadigan foydalanuvchi

```text
PWA icon yoki browser bookmark → /home
→ bugungi nom va sana
→ kun savoli → izoh
→ bugungi mikro-dars → kichik sinov
→ vaqt bo‘lsa missiya → tahlil
→ va'da yoki ulashish
```

**Qabul:** kunlik paket o‘sha kun ichida qayta boshlanganida o‘zgarmaydi, hal qilingan savol yana noma'lumdek chiqmaydi. Qo‘shimcha faoliyat uchun qat'iy bosim yo‘q.

### 21.3. Xavfga uchragan odam

```text
Har qanday tab → [SOS] → /sos
→ “Kartam xavfda”
→ 1. Bank rasmiy aloqasi  2. Karta bloklash yo‘li  3. Dalil saqlash
→ kerakli rasmiy qo‘llanma (internet talab qilsa ochiq aytiladi)
```

**Qabul:** `SOS`ga <=2 marta tegish; 320px ekranda birinchi amaliy qadam scrollsiz chiqishiga intilish; offline ham matn bor. Ilova bank raqamini avtomatik uydirmaydi.

### 21.4. Internet uzildi

```text
/home → offline banner → saqlangan dars / saqlangan 5 mashq
→ attempt local queue → internet qaytganda background sync
→ “Natija saqlandi”  yoki  “Sinxronlashni tugatish kerak”
```

**Qabul:** retry ikki marta sanalmaydi; backendda 409 to‘qnashuv foydalanuvchi e'tiboridan yashirilmaydi, natija yo‘qolmaydi.

### 21.5. Mehmon → hisob ochish

```text
/profil → “Natijalarni qurilmalar orasida saqlash”
→ /auth/register → tasdiq
→ “Ushbu qurilmadagi mashqlaringizni hisobga qo‘shamizmi?”
→ import preview → confirm → sync (event id dedupe)
```

**Qabul:** login bilan mehmon progressi izsiz o‘chib ketmaydi; foydalanuvchi xohlamasa import qilmaydi.

### 21.6. Tizimga kirmasdan ulashish

```text
/home → Bilimingizni ulashing → maslahati preview
→ navigator.share (agar mavjud) / copy fallback
→ messengerga jo‘natishni foydalanuvchi o‘zi tasdiqlaydi
```

**Qabul:** foydalanuvchi ruxsatisiz avtomatik hech kimga xabar yuborilmaydi; uzun maslahat kartasini screen-reader o‘qiydi.

---

## 22. NEXT.JS + FLUTTER ISHLASH TEZLIGI — “QOTMASIN” TALABLARI

**Maqsad (field data, mobil 75-percentil):** LCP ≤ 2.5 s, INP ≤ 200 ms, CLS ≤ 0.1. Bu real telemetriya maqsadi; optimallashtirilmaguncha kafolat emas. Next.js SSR va WebView startup alohida o‘lchanadi.

| Yo‘nalish | Maqsad / qoida | Amaliy yechim | Tekshiruv |
|---|---|---|---|
| Flutter startup | Splash qotmaydi; 8–10 s ichida fallback | Native splash → loading → timeout/offline UI | Cold/warm startup |
| Next first paint | Birinchi foydali matn tez chiqsin | App Router Server Components, `loading.tsx`, streaming, minimal client JS | Slow 4G / CPU throttle |
| WebView gesture | Scroll jank yo‘q | Og‘ir blur, 3D va uzoq JS looplarni cheklash | Eski Android/iPhone |
| Rasmlar | Kritik rasmlarda o‘lcham oldindan belgilangan | `next/image`, WebP/AVIF, responsive, SVG | CLS / Network |
| JS | Har sahifaga faqat kerakli client JS | `use client` chegarasini qisqartirish, dynamic import, `next/link` | Bundle analyzer |
| API | p95 warm ≤ 250ms — loyiha maqsadi | PHP OPcache, DB indekslar, eager join, bounded payload | Load test |
| Navigation | WebViewda qo‘shimcha reload bo‘lmasin | Next `Link`, App Router, browser history | Back/deep-link test |
| Tezlik uchun cache | Public content cache; private no-store | Next server fetch policy explicit, CDN private cache yo‘q | Auth cross-user test |
| Offline | Spinner emas, foydali izoh | Native fallback + lokal material | Airplane mode |

**Muhim:** Next.js caching semantikasi versiyaga qarab o‘zgaradi. `use cache` yoki `revalidate`ni har route uchun ko‘r-ko‘rona yozmang. `public` darslar revalidatsiyalanadi; **foydalanuvchining natijasi, sessioni, profil ma'lumotlari public cache'da hech qachon qolmaydi**. Admin yangilanishidan keyin tegishli kontent cache invalitatsiyasi aniq kontraktga ega bo‘lishi shart.

### 22.1. Performance budget (dastlabki, sinov orqali sozlanadi)

- Home above-the-fold'ga 1 ta hero, 1 ta xabar, kun savolining boshlanishi yetadi. Og‘ir grafik / katta SVG/3D yo‘q.
- Sahifa uchun faqat bitta shrift oilasi, optimal subset, fallback font.
- React component tree'da har scrollga 30 ta re-render qilmaslik; list virtualizatsiyasi faqat uzun kataloglarda.
- Slow network / server down test: 8 sekundlik kutishdan keyin “Ulanmadi” va aniq keyingi harakat.
- Client-side kun sanasi ekran uchun; kanonik daily paket ID/date serverdan, `Asia/Tashkent` kundan o‘tish holatini boshqaradi.
- Flutter native monitoringda cold start, WebView load, HTTP error, navigation reject, JS bridge error, ANR/crash alohida yozilsin — hech qachon parol yoki test javoblari loglanmasin.


---

## 23. FLUTTER WEBVIEW ANDROID + IOS: TO‘LIQ QOBIQ REJASI

### 23.1. Qat'iy qaror

- **Bitta Flutter kod bazasi**: `flutter/` ichida `lib/`, `android/`, `ios/` platforma loyihalari.
- **`webview_flutter`** asosiy paket: Androidda tizim WebView, iOSda WKWebView. Hozirgi barqaror versiyani lockfile bilan pin qiling; supported Android/iOS minimal OS plugin README va release siyosati bo‘yicha tekshiriladi.
- Flutter **onlayn Next.js domenni URL orqali** ochadi; HTML/JS to‘liq Flutter asset sifatida paketlanmaydi. Shuning uchun bu reja Capacitor va static-export rejasidan farqli.
- Flutter qobiqdagi tizim ruxsatlari foydalanuvchi talab qilmaguncha ko‘rsatilmaydi.

### 23.2. Flutterga tegishli minimal ekranlar

| Flutter ekrani | Qachon ko‘rinadi | UX | Web'dan farqi |
|---|---|---|---|
| Native Splash | cold start | Logo + fon, tez o'tish | Ilova ishga tushishida kerak |
| Loading | URL birinchi marta ochilmoqda | Skeleton / progress, uzoq spinner emas | Next HTML kelguncha |
| Internet yo‘q | Main frame yuklanmadi / airplane | Retry + Offline SOS + help | Flutter asset UI |
| WebView | Ishlayotgan sayt | Web'ning 4 tab navigatsiyasi | Native gesture / system UI |
| External link | Rasmiy manba / help / `mailto:` | Tizim brauzeriga / mos ilovaga | Domain allowlist |
| Share | “Bilimni ulashish” | Native share sheet, aks holda web fallback | OS API |
| Notification tap | Foydalanuvchi roziligidan keyin | Deep link -> ichki ruxsat etilgan route | Native APNs/FCM |
| Logout | Web hisobdan chiqdi | Server invalidate; web cookie o'chish sinovi | Native auth UI nusxasi emas |

### 23.3. URL siyosati

- `https://APP_DOMEN/*` asosiy WebView ichida. Exact scheme HTTPS + exact approved hostname (optional `www` oldindan aniqlanadi), `userinfo`, noto‘g‘ri port, `javascript:`/`file:`/`data:` bloklanadi.
- `https://gov.uz`, `https://csec.uz`, yoki boshqa tekshirilgan rasmiy manbalar **WebView ichida emas**, foydalanuvchi bosganda external browserda. Manbalarning mavjudligi va aynan URL tekshiriladi.
- `tel:`/`mailto:` foydalanuvchi bosganida mos tizim ilovasi; `intent:`, custom scheme va `market:` faqat alohida qat'iy allowlist hamda platforma siyosatiga muvofiq.
- Redirect chain xavfsizlik tekshiruvini chetlab o‘tmasin; `NavigationDelegate` + native platform hook va kontentdagi safe-link politikasi testdan o‘tsin.
- O‘zingiz nazorat qilmaydigan HTML WebView ichida render qilinmaydi; simulyatsiyadagi “soxta sayt” — tashqi zararli URL emas, **ichki xavfsiz maket**.

### 23.4. Back, refresh, lifecycle

- Android Back: WebView ichida oldingi route mavjud bo‘lsa `goBack()`; agar root `/home` bo‘lsa ilovadan chiqish yoki “Chiqasizmi?” policy; faol mashqda chiqishda `Progress yo‘qolishi mumkin` confirmation.
- iOS: `WKWebView` swipe-back qo‘llanadi, route back toolbar affordance bo‘ladi. Native appdan o‘zboshimchalik bilan exit qilinmaydi.
- App background → foreground: imkon bo‘lsa holat saqlanadi; refresh majburiy emas; sessiya muddati o‘tgan bo‘lsa `Kirish` va progressni saqlash yo‘li.
- On restore: oxirgi xavfsiz route (masalan `/learn/...`) yoki `/home`, token/OTP URL queryga yozilmaydi.
- Pull-to-refresh majburiy emas; simulyatsiya va form ichida refreshni bloklash yoki holatni saqlash, asosiy sahifada `Yangilash` aniq.
- Android ichki hardware keyboard, iOS keyboard inset, telefon klaviaturasi ko‘tarilganda `bottomNav` qoplanishi test qilinadi.

### 23.5. Safe Area — ikki marta padding qilinmasin

**Bir mas'ul tanlaymiz:** Flutter `Scaffold` va `SafeArea` orqali WebView viewportini tizim paneli/notch/home-indicatordan ajratadi. Web sahifada `data-shell="flutter"` borligida `--app-safe-top/bottom` tokenlari `0px` bo‘ladi; oddiy Safari/Chrome/PWA'da `env(safe-area-inset-*)` qo‘llanadi. `?shell=flutter` yoki app marker **faqat uslub/UX ishorasi**, u hech qachon login yoki API ruxsati beruvchi xavfsizlik belgisi emas. Browserda qo‘lda yozish mumkinligini unutmang.

- **Bitta pastki tab bar:** `Asosiy / Bilimlar / Simulyatsiya / Profil` — **Next.js HTML/CSS'da** chiziladi. Flutter yana bitta BottomNavigationBar chizmaydi.
- Flutter asosiy native system chrome va har doim ishlaydigan native offline/SOS fallback'ni boshqaradi.
- Agar native tab bar tanlansa, butun app bo‘yicha qarorni yangidan rasmiylashtirish kerak; web va native ikki nav birga ko‘rinmaydi.

### 23.6. Native qiymat va do‘kon talablari

Apple App Store'da oddiy URL wrapper yetarli bo‘lmasligi mumkin: appga mustaqil foyda olib kiradigan **offline SOS**, native share, foydalanuvchi roziligi bilan push, ishlaydigan app-level offline/error, to‘g‘ri deep link va to‘liq o‘quv/simulyatsiya funksiyasi qo‘shiladi. Review'dan o‘tish baribir kafolatlanmaydi. Google Play uchun ham barqaror, foydali, crash bo‘lmaydigan funksionallik, xavfsiz URL va amaldagi target SDK/release talablari tekshiriladi.

### 23.7. Android va iOS alohida build rejasi

**Android:** Flutter stable SDK; Android Studio; `applicationId`, icon, splash, HTTPS internet permission, `usesCleartextTraffic=false`, release keystore (sir saqlash), App Links `assetlinks.json`, FCM faqat ruxsat olgach, `flutter build appbundle`, Play internal/closed test, staged rollout.

**iOS:** Mac + Xcode + signing/provisioning; `Bundle Identifier`, icons, launch, ATS HTTPS, Universal Links `apple-app-site-association`, APNs/FCM optional, `flutter build ipa` (mos muhitda), TestFlight, App Store Connect review. **iOS release Windows'da mustaqil Xcode'siz bajarilmaydi**; CI Mac build agent yoki Mac talab qilinadi.

**TWA/Capacitor ko‘rsatmalari 3.0dan olib tashlandi:** bu loyiha uchun ikkita native container yaratmaymiz. WebView va native qatlamlar uchun barcha regressiya birgalikda sinovdan o‘tadi.


---

## 24. BROWSER / PWA — FLUTTERDAN ALOHIDA QO‘SHIMCHA TARQATISH

Asosiy mahsulot baribir brauzerda ishlaydigan Next.js sayt. `PWA`ni xohlagan foydalanuvchi o‘rnata olishi qulay, **ammo Flutter WebView'ning asosiy sharti EMAS**. PWA manifest qo‘shilgan bo‘lsa ham Flutter ilovasi uning ichida boshqa PWAni “install” qilmaydi.

- Next.js `app/manifest.ts` va `public/icons/` bilan nom, theme, 192/512 px ikonlar, `start_url=/home`.
- Next.js server-side SSR ishlashi uchun static-exportga majburlamaymiz.
- Service Worker faqat haqiqiy offline sinovdan keyin; RSC/Flight, auth cookie va shaxsiy APIlarni noto‘g‘ri cache qilish xavfi tahlil qilinadi.
- Web UI platformaga qarab install taklifini ko‘rsatadi. **Flutter WebView'da “Ilovani o‘rnating” banneri yashiriladi**, chunki foydalanuvchi allaqachon ilovada.
- Website share `navigator.share()` bo‘lsa ishlatadi, bo‘lmasa copy; Flutterda bridge yoqilsa native share sheet; foydalanuvchi harakatidan tashqari avtomatik ulashish yo‘q.
- Browser push platformaga qarab ishlashi mumkin; Flutter uchun alohida native FCM/APNs channel va bitta umumiy notification preferences manbasi.


---

## 25. REPO VA PAPKALAR — ANIQ NEXT.JS / FLUTTER / PHP JOYLASHUVI

```text
CyberGigiyena/
├── README.md
├── .gitignore
├── docs/
│   ├── CYBERGIGIYENA_MASTER_PLAN_WEB_FLUTTER_V4.md   # Ushbu hujjat
│   ├── UX_FLOW.md
│   ├── FIGMA_DESIGN_SYSTEM.md
│   ├── SCREEN_CONTRACTS.md
│   ├── API_OPENAPI.yaml
│   ├── DB_ERD.md
│   ├── FLUTTER_WEBVIEW_CONTRACT.md
│   ├── SECURITY_CHECKLIST.md
│   ├── CONTENT_EDITOR_GUIDE.md
│   ├── QA_MATRIX.md
│   ├── RELEASE_CHECKLIST.md
│   └── CHANGELOG.md
├── web/                               # Next.js + TS + Tailwind
│   ├── src/
│   │   ├── app/
│   │   │   ├── layout.tsx
│   │   │   ├── page.tsx                 # landing / redirect
│   │   │   ├── globals.css
│   │   │   ├── manifest.ts
│   │   │   ├── not-found.tsx
│   │   │   ├── (public)/welcome/page.tsx
│   │   │   ├── (auth)/auth/login/page.tsx
│   │   │   ├── (auth)/auth/register/page.tsx
│   │   │   ├── (app)/layout.tsx          # AppShell + 4 tab
│   │   │   ├── (app)/home/page.tsx
│   │   │   ├── (app)/mission/today/page.tsx
│   │   │   ├── (app)/question/today/page.tsx
│   │   │   ├── (app)/learn/page.tsx
│   │   │   ├── (app)/learn/lesson/[slug]/page.tsx
│   │   │   ├── (app)/practice/page.tsx
│   │   │   ├── (app)/practice/[slug]/intro/page.tsx
│   │   │   ├── (app)/practice/[slug]/play/page.tsx
│   │   │   ├── (app)/practice/[slug]/result/page.tsx
│   │   │   ├── (app)/profile/page.tsx
│   │   │   ├── (app)/profile/weaknesses/page.tsx
│   │   │   ├── (app)/sos/page.tsx
│   │   │   ├── (admin)/admin/page.tsx
│   │   │   ├── (admin)/admin/alerts/page.tsx
│   │   │   ├── loading.tsx
│   │   │   └── error.tsx
│   │   ├── components/{ui,layout,education,simulation,alerts}/
│   │   ├── features/{auth,daily,learn,practice,profile,promise,share,sos}/
│   │   ├── lib/{api-client.ts,server-api.ts,schemas.ts,dates.ts,webview.ts}
│   │   ├── hooks/{useAppShell.ts,useOnlineStatus.ts,useDaily.ts}
│   │   ├── stores/{guest-progress.ts,offline-queue.ts}
│   │   └── i18n/{uz-Latn.json,uz-Cyrl.json,ru.json}
│   ├── public/{icons,illustrations,sos-assets}/
│   ├── tests/{unit,integration,e2e,a11y}/
│   ├── next.config.ts
│   ├── package.json
│   ├── postcss.config.mjs
│   ├── tsconfig.json
│   └── .env.example                   # sirlarni kiritmang
├── flutter/                           # Flutter WebView native qobiq
│   ├── lib/
│   │   ├── main.dart
│   │   ├── app.dart
│   │   ├── core/{config,network,logging,security}/
│   │   ├── webview/{webview_screen.dart,webview_controller.dart,
│   │   │            navigation_policy.dart,bridge_handler.dart,webview_cookies.dart}/
│   │   ├── native/{splash,offline_sos,share,notification,deep_link}/
│   │   └── theme/{app_theme.dart,design_tokens.dart}/
│   ├── assets/{logo,offline_sos,icons}/
│   ├── android/                        # Gradle, manifests, release keys NOT repo'da
│   ├── ios/                            # Xcode, signing, entitlements
│   ├── test/{unit,widget}/
│   ├── integration_test/
│   └── pubspec.yaml
├── backend/                            # PHP + MySQL, v3 backend modeli saqlangan
│   ├── public/index.php
│   ├── app/{Controllers,Services,Repositories,Domain,Middleware,Validation,Jobs}/
│   ├── routes/api.php
│   ├── database/{migrations,seeders}/
│   ├── tests/{Unit,Integration,Security}/
│   ├── config/
│   ├── composer.json
│   └── .env.example
├── infra/{nginx,docker,monitoring,cron,backup}/
├── scripts/{seed_demo.php,check_content.php,verify_backups.sh}/
└── .github/workflows/{web-ci,php-ci,flutter-android-ci,ios-ci,deploy-staging}.yml
```

**Route group eslatmasi:** Next.js `(app)`, `(auth)` kabi qavsli papkalar URLga kirmaydi; `/home` saqlanadi. `:slug` eski hujjatdagi URL-kontrakt yozuvi; Next.js fayl strukturasida `[slug]` ishlatiladi. **Bir nechta bir xil pathga ega `page.tsx` yaratmang.** Hujjatning 20-bo‘limidagi 53 ekran shu App Router ierarxiyasiga joylanadi; yuqorida faqat asosiylari misol.

**Muhim qurilish qoidasi:** `web/`dagi Tailwind komponentlari `flutter/`da takrorlanmaydi. Flutter native xabarlar, offline SOS, xato holati va OS integratsiyasiga javobgar. PHP `backend/` yagona biznes manbai; Flutter/MySQL bevosita ulanish yo‘q.


---

## 26. HOSTING / DOMEN / CI-CD — NEXT.JS + PHP BIR ORIGIN

### 26.1. Muhitlar

| Muhit | Veb | API | App |
|---|---|---|---|
| Local | Next `localhost:3000` | PHP local, Nginx reverse proxy yoki `/api/v1` dev rewrite | Flutter emulator `https://staging...`ga ulansin; production cookie emas |
| Staging | `https://staging.APP_DOMEN` | Shu origin `/api/v1` | Flutter `--dart-define=APP_URL=...` orqali test |
| Production | `https://APP_DOMEN` | Shu origin `/api/v1` | Ikkala store release stagingdan ajratilgan |

**Veb hosting talabi:** Next.js App Router SSR ishlashi uchun Node.js runtime mavjud **VPS/container** yoki mos managed Next hosting talab etiladi. Faqat PHP shared hostingni `Next.js sayt + SSR` uchun yetarli deb qabul qilmang. PHP/MySQL alohida container/server yoki shu VPS'da PHP-FPM orqali ishlashi mumkin. Barcha URL bir HTTPS origin orqali chiqariladi.

### 26.2. Nginx routing konsepti (real server uchun adaptatsiya shart)

```nginx
server {
    listen 443 ssl http2;
    server_name APP_DOMEN;
    # TLS sertifikat yo‘llari, HSTS va boshqa headerlar infra'da aniqlanadi.

    location ^~ /api/v1/ {
        # Bu yerda PHP-FPM'ga mos API front-controller konfiguratsiyasi yoki
        # ichki API reverse proxy ishlaydi. PHP index path real joylashuvga mos.
        proxy_pass http://php_api_upstream;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-Proto https;
        proxy_set_header X-Real-IP $remote_addr;
    }
    location / {
        proxy_pass http://nextjs:3000;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-Proto https;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

`php_api_upstream` — *namunaviy servis nomi*: real PHP-FPM'ga bevosita `proxy_pass` ishlamaydi, chunki PHP-FPM FastCGI. Bunda Nginx `fastcgi_pass` front controller konfiguratsiyasi yoki **PHP HTTP server gateway** orqali `proxy_pass` kerak. Infrastrukturachi aynan bitta variantni ishlatadi va testlaydi. Bu snippet nusxa-ko‘chirib tayyor deploy qilish uchun emas, marshrut konseptini ko‘rsatadi.

### 26.3. CI/CD

1. `web`: `npm ci` → lint → `tsc --noEmit` → unit → Playwright (mobile) → `next build` → preview.
2. `backend`: `composer install --no-dev` faqat release stage → PHPStan/test → SQL migration dry-run → contract/security test.
3. `flutter`: `flutter pub get` → `dart analyze` → widget/unit → Android emulator integration → `flutter build appbundle` (signed release branch).
4. `ios`: **macOS agent** → Xcode/build → TestFlight → device integration.
5. E2E: Next + PHP + MySQL + Flutter WebView birga: `guest`, `login`, `daily`, `offline`, `notification`, `SOS`, `delete account`.
6. Deploy: Next/PHP staging, smoke, DB backup/restore, release toggle, logs, 5xx alert, rollback. Web deploy bilan mobil versiya moslik kontrakti (API version, bridge protocol) saqlanadi.

### 26.4. Production release nazoratlari

- HTTPS va HSTS, `Secure HttpOnly SameSite` sessiya cookie, CSRF, CSP, input validation, SQL prepared statements, RBAC, rate-limit.
- Next static asset CDN cache; profil, natija, akkaunt HTTP response'lari `private/no-store`, shared proxy cache yo‘q.
- Flutter domen allowlist, HTTPS-only; native bridge bo‘lsa xavfsiz allowlist commandlar; logda credential/OTP bo‘lmaydi.
- Yangi web deploy **eski App Store/Play Store binary**da ham ishlashi lozim; bridge protocol orqaga mos yoki fallback.
- Uptime va kontent tanlovi: `ertangi daily bundle` tekshiruvi; alert expiry; crash/ANR/WebView load telemetry.


---

## 27. TEST MATRITSASI — HAR BIR SAHIFA UCHUN QABUL SHARTLARI

### 27.1. Ekran va platformalar

| Qurilma/profil | Viewport | Qanday sinov |
|---|---|---|
| Juda kichik Android | 320×568 CSSpx | Gorizontal scroll, tugma sig‘ishi |
| Kichik Android | 360×740 | Keyboard, bottom nav, modal |
| Standart telefon | 393×852 | Asosiy UX, real touch |
| Katta telefon | 430×932 | Karta o‘lchovi, safe area |
| Tablet/fold | 768×1024 | 2 ustun, nav transform |
| Desktop | 1440×900 | Sidebar, max-width, keyboard |
| Android Chrome + Flutter WebView | kamida 2 device | browser va ichki WebView login, offline, back gesture |
| Samsung Internet | real | CSS/manifest/source behavior |
| iOS Safari + Flutter WKWebView | kamida 2 iPhone | safe area, login, native share, back gesture |
| Accessibility | 200% font, screen reader | Kontrast, focus, aria, scroll |

### 27.2. Qabul sinovlari (manual + automated)

| ID | Test | Kutilgan natija |
|---|---|---|
| QA01 | Landingdan mehmon bo‘lib kirish | Hisobsiz 1 missiya boshlanadi |
| QA02 | Onboarding skip | /homega yetadi |
| QA03 | 320px ekran | Yon tomonga scroll yo‘q |
| QA04 | 200% matn | Sarlavha/button bir-biriga chiqmaydi |
| QA05 | Dark tema | Barcha matn o‘qiladi |
| QA06 | System tema | OS tema bilan moslashadi |
| QA07 | `reduced-motion` | Keraksiz animatsiya yo‘q |
| QA08 | Touch target | Har asosiy tugma >=44px |
| QA09 | Keyboard nav | Tab/focus tartibi to‘g‘ri |
| QA10 | Screen reader | Icon labels to‘g‘ri |
| QA11 | Header SOS | Ikki tap ichida asosiy qadam |
| QA12 | SOS offline | Statik qadamlar ishlaydi |
| QA13 | Public alert source | Sana va URL ko‘rinadi |
| QA14 | Eski alert | `dolzarb bugun` deyilmaydi |
| QA15 | Alert source link broken | Factcheck warning/moderation |
| QA16 | Kun o‘rtasida refresh | Daily assignment o‘zgarmaydi |
| QA17 | Soatni o‘zgartirish | Server kuni ustuvor |
| QA18 | 00:00 timezone | Yangi paket ochiladi |
| QA19 | Missing daily bundle | Aniq fallback, bo‘sh ekran yo‘q |
| QA20 | Kun savoli 1 javob | Sababi chiqadi |
| QA21 | Double click submit | Attempt ikkilamaydi |
| QA22 | Savolni oldindan ko‘rish | Answer key clientda oshkor emas |
| QA23 | Darsni yopib ochish | Progress saqlanadi |
| QA24 | Dars mini-test xato | Uyaltirmasdan tushuntiradi |
| QA25 | Search offline | Faqat cached kontent va label |
| QA26 | Simulyatsiya scene branching | Faqat valid next scene |
| QA27 | Simulyatsiyadan chiqish | Save/exit dialog |
| QA28 | Simulyatsiya natija refresh | Ikkinchi yozuv yaratilmaydi |
| QA29 | Simulyatsiya linklari | Real zararli domen ochilmaydi |
| QA30 | OTP/parol maydoni | Haqiqiy qiymat kiritishni so‘ramaydi |
| QA31 | Zaif tomon foizi 1 savolda | `yetarli dalil yo‘q` |
| QA32 | Zaiflik tavsiyasi | Mos dars/mashq ochiladi |
| QA33 | Va'da offline | Local status saqlanadi |
| QA34 | Va'da sync | Idempotent, conflict controlled |
| QA35 | Share API yo‘q | Copy fallback ishlaydi |
| QA36 | Share private | Email/ismi/xato kirmaydi |
| QA37 | Auth login success | Session, redirect, no leak |
| QA38 | Login 429 | Rate-limit ogohlantiradi |
| QA39 | Parolni tiklash | User enumeration yo‘q |
| QA40 | Guest→auth import | Rozilik, progress yo‘qolmaydi |
| QA41 | Logout | Private data cache siyosati ishlaydi |
| QA42 | Export | Faqat o‘z data yuklanadi |
| QA43 | Delete | Reauth + two-step + server delete |
| QA44 | Push denied | Ilova ishlashda davom etadi |
| QA45 | PWA offline refresh | Shell ochiladi |
| QA46 | PWA update mid-practice | Mashq tugaguncha eski version qoladi |
| QA47 | Cache quota full | Graceful fallback, attempt yo‘qolmaydi |
| QA48 | Admin alert publish | Source/date/review majburiy |
| QA49 | Admin role permission | 403, audit event |
| QA50 | Admin daily calendar empty | Warning + static fallback |
| QA51 | API SQLi/XSS negative tests | Query parametrizatsiya, sanitize |
| QA52 | API IDOR negative tests | Boshqa user data 403/404 |
| QA53 | API CSRF | Unauthorized mutation rad |
| QA54 | Service Worker private API | `no-store`; cache leak yo‘q |
| QA55 | Lighthouse mobile | Core Web Vitals maqsadlari baholanadi |
| QA56 | 100 concurrent user | Critical p95 va error budget ichida |
| QA57 | Real device battery | 5 min o‘qishda agressiv animatsiya yo‘q |
| QA58 | Browser back | Yo‘qolgan unsaved work yo‘q |
| QA59 | Flutter Android App Links va iOS Universal Links | `assetlinks.json` va `apple-app-site-association`, real link test |
| QA60 | Release rollback | Oldingi versiyaga qaytish ishlaydi |

**Test qoidasi:** `passed` holati faqat build ID, test date, qurilma, screenshot/log va tester bilan qayd etiladi. `Untested` = `passed` emas. Flutter Android va iOS WebView uchun 39-bo‘limdagi alohida platforma QA matriksi ham majburiy.

---

## 28. OMMAVIYLASHTIRISH: O‘QITISHGA MOS, SPAMSIZ O‘SISH

### 28.1. Ishga tushish bosqichlari

1. **20 nafar yaqin beta tester**: telefon turlari, yosh toifasi va xavf bo‘yicha fikrlarni yig‘ish. Bu mahsulot sinovidir, rasmiy statistik survey emas.
2. **50 nafar ochiq beta tester**: 5 kun ichida nechta odam bir dars/missiyani to‘liq tugatishini o‘lchash.
3. **100–300 nafar ochiq sinov**: server ko‘tarishi, xato, chalkash UI, o‘zbekcha imlo va kunlik paket sifatini o‘lchash.
4. **Public release**: mobil landing, PWA install instruktsiya, 1 daqiqa video, QR tashrif linki.
5. **Universitet, kutubxona, mahalla/o‘quv markazi hamkorligi**: targ‘ibot, lekin foydalanuvchi consentisiz natijani ulashmaslik.

### 28.2. Foydani o‘lchovchi metrikalar

| Metrika | Ta'rif | Dastlabki loyiha maqsadi (o‘lchamaguncha fakt emas) |
|---|---|---|
| Activation | 1 tashrifda 1 dars yoki simulyatsiya yakunlanishi | >=40% test maqsad |
| Time to first value | /home dan birinchi izohgacha | <=3 daqiqa |
| Mission completion | Boshlangan missiondan yakunlangani | >=60% beta maqsad |
| Explanation read rate | Natijadan keyin izoh o‘qiganlar | >=70% |
| Knowledge retention | 7 kundan so‘ng o‘xshash yangi vaziyatda to‘g‘ri qaror | Beta boshlang‘ich ko‘rsatkichidan yaxshilanish |
| SOS discoverability | UX testda <=2 tap topish | >=90% test qatnashchisi |
| Page error rate | Xatoli seanslar | <1% reja maqsadi |
| Daily content coverage | Oldindan tasdiqlangan bo‘sh bo‘lmagan kunlar | >=30 kun |
| PWA installation | Qaytgan foydalanuchidan installga o‘tish | Kuzatuv, majburiy emas |

**Ommaviylik uchun qilinmaydi:** sun'iy reyting, zo‘ravon “FOMO”, xabarlardan qo‘rqitib obuna, keraksiz kontakt ruxsatlari, yashirin ijtimoiy tarmoq post yuborish. Sifat va haqiqiy foyda markazda bo‘lsin.

---

## 29. ISHLAB CHIQISH REJASI — NEXT.JS SAYT, PHP, SO‘NG FLUTTER (REAL BACKLOG)

### 2026-11-06 — admin kontent tarixi va namoyishi

- [x] Kontent ro‘yxatida sarlavha bo‘yicha qidiruv va holat filtri qo‘shildi.
- [x] Dars, ogohlantirish va ssenariy uchun saqlanmagan tahrir namoyishi; 320/393/768 kenglik tanlovi va light/dark mavjud. Ssenariy tanlovlari lokal namoyishda ishlaydi.
- [x] Oldingi tahrirlar ro‘yxati va nusxa namoyishi qo‘shildi. Tiklash joriy nusxani saqlaydi, versiyani oshiradi, reviewer ni tozalaydi va yangi qoralama yaratadi.
- [x] PHP/MySQL tranzaksion sinovi: role/MFA, eskirgan versiya, mavjud bo‘lmagan revision, tarix saqlanishi va audit o‘tdi.
- [x] TypeScript va production build o‘tdi. Edge brauzerida qidiruv, saqlanmagan tahrir namoyishi, tun rejimi, tarix va versiya to‘qnashuvi sinovi o‘tdi.
- [ ] Taqvimning to‘liq tahririy jarayoni, bo‘sh/takror kun ogohlantirishlari va qolgan admin qabul mezonlari tugatilsin.

Dalil va chegaralar: `docs/QA-2026-11-06.md`. Kontent namoyishi barcha public ekranlarning aynan nusxasi yoki to‘liq qurilma sinovi hisoblanmaydi.

### 2026-11-05 — tekshirilgan kichik ishlar kundaligi

Bu kichik bandlar quyidagi keng fazalarni avtomatik tugatmaydi. Har bir belgi shu kunda qayta bajarilgan sinovga tayangan. Qolgan talablar `[ ]` holatida qoladi.

- [x] PHP/MySQL integratsiya testi: 12 dars, 5 tarmoqli mashq, kunlik paket, sessiya, CSRF, begona natijani rad etish, takroriy yuborish, guest import, eksport va hisobni o‘chirish sinovlari o‘tdi (`python backend/tests/integration.py`).
- [x] Flutter HTTP xato callback’idagi kompilyatsiya xatosi tuzatildi; `flutter analyze` va 5 ta unit/widget testi o‘tdi.
- [x] Next.js TypeScript tekshiruvi va production build o‘tdi (`npm run typecheck`, `npm run build`).
- [x] Brauzerda mehmon: onboarding → bugungi missiya → 3 qaror izohi → va’da holati qayta yuklangandan so‘ng saqlanishi o‘tdi.
- [x] Brauzerda kun savoli progress yangilanishini kutib, to‘g‘ri natija sahifasiga o‘tishi o‘tdi.
- [x] Brauzerda darsni o‘qish → mini-test → yakun → qayta yuklashda natija saqlanishi o‘tdi.
- [x] Brauzer offline holatida statik SOS yo‘riqnomasi qayta yuklanganda ochilishi o‘tdi.
- [x] 320/393/768/1440 CSS px asosiy sahifalarda gorizontal scroll yo‘qligi tekshirildi.
- [x] 320 px va 200% matn bilan yonma-yon scroll yo‘qligi va tun rejimi tekshirildi. Bu screen reader hamda haqiqiy qurilma sinovi o‘rnini bosmaydi.
- [x] Yaroqsiz kunlik paket xavfsiz zaxiraga o‘tadi; PHP/MySQL fallback testi va brauzerdagi yorliq testi o‘tdi.
- [x] Kelgusi 29 ta tegilmagan avtomatik takror kun draftga o‘tkazildi; bugungi paket saqlandi. Migratsiya qayta bajarilganda 0 yozuv o‘zgardi.
- [x] Admin taqvimi qoralama va nashr sonini aniq ko‘rsatadi; TypeScript va production build o‘tdi.
- [x] Alert validatsiyasi bo‘sh qadamni, xavfli manba URLini va eskirgan tekshiruv bilan nashrni rad etadi; PHP sinovi o‘tdi.
- [x] 2026-07-07 dagi UZCERT xabari rasmiy manba, sanalar va amaliy qadamlar bilan faqat qoralama sifatida kiritildi; mustaqil muharrir/reviewer tasdig‘i hali yo‘q.
- [ ] Rasmiy ogohlantirish muharrir tahriri, mustaqil tekshiruv va publisher nashridan o‘tsin; hozir foydalanuvchiga xabar chiqmaydi.
- [ ] Barcha 53 ekran kontrakti, har bir tugma, 320/360/393/430/480 px, 200% va haqiqiy qurilmalardagi qabul sinovlari yakunlansin.
- [ ] 30 kunlik tahririy kontent tasdiqlansin. Hozir seed jadval 5 ssenariyni takrorlaydi; bu 30 ta mustaqil mavzu degani emas.
- [ ] Android/iOS build, signing, haqiqiy telefon, beta, production domen/HTTPS va do‘kon nashri yakunlansin.

Batafsil dalil va cheklovlar: `docs/QA-2026-11-05.md`. Quyidagi fazalardagi murakkab bandlar ularning **barcha** qismi tekshirilmaguncha ochiq qoladi.



**Tartib qat'iy, muddat esa ish jamoasi va imkoniyatga bog‘liq.** Har bir faza oxirida tugmalar real ishlashi shart. Design-only skelet mahsulot hisoblanmaydi.

### FAZA 0 — Mahsulot + arxitektura + dizayn

- [ ] Ushbu `v4` reja va `docs/` papkasi gitga qo‘yildi; eski v3 faqat arxivda.
- [ ] `web/` Next.js App Router + TypeScript + Tailwind CSS; format/lint/typecheck/test.
- [ ] `flutter/` Flutter stable + `webview_flutter`; Android va iOS min OS tekshirildi.
- [ ] `backend/` PHP REST + MySQL migrations; Nginx `/api/v1` contract.
- [ ] Domain, HTTPS, local/staging/prod environment; `health` endpoint.
- [ ] Design system: 320/360/393/430/480 px, tablet, safe area, 44–48 px touch, light/dark, Figma.
- [ ] 4 tab AppShell, SOS har sahifadan topilishi, skeleton/error/empty/offline holatlari.
- [ ] URL policy, auth cookies, one-nav-owner, frontend session boundary yozilgan.

**Exit:** brauzerda 4 tab, Flutter emulatorida URL ochiladi, offline fallback bo‘ladi; bunda hali barchasi tayyor deb e'lon qilinmaydi.

### FAZA 1 — Haqiqiy ishlaydigan veb MVP

- [ ] Mehmon onboarding, `/home` va bugungi missiya.
- [ ] Bilimlar katalogi, 12 ta haqiqiy o‘qiladigan dars, dars natijasi.
- [ ] 5 ta xavfsiz simulyatsiya, sahna qarori + nima uchun to‘g‘ri/xato tahlili.
- [ ] 30 kunlik daily paketlarni admin navbat bilan nashr qilishi; kundalik savol.
- [ ] O‘zbekiston rasmiy, manbali va sanali alert CMS.
- [ ] SOS yo‘riqnomalari, Share, Va’da, Zaif tomonlarim bo‘limi; zaiflik dalil yetmasa foiz yo‘q.
- [ ] Testlar: 320px, TalkBack/VoiceOver, 200% zoom, slow 4G.

**Exit:** brauzer orqali real foydalanuvchi har kuni 5 daqiqa foyda oladi.

### FAZA 2 — Hisob, progress, ishonchli backend

- [ ] Login/register/recover/logout; `__Host-` cookie, CSRF, rate-limit, password hashing.
- [ ] Guest progress import preview, idempotent sync, transaction-safe PHP backend.
- [ ] Profil: skill/weakness/history/promises/checklist/privacy/export/delete.
- [ ] Admin RBAC, MFA, audit, source review, daily scheduler.
- [ ] Xavfsizlik: IDOR/BOLA, XSS, SQLi, CSP, session fixation, CORS, logs.

**Exit:** Web browser va Flutter WebViewda bitta akkaunt server natijasini to‘g‘ri ko‘rsatadi (bir xil browser cookie emas, bitta backend hisob).

### FAZA 3 — Flutter Android/iOS WebView qobiq

- [ ] Native splash/loading/offline/error/SOS.
- [ ] Domen allowlist, tashqi linklar, `tel:`/`mailto:`, deep links.
- [ ] Android Back, iOS back gesture, navbar va keyboard, safe area, zoom.
- [ ] Auth: app ichida login/logout; cookie persist/reinstall/session expire.
- [ ] Native Share; native push faqat opt-in va keyingi zarurat bo‘lsa.
- [ ] App API bridge minimal protokol; XSS va begona URL bilan privilege escalation test.
- [ ] Flutter Android release AAB + iOS IPA/TestFlight build.

**Exit:** foydalanuvchi web va ikkala native ilovada bir xil dars va simulyatsiyani uzluksiz ishlata oladi; OS farqlari testlangan.

### FAZA 4 — Ommaviy beta va do‘konlarga chiqarish

- [ ] 20–50 real odam bilan UX test; onalar/kattalar/talabalar kabi turli savodxonlik profillari.
- [ ] App Store review 4.2 va Google Play functionality siyosati bo‘yicha o‘ziga xos foydalar namoyish qilinadi.
- [ ] Privacy policy, terms, support, screenshots, content rating, app metadata, review demo account.
- [ ] Crash / backend API monitoring va rollback protsedura.
- [ ] Android internal/closed test, iOS TestFlight; keyin staged public release.

**Exit:** mahsulot nashrdan keyin ham ishchiligini, kontent sifatini va xavfsizligini kuzatish imkoniga ega.

### Dastlabki 35 ta GitHub issue

| ID | Nima qilinadi? | Qabul mezoni |
|---|---|---|
| ARC-001 | Web+Flutter+PHP mas'uliyat chegarasi | 1 ta chizma, auth/route owners |
| UX-001 | Figma design tokens va light/dark | 320,393,430,tablet |
| WEB-001 | Next.js TypeScript Tailwind init | `next build`, lint, tests |
| WEB-002 | App Router 4 tab AppShell | barcha tab ishlaydi |
| WEB-003 | Header/SOS/bottom nav | safe area va keyboard sinovi |
| WEB-004 | Button/card/input/alert UI kit | loading/error/focus/disabled |
| WEB-005 | Next page layouts/error/loading | refresh + 404 + offline UX |
| API-001 | PHP API project & health | JSON schema, log sanitization |
| DB-001 | MySQL migrations & seed | repeatable migration |
| API-002 | Daily bundle scheduler | 30 days, stable timezones |
| WEB-006 | Home dinamik | mission, alert, question |
| WEB-007 | Learn modules | 12 lessons accessible |
| WEB-008 | Practice engine | 5 sim xavfsiz |
| WEB-009 | Result analysis | explanation and retry |
| WEB-010 | SOS / share / promise | usable no-login |
| WEB-011 | Profile/weakness | sufficient sample guard |
| WEB-012 | Auth forms & recover | valid/error/expiry flows |
| API-003 | Auth cookie/CSRF | security integration test |
| API-004 | Idempotent attempt | double tap duplicates yo‘q |
| WEB-013 | Guest local progress & sync | import preview |
| ADM-001 | Admin CMS lesson/quiz | publish/review/audit |
| ADM-002 | Admin scenario editor | branching schema validation |
| ADM-003 | Alert factcheck | sources+date required |
| FLT-001 | Flutter WebView app shell | Android/iOS startup |
| FLT-002 | Flutter URL allowlist | block unsafe schemes |
| FLT-003 | Native offline + SOS | internet off ishlaydi |
| FLT-004 | Android Back + iOS swipe | no inadvertent exit |
| FLT-005 | Auth cookie/lifecycle | session persists predictably |
| FLT-006 | Native Share | no auto-send; fallback |
| FLT-007 | Push opt-in & deep links (optional phase) | consent and route whitelist |
| QA-001 | Playwright mobile routes | 53 contracts smoke coverage |
| QA-002 | Flutter integration test | real device routing/auth |
| QA-003 | Accessibility audit | WCAG + text scale |
| SEC-001 | OWASP API + WebView audit | no high vulnerabilities |
| REL-001 | Android & iOS release runbooks | store testers can use app |


---

## 30. MVP / STOREGA TAYYORLIK — QAT'IY QABUL MEZONLARI

### 30.1. Veb MVP

- [ ] 4 tab, SOS, har bir ko‘rinadigan tugma ishlaydi; hech qanday bo‘sh sahifa yo‘q.
- [ ] Mehmon rejimi: 12+ dars, 5+ simulyatsiya, kunlik savol, xato tahlili, SOS, va'da, maslahat ulashish.
- [ ] 30 kunlik dastur va real ma'lumotlar asosida yangilanadigan paketlar; alert manbali va sana bilan.
- [ ] Profil/zaif tomonlar raqamlari real testlardan; ma'lumot yetmasa `ma'lumot yetarli emas`.
- [ ] 320–480 px, keng ekran, 200% zoom, katta shrift, 44px+ touch, dark/light, screenreader.
- [ ] PHP auth/CSRF, XSS, SQLi, IDOR, ruxsat, log, backup/restore, admin audit; private cache no-store.
- [ ] Next SSR + same origin API, deep links, browser back, refresh no 404; performance real qurilmada o‘lchangan.

### 30.2. Android/iOS Flutter qobiq

- [ ] Android WebView va iOS WKWebView ochiladi, HTTPS exact allowlist ishlaydi.
- [ ] Startup loading, failure/retry, **native offline SOS** doim ishlaydi; app shunchaki oq ekran bo‘lib qolmaydi.
- [ ] Cookie login/logout va qayta kirish ishlaydi; browser cookie bilan bir xil deb faraz qilinmaydi.
- [ ] Safe area, iPhone notch/home indicator, Android gesture, keyboard; **ikkinchi tab bar yo‘q**.
- [ ] Back/deep link/share/tel/mailto/external URL real qurilmada sinovdan o‘tgan.
- [ ] App-specific foyda (offline SOS/native share va boshqalar) va do‘kon siyosati release oldidan tekshirilgan.
- [ ] Android AAB va iOS IPA/TestFlight uchun signing/build alohida hujjatlashtirilgan.
- [ ] Native bridge kerak bo‘lsa buyruqlar cheklangan, login/OTP/DBga kirish bridge orqali mumkin emas.
- [ ] Beta 20–50 real foydalanuvchi, kamida 5 turdagi Android + 3 iPhone/iOS profilida test.

**Biror bir shart o‘tmasa “tayyor” deb e'lon qilmang.** Bu loyiha reja; ishchi sayt va ikkala ilova haqiqiy implementatsiya, QA va release'dan keyin paydo bo‘ladi.


---

## 31. MANBALAR / TEKSHIRILGAN TEXNIK TAYANCH (2026-11-05)

1. Next.js App Router: https://nextjs.org/docs/app — route, layout, server/client component.
2. Next.js create-next-app va TypeScript/Tailwind: https://nextjs.org/docs/app/getting-started/installation — stackning rasmiy boshlanishi.
3. Next.js Backend for Frontend: https://nextjs.org/docs/app/guides/backend-for-frontend — Node runtime va statik export farqi; API qatlamlari.
4. Flutter `webview_flutter` (Flutter team): https://pub.dev/packages/webview_flutter — Android WebView / iOS WKWebView, `WebViewController`, `NavigationDelegate`.
5. Flutter WebView README/source: https://github.com/flutter/packages/blob/main/packages/webview_flutter/webview_flutter/README.md — plugin platform qo‘llovi.
6. Apple App Review Guidelines 4.2: https://developer.apple.com/app-store/review/guidelines/ — oddiy repackaged website yetmasligi mumkin.
7. Google Play functionality policy: https://support.google.com/googleplay/android-developer/answer/9898783?hl=en — real foydali, barqaror app.
8. Android WebView native bridge risks: https://developer.android.com/privacy-and-security/risks/insecure-webview-native-bridges — JS bridge ustidan tahdid.
9. OWASP Mobile WebView hardening: https://mas.owasp.org/MASTG/best-practices/MASTG-BEST-0012/ — JavaScript talab qilinsa allowlist va bridge audit.
10. Apple Human Interface Guidelines Layout: https://developer.apple.com/design/human-interface-guidelines/layout — safe area, adaptiv UI, katta shrift.
11. Apple Human Interface Guidelines Web Views: https://developer.apple.com/design/human-interface-guidelines/web-views — back/forward qo‘llash.
12. WCAG 2.2: https://www.w3.org/TR/WCAG22/ — accessibility.
13. OWASP ASVS: https://asvs.owasp.org/ — API va web server uchun secure coding.
14. Web Vitals: https://web.dev/articles/vitals — LCP, INP, CLS maqsadlari.

**Qoidalar tez yangilanadi.** Flutter/Next.js package va platforma minimumlari, Apple review, Google Play target SDK, O‘zbekiston ma'lumotlarni himoyalash bo‘yicha mahalliy talablar nashr paytida qayta tekshiriladi. Bu reja huquqiy maslahat emas.


---

## 32. QAYERDAN BOSHLASH KERAK — BUGUNGI AMALIY TOPSHIRIQ

1. GitHub monorepo: `web/`, `flutter/`, `backend/`, `docs/`; ushbu v4.0 fayl `docs/`ga joylashsin.
2. `web/`da **Next.js App Router + TypeScript + Tailwind** yaratilsin. 4 tab + SOS navigation, `loading.tsx`, `error.tsx`, responsive UI; 393px dan boshlab 320px test.
3. `backend/`da PHP/MySQL `/api/v1/daily` va health endpoint; Nginxdan **bitta HTTPS origin**ga chiqilsin.
4. Bir foydalanuvchi flow'i end-to-end: `Guest → Missiya → Simulyatsiya → Xato izohi → Va'da` brauzerda 100% ishlasin.
5. Keyin Flutter `webview_flutter`: aynan saytni ochsin, offline/SOS, external link policy, back, keyboard, safe area.
6. Keyin login/progress sync, CMS, 30 kunlik kontent, real mobil test, Android Play va iOS TestFlight.

**To‘g‘ri ketma-ketlik:** veb sayti haqiqiy foydali → webdagi funksiyalar real backend bilan bog‘langan → Flutter ikki platformada yaroqli qobiq → push/deeplink/offline/sos → beta → nashr. Flutter vebdagi yomon UXni tuzatmaydi; boshidanoq ikkala muhit uchun UX test qilinadi.


---

## 33. QO‘SHIMCHA AMALIY MOBIL WIREFRAME'LAR — FIGMA / HTML UCHUN ANIQ JOYLASHUV

Quyidagi maketlar **393 CSS px** asosida. `|` va `─`lar chizmadagi bloklarni bildiradi, veb sahifada esa real HTML/CSS komponentlar bo‘ladi. `*` bilan belgilanmagan tugmalar ham haqiqiy yo‘lga bog‘lanadi. Har maketning yuqori/ostki `safe area`si real telefon bilan sinovdan o‘tishi kerak.

### 33.1. Bilimlar katalogi — `/learn`

```text
  ← yo‘q: bu root tab      Bilimlar             ⌕
  Bugun nimani o‘rganamiz?
  ┌───────────────────────────────────────┐
  │ 🔎 Mavzu, atama yoki xavfni qidiring  │
  └───────────────────────────────────────┘
  [Barchasi] [Boshlang‘ich] [Mustahkamlash]

  Sizga tavsiya
  ┌───────────────────────────────────────┐
  │ 🛡️  Fishing va soxta havolalar       → │
  │ 8 ta dars  ·  3–5 daqiqadan           │
  │ [1-darsdan boshlash]                  │
  └───────────────────────────────────────┘

  Barcha mavzular
  ┌───────────────────────────────────────┐
  │ 🔐 Parol va 2FA           6 dars     → │
  ├───────────────────────────────────────┤
  │ 💬 Telegram xavfsizligi   6 dars     → │
  ├───────────────────────────────────────┤
  │ 💳 Karta xavfsizligi      6 dars     → │
  └───────────────────────────────────────┘
  Asosiy     Bilimlar*    Simulyatsiya   Profil
```

**UX:** katalogni ko‘rish uchun swipe kerak emas, barcha 12 modulni oddiy vertikal scroll bilan topadi. Noshir `published` qilmagan dars kartadagi soniga qo‘shilmaydi. Karta sarlavhasi va butun kartaning bosiladigan maydoni bir xil action qiladi; yonida ikkinchi sirli tugma yo‘q.

### 33.2. Dars ichidagi bir ekran — `/learn/lesson/:slug`

```text
  ← Orqaga                         Saqlab chiqish
  2 / 5                         ━━━━━━━─────
  
  Nega bu SMS shubhali?
  Haqiqiy xizmatlar sizni shoshiltirib ...
  ┌───────────────────────────────────────┐
  │      [Illustratsiya / SMS mock]       │
  │      SIMULYATSIYA NAMUNASI            │
  └───────────────────────────────────────┘
  ⚠ Diqqat belgisi #1
  Xabarda hisob bloklanishi bilan qo‘rqitilgan.

  Nima qilish kerak?
  • Havola manzilini tekshiring.
  • Xizmatning rasmiy ilovasidan kiring.

  [ ← Avvalgi ]           [ Keyingi → ]
  [    Yordam kerakmi? / Atamani tushunish    ]
```

**UX:** `Keyingi` tugmasi yuqorida emas, bosh barmoq yetadigan pastda; lekin ekranning oxirini yopmaydi. Dars progresi sahifa refreshidan so‘ng saqlanadi. Bir darsda 5 sahifadan ko‘p bo‘lsa hajmga qarab bo‘linadi.

### 33.3. Simulyatsiya katalogi — `/practice`

```text
  Simulyatsiyalar                     [?]
  Xavfsiz muhitda real vaziyatga tayyorlaning.
  [Tavsiya] [Telegram] [Bank] [Barchasi]

  ┌───────────────────────────────────────┐
  │ [💬] Soxta Telegram ovoz berish      │
  │       4 daqiqa  •  boshlang‘ich      │
  │       [Simulyatsiyani boshlash →]     │
  └───────────────────────────────────────┘
  ┌───────────────────────────────────────┐
  │ [💳] Soxta bank SMS                  │
  │       5 daqiqa  •  o‘rta             │
  │       [Boshlash →]                   │
  └───────────────────────────────────────┘
  ...
  Asosiy      Bilimlar    Simulyatsiya*  Profil
```

**UX:** har bir elementda `o‘yin` emas, `mashq` ekani tushunarli. Tizimda nashr qilinmagan ssenariy `Boshlash` tugmasi bilan chiqmaydi.

### 33.4. Simulyatsiya ichida — `/practice/:slug/play`

```text
  ← Chiqish                  SIMULYATSIYA  2 / 4
  
  Sizga noma'lum odamdan xabar keldi
  ┌───────────────────────────────────────┐
  │    📩  XAVFSIZ SMS MAKETI            │
  │    Yuboruvchi: NamunaXizmat         │
  │    "Hisobingiz bloklandi..."          │
  │    example.invalid/pay               │
  └───────────────────────────────────────┘
  Qanday harakat qilasiz?
  ┌───────────────────────────────────────┐
  │ ○ Xabardagi link orqali tekshiraman  │
  └───────────────────────────────────────┘
  ┌───────────────────────────────────────┐
  │ ○ Rasmiy ilovadan mustaqil tekshiraman│
  └───────────────────────────────────────┘
  [             Javobni tasdiqlash          ]
```

**UX:** “SIMULYATSIYA” belgisi ko‘rinib turadi, lekin `Qaysi javob to‘g‘ri` oldindan aytilmaydi. Tanlov berilgan zahoti tanlangan holat ravshan, faqat `Javobni tasdiqlash`dan keyin sahna o‘zgaradi. Tasdiq loading paytida qayta bosish bloklanadi.

### 33.5. Natija — `/practice/:slug/result`

```text
  < Chiqish                 Vaziyat tahlili

  [🛡]  Xavfsiz qarorni tanladingiz
  
  Nimani to‘g‘ri topdingiz?
  ✓ Xabardagi bosimga uchmadingiz.
  ✓ Rasmiy ilovadan tekshirishni tanladingiz.

  Nima uchun?
  Soxta xabarlar linkni zudlik bilan bosishga ...

  [ 📘 Mavzuni chuqurroq o‘rganish → ]
  [ 🔄 Boshqa vaziyatda qayta sinash     ]
  [ ✔ Bir hayotiy odatni boshlash       ]
```

**UX:** xatoga ham xuddi shu strukturada xolis izoh beriladi. Xavfli tanlov qilgan odam uchun xabar `Siz noto‘g‘ri odamsiz` emas, `Bu variant xavfli bo‘lishi mumkin, sababi ...`.

### 33.6. Profil — `/profile`

```text
  Mening profilim                       ⚙
  [👤] Mehmon  [Natijalarni sinxronlash →]

  Mening ko‘nikmalarim              [Batafsil]
  ┌───────────────────────────────────────┐
  │ Soxta havolalarni aniqlash  [Dalillar]│
  │ Parol/2FA                  [Dalillar]  │
  └───────────────────────────────────────┘
  ┌───────────────────────────────────────┐
  │ 🛡️ Mustahkamlash kerak bo‘lganlar → │
  │ 📖 O‘qish tarixim                 → │
  │ 🤝 Va'dalarim                     → │
  │ ✅ Xavfsizlik cheklistim           → │
  │ 🔔 Bildirishnomalar               → │
  │ 🌐 Til va ko‘rinish               → │
  │ 🔒 Maxfiylik va ma'lumotlarim      → │
  │ 🆘 SOS                            → │
  └───────────────────────────────────────┘
  Asosiy      Bilimlar    Simulyatsiya   Profil*
```

**UX:** `Mehmon` profilda data lokalligini aniq ayting; foydalanuvchi shaxsiy natijasini yo‘qotmaslik uchun sinxronlashni istashi mumkin, lekin hech qachon majburiy login emas.

### 33.7. Ogohlantirish batafsil — `/alerts/:slug`

```text
  ← Ogohlantirishlar
  ⚠ Telegram orqali soxta ovoz berish xabarlari
  Manba: [Rasmiy idora]       Sana: [date]
  Tekshirildi: [date]
  ┌───────────────────────────────────────┐
  │ Qanday xabarlar uchrashi mumkin?     │
  │  [Sintetik namuna + aniq izoh]        │
  └───────────────────────────────────────┘
  3 ta muhim belgi
  1. Sizni shoshiltirishadi.
  2. Tasdiqlash kodi so‘raladi.
  3. Domen shubhali.

  Nima qilish kerak?
  [ ✔ Rasmiy ilovadan tekshirish          ]
  [ ▶ Shu vaziyatni mashq qilaman         ]
  [ ↗ Asl rasmiy manbani ochish           ]
```

**UX:** `Asl rasmiy manba` yangi tab/OS browserida, aniq manzil bilan; ilova ichidagi SIM labeli real alert bilan chalkashmasin.

### 33.8. SOS — `/sos`

```text
  ← Orqaga           🛡 Tezkor yordam
  Sizga nima bo‘ldi?
  [ 🔐 Akkauntimga begona kirdi        → ]
  [ 💳 Kartam / pulim xavf ostida     → ]
  [ 🔗 Shubhali havolani ochdim       → ]
  [ 📱 Noma'lum APK o‘rnatdim         → ]
  [ 📍 Telefonim yo‘qoldi             → ]

  ❕ Bu sahifa internet bo‘lmasa ham ishlaydi.
  Muhim: PIN, parol yoki SMS-kodni kiritmang.
```

**UX:** bu ekran uchun animatsiyani minimallashtiring. `Yordam` doim tanib olinadi, `SOS` ma'nosi bilmagan foydalanuvchi uchun `Tezkor yordam` matni ham yozilsin.

### 33.9. Login — `/auth/login`

```text
  ← Orqaga                  CyberGigiyena
       Xush kelibsiz
  Akkauntingiz bo‘lsa natijalar saqlanadi.
  Email manzilingiz
  ┌───────────────────────────────────────┐
  │ email@misol.uz                         │
  └───────────────────────────────────────┘
  Parol
  ┌───────────────────────────────────────┐
  │ ••••••••••                        👁  │
  └───────────────────────────────────────┘
  [                Kirish                 ]
  [       Parolni unutdingizmi?           ]
  [    Mehmon bo‘lib davom etish          ]
```

**UX:** login form inputlar telefonning virtual klaviaturasi bilan yopilmaydi. Emailda autocomplete, parolda password manager mumkin. `Mehmon` linki doim ko‘rinadi.

---

## 34. 30 KUNLIK KONTENT REJASI — 15–30 KUNLAR (13.4 JADVAL DAVOMI)

| Kun | Missiya | Kun savoli | Mikro-dars | Bitta amaliy va'da |
|---:|---|---|---|---|
| 15 | Qo‘shaloq domen | URLdagi asosiy domen qaysi? | URLni qismlarga ajratish | Linkni bosishdan oldin to‘liq ko‘raman |
| 16 | Soxta lotereya | Haddan tashqari yaxshi taklif belgisi | Internetdagi sovg‘alar | Sovg‘a uchun OTP bermayman |
| 17 | O‘g‘irlangan do‘st akkaunti | Tanishdan kelgan so‘rovga ishonish | Kontaktni boshqa kanal bilan tekshirish | Pul talabini mustaqil tasdiqlayman |
| 18 | Soxta texnik yordam | “Kompyuteringizda virus” oynasi | Brauzerda soxta pop-up | Noma'lum yordam dasturini o‘rnatmayman |
| 19 | Mikroqarz tahdidi | Shaxsiy ma'lumotlarni so‘rash | Identifikatsiya va rasmiy servis | Hujjat rasmini notanishga bermayman |
| 20 | SIM-swap belgisi | Tarmoq yo‘qolishi va OTP | Mobil raqam bilan bog‘liq xavflar | Operatorga rasmiy kanaldan murojaat qilaman |
| 21 | Soxta ish suhbati | Avval pul so‘ralishi | Vakansiya firibgarligi | Ishga kirish uchun shubhali to‘lov qilmayman |
| 22 | Begona USB | Notanish qurilma ulash | USB va qurilma ishonchi | Begona qurilmani ulamayman |
| 23 | Shaxsiy profil feyki | Maxfiylik sozlamasi | Ijtimoiy tarmoq profili | Ko‘rinish sozlamasini tekshiraman |
| 24 | Soxta tasdiqlash oynasi | “SMS-kodni kiriting” belgisi | Kodlar va autentifikatsiya | Kodni hech kimga aytmayman |
| 25 | Ekran ulashish so‘rovi | Firibgar yordam taklifi | Remote access xavfi | Ekran ulashishni tekshiraman |
| 26 | Zaxira nusxa yo‘q | Yo‘qolgan faylni tiklash | Backup qoidasi | Bir muhim faylni zaxiralayman |
| 27 | Ilova ortiqcha ruxsat so‘raydi | Mikrofon/kontakt ruxsati | App permissions | Bir ilovaning ruxsatini ko‘raman |
| 28 | Yetkazib berish soxtasi v2 | URL parametrini o‘rganish | Tashqi to‘lov sahifalari | Rasmiy yetkazib beruvchini tekshiraman |
| 29 | 2FA qayta tekshiruv | Koddan boshqa himoya | Passkeys va 2FA asoslari | 2FA recovery kodlari xavfsiz saqlanishini ko‘raman |
| 30 | Oila xavfsizligi | Xavfni kimga aytamiz? | Yaqinga muloyim tushuntirish | Bir yaqinimga foydali maslahat ulashaman |

**Kontent fakt-check:** bu 30 kunlik jadval `tayyor darslar` degani emas. Har satr uchun savol va javob, 2–5 sahnali ssenariy, izoh, rasm/alt text, va'da va QA ishlab chiqiladi. Kunlik paket nashr bo‘lishi uchun besh komponentning hammasi tayyor bo‘lsin.

---

## 35. FLUTTER `webview_flutter` IMPLEMENTATSIYA KONTRAKTI — BOSHLANG‘ICH KOD VA CHEGARALAR

**Kod — production uchun tayyor mutlaq retsept emas:** plugin versiyasi, app domeni, platforma URL scheme, error handling va browser tashqi ilovaga ochilishi real qurilmada testlanadi. WebView javascript ishlatadi, chunki Next.js interaktiv sahifalar uchun JS talab qiladi; shu sabab hardening qat'iy.

### 35.1. `pubspec.yaml` paketlari

```yaml
name: cybergigiyena
publish_to: none
version: 0.1.0+1

environment:
  sdk: '>=3.4.0 <4.0.0' # haqiqiy Flutter stable SDKga moslab locklanadi

dependencies:
  flutter:
    sdk: flutter
  webview_flutter: any     # production'da sinovdan o'tgan fixed versiya + pubspec.lock
  url_launcher: any        # URLni OS brauzerida ochish
  share_plus: any          # native share (ixtiyoriy, native qiymat)
  connectivity_plus: any   # ulanish signali; internet borligini kafolatlamaydi

dev_dependencies:
  flutter_test:
    sdk: flutter
```

`any` — **faqat reja konsepti**. Ishni boshlashda `flutter pub add ...` bilan oxirgi o‘zaro mos barqaror versiyalarni oling, `pubspec.lock`ni commit qiling; production'da `any` qoldirmang.

### 35.2. Flutter minimal WebView strukturasi (`lib/webview/webview_screen.dart`)

```dart
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'package:url_launcher/url_launcher.dart';

const appHost = 'app.example.uz'; // namuna, .env/dart-define bilan almashtiriladi
const appHome = 'https://app.example.uz/home?shell=flutter';

bool isTrustedInternal(Uri uri) =>
    uri.scheme == 'https' &&
    uri.host.toLowerCase() == appHost &&
    (uri.hasPort == false || uri.port == 443) &&
    uri.userInfo.isEmpty;

class CyberWebView extends StatefulWidget {
  const CyberWebView({super.key});
  @override
  State<CyberWebView> createState() => _CyberWebViewState();
}

class _CyberWebViewState extends State<CyberWebView> {
  late final WebViewController controller;
  bool loading = true;
  String? loadError;

  @override
  void initState() {
    super.initState();
    controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0xFFF6F8FC))
      ..setNavigationDelegate(NavigationDelegate(
        onPageStarted: (_) {
          if (mounted) setState(() { loading = true; loadError = null; });
        },
        onPageFinished: (_) {
          if (mounted) setState(() { loading = false; });
        },
        onWebResourceError: (error) {
          if (error.isForMainFrame == true && mounted) {
            setState(() { loading = false; loadError = error.description; });
          }
        },
        onNavigationRequest: (request) async {
          final uri = Uri.tryParse(request.url);
          if (uri == null) return NavigationDecision.prevent;
          if (isTrustedInternal(uri)) return NavigationDecision.navigate;
          if (uri.scheme == 'https' || uri.scheme == 'mailto' || uri.scheme == 'tel') {
            // Tashqi havola avtomatik ochilmaydi: redirect ham shu yerga keladi.
            // MVPda explicit confirmation; productionda trusted source/gesture ham tekshiriladi.
            if (mounted) {
              final ok = await showDialog<bool>(
                context: context,
                builder: (ctx) => AlertDialog(
                  title: const Text('Tashqi ilovani ochasizmi?'),
                  content: Text(uri.host.isEmpty ? uri.scheme : uri.host),
                  actions: [
                    TextButton(onPressed: () => Navigator.pop(ctx, false), child: const Text('Bekor qilish')),
                    TextButton(onPressed: () => Navigator.pop(ctx, true), child: const Text('Ochish')),
                  ],
                ),
              );
              if (ok == true) await launchUrl(uri, mode: LaunchMode.externalApplication);
            }
          }
          return NavigationDecision.prevent;
        },
      ))
      ..loadRequest(Uri.parse(appHome));
  }

  Future<void> handleBack() async {
    if (await controller.canGoBack()) {
      await controller.goBack();
    } else {
      // Native root policy: confirm exit on Android, pop on nested flow.
      // Appni darhol SystemNavigator.pop() qilib yubormang.
    }
  }

  @override
  Widget build(BuildContext context) => Scaffold(
    backgroundColor: const Color(0xFFF6F8FC),
    body: SafeArea(child: Stack(children: [
      WebViewWidget(controller: controller),
      if (loading)
        const Center(child: CircularProgressIndicator()),
      if (loadError != null)
        Center(child: Column(mainAxisSize: MainAxisSize.min, children: [
          const Text('Internet bilan ulanishni tekshiring.'),
          TextButton(onPressed: () => controller.reload(), child: const Text('Qayta urinish')),
          // Native Offline SOS tugmasi navigatsiyasi alohida yoziladi.
        ])),
    ])),
  );
}
```

**Bu minimal kodga qo‘shimchalar shart:** Tashqi havola tasdiqlash dialogi redirectlarda ham chiqishi mumkin; productionda faqat ishonchli manba va foydalanuvchi gesture kelib chiqishini ajrating. OS back `PopScope`; app links; splash; debounced timeout; reliable offline; iOS/Android sessiya migratsiyasi; main-frame navigation policy; tashqi URLga faqat user-initiated interaction bo‘yicha ruxsat; JS bridge validation; iOS ATS; analytics. Internet bo‘lmasa real ishlaydigan SOS native widgetda bo‘ladi. `launchUrl` har bir navigatsiya triggeridan avtomatik pop-up ochib yubormasligi uchun server redirect va user gesture farqini sinang.

### 35.3. Flutter WebViewdan Next'ga boradigan ishlash signali

- `?shell=flutter` **faqat UI hint**; bu query'dan kelib chiqqan holda hech qachon auth/permission/token berilmaydi.
- Next UI native borligini client kontekstda aniqlab, `Install PWA` bannerini yashirishi, safe-area tokenini almashtirishi mumkin. Query marker bo‘lmasa ham sayt normal ishlaydi.
- `webview_flutter` JS channel qo‘shishdan oldin biror haqiqiy native ehtiyoj mavjud bo‘lsin. Eng kichik MVPda **kanal umuman qo‘shilmasa yaxshi**.

### 35.4. App version / backend kompatibilitet

```text
Flutter build 1.0 + web build 2026.11.05 + /api/v1
Flutter build 1.1 + web build 2026.11.12 + /api/v1
```

- Native'da `appVersion`, `bridgeVersion`, `platform` telemetry bor; UI serverga yuboradigan ma'lumot minimal va maxfiylik siyosatiga mos.
- Backend eski mobil release (foydalanuvchi yangilamagan bo‘lishi mumkin) bilan kelishilgan vaqt davomida ishlasin. `404 route` yoki bridge breaking o‘zgarish deploy'da changelogsiz bo‘lmasin.
- Critical platform mismatch bo‘lsa **ochiq xabar + app do‘konidan yangilash**; forcing login loop emas.

## 36. NEXT.JS APP ROUTER + TAILWIND DIZAYN: MOBILGA HAR BIR DETAL

### 36.1. Dizaynning qat'iy ko‘rinishi

**Uslub:** Apple ruhidagi silliq minimalizm, lekin CyberGigiyena o‘z brendi. Qalin qora ko‘k matn, yumshoq oq-sovuq fon, faqat muhim tugmada yorqin ko‘k, ogohlantirishda qizil/sariq, yutuq medali/reytинг/XP yo‘q. Font uchun litsenziyali system sans yoki Inter; Apple SF Pro faylini tarqatmang.

| Token | Light | Dark | Izoh |
|---|---|---|---|
| `--cg-bg` | `#F5F7FB` | `#0D1424` | App background |
| `--cg-surface` | `#FFFFFF` | `#142039` | Card |
| `--cg-text` | `#101828` | `#F8FAFC` | Bosh matn |
| `--cg-muted` | `#536071` | `#B4C0D0` | Ikkilamchi matn |
| `--cg-primary` | `#1677F2` | `#4F9BFF` | CTA, selection |
| `--cg-success` | `#087F5B` | `#31D7A2` | Feedback |
| `--cg-warning` | `#986100` | `#F6C85D` | Ogohlantirish |
| `--cg-danger` | `#C3323F` | `#FF7885` | Xavf / SOS |
| `--cg-line` | `#E1E7F0` | `#26344B` | Divider |

- Card radius `18–24 px`; tugma `14–16 px`; hero `24–28px`; shadow juda yumshoq va faqat depth uchun.
- Design grid 4pt yoki 8pt; 16px yon padding 320–430, 20/24px keng telefon, 32px tablet.
- `min-height: 44px` interaktiv elementlar; asosiy CTA 48–52px; aria labels; 200% zoom va katta fontda matn kesilmasin.
- Home scroll: profil salom / SOS → **bugungi missiya** → **sanali rasmiy alert** → **kun savoli** → qisqa bilim → zaif mavzular → ulashish → va'da. Tab bar fixed va sahifa pastidagi padding to‘g‘ri.
- Loading skeleton mos card konturida; success/error nazoratli inline, toast o‘qiladigan; `No internet`da axborot yo‘qolmasin.

### 36.2. Responsive brauzer va qobiq

| Viewport (CSS px) | Layout | Max container | Alohida sinov |
|---|---|---|---|
| 320×568 | Bitta ustun, 16px padding | 100% | 2 qatorli uzun CTA, radio |
| 360×740 | Bitta ustun | 100% | bottom nav 4 nom to‘liq |
| 375×812 | iPhone kompakt | 100% | Safari safe area |
| 393×852 | Asosiy Figma target | 100% | barcha card spacing |
| 412/430/480 | Telefon | 100% | webview keyboard |
| 600–839 | 1–2 ustun mos | 720px | foldable /
| ≥840 | Ikki/uch ustun, sidebar | 1100px | tablet va landscape |

**Native shell va browser farqi:** `WebViewWidget` Flutter `SafeArea` ichida bo‘lsa CSS `env(safe-area-inset-*)`ni takrorlash yaramaydi. `data-shell=flutter`da `--shell-safe-bottom:0px`, browser/PWA'da `env(safe-area-inset-bottom)` ishlatiladi. Test: iPhone Dynamic Island, Android gesture bar, large font, landscape, split screen.

### 36.3. Next.js `viewport`, `layout.tsx` asosiy namuna

```tsx
// web/src/app/layout.tsx
import type { Metadata, Viewport } from 'next';
import './globals.css';

export const metadata: Metadata = {
  title: { default: 'CyberGigiyena', template: '%s | CyberGigiyena' },
  description: 'Har kuni real raqamli xavflardan himoyalanishni o‘rganing.',
};
export const viewport: Viewport = {
  width: 'device-width', initialScale: 1, viewportFit: 'cover',
  themeColor: '#F5F7FB',
};
export default function RootLayout({ children }: { children: React.ReactNode }) {
  return <html lang="uz"><body>{children}</body></html>;
}
```

`maximumScale=1` yoki `user-scalable=no` qo‘ymang — bu foydalanuvchining zoom huquqini cheklaydi. App Router `loading.tsx`, `error.tsx`, `not-found.tsx`, `<Link>` birinchi kundan yoziladi. Form input telefon klaviaturasini to‘g‘ri ochadigan `type`, `inputMode`, `autoComplete` bilan.

### 36.4. Tailwind + safe area konsepti

```css
/* web/src/app/globals.css — Tailwind o‘rnatish uslubi versiyaga mos */
:root {
  --cg-bg:#F5F7FB; --cg-primary:#1677F2;
  --cg-safe-bottom:env(safe-area-inset-bottom, 0px);
}
html[data-shell='flutter'] { --cg-safe-bottom:0px; }
.cg-tabbar {
  position:fixed; inset-inline:0; bottom:0;
  padding-bottom:var(--cg-safe-bottom);
  background:color-mix(in srgb, white 92%, transparent);
  backdrop-filter:blur(16px);
}
.cg-page { min-height:100dvh; padding-bottom:calc(76px + var(--cg-safe-bottom)); }
@media (prefers-reduced-motion:reduce) {
  *,*::before,*::after { scroll-behavior:auto!important; animation-duration:0.01ms!important; }
}
```

Tailwind utilitylarini `rounded-2xl`, `text-sm`, `px-4`, `min-h-12` kabi izchil ishlating; hardcode inline ranglar bilan har ekran boshqacha bo‘lmasin. Dark mode toggle va OS default; `html`da `data-theme` hamda hydration flicker sinovi.

### 36.5. Har bir karta nimalardan iborat?

| UI karta | Minimum matn / CTA | Xato holat | Dinamik manba |
|---|---|---|---|
| MissionHero | nom, 3–5 min, `Boshlash` | cached hint | daily bundle |
| UzbekAlert | sarlavha, rasmiy manba, sana, `Batafsil` | `Hozir yangi xabar yo‘q` | verified alerts |
| DailyQuestion | 2–4 variant, `Tekshirish`, javob izohi | empty/offline | question API |
| MicroLesson | 2 min, mavzu, `O‘rganish` | saved last lesson | lessons |
| WeaknessCard | dalilga asoslangan 1–2 mavzu, `Mustahkamlash` | `Yetarli ma'lumot yo‘q` | skill API |
| ShareTip | bitta qoida, `Ulashish` | copy | safe tip API |
| Promise | bitta kundalik odat, `Va'da berish` | local queue | promises API |
| SOS | xavf toifasi, `Tez yordam` | Flutter native asset | verified guide |

## 37. WEB ↔ FLUTTER KONTRAKTLAR: AUTH, JS BRIDGE, DEEP-LINK, SHARE, PUSH

### 37.1. Auth: chalkashliksiz yagona yo‘l

1. User ilovada `https://APP_DOMEN/auth/login`ni ochadi.
2. Browser/WebView web formdan `POST /api/v1/auth/login` **same-origin** ketadi.
3. PHP parolni tekshiradi, login sessiyasini almashtiradi, `Secure; HttpOnly; SameSite=Lax; Path=/` (host-only `__Host-` prefix mos bo‘lsa) cookie yaratadi.
4. Next client UI `GET /api/v1/me` orqali account ko‘rsatadi; Flutter **parolni olmaydi**.
5. WebView va browser cookie storage alohida bo‘lishi tabiiy; user bitta login hisobiga kirganda backend progress mos tushadi.
6. Logout `POST /api/v1/auth/logout` sessiyani serverda bekor qiladi, browser cookie muddati tugatiladi; native cached UI private data ko‘rsatmaydi.
7. Session expiry: `401` → oldin yarim qolgan guest/offline harakatlar saqlanadi → login → serverdan state sync.

**OAuth / Google/Apple Sign-In qo‘shilsa:** login jarayoni tashqi system browser / ASWebAuthenticationSession/Custom Tabs orqali xavfsiz olib borilishi va verified universal/app link callback ishlatilishi kerak; OAuth embedded WebView ichida ishlashini faraz qilmang. MVP email/password yoki ruxsat etilgan auth provayder qoidalari asosida. Native app sign-in flow alohida security review.

### 37.2. Next server data fetch

- Public dars / landing server componentda render qilinishi mumkin.
- Private `/profile` ma'lumotlarining server-side fetchi `Cookie`ni audit bilan faqat ishonchli PHP APIga forward qilishni yoki client `same-origin` fetchni talab qiladi; **umumiy `fetch` cache'ga session bilan bog‘liq natija chiqmasin**.
- PHP APIda server `origin`, `CSRF` va harakat permissionlarini tekshiradi. `?shell=flutter`, `User-Agent`, request headerdagi `platform` **authorization**ga ta'sir qilmaydi.
- Shared types OpenAPI bilan; Next TypeScript schema va PHP DTO contract test. Har `Idempotency-Key` UUID bir urinishda bitta yozuv.

### 37.3. Native bridge — faqat zarurat bo‘lganda

**V1da talab minimal:** `shareTip`, `openSettings`, `requestPushPermission` (foydalanuvchi ochiq bosganda), `openOfflineSOS`, `getAppInfo` (sensitive emas). `readSMS`, `getContacts`, `getPassword`, `readCookies`, `executeCommand` **mavjud emas**.

```json
{
  "version": 1,
  "requestId": "uuid-v4",
  "action": "shareTip",
  "payload": { "text": "Tasdiqlash kodini hech kimga bermang." }
}
```

**Security contract:** Flutter qobiq JS channel xabarining o‘zi qayerdan kelganini kriptografik isbotlab bermaydi. Sahifa DOMi XSSsiz, faqat allowlisted domain yuklanadi; `requestId` uniq, action whitelist, max payload bytes, string sanitization, user gesture/ruxsat tasdig‘i, native rate-limit, default-deny. Ko‘r-ko‘rona JS'ga privileged native funksiyalarni ochmaslik. Bridge yordamida auth token yubormang. Sifatli MASTG/WebView security audit shart.

### 37.4. Deep-link

| Link | Web direct | Native app ochilgan bo‘lsa | Login talab? |
|---|---|---|---|
| `/home` | Home | WebView `.loadRequest` / JS router | Yo‘q |
| `/learn/lesson/otp` | Dars | App Links / Universal Links → shu route | Yo‘q |
| `/practice/sim01/intro` | Sim intro | In-app route | Yo‘q |
| `/practice/sim01/result` | Direct kirish state tekshiriladi | state yo‘q bo‘lsa intro/history | Hisob natijasi uchun ha |
| `/profile/weaknesses` | Guest summary | App login redirect va return | Shaxsiy tarix uchun ha |
| `/sos/account` | SOS | Har doim tez ochilsin | Yo‘q |

Deep-link uchun Android `assetlinks.json` va iOS `apple-app-site-association`ni **domen egasi** joylashtiradi. Unknown URL app rootga xavfsiz fallback. Xabar orqali kelgan manzilni validatsiyasiz WebView'ga berish mumkin emas.

### 37.5. Push (keyingi bosqich)

- Foydalanuvchining **o‘z tanlovi** bilan kunlik eslatma yoki muhim verified alert; default off.
- Flutter push service FCM/APNs, iOS user permission, Android version-specific notification permission.
- Backend `device_installations` / `notification_preferences` jadvallarida token serverda hashlash yoki ehtiyotkor encrypted storage siyosati; logout/revoke/delete ishlaydi.
- Push payload **parol yoki shaxsiy maxfiy data emas**; `route=/alerts/slug`, `notificationId`, `contentVersion`, event timestamp.
- Alertni haqiqiy dolzarb xabar deb ko‘rsatishdan oldin editor approve va expiry. Qo‘rqitish/spam yo‘q, 1 kunda excessive push yo‘q.

## 38. WEBVIEW OFFLINE / QOTISHGA QARSHI MUHANDISLIK

### 38.1. Transport muammolari va xabarlar

| Holat | Foydalanuvchi ko‘radigan matn | Tugma | Fallback |
|---|---|---|---|
| Internet yo‘q | `Internet ulanmagan` | `Qayta urinish` | Native SOS |
| DNS/SSL | `Saytga xavfsiz ulanishning imkoni bo‘lmadi` | `Tekshirish` | HTTPS-only; SSL bypass yo‘q |
| 401 | `Sessiya yakunlangan` | `Kirish` | oldingi progress navbatda |
| 403 | `Bu bo‘limga kirish ruxsati yo‘q` | `Asosiyga qaytish` | login / guest |
| 404 | `Sahifa topilmadi` | `Asosiy` | route fallback |
| 429 | `Birozdan keyin qayta urinib ko‘ring` | delayed retry | countdown |
| 500/503 | `Xizmat vaqtincha ishlamayapti` | `Qayta urinish` | cached guide |
| JSON xato | `Ma'lumotni yuklashda muammo` | `Takrorlash` | no blank card |
| Flutter web error | Native offline / SOS | `Retry` | ilovani yopish shart emas |
| Update conflict | `Mashg‘ulot yangilandi` | `Natijani saqlash` | active attempt saqlansin |

### 38.2. Freeze/white-screen testlar

- WebView startup failure, 30 sek davomida `waiting` qolmasin; timeout sozlangan native offline.
- Next SSR qotishi, Node restart, PHP 502, DB timeout, DNS failure — foydalanuvchi hech qachon 1 daqiqa spinnerga qaramasin.
- Telefon dasturi boshqa appga o'tib qaytganda WebView reload emas, pending scenario imkon qadar tiklanadi.
- Android process kill, iOS memory pressure: local state, server resume token, URL deep link security.
- HTML form input focus: iOS keyboard viewportni buzmasin, Android back keyboard'ni yopganda accidental exit bo‘lmasin.
- WebView ruscha/lotincha uzun so‘zlar, 320px va zoom: har CTA qolip ichiga sig‘sin.
- JS disabled emas, chunki Next dinamik UX uni talab qiladi; buning evaziga CSP, dependencies audit, safe rendering va allowlist qat'iy.

### 38.3. Kontent update siyosati

- `daily_bundle` serverda kunlik; `publishedAt`, `effectiveDate`, `expiresAt`, `checkedAt`, `version` majburiy.
- Flutter app yangilanishi web content uchun talab qilinmaydi; lekin **native bridge breaking o‘zgarishlari** yoki SDK policy uchun mobil release talab bo‘lishi mumkin.
- Cache versiya nosoz bo‘lsa `Clear old public cache` va `Retry`; maxfiy ma'lumotni tasodifan o‘chirib yubormang.

## 39. MULTIPLATFORM QA MATRITSA: QAYSI TUGMA QAYERDA SINOVDAN O‘TADI?

### 39.1. Minimum haqiqiy qurilmalar

| Qurilma | Brauzer | Flutter qobiq | Vazifa |
|---|---|---|---|
| 320px low-end Android | Chrome | Android WebView | sekin CPU + keyboard |
| 360px Samsung | Samsung/Chrome | Android WebView | gestures + tabbar |
| Pixel 393px | Chrome | Android WebView | standart tayanch |
| iPhone SE klassik kompakt | Safari | WKWebView | kichik ekran/safe area |
| iPhone Dynamic Island | Safari | WKWebView | status bar/notch |
| Katta iPhone | Safari | WKWebView | text scaling/back |
| iPad / Android tablet | Safari/Chrome | WKWebView/WebView | 600/840px breakpoints |

### 39.2. Har bir sahifada 12 ta umumiy test

1. URL orqali to‘g‘ridan ochilishi va refreshdan keyin 404 bermasligi.
2. 320/393/430px, landscape, 200% matn: gorizontal scroll yo‘q.
3. Header, SOS, nav va primary CTA tekshiruvi.
4. Loading/empty/error/offline states.
5. Screen reader semantika, input label va focus.
6. `Next Link` client navigation, back / forward / deep-link.
7. API 200/400/401/403/404/429/500 javoblariga foydali xabar.
8. Safari va WKWebView farqlari; Chrome va Android WebView farqlari.
9. Ma'lumotni ortiqcha yuborish/logdan saqlash.
10. Guest va logged-in oqimida ishlash.
11. Qayta bosish / double submit natijani dublikat qilmasligi.
12. Tashqi link doim foydalanuvchi nazoratida, zararli URL simulyatsiyada ochilmaydi.

### 39.3. Alohida WebView integratsiya testlari

- [ ] Flutter splashdan WebViewga bir marta o‘tish, oq ekran yo‘q.
- [ ] iOS/Android cookie login va app restart.
- [ ] Redirect `evil.APP_DOMEN.attacker` exact host allowlist bilan bloklangan.
- [ ] `https://APP_DOMEN.evil.com`, `http://APP_DOMEN`, `file://` blocked.
- [ ] SMS OTP kiritish so‘ralmaydi, xavfli APK download qilinmaydi.
- [ ] Flutter native SOS offline, web SOS navigatsiyasi online.
- [ ] iPhone safe area va Android nav bar ustida tab ko‘rinadi.
- [ ] Keyboardda CTA va form input scroll bilan topiladi.
- [ ] Deep-link faqat known internal route; notification invalid route reject.
- [ ] Oflayn/online o‘tish sessiyani buzmaydi.
- [ ] Tashqi link brauzer ochilib qaytganda WebView oldingi holatda.
- [ ] Native share sheet user gesture; auto-send yo‘q.
- [ ] App uninstall/reinstall private cookie yo‘qotilishi mumkin, server account progress saqlanadi.
- [ ] Web release rollback eski Flutter binary bilan ishlaydi.
- [ ] App Store review demo mode va Play release stability test.

**Test vositalari:** Next `Playwright`, React Testing Library/Vitest yoki loyiha bilan mos test runner; PHP PHPUnit, static analysis; Flutter `flutter_test`, `integration_test`; Lighthouse/Web Vitals; OWASP ZAP ruxsat etilgan staging muhitda. `Next build` hamda Flutter analyzer green bo‘lmaguncha release yo‘q.

## 40. RELEASE, DOMAIN, SERVER, ANALITIKA VA MA'LUMOT HIMOYASI

### 40.1. Muhitlararo shartnoma

```text
prod.app.example.uz    -> bitta HTTPS production web (namuna)
staging.app.example.uz -> alohida staging (test data only)
GitHub                 -> web/, flutter/, backend/, docs/
Next Node              -> SSR, route layouts, static chunks
PHP-FPM/API            -> auth, content, scoring, editor, API
MySQL                  -> content + privacy safe user data
Flutter                -> HTTPS Next site + native fallback/OS integration
```

**DNS / domen:** hali haqiqiy domen tanlanmagan; mana bu misol URLlardan hech biri rasmiy manba emas. Production domen tanlangach URL siyosat, SSL, `.well-known` fayllar va store listingga bitta identifikator bog‘lanadi.

### 40.2. Maxfiylik

- Account minimum: email (yoki keyin boshqa login), parol hash, ixtiyoriy ism; foydalanish uchun guest mavjud.
- Tracking: agregatsiya va rozilik, xom SMS / haqiqiy maxfiy yozishmalar yig‘ilmaydi.
- `Export my data`, `Delete my account`, `Privacy Policy`, `Support`, `Report wrong warning` real ishlashi kerak.
- Admin moderator alertlarga asl manba, sana va tekshiruv izohini biriktiradi.
- Backup tiklash testi; log retention va ma'lumot o‘chirish to‘g‘ri ishlaydi.

### 40.3. Operator runbook

1. Kunlik health checks: web 200, PHP API health 200, DB connection, ertangi daily paket tayyor.
2. Alert monitoring: expiry, broken source, incorrect URL, editor approval.
3. Xavfsizlik: failed login spike, CSP report, API 4xx/5xx, JS bridge reject signal.
4. Web release: `next build` + migratsiya audit + smoke; rollback; eski mobil binary bilan regressiya.
5. Mobil release: Android AAB signed, iOS signed IPA; store listing, privacy and demos; staged deployment.
6. Favqulodda xatoda native app offline SOS foydali qoladi; buni reklama yoki avtomatik vahima uchun ishlatmang.

## 41. IJROGA TAYYOR ISH PAKETLARI — QADAM QADAM, TEKSHIRILADIGAN NATIJA

| Sprint / epik | Topshiriq | Kod joyi | Test natijasi |
|---|---|---|---|
| 01 | Repo, Next + Flutter + PHP init | `/web /flutter /backend` | build + run |
| 02 | CSS tokens, UI kit, 4 tab | `/web/src/components` | 320/393 screenshots |
| 03 | Home/daily API/MySQL | `/web/src/app/(app)/home`, `/backend` | haqiqiy day packet |
| 04 | Bilimlar va 12 dars | `/web/src/app/(app)/learn` | dars progress |
| 05 | 5 simulyatsiya + natija | `/web/src/app/(app)/practice` | scenario 7-step test |
| 06 | SOS, ulashish, va'da | web features + flutter assets | guest usable |
| 07 | Profil + auth + weak areas | web + backend auth | 2 devices sync |
| 08 | Flutter navigation + cookie + SOS | `/flutter/lib` | Android/iOS test |
| 09 | CMS + alert factcheck | `/web/src/app/(admin)` | editor publish |
| 10 | Privacy, accessibility, QA | all | no critical defects |
| 11 | Native share/push optional | `/flutter/lib/native` | user opt-in |
| 12 | Production + Android/iOS beta | `/infra`, store metadata | public beta |

### 41.1. Bitta tugmani “tayyor” deyish uchun Definition of Done

- Tugma maqsadi yozilgan, route to‘g‘ri, loading/disabled/error/success holati bor.
- Hamma ekran o‘lchamlarida bosiladi; screen reader label bor.
- API validatsiya qiladi; tegishli user/ruxsatni tekshiradi.
- Dublikat urinish yoki tez-tez bosish to‘g‘ri ishlaydi.
- Browser Chrome/Safari va Flutter Android/iOS WebViewda bir xil asosiy natija.
- Test va demo ma'lumotlari haqiqiy, `TODO` placeholder qoldirilmagan.

### 41.2. Qabul senariysi — foydalanuvchi oxirigacha foyda oladimi?

```text
1) Ilovani birinchi marta ochadi.
2) Mehmon bo‘lib davom etadi.
3) Asosiy sahifada bugungi missiya va tekshirilgan xabarni ko‘radi.
4) Kun savoliga javob beradi va nega xato/to‘g‘ri ekanini o‘rganadi.
5) Simulyatsiyada soxta bank SMSni tanlaydi.
6) Qarori oqibatini, real xavfsiz qadamni va izohni oladi.
7) Zaif mavzusi aniqlansa, mos darsga yuboriladi (yetarli dalilsiz foiz yo‘q).
8) Bugungi xavfsizlik va'dasini qabul qiladi.
9) Foydali maslahatni ixtiyoriy ulashadi.
10) Agar internet uzilsa, Flutter offline SOS yordamidan foydalana oladi.
11) Keyingi kuni yangi daily bundle bilan qaytadi.
12) Hisob ochsa, brauzer va Android/iOS hisobda bir xil progressni ko‘radi.
```

**Yakuniy sifat talabi:** CyberGigiyena — statistik bannerlar to‘plami emas; real odamga xavfni tanish, xavfsiz qaror qilish va foydali odatni mustahkamlash imkonini beradigan ishlaydigan mahsulot. **Bitta Next.js sayt + Flutter Android va iOS qobig‘i** shu natijaga xizmat qiladi.
