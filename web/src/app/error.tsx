'use client';
import Link from 'next/link';
export default function ErrorPage({reset}:{reset:()=>void}){return <section className="empty-state"><h1>Sahifani yuklab bo‘lmadi</h1><p>Qayta urinib ko‘ring. SOS yo‘riqnomalari ham mavjud.</p><button className="button primary" onClick={reset}>Qayta urinish</button><Link className="button secondary" href="/sos">SOS yordam</Link></section>;}
