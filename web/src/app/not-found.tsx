import Link from 'next/link';
export default function NotFound(){return <section className="empty-state"><span className="eyebrow">404</span><h1>Sahifa topilmadi</h1><p>Manzil o‘zgargan yoki bu sahifa mavjud emas.</p><Link className="button primary" href="/home">Asosiy sahifaga</Link></section>;}
