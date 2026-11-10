import type { Metadata, Viewport } from 'next';
import { AppProvider } from '@/components/provider';
import { Shell } from '@/components/shell';
import './globals.css';
export const metadata:Metadata={title:{default:'CyberGigiyena — xavfsiz raqamli odatlar',template:'%s | CyberGigiyena'},description:'Har kuni bir necha daqiqada raqamli xavflarni tanish va xavfsiz qaror qilishni o‘rganing.',manifest:'/manifest.webmanifest',appleWebApp:{capable:true,title:'CyberGigiyena',statusBarStyle:'default'},icons:{icon:'/icon-192.png',apple:'/icon-192.png'},robots:{index:false,follow:false}};
export const viewport:Viewport={width:'device-width',initialScale:1,viewportFit:'cover',themeColor:'#F5F7FB'};
export default function RootLayout({children}:{children:React.ReactNode}){return <html lang="uz"><body><AppProvider><Shell>{children}</Shell></AppProvider></body></html>;}
