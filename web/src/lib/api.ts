let csrf = '';
export class ApiError extends Error { constructor(message: string, public code: string, public status: number) { super(message); } }
export async function api<T>(path: string, method = 'GET', body?: unknown, key?: string): Promise<T> {
  if (method !== 'GET' && !csrf) await api('/session');
  const controller = new AbortController();
  const timer = setTimeout(() => controller.abort(), 12000);
  try {
    const res = await fetch(`/api/v1${path}`, {
      method, credentials: 'same-origin', cache: 'no-store', signal: controller.signal,
      headers: { 'Content-Type': 'application/json', ...(method !== 'GET' ? { 'X-CSRF-Token': csrf, 'Idempotency-Key': key || crypto.randomUUID() } : {}) },
      ...(body !== undefined ? { body: JSON.stringify(body) } : {}),
    });
    const result = await res.json();
    if (!result.ok) { if(result.error?.code === 'CSRF_INVALID') csrf = ''; throw new ApiError(result.error?.message || 'Ma’lumot yuklanmadi.', result.error?.code || 'ERROR', res.status); }
    if (result.data?.csrf) csrf = result.data.csrf;
    return result.data as T;
  } catch (e) {
    if (e instanceof ApiError) throw e;
    throw new ApiError('Ulanishda muammo. Internetni tekshirib, qayta urinib ko‘ring.', 'NETWORK', 0);
  } finally { clearTimeout(timer); }
}
export const errorText = (e: unknown) => e instanceof Error ? e.message : 'Amal bajarilmadi. Qayta urinib ko‘ring.';
export function readLocal<T>(key: string, fallback: T): T { try { const v = localStorage.getItem('cg:' + key); return v ? JSON.parse(v) : fallback; } catch { return fallback; } }
export function writeLocal(key: string, data: unknown) { try { localStorage.setItem('cg:' + key, JSON.stringify(data)); } catch { /* Storage can be disabled. Server state is still authoritative. */ } }
export async function publicCache<T>(key: string, value?: T): Promise<T | null> {
  return new Promise((resolve) => {
    if (!globalThis.indexedDB) return resolve(null);
    const open = indexedDB.open('cybergigiyena-public', 1);
    open.onupgradeneeded = () => open.result.createObjectStore('content');
    open.onerror = () => resolve(null);
    open.onsuccess = () => {
      const db = open.result; const tx = db.transaction('content', value === undefined ? 'readonly' : 'readwrite');
      const req = value === undefined ? tx.objectStore('content').get(key) : tx.objectStore('content').put(value, key);
      req.onsuccess = () => { if(value === undefined) resolve(req.result ?? null); };
      tx.oncomplete = () => { db.close(); if(value !== undefined) resolve(value); };
      tx.onerror = () => { db.close(); resolve(null); };
    };
  });
}
