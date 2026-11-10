/// A failed image/API request must not replace a usable page with the native
/// error screen. The shared plugin API exposes a URI, not isForMainFrame.
bool isMainDocumentHttpFailure({
  required Uri? requestUri,
  required Uri? documentUri,
  required int? statusCode,
}) {
  if (requestUri == null || documentUri == null || statusCode == null || statusCode < 400) {
    return false;
  }
  return requestUri.removeFragment() == documentUri.removeFragment();
}
