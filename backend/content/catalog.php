<?php
declare(strict_types=1);

// Editorial seed. Scoring stays in PHP and is removed from all public responses.
$topics = [
 ['basics','Raqamli xavfsizlik asoslari','Shoshilmang. Tekshiring. Keyin qaror qiling.','shield','mint'],
 ['password','Parol va ikki bosqichli himoya','Hisobingiz uchun ikki qavatli himoya.','key','purple'],
 ['phishing','Soxta havolalar','Haqiqiy manzilni soxta manzildan ajrating.','link','blue'],
 ['telegram','Telegram xavfsizligi','Akkauntingiz va suhbatlaringizni asrang.','message','sky'],
 ['bank','Karta va to‘lovlar','Pul va tasdiqlash kodlarini himoya qiling.','card','peach'],
 ['apps','Ilovalar va APK','Faylni ochishdan oldin manbasini tekshiring.','phone','purple'],
 ['network','Wi-Fi va QR-kod','Qayerga ulanayotganingizni biling.','wifi','mint'],
 ['social','Ijtimoiy muhandislik','Shoshiltirish va soxta vakilni taning.','users','peach'],
 ['shopping','Xavfsiz onlayn savdo','To‘lov va buyurtmani mustaqil tekshiring.','bag','blue'],
 ['privacy','Shaxsiy ma’lumotlar','Internetda nimalar ulashayotganingizni biling.','eye','purple'],
 ['backup','Qurilma va zaxira nusxa','Muhim ma’lumotlaringizni yo‘qotmang.','cloud','sky'],
 ['help','Hodisada birinchi yordam','Xavf yuz bersa, birinchi qadamni biling.','heart','peach'],
];
$lessonRows = [
 ['basics','Bir lahza to‘xtash odati','Shoshilinch xabar kelganda xavfsiz qaror qilishni o‘rganasiz.',
 'Sizga “Hisobingiz bugun yopiladi” degan xabar keldi. Xabar sizdan darhol havolaga kirishni so‘raydi.',
 ['Shoshirish — tekshirish uchun signal.|“Hozir bajaring” degan talabga darhol javob bermang. Bir daqiqa to‘xtang.', 'Boshqa kanaldan tekshiring.|Xabardagi raqamga emas, o‘zingiz bilgan rasmiy ilova yoki saytga murojaat qiling.', 'Keraksiz ma’lumot bermang.|Tasdiqlash kodi va parolingiz sizga tegishli. Ularni suhbatdoshga aytmang.'],
 'Muhim xabar kelganda rasmiy ilovani o‘zingiz ochishga odatlaning.',
 'Xabar sizni zudlik bilan havolaga kirishga undayapti. Birinchi qadam?', ['Havolani darhol ochish','To‘xtab, rasmiy kanaldan tekshirish','Xabarni hammaga yuborish'],1,'Shoshiltirish fikrlash uchun vaqtni kamaytiradi. Mustaqil kanal orqali tekshirish sizga nazoratni qaytaradi.'],
 ['password','Kuchli parol, qo‘shimcha himoya','Takrorlanmaydigan parol va ikki bosqichli himoyani tushunasiz.',
 'Bir do‘kondagi parolingiz boshqa xizmatdagi parolingiz bilan bir xil. Birinchi xizmatdagi ma’lumot sizib chiqsa, ikkinchi hisob ham xavf ostida.',
 ['Har hisobga alohida parol.|Bir parolni bir necha joyda ishlatmang. Ishonchli parol menejeri yordam berishi mumkin.', 'Uzunlik va noyoblik muhim.|Taxmin qilinadigan ism yoki tug‘ilgan sana o‘rniga uzun, tasodifiy parol tanlang.', 'Ikki bosqichli himoyani yoqing.|Xizmat taklif qilsa passkey yoki autentifikatorni tanlang. Tiklash kodini xavfsiz joyda saqlang.'],
 'Bitta muhim hisobingizning xavfsizlik sozlamalarini ochib, ikki bosqichli himoyani tekshiring.',
 'Bir xil parolni bir nechta xizmatda ishlatish nega xavfli?', ['Yodlash qiyinlashadi','Bitta sizib chiqish boshqa hisoblarni ham xavfga qo‘yadi','Telefon tezroq qiziydi'],1,'Firibgar oshkor bo‘lgan parolni boshqa xizmatlarda ham sinab ko‘rishi mumkin. Noyob parol bu xavfni kamaytiradi.'],
 ['phishing','Havolaning asl manzili','Domenni o‘qish va shubhali manzilni ajratishni mashq qilasiz.',
 'Xabarda “bank” so‘zi bor, lekin manzil bank.example.invalid ko‘rinishida. Tanish nom borligi manzilning haqiqiyligini isbotlamaydi.',
 ['Domenni to‘liq o‘qing.|Logotipga yoki bitta tanish so‘zga ishonmang. Domen oxiridagi asosiy nomni tekshiring.', 'HTTPSning o‘zi yetarli emas.|Qulf belgisi ulanish shifrlanganini bildiradi. Sayt halol ekanini kafolatlamaydi.', 'Rasmiy ilovani mustaqil oching.|Xabar orqali kelgan kirish havolasi o‘rniga oldindan o‘rnatilgan rasmiy ilovadan foydalaning.'],
 'Muhim xizmat manzilini tekshirib, brauzer xatcho‘piga saqlang.',
 'Manzilda HTTPS bor. Bu nimani bildiradi?', ['Sayt albatta rasmiy','Ulanish shifrlangan, ammo saytni yana tekshirish kerak','Sayt hech qanday ma’lumot yig‘maydi'],1,'Firibgar sayt ham HTTPS ishlatishi mumkin. Domen va xabar kontekstini alohida tekshiring.'],
 ['telegram','Telegramdagi faol seanslar','Hisobingizga qaysi qurilmalar kirganini tekshirishni o‘rganasiz.',
 'Telegram qurilmalari ro‘yxatida o‘zingiz tanimaydigan seans ko‘rindi. Qurilma nomi va oxirgi faollikni birga tekshirish kerak.',
 ['Sozlamalardan tekshiring.|Telegram → Sozlamalar → Qurilmalar bo‘limini oching. Menyu nomi versiyaga qarab farq qilishi mumkin.', 'Begona seansni yakunlang.|O‘zingizga tegishli bo‘lmagan seansni tekshirib yoping. Joylashuv taxminiy bo‘lishi mumkin.', 'Kodni bermang.|Telegram kirish kodi ovoz berish yoki sovrin uchun kerak bo‘lmaydi. Ikki bosqichli parolni ham yoqing.'],
 'Telegram → Sozlamalar → Qurilmalar bo‘limida seanslarni o‘zingiz tekshiring.',
 'Do‘stingiz ovoz berish uchun Telegram kirish kodini so‘rayapti. Nima qilasiz?', ['Kodni yuboraman','Kodni bermayman, do‘stim bilan boshqa kanaldan bog‘lanaman','Kodni guruhga yozaman'],1,'Do‘stingizning akkaunti egallangan bo‘lishi mumkin. Kirish kodi hisobga kirish imkonini beradi.'],
 ['bank','SMS-kod — faqat siz uchun','Bank nomidan kelgan so‘rovni mustaqil tekshirishni o‘rganasiz.',
 'Qo‘ng‘iroq qilgan odam o‘zini bank xodimi deb tanishtirib, “pulni saqlash uchun” SMS-kodni aytishingizni so‘radi.',
 ['Kodning matnini o‘qing.|SMS qaysi amalni tasdiqlashini aytadi. O‘zingiz boshlamagan amalni tasdiqlamang.', 'Kodni suhbatdoshga bermang.|OTP, PIN va parolni qo‘ng‘iroq yoki chatda aytmang. Ular hisobga kirish yoki to‘lovni tasdiqlashi mumkin.', 'Bankka o‘zingiz bog‘laning.|Qo‘ng‘iroqni yakunlab, bankning rasmiy ilovasi yoki kartadagi aloqa kanalidan foydalaning.'],
 'Bankingizning rasmiy qo‘llab-quvvatlash bo‘limi qayerda ekanini topib qo‘ying.',
 'Bank xodimi deb tanishtirgan kishi SMS-kodni so‘radi. Eng xavfsiz tanlov?', ['Kodni aytish','Suhbatni yakunlab, bankka rasmiy kanal orqali bog‘lanish','Kodni ikki qismga bo‘lib aytish'],1,'Suhbatdoshning kimligini ism yoki telefon raqami bilan aniqlab bo‘lmaydi. Mustaqil rasmiy kanal orqali tekshiring.'],
 ['apps','Noma’lum APK fayli','Ilova manbasi va ruxsatlarini tekshirishni o‘rganasiz.',
 'Suhbatda “Rasmda sizmisiz?” nomli .apk fayl keldi. Bu oddiy rasm emas, Android ilova o‘rnatish fayli.',
 ['Fayl turini tekshiring.|.apk — ilova paketi. Nomi rasmga o‘xshashi uning rasm ekanini bildirmaydi.', 'Manbaga e’tibor bering.|Ilovalarni rasmiy do‘kondan oling. Ishlab chiquvchi va ruxsatlarni ham tekshiring.', 'Ortiqcha ruxsat bermang.|Oddiy xizmat uchun SMS yoki Accessibility ruxsati talab qilinishi tekshirishga sabab.'],
 'Sozlamalardagi noma’lum ilovalarni o‘rnatish ruxsatlarini tekshiring.',
 'Chatdan kelgan “rasm.apk” nima?', ['Oddiy surat','Android ilova o‘rnatish fayli','Faqat audio'],1,'Fayl kengaytmasi .apk bo‘lsa, u ilova paketi. Uni ko‘rish uchun o‘rnatish xavf tug‘dirishi mumkin.'],
 ['network','QR-kodni ochishdan oldin','QR ichidagi manzilni tekshirish odatini olasiz.',
 'Kafedagi QR-kod menyu o‘rniga karta ma’lumotlarini so‘raydigan sahifaga olib bormoqda.',
 ['QR ham havola.|Kod xavfsizlik belgisi emas. Ochiladigan manzilni oldindan ko‘ring.', 'Talab maqsadga mos bo‘lsin.|Menyuni ko‘rish uchun bank kartasi yoki kirish kodi kerak emas.', 'Xodimdan aniqlashtiring.|Ustidan yopishtirilgan QR yoki noma’lum manzilni ko‘rsangiz, rasmiy menyuni so‘rang.'],
 'Keyingi QR-kodni ochishda manzil va maqsad mosligini tekshiring.',
 'Menyu QR-kodi karta ma’lumotini so‘rayapti. Nima qilasiz?', ['Ma’lumotlarni kiritaman','Sahifani yopib, xodimdan tekshiraman','Telefonni do‘stimga beraman'],1,'Oddiy menyuga karta ma’lumoti kerak emas. Maqsadga mos kelmagan talabda to‘xtash kerak.'],
 ['social','Shoshiltirishga qarshi bir daqiqa','Soxta vakil va hissiy bosimni tanishni o‘rganasiz.',
 '“Rahbaringizman, hozir pul yuboring, hech kimga aytmang” degan xabar keldi. Surat va ism tanish.',
 ['Ism va surat dalil emas.|Profilni ko‘chirish yoki akkauntni egallash mumkin.', 'Maxfiy va shoshilinch talabni tekshiring.|“Hech kimga aytmang” degan gap sizni maslahatdan ajratishi mumkin.', 'Boshqa kanaldan tasdiqlang.|O‘zingiz bilgan raqamga qo‘ng‘iroq qiling. Xabardagi yangi raqamga tayanmang.'],
 'Yaqinlaringiz bilan shoshilinch pul so‘rovini qo‘ng‘iroqda tekshirishga kelishib oling.',
 'Rahbar nomidan pul so‘raldi. Avval nima qilasiz?', ['Suratiga ishonaman','Oldindan tanish raqam orqali tasdiqlayman','Darhol yuboraman'],1,'Ism va suratni taqlid qilish oson. Mustaqil kanal orqali tasdiqlash soxta vakil xavfini kamaytiradi.'],
 ['shopping','To‘lov cheki hali pul emas','Savdoda tushumni mustaqil tekshirishni o‘rganasiz.',
 'Xaridor to‘lov skrinshotini yubordi va mahsulotni tezroq jo‘natishni so‘radi. Hisobingizdagi tushum hali tekshirilmagan.',
 ['Skrinshotni tasdiq deb olmang.|Rasmni tahrirlash mumkin. U haqiqiy to‘lov bo‘lganini isbotlamaydi.', 'Tushumni bank ilovasida ko‘ring.|Ilovani mustaqil ochib, tranzaksiya va summani tekshiring.', 'Platforma himoyasini saqlang.|Sotuvchi noma’lum havolada qo‘shimcha to‘lov so‘rasa, platforma yordamidan aniqlashtiring.'],
 'Mahsulotni berishdan oldin tushumni o‘z bank ilovangizda tasdiqlang.',
 'Xaridor chek skrinshotini yubordi. Qaysi dalilga tayanasiz?', ['Skrinshot rangiga','Bank ilovamdagi haqiqiy tushumga','Xaridorning shoshilishiga'],1,'O‘z bank ilovangizdagi tranzaksiya skrinshotga qaraganda ishonchliroq tasdiqdir.'],
 ['privacy','Nimalarni ulashayotganingizni biling','Profil va ilova ruxsatlarini kamaytirishni o‘rganasiz.',
 'Ochiq profilingizda telefon raqami, tug‘ilgan sana va safar joylashuvi birga ko‘rinmoqda.',
 ['Auditoriyani tanlang.|Har bir post hammaga ko‘rinishi shart emas. Maxfiylik sozlamalarini ko‘rib chiqing.', 'Ruxsatlarni qayta ko‘ring.|Joylashuv, kamera va kontaktlarni faqat vazifa uchun kerak bo‘lsa bering.', 'Ortiqcha tafsilotni olib tashlang.|Hujjat rasmi, bank ma’lumoti va tiklash kodini ommaviy ulashmang.'],
 'Bitta ijtimoiy tarmoqda telefon raqamingizni kim ko‘rishini tekshiring.',
 'Oddiy fon rasmi ilovasi kontaktlarga kirishni so‘radi. Nima qilasiz?', ['Barcha ruxsatni beraman','Zaruratini tekshirib, kerak bo‘lmasa rad etaman','Kontaktlarni unga yuboraman'],1,'Ruxsat ilovaning vazifasiga mos bo‘lishi kerak. Keraksiz ruxsatni bermaslik ma’lumot tarqalishini kamaytiradi.'],
 ['backup','Muhim fayllarning ikkinchi nusxasi','Zaxira nusxa va ekran qulfini tekshirasiz.',
 'Telefon yo‘qolsa, undagi yagona surat va hujjatlar ham yo‘qolishi mumkin. Oldindan zaxira yordam beradi.',
 ['Zaxirani yoqing va tekshiring.|Zaxira muvaffaqiyatli tugagan sana va nimalar saqlanganini ko‘ring.', 'Ekran qulfini ishlating.|Taxmin qilish qiyin kod tanlang. Kodni telefon bilan birga saqlamang.', 'Tiklash yo‘lini oldindan biling.|Qurilma topish xizmatini rasmiy sozlamalarda tekshiring. Hisobni tiklash imkoniyatini saqlang.'],
 'Zaxira nusxangizning oxirgi muvaffaqiyatli sanasini tekshiring.',
 'Zaxira borligini qanday bilasiz?', ['Tugma yoqilganiga qarab','Oxirgi muvaffaqiyatli nusxa va tiklash imkoniyatini tekshirib','Telefon yangi bo‘lsa yetarli'],1,'Sozlama yoqilgan bo‘lsa ham xotira yoki internet sabab zaxira tugamagan bo‘lishi mumkin. Natijani tekshiring.'],
 ['help','Xavf yuz bersa, birinchi qadam','Hodisani kattalashtirmasdan yordam topishni o‘rganasiz.',
 'Shubhali sahifada parol kiritganingizni payqadingiz. Endi vaziyatni tan olib, himoya choralarini boshlash muhim.',
 ['Rasmiy xizmatga o‘ting.|Shubhali sahifani yoping. Hisob sozlamalarini ishonchli ilova yoki rasmiy manzildan oching.', 'Kiritilgan ma’lumotga qarab harakat qiling.|Parol berilgan bo‘lsa uni almashtiring va seanslarni tekshiring. Pul xavfida bankning rasmiy yordamiga murojaat qiling.', 'Dalillarni ehtiyotkor saqlang.|Sana, xabar va tranzaksiya tafsilotlari kerak bo‘lishi mumkin. Ularni ommaga yoki noma’lum “yordamchi”ga yubormang.'],
 'SOS bo‘limidan o‘zingizga kerakli vaziyat yo‘riqnomasini ochib ko‘ring.',
 'Shubhali sahifada parol kiritdingiz. Endi-chi?', ['Hech kimga aytmay kutaman','Rasmiy xizmat orqali parol va seanslarni himoyalayman','Yana o‘sha havolaga kiraman'],1,'Rasmiy xizmat orqali himoyani tiklash kerak. Tasodifiy “qutqaruvchi”ga yangi kod yoki parol bermang.'],
];
$lessons=[]; $questions=[];
foreach ($lessonRows as $row) {
 [$slug,$title,$summary,$story,$rules,$action,$question,$options,$correct,$explanation]=$row;
 $lessons[]=['slug'=>$slug,'topic'=>$slug,'title'=>$title,'summary'=>$summary,'story'=>$story,'rules'=>array_map(fn($r)=>array_combine(['title','body'],explode('|',$r,2)),$rules),'action'=>$action,'minutes'=>2,'version'=>1,'questionId'=>'q-'.$slug];
 $questions['q-'.$slug]=['id'=>'q-'.$slug,'topic'=>$slug,'title'=>$question,'options'=>array_map(fn($label,$i)=>['id'=>'a'.$i,'label'=>$label],$options,array_keys($options)),'correct'=>'a'.$correct,'explanation'=>$explanation,'lesson'=>$slug];
}
$makeScene=fn(string $id,string $kind,string $sender,string $body,string $prompt,array $choices)=>compact('id','kind','sender','body','prompt','choices');
$choice=fn(string $id,string $label,bool $safe,string $next,string $why)=>compact('id','label','safe','next','why');
$scenarios=[];
$scenarioRows=[
 ['bank-sms','Soxta bank SMS','Bir xabar. Bitta havola. Qanday yo‘l tutasiz?','bank','card','peach','Namuna bank','Hisobingiz bloklandi. Ochish uchun bank-check.example.invalid manziliga kiring.', 'SMSdagi havolani ochaman','Bankning rasmiy ilovasini o‘zim ochaman','Noma’lum SMS havolasi hisobga kirish ma’lumotlarini o‘g‘irlash uchun ishlatilishi mumkin.', 'Ochilgan sahifa SMS-kodingizni so‘rayapti. Bu mashqda kod kiritilmaydi.','Kodni berishga roziman','Sahifani yopaman, bankka rasmiy kanaldan bog‘lanaman','OTP siz boshlagan amalni tasdiqlaydi. Uni suhbatdoshga berish xavfli.', 'Bankning rasmiy ilovasida cheklov ko‘rinmadi. Xabardagi talab tasdiqlanmadi.','Xabarga ishonib yana havolaga qaytaman','Shubhali xabarni bildiraman va o‘chiraman','Mustaqil tekshiruv shoshilinch talabning haqiqiyligini aniqlashga yordam beradi.'],
 ['telegram-vote','Do‘stdan kelgan havola','Ovoz berish ortida nima yashiringan?','telegram','message','sky','Tanish kontakt','Salom! Tanlovda menga ovoz ber: vote.example.invalid. Keyin Telegram kodi keladi.', 'Do‘stim yuborgan, demak ishonchli','Do‘stimdan boshqa kanalda aniqlashtiraman','Tanish akkaunt ham egallangan bo‘lishi mumkin. Ismning o‘zi dalil emas.', 'Bot ovozni tasdiqlash uchun Telegram kirish kodini so‘rayapti.','Kodni berishga roziman','Kodni bermayman va botni yopaman','Telegram kirish kodi ovoz berish uchun ishlatilmaydi. U hisobingizga kirishga xizmat qiladi.', 'Do‘stingiz telefon orqali bunday xabar yubormaganini aytdi.','Havolani boshqalarga tarqataman','Do‘stimga seanslarni tekshirishni tavsiya qilaman','Boshqa kanal orqali tekshirish soxta xabarni aniqladi. Kodni ulashmang.'],
 ['active-sessions','Akkauntingizga kim kirgan?','Telegramdagi faol seanslarni tekshiring.','telegram','phone','purple','Qurilmalar • mashq','Joriy qurilma: Android. Boshqa faol seans: noma’lum Windows qurilmasi, bugun 09:40.', 'Noma’lum seansni e’tiborsiz qoldiraman','Qurilma va vaqtni o‘z faoliyatim bilan solishtiraman','Qurilma nomi va vaqtni birga tekshiring. Taxminiy joylashuvning o‘zi xulosa uchun yetmaydi.', 'Bu qurilmada siz kirmagansiz. Seans begona ekani aniqlandi.','Seansni faol qoldiraman','Begona seansni yakunlayman','Begona seansni yopish uning hisobingizdan foydalanishini cheklaydi.', 'Begona seans yopildi. Keyingi himoya qadami qanday?','Boshqa himoya kerak emas','Ikki bosqichli himoya va tiklash emailini tekshiraman','Seansni yopishdan so‘ng himoya va tiklash sozlamalarini tekshirish foydali.'],
 ['delivery','Kutilmagan yetkazib berish','Kichik to‘lov talabi katta xavf bo‘lishi mumkin.','shopping','bag','mint','Namuna yetkazuvchi','Posilkangiz kutmoqda. Manzilni tasdiqlash uchun delivery.example.invalid orqali 3 000 so‘m to‘lang.', 'Summa kichik, havolaga kiraman','Buyurtmamni rasmiy do‘kon ilovasidan tekshiraman','Kichik summa ham karta ma’lumotlarini yig‘ish uchun bahona bo‘lishi mumkin.', 'Sahifa to‘liq karta ma’lumoti va SMS-kod so‘ramoqda.','Ma’lumot berishga roziman','Sahifani yopib, rasmiy yordamga yozaman','Kutilmagan to‘lov havolasiga karta va tasdiqlash kodi kiritilmaydi.', 'Rasmiy do‘kon qo‘shimcha to‘lov yo‘qligini tasdiqladi.','SMSdagi talabni baribir bajaraman','Xabarni shubhali deb belgilayman','Rasmiy xizmatdagi buyurtma holatiga tayaning.'],
 ['qr-menu','QR-kod qayerga olib boradi?','Oddiy menyu ortidagi so‘rovni tekshiring.','network','qr','blue','Kafe menyusi • mashq','Stoldagi QR ustiga boshqa stiker yopishtirilgan. Ochiladigan manzil: menu-pay.example.invalid.', 'Stikerga e’tibor bermay davom etaman','Ochishdan oldin manzilni xodimdan aniqlashtiraman','QR ustidagi yangi stiker va noma’lum manzil qo‘shimcha tekshiruvga sabab.', 'Menyuni ko‘rish uchun bank kartasi bilan tasdiqlash so‘raldi.','Karta ma’lumotini berishga roziman','Talabga rozi bo‘lmay, sahifani yopaman','Oddiy menyuni ko‘rish uchun bank kartasi kerak emas.', 'Xodim boshqa rasmiy menyuni ko‘rsatdi. Keyingi safar nima qilasiz?','Har qanday QRni ishonchli deb bilaman','Har safar manzil va talab maqsadini tekshiraman','QR faqat ma’lumot tashiydi; u manzil xavfsizligini kafolatlamaydi.'],
];
foreach($scenarioRows as $r) {
 [$slug,$title,$summary,$topic,$icon,$color,$sender,$body,$bad1,$good1,$why1,$body2,$bad2,$good2,$why2,$body3,$bad3,$good3,$why3]=$r;
 $scenarios[$slug]=['slug'=>$slug,'title'=>$title,'summary'=>$summary,'topic'=>$topic,'icon'=>$icon,'color'=>$color,'minutes'=>4,'version'=>1,'start'=>'s1','lesson'=>$topic,'scenes'=>[
 's1'=>$makeScene('s1','message',$sender,$body,'Birinchi qaroringiz qanday?',[$choice('c1',$bad1,false,'risk',$why1),$choice('c2',$good1,true,'safe',$why1)]),
 'risk'=>$makeScene('risk','browser','Xavfsiz mashq',$body2,'Endi qanday yo‘l tutasiz?',[$choice('c1',$bad2,false,'s3',$why2),$choice('c2',$good2,true,'s3',$why2)]),
 'safe'=>$makeScene('safe','verified','Mustaqil tekshiruv',$body3,'Tekshiruvdan keyingi qadamingiz?',[$choice('c1',$bad3,false,'s3',$why3),$choice('c2',$good3,true,'s3',$why3)]),
 's3'=>$makeScene('s3','reflection','Xulosa','Bir lahza to‘xtash va rasmiy kanaldan tekshirish qarorni sizning qo‘lingizda qoldiradi.','Bu vaziyatdan qaysi odatni olib chiqasiz?',[$choice('c1','Tanish nom va logotipga ishonish',false,'end','Ism va logotipni nusxalash mumkin. Mustaqil tekshiruv muhim.'),$choice('c2','Shoshilmasdan, mustaqil rasmiy kanaldan tekshirish',true,'end','To‘xtash, tekshirish va kodni sir saqlash — qo‘llash mumkin bo‘lgan odatlar.')])
 ]];
}
return ['version'=>1,'locale'=>'uz-Latn-UZ','topics'=>array_map(fn($t)=>array_combine(['slug','title','summary','icon','color'],$t),$topics),'lessons'=>$lessons,'questions'=>$questions,'scenarios'=>$scenarios,
 'glossary'=>[
 ['term'=>'Phishing','body'=>'Ishonchli xizmat ko‘rinishida ma’lumot yoki pul olishga urinish.','lesson'=>'phishing'],
 ['term'=>'OTP','body'=>'Bir martalik tasdiqlash kodi. Uni boshqa odamga bermang.','lesson'=>'bank'],
 ['term'=>'2FA','body'=>'Ikki bosqichli himoya: hisobga kirishda qo‘shimcha tasdiqlash.','lesson'=>'password'],
 ['term'=>'Domen','body'=>'Saytning internetdagi nomi. To‘liq manzilni tekshirish muhim.','lesson'=>'phishing'],
 ['term'=>'APK','body'=>'Android ilovasini o‘rnatish paketi. Noma’lum manbadan o‘rnatmang.','lesson'=>'apps'],
 ['term'=>'Seans','body'=>'Bir qurilmada hisobingizga ochilgan kirish holati.','lesson'=>'telegram'],
 ['term'=>'Zaxira nusxa','body'=>'Ma’lumot yo‘qolganda tiklashga xizmat qiladigan alohida nusxa.','lesson'=>'backup'],
 ['term'=>'Passkey','body'=>'Qurilmangiz yordamida hisobga xavfsiz kirish usuli.','lesson'=>'password']
 ]];
