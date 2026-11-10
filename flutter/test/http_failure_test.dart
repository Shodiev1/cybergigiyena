import 'package:cybergigiyena/http_failure.dart';
import 'package:flutter_test/flutter_test.dart';

void main() {
  final document = Uri.parse('https://app.example.uz/home');
  test('HTTP errors for the main document show the native fallback', () {
    for (final status in [400, 404, 500, 503]) {
      expect(isMainDocumentHttpFailure(requestUri: document, documentUri: document, statusCode: status), isTrue);
    }
  });
  test('Failed subresources and unknown requests keep the page visible', () {
    for (final request in [null, Uri.parse('https://app.example.uz/logo.png'), Uri.parse('https://app.example.uz/api/v1/daily')]) {
      expect(isMainDocumentHttpFailure(requestUri: request, documentUri: document, statusCode: 503), isFalse);
    }
    expect(isMainDocumentHttpFailure(requestUri: document, documentUri: null, statusCode: 503), isFalse);
  });
  test('Success responses are not failures; document fragments are ignored', () {
    expect(isMainDocumentHttpFailure(requestUri: document, documentUri: document, statusCode: 200), isFalse);
    expect(isMainDocumentHttpFailure(requestUri: document, documentUri: document, statusCode: null), isFalse);
    expect(isMainDocumentHttpFailure(requestUri: document, documentUri: document.replace(fragment: 'main'), statusCode: 503), isTrue);
  });
}
