import 'package:flutter_test/flutter_test.dart';
import 'package:cybergigiyena/url_policy.dart';
import 'package:cybergigiyena/sos.dart';
import 'package:flutter/material.dart';
void main() {
  test('Exact HTTPS origin, scheme and port are enforced', () {
    final policy = UrlPolicy('https://app.example.uz');
    expect(policy.isInternal(Uri.parse('https://app.example.uz/home')), isTrue);
    for (final url in ['https://app.example.uz.evil.invalid', 'http://app.example.uz', 'https://app.example.uz:444/home', 'https://evil@app.example.uz/home', 'file:///etc/passwd', 'javascript:alert(1)', 'https://app.example.uz/file.apk']) {
      expect(policy.isInternal(Uri.parse(url)), isFalse, reason: url);
    }
    expect(UrlPolicy('').valid, isFalse);
    expect(UrlPolicy('http://10.0.2.2:3000', debug: true).valid, isTrue);
    expect(UrlPolicy('http://10.0.2.2:3000').valid, isFalse);
    expect(policy.isExternal(Uri.parse('https://trusted.example/guide')), isTrue);
    expect(policy.isExternal(Uri.parse('https://trusted.example/app.apk')), isFalse);
  });
  testWidgets('SOS renders without a WebView or network', (tester) async {
    await tester.pumpWidget(const MaterialApp(home: NativeSos()));
    expect(find.text('SOS · Saqlangan yordam'), findsOneWidget);
    expect(offlineGuides.length, 5);
    await tester.tap(find.text('Akkauntimga begona kirdi')); await tester.pumpAndSettle();
    expect(find.text('Rasmiy ilova yoki saytni o‘zingiz oching.'), findsOneWidget);
  });
}
