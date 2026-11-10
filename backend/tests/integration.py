"""Real HTTP/MySQL integration tests. Creates isolated test identities, cleans up via API."""
import concurrent.futures
import http.cookiejar
import json
import os
import urllib.error
import urllib.request
import urllib.parse
import uuid

BASE = os.environ.get('CG_TEST_URL', 'http://127.0.0.1:8000/api/v1')

class Client:
    def __init__(self):
        self.jar = http.cookiejar.CookieJar()
        # Local test traffic must stay on loopback, even on hosts with an HTTP proxy.
        handlers = [urllib.request.HTTPCookieProcessor(self.jar)]
        if urllib.parse.urlparse(BASE).hostname in ('127.0.0.1', 'localhost', '::1'):
            handlers.append(urllib.request.ProxyHandler({}))
        self.http = urllib.request.build_opener(*handlers)
        self.csrf = ''
        self.request('GET', '/session')

    def request(self, method, path, body=None, key=None, csrf=True, expected=200, origin=None):
        headers = {'Content-Type': 'application/json'}
        if method != 'GET':
            headers['X-CSRF-Token'] = self.csrf if csrf else 'invalid'
            headers['Idempotency-Key'] = key or str(uuid.uuid4())
        if origin:
            headers['Origin'] = origin
        req = urllib.request.Request(BASE + path, data=json.dumps(body).encode() if body is not None else None, headers=headers, method=method)
        try:
            response = self.http.open(req, timeout=20)
        except urllib.error.HTTPError as e:
            response = e
        raw = response.read()
        assert response.status == expected, (method, path, response.status, expected, raw.decode())
        result = json.loads(raw)
        if result.get('data', {}).get('csrf') if isinstance(result.get('data'), dict) else False:
            self.csrf = result['data']['csrf']
        return result.get('data', result.get('error'))

def test():
    a, b = Client(), Client()
    assert a.request('GET', '/health')['database'] == 'mysql'
    cat = a.request('GET', '/catalog')
    assert len(cat['lessons']) == 12 and len(cat['scenarios']) == 5
    serialized = json.dumps(cat)
    assert '"correct"' not in serialized and '"safe"' not in serialized and '"scenes"' not in serialized
    d1, d2 = a.request('GET', '/daily'), a.request('GET', '/daily?date=1900-01-01')
    assert d1 == d2 and d1['timezone'] == 'Asia/Tashkent'
    a.request('POST', '/scenario-attempts', {'scenario': 'bank-sms'}, csrf=False, expected=403)
    a.request('POST', '/scenario-attempts', {'scenario': 'bank-sms'}, origin='https://attacker.invalid', expected=403)
    key = str(uuid.uuid4())
    attempt = a.request('POST', '/scenario-attempts', {'scenario': 'bank-sms'}, key=key)
    duplicate = a.request('POST', '/scenario-attempts', {'scenario': 'bank-sms'}, key=key)
    assert duplicate == attempt
    a.request('POST', '/scenario-attempts', {'scenario': 'qr-menu'}, key=key, expected=409)
    aid = attempt['id']
    b.request('GET', '/scenario-attempts/' + aid, expected=404)
    a.request('POST', '/scenario-attempts/' + aid + '/finish', {}, expected=409)
    a.request('POST', '/scenario-attempts/' + aid + '/choices', {'scene': 's1', 'choice': 'invented', 'safe': True}, expected=422)
    first_key = str(uuid.uuid4())
    first_body = {'scene': 's1', 'choice': 'c1', 'safe': True}
    wrong = a.request('POST', '/scenario-attempts/' + aid + '/choices', first_body, key=first_key)
    assert wrong['scene']['id'] == 'risk' and wrong['feedback'][0]['safe'] is False
    assert wrong == a.request('POST', '/scenario-attempts/' + aid + '/choices', first_body, key=first_key)
    a.request('POST', '/scenario-attempts/' + aid + '/choices', first_body, expected=409)
    a.request('POST', '/scenario-attempts/' + aid + '/choices', {'scene': 'risk', 'choice': 'c2'})
    final = a.request('POST', '/scenario-attempts/' + aid + '/choices', {'scene': 's3', 'choice': 'c2'})
    assert final['ready'] and not final['finished']
    assert a.request('POST', '/scenario-attempts/' + aid + '/finish', {})['finished']
    assert a.request('POST', '/scenario-attempts/' + aid + '/finish', {})['finished']
    for scenario in cat['scenarios']:
        run = a.request('POST', '/scenario-attempts', {'scenario': scenario['slug']})
        for _ in range(3):
            run = a.request('POST', '/scenario-attempts/' + run['id'] + '/choices', {'scene': run['scene']['id'], 'choice': 'c2'})
        assert run['ready']
        a.request('POST', '/scenario-attempts/' + run['id'] + '/finish', {})
    q = a.request('GET', '/questions/q-bank')
    assert 'correct' not in q and 'explanation' not in q
    r = a.request('POST', '/question-attempts', {'questionId': 'q-bank', 'answer': 'a0', 'correct': True})
    assert r['correct'] is False and r['explanation']
    a.request('POST', '/lesson-progress', {'slug': 'phishing', 'step': 5}, expected=409)
    a.request('POST', '/lesson-progress', {'slug': 'bank', 'step': 5})
    promise = a.request('POST', '/promises', {})
    assert promise['id'] == a.request('POST', '/promises', {})['id']
    b.request('PATCH', '/promises/' + promise['id'], {'state': 'done'}, expected=404)
    a.request('PATCH', '/promises/' + promise['id'], {'state': 'done'})
    assert not next(s for s in a.request('GET', '/progress')['skills'] if s['topic'] == 'bank')['enough']
    a.request('GET', '/admin/overview', expected=401)
    email = 'integration-' + uuid.uuid4().hex[:12] + '@example.invalid'
    password = 'Test-' + uuid.uuid4().hex
    a.request('POST', '/auth/register', {'email': email, 'password': password, 'consent': True, 'name': 'Test'})
    a.request('GET', '/admin/overview', expected=403)
    assert len(a.request('GET', '/progress')['attempts']) == 0
    a.request('POST', '/progress/sync', {'accept': True})
    count = len(a.request('GET', '/progress')['attempts'])
    assert count == 6
    a.request('POST', '/progress/sync', {'accept': True})
    assert len(a.request('GET', '/progress')['attempts']) == count
    a.request('POST', '/auth/logout', {})
    assert len(a.request('GET', '/progress')['attempts']) == 0
    a.request('POST', '/auth/login', {'email': email, 'password': password})
    b.request('POST', '/auth/login', {'email': email, 'password': password})
    assert len(b.request('GET', '/progress')['attempts']) == count
    # Two distinct browser sessions submit the same operation concurrently.
    concurrent_key = str(uuid.uuid4())
    with concurrent.futures.ThreadPoolExecutor(2) as pool:
        results = list(pool.map(lambda c: c.request('POST', '/scenario-attempts', {'scenario': 'bank-sms'}, key=concurrent_key), [a, b]))
    assert results[0]['id'] == results[1]['id']
    export = a.request('GET', '/me/export')
    assert 'password_hash' not in json.dumps(export) and 'csrf' not in json.dumps(export) and export['user']['email'] == email
    a.request('DELETE', '/me', {'confirm': 'wrong', 'password': password}, expected=422)
    a.request('DELETE', '/me', {'confirm': 'O‘CHIRISH', 'password': password})
    b.request('GET', '/me', expected=401)
    assert len(a.request('GET', '/progress')['attempts']) == 0
    print('PASS: MySQL, 12 lessons, 5 branching scenarios, daily date pinning, CSRF, origin, answer-key privacy, server scoring, IDOR, transition validation, replay/concurrency, lesson guard, promises, evidence threshold, auth, explicit import, cross-session sync, export, account deletion.')

if __name__ == '__main__':
    test()
