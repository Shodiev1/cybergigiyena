import { test, expect, type Page } from '@playwright/test';
async function post(page:Page,path:string,body:unknown){
 const session=await (await page.request.get('/api/v1/session')).json();
 const response=await page.request.post('/api/v1'+path,{data:body,headers:{'X-CSRF-Token':session.data.csrf,'Idempotency-Key':crypto.randomUUID()}});
 expect(response.ok()).toBeTruthy();return (await response.json()).data;
}
async function finish(page:Page,safe:boolean){
 let a=await post(page,'/scenario-attempts',{scenario:'bank-sms'});
 for(let i=0;i<3;i++)a=await post(page,`/scenario-attempts/${a.id}/choices`,{scene:a.scene.id,choice:i===0&&!safe?'c1':'c2'});
 return await post(page,`/scenario-attempts/${a.id}/finish`,{});
}
test('history: exact older result survives reload without replacing unfinished attempt',async({page})=>{
 const old=await finish(page,false);await finish(page,true);
 const ongoing=await post(page,'/scenario-attempts',{scenario:'bank-sms'});
 await page.goto('/profile/history');
 await page.evaluate(id=>localStorage.setItem('cg:attempt:bank-sms',JSON.stringify(id)),ongoing.id);
 await page.getByLabel('Faoliyat turi').selectOption('scenario');
 await page.getByLabel('Bajarilish holati').selectOption('done');
 await expect(page.locator('a.history-item')).toHaveCount(2);
 await page.locator(`a[href$="result?attempt=${old.id}"]`).click();
 await expect(page.locator('.decision-review').first()).toContainText(old.feedback[0].why);
 await page.reload();
 await expect(page.locator('.decision-review').first()).toContainText(old.feedback[0].why);
 expect(await page.evaluate(()=>JSON.parse(localStorage.getItem('cg:attempt:bank-sms')!))).toBe(ongoing.id);
 await page.goto('/profile/history');
 await page.getByLabel('Bajarilish holati').selectOption('ongoing');
 await expect(page.locator('a.history-item')).toHaveCount(1);
 await page.locator('a.history-item').click();
 await expect(page.locator('.choices-panel')).toBeVisible();
 await page.goto('/profile/history');
 await page.getByLabel('Sana (Toshkent vaqti)').fill('2000-01-01');
 await expect(page.getByText('Bu filtrlarga mos yozuv yo‘q')).toBeVisible();
 await page.getByRole('button',{name:'Filtrlarni tozalash'}).click();
 await expect(page.locator('a.history-item')).toHaveCount(3);
 await page.setViewportSize({width:320,height:900});
 await page.evaluate(()=>{document.documentElement.style.fontSize='200%';document.documentElement.dataset.largeText='true';});
 expect(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth+1)).toBe(true);
});
test('history: unavailable and mismatched attempts show errors; transient failure can retry',async({page,browser})=>{
 const owned=await finish(page,true);
 const other=await browser.newContext();const outsider=await other.newPage();
 await outsider.goto(`/practice/bank-sms/result?attempt=${owned.id}`);
 await expect(outsider.getByRole('heading',{name:'Mashqni yuklab bo‘lmadi'})).toBeVisible();
 await expect(outsider.locator('.decision-review')).toHaveCount(0);await other.close();
 await page.goto(`/practice/qr-menu/result?attempt=${owned.id}`);
 await expect(page.getByText('Bu natija boshqa mashqqa tegishli.')).toBeVisible();
 let failed=false;
 await page.route(`**/api/v1/scenario-attempts/${owned.id}`,r=>{if(!failed){failed=true;return r.abort();}return r.continue();});
 await page.goto(`/practice/bank-sms/result?attempt=${owned.id}`);
 await expect(page.getByRole('heading',{name:'Mashqni yuklab bo‘lmadi'})).toBeVisible();
 await page.getByRole('button',{name:'Qayta urinish',exact:true}).click();
 await expect(page.locator('.decision-review')).toHaveCount(3);
});
