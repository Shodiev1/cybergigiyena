'use client';
import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useApp } from './provider';
import { Empty, Go, Notice, PageTitle, Skeleton } from './ui';
import { HomePage, LearnPage, LessonPage, PracticeFlow, PracticePage, QuestionCard } from './learning';
import { AlertsPage, AuthPage, Diagnostic, FeedbackPage, ProfilePage, PromisePage, SharePage, SosPage, Welcome } from './account-support';
import { AdminPage } from './admin';
import { readLocal } from '@/lib/api';
export function RouterView({path,attemptId}:{path:string;attemptId?:string}){
 const {loading,error,catalog,daily,progress,refresh}=useApp();const router=useRouter();
 useEffect(()=>{if(path==='/')router.replace(readLocal('onboarded',false)?'/home':'/welcome');},[path,router]);
 const parts=path.split('/').filter(Boolean);
 if(path==='/')return <Skeleton/>;
 if(parts[0]==='sos')return <SosPage kind={parts[1]}/>;
 if(path==='/welcome')return <Welcome/>;
 if(parts[0]==='auth')return <AuthPage key={path} mode={parts[1]}/>;
 if(path==='/feedback')return <FeedbackPage/>;
 if(loading&&!catalog)return <Skeleton/>;
 if(!catalog)return <><Notice error>{error||'Ma’lumot yuklanmadi.'}</Notice><Empty title="Ulanishni qayta tekshiramiz." body="Darslar va mashqlarni yuklash uchun server bilan aloqa kerak. SOS yordamiga kirishingiz mumkin." href="/sos" label="SOS yordam"/><button className="button primary full" onClick={()=>void refresh()}>Qayta urinish</button></>;
 if(path==='/home')return <HomePage/>;
 if(parts[0]==='learn'){
  if(parts[1]==='lesson')return <LessonPage key={path} slug={parts[2]} mode={parts[3]}/>;
  return <LearnPage key={path} mode={parts[1]||'catalog'} slug={parts[1]==='topic'?parts[2]:undefined}/>;
 }
 if(parts[0]==='practice')return parts.length===1?<PracticePage/>:<PracticeFlow key={parts[1]+(attemptId||'')} slug={parts[1]} mode={parts[2]} attemptId={attemptId}/>;
 if(path==='/mission/today')return daily?<PracticeFlow key={daily.mission.slug} slug={daily.mission.slug} mode="intro"/>:null;
 if(parts[0]==='profile')return <ProfilePage key={path} mode={parts[1]}/>;
 if(parts[0]==='promise')return <PromisePage id={parts[1]}/>;
 if(parts[0]==='share')return <SharePage archive={parts[1]==='archive'}/>;
 if(parts[0]==='alerts'||parts[0]==='inbox')return <AlertsPage slug={parts[1]} inbox={parts[0]==='inbox'}/>;
 if(parts[0]==='admin')return <AdminPage key={path} mode={parts[1]}/>;
 if(path==='/diagnostic')return <Diagnostic/>;
 if(parts[0]==='question'&&daily){
  const result=progress.questions.find(q=>q.question_id===daily.question.id);
  return <div className="reading-width"><PageTitle title="Bugungi savol" eyebrow={daily.date} back="/home"/>{parts[2]==='result'?result?<section className="card completion-card"><h2>{Number(result.correct)?'To‘g‘ri yo‘lni tanladingiz':'Sababini tushunib olaylik'}</h2><p>{result.explanation}</p><Go href={`/learn/lesson/${daily.lesson.slug}`}>Mavzuni o‘rganish</Go></section>:<Go href="/question/today">Savolga javob berish</Go>:<QuestionCard question={daily.question} onComplete={()=>router.push('/question/today/result')}/>}</div>;
 }
 return <Empty title="Sahifa topilmadi" body="Asosiy sahifadan davom eting." href="/home" label="Asosiy sahifaga"/>;
}
