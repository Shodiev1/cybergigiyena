import 'dart:async';
import 'dart:convert';
import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:share_plus/share_plus.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:webview_flutter/webview_flutter.dart';
import 'url_policy.dart';
import 'sos.dart';
import 'http_failure.dart';

void main() { WidgetsFlutterBinding.ensureInitialized(); runApp(const CyberGigiyenaApp()); }
class CyberGigiyenaApp extends StatelessWidget {
  const CyberGigiyenaApp({super.key});
  @override
  Widget build(BuildContext context) => MaterialApp(
    debugShowCheckedModeBanner: false, title: 'CyberGigiyena',
    theme: ThemeData(colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xff1677f2)), useMaterial3: true, scaffoldBackgroundColor: const Color(0xfff5f7fb)),
    darkTheme: ThemeData(colorScheme: ColorScheme.fromSeed(seedColor: const Color(0xff79b4ff), brightness: Brightness.dark), useMaterial3: true),
    home: const WebShell(),
  );
}
class WebShell extends StatefulWidget {
  const WebShell({super.key});
  @override
  State<WebShell> createState() => _WebShellState();
}
class _WebShellState extends State<WebShell> {
  static const configuredUrl = String.fromEnvironment('APP_URL');
  WebViewController? _controller;
  late final UrlPolicy _policy;
  Timer? _timeout;
  Uri? _documentUri;
  bool _loading = true, _failed = false, _externalDialog = false;
  int _lastBridge = 0;
  final Set<String> _requests = {};
  @override
  void initState() {
    super.initState();
    _policy = UrlPolicy(configuredUrl, debug: kDebugMode);
    if (!_policy.valid) { _failed = true; _loading = false; return; }
    _controller = WebViewController()
      ..setJavaScriptMode(JavaScriptMode.unrestricted)
      ..setBackgroundColor(const Color(0xfff5f7fb))
      ..addJavaScriptChannel('CyberGigiyena', onMessageReceived: _bridge)
      ..setNavigationDelegate(NavigationDelegate(
        onNavigationRequest: _navigate,
        onPageStarted: (url) { if (!mounted) return; _documentUri = Uri.tryParse(url); setState(() { _loading = true; _failed = false; }); _startTimeout(); },
        onPageFinished: (url) async {
          if (!_policy.isInternal(Uri.tryParse(url))) return;
          _timeout?.cancel();
          await _controller?.runJavaScript("document.documentElement.dataset.shell='flutter';");
          if (mounted) setState(() { _loading = false; });
        },
        onWebResourceError: (e) { if (e.isForMainFrame == true) _showFailure(); },
        onHttpError: (e) {
          if (isMainDocumentHttpFailure(requestUri: e.request?.uri, documentUri: _documentUri, statusCode: e.response?.statusCode)) _showFailure();
        },
      ));
    _load();
  }
  void _startTimeout() { _timeout?.cancel(); _timeout = Timer(const Duration(seconds: 15), _showFailure); }
  void _showFailure() { _timeout?.cancel(); if (mounted) setState(() { _failed = true; _loading = false; }); }
  Future<void> _load() async {
    if (!_policy.valid) return;
    setState(() { _failed = false; _loading = true; }); _startTimeout();
    try { await _controller!.loadRequest(_policy.origin!.resolve('/home')); } catch (_) { _showFailure(); }
  }
  Future<NavigationDecision> _navigate(NavigationRequest request) async {
    final uri = Uri.tryParse(request.url);
    if (_policy.isInternal(uri)) return NavigationDecision.navigate;
    if (request.isMainFrame && _policy.isExternal(uri) && !_externalDialog) {
      _externalDialog = true;
      if (mounted) {
        final accepted = await showDialog<bool>(context: context, builder: (context) => AlertDialog(
          title: const Text('Tashqi havolani ochish'),
          content: Text(uri!.scheme == 'https' ? '${uri.host} tizim brauzerida ochiladi.' : 'Bu havola telefoningizdagi mos ilovada ochiladi.'),
          actions: [TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Bekor qilish')), FilledButton(onPressed: () => Navigator.pop(context, true), child: const Text('Ochish'))],
        ));
        if (accepted == true) await launchUrl(uri!, mode: LaunchMode.externalApplication);
      }
      _externalDialog = false;
    }
    return NavigationDecision.prevent;
  }
  Future<void> _bridge(JavaScriptMessage message) async {
    if (message.message.length > 4096 || _failed) return;
    final now = DateTime.now().millisecondsSinceEpoch;
    if (now - _lastBridge < 800) return;
    _lastBridge = now;
    if (!_policy.isInternal(Uri.tryParse(await _controller?.currentUrl() ?? ''))) return;
    try {
      final data = jsonDecode(message.message);
      if (data is! Map<String, dynamic> || data['version'] != 1) return;
      final id = data['requestId'];
      if (id is! String || !RegExp(r'^[a-fA-F0-9-]{36}$').hasMatch(id) || _requests.contains(id)) return;
      if (_requests.length > 100) _requests.clear(); _requests.add(id);
      if (data['action'] == 'openOfflineSOS') { _openSos(); }
      else if (data['action'] == 'getAppInfo') {
        final result = jsonEncode({'requestId': id, 'version': 1, 'platform': defaultTargetPlatform.name, 'appVersion': '0.1.0'});
        await _controller?.runJavaScript('window.dispatchEvent(new CustomEvent("cybergigiyena:native", {detail: $result}));');
      } else if (data['action'] == 'shareTip') {
        final payload = data['payload'];
        if (payload is! Map || payload['text'] is! String) return;
        final text = payload['text'] as String;
        if (text.isEmpty || text.length > 1000 || !mounted) return;
        final accepted = await showDialog<bool>(context: context, builder: (context) => AlertDialog(
          title: const Text('Maslahatni ulashish'), content: Text(text),
          actions: [TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Bekor qilish')), FilledButton(onPressed: () => Navigator.pop(context, true), child: const Text('Ulashish'))],
        ));
        if (accepted == true && mounted) {
          final box = context.findRenderObject() as RenderBox?;
          await SharePlus.instance.share(ShareParams(text: text, sharePositionOrigin: box == null ? null : box.localToGlobal(Offset.zero) & box.size));
        }
      }
    } catch (_) { /* Unknown or malformed messages are denied. */ }
  }
  void _openSos() { if (mounted) Navigator.of(context).push(MaterialPageRoute<void>(builder: (_) => const NativeSos())); }
  Future<void> _back() async {
    if (!_failed && await _controller?.canGoBack() == true) { await _controller?.goBack(); return; }
    if (!mounted) return;
    final close = await showDialog<bool>(context: context, builder: (context) => AlertDialog(
      title: const Text('Ilovadan chiqasizmi?'), content: const Text('Saqlangan natijalaringiz qoladi.'),
      actions: [TextButton(onPressed: () => Navigator.pop(context, false), child: const Text('Qolish')), FilledButton(onPressed: () => Navigator.pop(context, true), child: const Text('Chiqish'))],
    ));
    if (close == true) await SystemNavigator.pop();
  }
  @override
  void dispose() { _timeout?.cancel(); super.dispose(); }
  @override
  Widget build(BuildContext context) => PopScope(
    canPop: false, onPopInvokedWithResult: (didPop, result) { if (!didPop) _back(); },
    child: Scaffold(body: SafeArea(child: Stack(children: [
      if (!_failed && _controller != null) WebViewWidget(controller: _controller!),
      if (_loading && !_failed) const LinearProgressIndicator(minHeight: 3),
      if (_failed) Center(child: SingleChildScrollView(padding: const EdgeInsets.all(28), child: ConstrainedBox(constraints: const BoxConstraints(maxWidth: 440), child: Column(mainAxisSize: MainAxisSize.min, children: [
        const Icon(Icons.shield_outlined, size: 72, color: Color(0xff1677f2)), const SizedBox(height: 24),
        Text(_policy.valid ? 'Saytga ulanib bo‘lmadi' : 'Sayt manzili sozlanmagan', style: Theme.of(context).textTheme.headlineSmall, textAlign: TextAlign.center), const SizedBox(height: 16),
        const Text('SOS yo‘riqnomalari internet bo‘lmasa ham shu yerda. Maxfiy kod yoki parolingizni hech kimga bermang.', textAlign: TextAlign.center), const SizedBox(height: 25),
        if (_policy.valid) FilledButton.icon(onPressed: _load, icon: const Icon(Icons.refresh), label: const Text('Qayta urinish')), const SizedBox(height: 12),
        OutlinedButton.icon(onPressed: _openSos, icon: const Icon(Icons.health_and_safety_outlined), label: const Text('Saqlangan SOS yo‘riqnomalari')),
      ])))),
    ]))),
  );
}
