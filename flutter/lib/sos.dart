import 'package:flutter/material.dart';
const offlineGuides = <String, List<String>>{
  'Akkauntimga begona kirdi': ['Rasmiy ilova yoki saytni o‘zingiz oching.', 'Qurilmalar yoki seanslar bo‘limida begona seanslarni yoping.', 'Parol, ikki bosqichli himoya va tiklash ma’lumotlarini tekshiring.', 'Kira olmasangiz, xizmatning rasmiy tiklash jarayonidan foydalaning.'],
  'Karta yoki pulim xavf ostida': ['Shubhali suhbatni yakunlang. Yangi kod bermang.', 'Bankning rasmiy ilovasi yoki kartadagi aloqa kanalidan o‘zingiz bog‘laning.', 'Bank yo‘riqnomasiga ko‘ra kartani bloklash va shubhali operatsiyani bildirish choralarini ko‘ring.', 'Xabar va tranzaksiya dalillarini saqlang. Noma’lum “yordamchi”ga pul yubormang.'],
  'Shubhali havolani ochdim': ['Sahifani yoping va yangi ruxsat bermang.', 'Parol kiritgan bo‘lsangiz, rasmiy xizmatdan uni almashtiring va seanslarni tekshiring.', 'Karta yoki to‘lov kodi berilgan bo‘lsa, bankka rasmiy kanaldan murojaat qiling.', 'Yuklangan noma’lum faylni ochmang.'],
  'Noma’lum APK o‘rnatdim': ['Yangi kod yoki ruxsat bermang.', 'Tizim sozlamalarida ilova, qurilma administrator va Accessibility ruxsatlarini tekshiring.', 'Shubhali ilovani o‘chiring. O‘chmasa, ishlab chiqaruvchi yoki ishonchli mutaxassisdan yordam oling.', 'Muhim hisoblarni boshqa ishonchli qurilmadan tekshiring.'],
  'Telefonim yo‘qoldi': ['Boshqa ishonchli qurilmadan rasmiy qurilma topish xizmatini oching.', 'Masofadan qulflash yoki yo‘qolgan rejimdan foydalaning.', 'Operator va bankka rasmiy kanal orqali murojaat qiling.', '“Telefon topildi” degan noma’lum havolaga kod kiritmang.'],
};
class NativeSos extends StatelessWidget {
  const NativeSos({super.key});
  @override
  Widget build(BuildContext context) => Scaffold(
    appBar: AppBar(title: const Text('SOS · Saqlangan yordam')),
    body: ListView(padding: const EdgeInsets.all(20), children: [
      Text('Avval xotirjamlik. Keyin harakat.', style: Theme.of(context).textTheme.headlineSmall), const SizedBox(height: 15),
      const Text('Bu asosiy yo‘riqnomalar internet talab qilmaydi. Bank yoki xizmatning rasmiy yordamiga o‘zingiz bog‘laning.'), const SizedBox(height: 20),
      ...offlineGuides.entries.map((entry) => Card(child: ExpansionTile(title: Text(entry.key), leading: const Icon(Icons.health_and_safety_outlined),
        children: entry.value.asMap().entries.map((step) => ListTile(leading: CircleAvatar(radius: 15, child: Text('${step.key + 1}')), title: Text(step.value), contentPadding: const EdgeInsets.fromLTRB(20, 8, 20, 12))).toList(),
      ))), const SizedBox(height: 20),
      const Text('Hech kimga parol, PIN yoki SMS-kod bermang. Yo‘riqnoma rasmiy yordamning o‘rnini bosmaydi.'),
    ]),
  );
}
