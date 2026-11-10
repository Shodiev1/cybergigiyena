class UrlPolicy {
  UrlPolicy(String url, {this.debug = false}) : origin = Uri.tryParse(url);
  final Uri? origin;
  final bool debug;
  bool get valid => origin != null && origin!.host.isNotEmpty && origin!.userInfo.isEmpty &&
    (origin!.scheme == 'https' || (debug && origin!.scheme == 'http' && ['localhost', '127.0.0.1', '10.0.2.2'].contains(origin!.host)));
  bool isInternal(Uri? uri) => valid && uri != null && uri.scheme == origin!.scheme && uri.host == origin!.host && uri.port == origin!.port && uri.userInfo.isEmpty && !uri.path.toLowerCase().endsWith('.apk');
  bool isExternal(Uri? uri) => uri != null && uri.userInfo.isEmpty && !uri.path.toLowerCase().endsWith('.apk') &&
    ((uri.scheme == 'https' && uri.host.isNotEmpty) || ['tel', 'mailto'].contains(uri.scheme));
}
