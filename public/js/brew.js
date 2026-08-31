const pages=[...document.querySelectorAll('.page')], nav=[...document.querySelectorAll('[data-nav]')];
function show(id){pages.forEach(p=>p.classList.toggle('active',p.id===id));nav.forEach(a=>a.classList.toggle('active',a.dataset.nav===id));scrollTo({top:0,behavior:'smooth'});}
nav.forEach(a=>a.addEventListener('click',e=>{e.preventDefault();show(a.dataset.nav)}));
document.querySelectorAll('[data-open]').forEach(b=>b.addEventListener('click',()=>{const d=document.getElementById(b.dataset.open);if(d){const select=d.querySelector('[name=recipe_id]');if(select&&b.dataset.recipe)select.value=b.dataset.recipe;d.showModal()}}));
document.querySelectorAll('dialog[id^="batch-"]').forEach(d=>{const id=d.id.replace('batch-',''),header=d.querySelector('header');if(!header)return;const button=document.createElement('button');button.type='button';button.className='journal-button';button.textContent='☷ Bitácora completa';button.addEventListener('click',()=>{d.close();document.getElementById(`journal-${id}`)?.showModal()});header.after(button)});
document.querySelectorAll('[data-close]').forEach(b=>b.addEventListener('click',()=>b.closest('dialog').close()));
document.querySelectorAll('dialog').forEach(d=>d.addEventListener('click',e=>{if(e.target===d)d.close()}));
document.querySelectorAll('[data-delete-recipe]').forEach(form=>form.addEventListener('submit',e=>{if(!confirm(`¿Eliminar “${form.dataset.deleteRecipe}”? También se borrarán sus cocciones y mediciones.`))e.preventDefault()}));
setTimeout(()=>document.querySelectorAll('.toast').forEach(t=>t.remove()),3500);
if('serviceWorker'in navigator)navigator.serviceWorker.register('/sw.js');
let installPrompt;window.addEventListener('beforeinstallprompt',e=>{e.preventDefault();installPrompt=e;const b=document.querySelector('#install');b.hidden=false;b.onclick=()=>installPrompt.prompt()});

function n(id){return Number(document.getElementById(id)?.value||0)}
function calculate(){
 const og=n('calc-og'),fg=n('calc-fg'),abv=(og-fg)*131.25,att=og>1?((og-fg)/(og-1))*100:0;
 if(document.querySelector('#abv-result'))document.querySelector('#abv-result').textContent=`${abv.toFixed(1)}% ABV · ${att.toFixed(0)}% atenuación`;
 const sg=n('corr-sg'),t=n('corr-temp'),corrected=sg+(t-20)*0.0002;
 if(document.querySelector('#corr-result'))document.querySelector('#corr-result').textContent=`${corrected.toFixed(3)} SG corregida a 20 °C`;
 const liters=n('prime-liters'),co2=n('prime-co2'),temp=n('prime-temp'),factor=n('prime-type')||1,residual=1.7-(temp-4)*0.025,grams=Math.max(0,(co2-residual)*4.0*liters/factor);
 if(document.querySelector('#prime-result'))document.querySelector('#prime-result').textContent=`${grams.toFixed(0)} g de azúcar · ${(grams/liters).toFixed(1)} g/L`;
 const effPotential=n('eff-potential'),points=(n('eff-og')-1)*1000*n('eff-liters'),eff=effPotential?points/effPotential*100:0;
 if(document.querySelector('#eff-result'))document.querySelector('#eff-result').textContent=`${eff.toFixed(1)}% eficiencia de sala`;
}
document.querySelectorAll('.calc input,.calc select').forEach(el=>el.addEventListener('input',calculate));calculate();

function drawCharts(){document.querySelectorAll('.fermentation-chart').forEach(canvas=>{const data=JSON.parse(canvas.parentElement.querySelector('.chart-data').textContent||'[]');if(data.length<2)return;const dpr=devicePixelRatio||1,w=canvas.clientWidth,h=210;canvas.width=w*dpr;canvas.height=h*dpr;const c=canvas.getContext('2d');c.scale(dpr,dpr);c.clearRect(0,0,w,h);const pad={l:42,r:35,t:15,b:28},cw=w-pad.l-pad.r,ch=h-pad.t-pad.b,grav=data.map(x=>x.gravity).filter(Boolean),temps=data.map(x=>x.temperature).filter(x=>x!==null),gmin=Math.min(...grav)-.002,gmax=Math.max(...grav)+.002,tmin=Math.min(...temps,15)-2,tmax=Math.max(...temps,20)+2;c.strokeStyle='#d9d4c8';c.fillStyle='#706f68';c.font='10px DM Sans';for(let i=0;i<4;i++){const y=pad.t+ch*i/3;c.beginPath();c.moveTo(pad.l,y);c.lineTo(w-pad.r,y);c.stroke();c.fillText((gmax-(gmax-gmin)*i/3).toFixed(3),2,y+3)}const line=(key,min,max,color)=>{c.strokeStyle=color;c.lineWidth=2;c.beginPath();let started=false;data.forEach((x,i)=>{if(x[key]===null)return;const px=pad.l+cw*i/(data.length-1),py=pad.t+ch*(max-x[key])/(max-min);started?c.lineTo(px,py):c.moveTo(px,py);started=true});c.stroke()};line('gravity',gmin,gmax,'#c9872b');line('temperature',tmin,tmax,'#526441');data.forEach((x,i)=>c.fillText(x.date,pad.l+cw*i/(data.length-1)-10,h-8));c.fillStyle='#c9872b';c.fillText('● Densidad',pad.l,12);c.fillStyle='#526441';c.fillText('● Temperatura',pad.l+80,12)})}
drawCharts();window.addEventListener('resize',drawCharts);
