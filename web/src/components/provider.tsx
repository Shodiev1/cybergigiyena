'use client';
import { createContext, useCallback, useContext, useEffect, useState, type ReactNode } from 'react';
import { api, errorText, publicCache, readLocal, writeLocal } from '@/lib/api';
import type { Catalog, Daily, Preferences, Progress, User } from '@/lib/types';
const emptyProgress:Progress={lessons:[],attempts:[],questions:[],promises:[],skills:[]};
const defaultPrefs:Preferences={theme:'system',fontScale:1,reducedMotion:false,checklist:[]};
type Store={catalog:Catalog|null;daily:Daily|null;progress:Progress;user:User|null;loading:boolean;error:string;offline:boolean;cached:boolean;prefs:Preferences;importAvailable:boolean;refresh:()=>Promise<void>;refreshProgress:()=>Promise<void>;setPrefs:(p:Preferences)=>void;toast:(s:string)=>void;reloadSession:()=>Promise<void>};
const Context=createContext<Store>(null!);
export const useApp=()=>useContext(Context);
export function AppProvider({children}:{children:ReactNode}) {
 const [catalog,setCatalog]=useState<Catalog|null>(null),[daily,setDaily]=useState<Daily|null>(null),[progress,setProgress]=useState(emptyProgress),[user,setUser]=useState<User|null>(null),[loading,setLoading]=useState(true),[error,setError]=useState(''),[offline,setOffline]=useState(false),[cached,setCached]=useState(false),[prefs,setPreferences]=useState(defaultPrefs),[message,setMessage]=useState(''),[importAvailable,setImportAvailable]=useState(false);
 const toast=useCallback((s:string)=>setMessage(s),[]);
 useEffect(()=>{if('serviceWorker' in navigator)void navigator.serviceWorker.register('/sw.js').catch(()=>{});},[]);
 useEffect(()=>{if(message){const t=setTimeout(()=>setMessage(''),4500);return()=>clearTimeout(t);}},[message]);
 const reloadSession=useCallback(async()=>{const s=await api<{user:User|null;importAvailable:boolean}>('/session');setUser(s.user);setImportAvailable(s.importAvailable);},[]);
 const refreshProgress=useCallback(async()=>{const p=await api<Progress>('/progress');setProgress(p);},[]);
 const refresh=useCallback(async()=>{
  setError('');
  try {const [c,d]=await Promise.all([api<Catalog>('/catalog'),api<Daily>('/daily')]);setCatalog(c);setDaily(d);setCached(false);await Promise.all([publicCache('catalog',c),publicCache('daily',d)]);await reloadSession();await refreshProgress();}
  catch(e){setError(errorText(e));const [c,d]=await Promise.all([publicCache<Catalog>('catalog'),publicCache<Daily>('daily')]);if(c){setCatalog(c);setDaily(d);setCached(true);}}
  finally{setLoading(false);}
 },[reloadSession,refreshProgress]);
 useEffect(()=>{setPreferences(readLocal('preferences',defaultPrefs));setOffline(!navigator.onLine);void refresh();const online=()=>{setOffline(false);void refresh();};const off=()=>setOffline(true);const visible=()=>{if(document.visibilityState==='visible')void refresh();};window.addEventListener('online',online);window.addEventListener('offline',off);document.addEventListener('visibilitychange',visible);return()=>{window.removeEventListener('online',online);window.removeEventListener('offline',off);document.removeEventListener('visibilitychange',visible);};},[refresh]);
 useEffect(()=>{const root=document.documentElement;root.dataset.theme=prefs.theme;root.dataset.largeText=String(prefs.fontScale>=1.5);root.style.fontSize=`${prefs.fontScale*100}%`;root.dataset.motion=prefs.reducedMotion?'reduced':'normal';},[prefs]);
 const setPrefs=(p:Preferences)=>{setPreferences(p);writeLocal('preferences',p);void api('/me/preferences','PATCH',p).catch(()=>toast('Sozlama shu qurilmada saqlandi.'));};
 return <Context.Provider value={{catalog,daily,progress,user,loading,error,offline,cached,prefs,importAvailable,refresh,refreshProgress,setPrefs,toast,reloadSession}}>{children}{message&&<div className="toast" role="status">{message}</div>}</Context.Provider>;
}
