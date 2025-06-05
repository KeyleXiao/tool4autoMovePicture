function setTheme(t){document.documentElement.setAttribute('data-theme',t);localStorage.setItem('theme',t);} 
function toggleTheme(){const cur=document.documentElement.getAttribute('data-theme')==='dark'?'light':'dark';setTheme(cur);} 
function initTheme(){setTheme(localStorage.getItem('theme')||'light');}
function showLoading(){const l=document.getElementById('loading');if(l)l.style.display='flex';}
function hideLoading(){const l=document.getElementById('loading');if(l)l.style.display='none';}
function showToast(msg,type){const c=document.querySelector('.toast-container');if(!c)return;const t=document.createElement('div');t.className='toast '+type;t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),3000);} 
function updateProgress(p){const bar=document.querySelector('.progress-bar div');if(bar)bar.style.width=p+'%';}
function toggleNav(){const n=document.querySelector('.nav-links');if(n)n.classList.toggle('show');}
function validateForm(){const req=document.querySelectorAll('input[required]');for(const r of req){if(!r.value.trim()){showToast('Please fill '+(r.name||r.placeholder),'error');r.focus();return false;}}return true;}
document.addEventListener('DOMContentLoaded',initTheme);
