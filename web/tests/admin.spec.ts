import { test, expect } from '@playwright/test';

test('admin: filter, unsaved preview (320/393/768 px & dark), history and version conflict', async ({ page }) => {
 const payload={slug:'test-lesson',title:'Dars sinovi',summary:'Qisqa izoh',story:'Vaziyat',rules:[{title:'Qoida',body:'Izoh'}],action:'Xavfsiz qadam'};
 const row={id:'test-lesson',kind:'lesson',state:'draft',version:2,payload,author:'tester',reviewer:null};
 await page.route('**/api/v1/session',r=>r.fulfill({json:{ok:true,data:{user:{id:'tester',name:'Muharrir',role:'author'},csrf:'test'}}}));
 await page.route('**/api/v1/admin/content',r=>r.fulfill({json:{ok:true,data:[row]}}));
 await page.route('**/api/v1/admin/content/test-lesson/revisions',r=>r.fulfill({json:{ok:true,data:[{id:1,version:1,created_at:'2026-11-06 10:00:00',payload:{...payload,title:'Eski sarlavha'}}]}}));
 await page.route('**/api/v1/admin/content/test-lesson/restore',async r=>{
  expect(r.request().postDataJSON()).toEqual({revisionId:1,version:2});
  await r.fulfill({status:409,json:{ok:false,error:{code:'VERSION_CONFLICT',message:'Boshqa muharrir tahrir qildi.'}}});
 });
 await page.goto('/admin/content');
 await expect(page.getByRole('heading',{name:'Dars sinovi'})).toBeVisible();
 await page.getByLabel('Kontent qidirish').fill('topilmaydi');
 await expect(page.getByRole('heading',{name:'Dars sinovi'})).toHaveCount(0);
 await page.getByLabel('Kontent qidirish').fill('');
 await page.getByRole('button',{name:'Tahrirlash',exact:true}).click();
 await page.getByLabel('Sarlavha',{exact:true}).fill('Yangi sarlavha');
 await page.getByRole('button',{name:'Oldindan ko‘rish',exact:true}).click();
 await expect(page.getByRole('article')).toContainText('Yangi sarlavha');
 await expect(page.getByRole('article')).toHaveAttribute('style',/width:\s*393px/);
 await page.getByLabel('Ko‘rinish kengligi').selectOption('768');
 await expect(page.getByRole('article')).toHaveAttribute('style',/width:\s*768px/);
 await page.getByLabel('Ko‘rinish kengligi').selectOption('320');
 await expect(page.getByRole('article')).toHaveAttribute('style',/width:\s*320px/);
 await page.getByLabel('Tun rejimi').check();
 await expect(page.getByRole('article')).toHaveCSS('background-color','rgb(13, 20, 34)');
 await page.getByRole('button',{name:'Tahrir tarixi',exact:true}).click();
 await page.getByRole('button',{name:'Versiya 1 ·'}).click();
 await expect(page.getByRole('article').last()).toContainText('Eski sarlavha');
 await page.getByRole('button',{name:'Versiya 1 ni qoralamaga tiklash'}).click();
 await expect(page.getByText('Boshqa muharrir tahrir qildi.',{exact:true})).toBeVisible();
 await expect(page.getByRole('article').first()).toContainText('Yangi sarlavha');
});

test('admin: scenario branch preview (all paths & broken scene) and calendar editorial workflow', async ({ page }) => {
 const scenarioPayload={
  slug:'bank-sms',
  title:'Bank SMS sinovi',
  summary:'Soxta SMS mashqi',
  start:'s1',
  scenes:{
   s1:{id:'s1',kind:'sms',sender:'BankAlert',body:'Kartangiz bloklandi.',prompt:'Nima qilasiz?',choices:[
    {id:'c1',label:'Havolani bosish',safe:false,next:'risk',why:'Havola soxta.'},
    {id:'c2',label:'Bank ilovasidan tekshirish',safe:true,next:'safe',why:'Rasmiy kanal xavfsiz.'},
    {id:'c3',label:'Uzilgan tarmoq',safe:false,next:'missing',why:'Tarmoq xatosi.'}
   ]},
   risk:{id:'risk',kind:'web',sender:'Soxta sahifa',body:'Kodni kiriting',prompt:'Keyingi qadam?',choices:[
    {id:'r1',label:'Sahifani yopish',safe:true,next:'s3',why:'To‘g‘ri to‘xtadingiz.'}
   ]},
   safe:{id:'safe',kind:'app',sender:'Rasmiy ilova',body:'Karta faol',prompt:'Yakuniy qadam?',choices:[
    {id:'ok1',label:'SMSni o‘chirish',safe:true,next:'s3',why:'Yaxshi odat.'}
   ]},
   s3:{id:'s3',kind:'summary',sender:'Xulosa',body:'Yakuniy sahna',prompt:'Tugatish?',choices:[
    {id:'end1',label:'Yakunlash',safe:true,next:'end',why:'Mashq tugadi.'}
   ]}
  }
 };
 const row={id:'bank-sms',kind:'scenario',state:'draft',version:1,payload:scenarioPayload,author:'tester',reviewer:null};
 let calRows=[
  {day:'2026-11-25',scenario:'bank-sms',lesson:'bank',theme:'Soxta bank SMS va havola',tip:'SMS havolasiga kirmang.',promise:'Kodni aytmayman.',state:'draft',version:2,author:'tester',reviewer:null},
  {day:'2026-11-26',scenario:'bank-sms',lesson:'bank',theme:'Ertangi maxsus mavzu',tip:'Ertangi maslahat.',promise:'Ertangi va’da.',state:'published',version:4,author:'tester',reviewer:'rev-1'}
 ];
 await page.route('**/api/v1/session',r=>r.fulfill({json:{ok:true,data:{user:{id:'tester',name:'Muharrir',role:'author'},csrf:'test'}}}));
 await page.route('**/api/v1/admin/content',r=>r.fulfill({json:{ok:true,data:[row]}}));
 await page.route('**/api/v1/admin/calendar',async r=>{
  if(r.request().method()==='GET'){
   await r.fulfill({json:{ok:true,data:calRows}});
   return;
  }
  expect(r.request().postDataJSON()).toEqual({day:'2026-11-25',scenario:'bank-sms',theme:'Yangilangan kun mavzusi',tip:'SMS havolasiga kirmang.',promise:'Kodni aytmayman.',version:2});
  calRows=calRows.map(c=>c.day==='2026-11-25'?{...c,theme:'Yangilangan kun mavzusi',version:3,state:'draft'}:c);
  await r.fulfill({json:{ok:true,data:{saved:true,state:'draft'}}});
 });
 await page.route('**/api/v1/admin/calendar/2026-11-25/state',async r=>{
  expect(r.request().postDataJSON()).toEqual({state:'review',version:3});
  calRows=calRows.map(c=>c.day==='2026-11-25'?{...c,version:4,state:'review'}:c);
  await r.fulfill({json:{ok:true,data:{updated:true,state:'review'}}});
 });

 await page.goto('/admin/scenarios');
 await page.getByRole('button',{name:'Tahrirlash',exact:true}).click();
 await page.getByRole('button',{name:'Oldindan ko‘rish',exact:true}).click();
 const article=page.getByRole('article');
 await expect(article).toContainText('Kartangiz bloklandi.');

 // Branch 1: s1 -> risk -> s3 -> end
 await article.getByRole('button',{name:'Havolani bosish'}).click();
 await expect(article).toContainText('Xavfli qaror. Havola soxta.');
 await expect(article).toContainText('Kodni kiriting');
 await article.getByRole('button',{name:'Sahifani yopish'}).click();
 await expect(article).toContainText('Yakuniy sahna');
 await article.getByRole('button',{name:'Yakunlash'}).click();
 await expect(article).toContainText('Mashq yakunlandi.');

 // Restart & Branch 2: s1 -> safe -> s3 -> end
 await article.getByRole('button',{name:'Mashqni qayta boshlash'}).click();
 await expect(article).toContainText('Kartangiz bloklandi.');
 await article.getByRole('button',{name:'Bank ilovasidan tekshirish'}).click();
 await expect(article).toContainText('Xavfsiz qaror. Rasmiy kanal xavfsiz.');
 await expect(article).toContainText('Karta faol');
 await article.getByRole('button',{name:'SMSni o‘chirish'}).click();
 await article.getByRole('button',{name:'Yakunlash'}).click();
 await expect(article).toContainText('Mashq yakunlandi.');

 // Restart & Broken branch: s1 -> missing
 await article.getByRole('button',{name:'Mashqni qayta boshlash'}).click();
 await article.getByRole('button',{name:'Uzilgan tarmoq'}).click();
 await expect(article).toContainText('Sahna topilmadi. Tarmoqlanishni tekshiring.');

 // Calendar workflow UI + Preview tomorrow + custom fields
 await page.getByRole('link',{name:'Taqvim'}).click();
 await expect(page.getByText(/1 ta qoralama, 0 ta tekshiruvda, 0 ta tasdiqlangan va 1 ta nashr qilingan kun bor/)).toBeVisible();
 await page.getByRole('button',{name:'Ertangi kunni ko‘rish'}).click();
 await expect(page.getByLabel('Kunlik paket namoyishi')).toContainText('Ertangi maxsus mavzu');
 await page.getByLabel('Holat').selectOption('draft');
 await expect(page.getByRole('heading',{name:/2026-11-26/})).toHaveCount(0);
 await page.getByRole('button',{name:'Tanlash'}).first().click();
 await page.getByLabel('Kun mavzusi (ixtiyoriy)').fill('Yangilangan kun mavzusi');
 await page.getByRole('button',{name:'Kunlik qoralamani saqlash'}).click();
 await expect(page.getByText(/Qoralama · versiya 3/)).toBeVisible();
 await page.getByRole('button',{name:'Tekshiruvga'}).click();
 await page.getByLabel('Holat').selectOption('review');
 await expect(page.getByText(/Tekshiruvda · versiya 4/)).toBeVisible();
});
