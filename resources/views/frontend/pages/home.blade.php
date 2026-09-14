@extends('layouts.frontend.master')

@section('content')

{{-- =============================================
     GOOGLE FONTS
     ============================================= --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">

<style>
  :root {
    --navy:   #050d1a;
    --navy2:  #0b1930;
    --teal:   #00c9b1;
    --teal2:  #00e5cc;
    --blue:   #1a6bff;
    --amber:  #f5a623;
    --rose:   #f54263;
    --glass:  rgba(255,255,255,0.04);
    --border: rgba(255,255,255,0.08);
    --text:   #e8edf5;
    --muted:  #7a8ba8;
  }
  * { box-sizing: border-box; }
  body {
    background: var(--navy);
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    margin: 0;
  }
  h1,h2,h3,h4,h5 { font-family: 'Syne', sans-serif; }

  /* ---- shared ---- */
  .section-tag {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(0,201,177,.12); color: var(--teal);
    border: 1px solid rgba(0,201,177,.25);
    padding: 6px 16px; border-radius: 999px;
    font-size: 12px; font-weight: 600; letter-spacing: .06em; text-transform: uppercase;
    margin-bottom: 16px;
  }
  .section-tag svg { width:14px; height:14px; }
  .hms-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 28px; border-radius: 12px;
    font-family: 'Syne', sans-serif; font-weight: 700; font-size: 14px;
    cursor: pointer; border: none; text-decoration: none; transition: all .2s;
  }
  .hms-btn-primary {
    background: linear-gradient(135deg, var(--teal), var(--blue));
    color: #fff; box-shadow: 0 8px 30px rgba(0,201,177,.35);
  }
  .hms-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(0,201,177,.45); }
  .hms-btn-outline {
    background: transparent; color: var(--text);
    border: 1px solid var(--border);
  }
  .hms-btn-outline:hover { border-color: var(--teal); color: var(--teal); }
  .glass-card {
    background: var(--glass);
    border: 1px solid var(--border);
    border-radius: 20px;
    backdrop-filter: blur(12px);
    transition: all .3s;
  }
  .glass-card:hover { border-color: rgba(0,201,177,.3); transform: translateY(-4px); box-shadow: 0 20px 60px rgba(0,0,0,.3); }

  /* ---- hero ---- */
  #hms-hero {
    min-height: 100vh;
    background: radial-gradient(ellipse 80% 60% at 50% -10%, rgba(0,201,177,.18) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 90% 50%, rgba(26,107,255,.12) 0%, transparent 60%),
                var(--navy);
    position: relative; overflow: hidden;
  }
  #hms-hero::before {
    content:''; position:absolute; inset:0;
    background-image: radial-gradient(rgba(255,255,255,.03) 1px, transparent 1px);
    background-size: 40px 40px;
  }
  .hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(0,201,177,.1); border: 1px solid rgba(0,201,177,.3);
    color: var(--teal); padding: 8px 20px; border-radius: 999px;
    font-size: 13px; font-weight: 500; margin-bottom: 28px;
  }
  .hero-badge span { width:8px;height:8px;border-radius:50%;background:var(--teal);animation:pulse 2s infinite; display:inline-block; }
  @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }
  .hero-title {
    font-size: clamp(40px, 6vw, 78px);
    font-weight: 800; line-height: 1.05;
    background: linear-gradient(135deg, #fff 40%, var(--teal));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    margin: 0 0 24px;
  }
  .hero-stat-card {
    background: rgba(255,255,255,.05); border: 1px solid var(--border);
    border-radius: 16px; padding: 20px 24px; text-align: center;
  }
  .hero-stat-card .num { font-family:'Syne',sans-serif; font-size:32px; font-weight:800; color:var(--teal); }
  .hero-float {
    position:absolute; border-radius:16px; padding:14px 18px;
    background:rgba(11,25,48,.85); border:1px solid var(--border);
    backdrop-filter:blur(16px); display:flex; align-items:center; gap:12px;
    animation: float 4s ease-in-out infinite;
  }
  @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }

  /* ---- services ---- */
  .service-icon {
    width:52px; height:52px; border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    font-size:22px; margin-bottom:16px;
  }

  /* ---- stats band ---- */
  #hms-stats { background: linear-gradient(90deg, rgba(0,201,177,.08), rgba(26,107,255,.08)); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
  .stat-item { border-right: 1px solid var(--border); }
  .stat-item:last-child { border-right: none; }

  /* ---- dept tabs ---- */
  .dept-tab {
    padding: 10px 22px; border-radius: 10px; cursor: pointer;
    font-family: 'Syne',sans-serif; font-weight: 600; font-size: 14px;
    border: 1px solid var(--border); background: transparent; color: var(--muted);
    transition: all .2s;
  }
  .dept-tab.active, .dept-tab:hover { background: rgba(0,201,177,.12); border-color: rgba(0,201,177,.35); color: var(--teal); }

  /* ---- team card ---- */
  .team-card { overflow:hidden; }
  .team-card .avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, var(--teal), var(--blue));
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; font-weight: 800; color: #fff;
    font-family: 'Syne',sans-serif; flex-shrink: 0;
  }
  .role-badge {
    font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em;
    padding: 4px 10px; border-radius: 6px;
  }

  /* ---- lab card ---- */
  .lab-card { overflow:hidden; position:relative; }
  .lab-card::before { content:''; position:absolute; top:0;left:0;right:0;height:3px; background:linear-gradient(90deg,var(--teal),var(--blue)); border-radius:20px 20px 0 0; }

  /* ---- timeline ---- */
  .timeline-line { width:2px; background:linear-gradient(180deg,var(--teal),var(--blue)); border-radius:2px; }
  .timeline-dot { width:14px;height:14px;border-radius:50%;background:var(--teal);border:3px solid var(--navy2);flex-shrink:0; }

  /* ---- testimonial ---- */
  .stars { color: var(--amber); letter-spacing:2px; font-size:14px; }

  /* ---- appointment ---- */
  #hms-appointment {
    background: radial-gradient(ellipse 70% 80% at 50% 50%, rgba(0,201,177,.07) 0%, transparent 70%), var(--navy2);
  }
  .form-field {
    width:100%; background:rgba(255,255,255,.05); border:1px solid var(--border);
    border-radius:12px; padding:14px 18px; color:var(--text);
    font-family:'DM Sans',sans-serif; font-size:14px; outline:none;
    transition: border-color .2s;
  }
  .form-field:focus { border-color: var(--teal); }
  .form-field option { background: var(--navy2); }

  /* ---- emergency ---- */
  #hms-emergency { background: linear-gradient(135deg, #1a0510, #050d1a); border-top: 1px solid rgba(245,66,99,.2); }

  /* ---- footer ---- */
  #hms-footer { background: #020810; border-top: 1px solid var(--border); }
  .footer-link { color: var(--muted); text-decoration:none; font-size:14px; transition:color .2s; }
  .footer-link:hover { color: var(--teal); }

  /* ---- scroll animation ---- */
  .fade-up { opacity:0; transform:translateY(30px); transition:opacity .6s, transform .6s; }
  .fade-up.visible { opacity:1; transform:translateY(0); }
</style>

{{-- ============================================================
     §1  NAVIGATION
     ============================================================ --}}
<nav class="fixed top-0 left-0 right-0 z-50" style="background:rgba(5,13,26,.85);backdrop-filter:blur(20px);border-bottom:1px solid var(--border)">
  <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="/" class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white font-extrabold text-sm" style="background:linear-gradient(135deg,var(--teal),var(--blue));font-family:'Syne',sans-serif">H+</div>
      <span class="text-lg font-bold" style="font-family:'Syne',sans-serif">HMS <span style="color:var(--teal)">Hospital</span></span>
    </a>

    <div class="hidden lg:flex items-center gap-1">
      @foreach([['#hms-services','Services'],['#hms-departments','Departments'],['#hms-doctors','Doctors'],['#hms-lab','Lab Tests'],['#hms-appointment','Appointment'],['#hms-contact','Contact']] as $item)
      <a href="{{ $item[0] }}" class="px-4 py-2 rounded-lg text-sm font-medium transition-all" style="color:var(--muted)" onmouseover="this.style.color='var(--teal)';this.style.background='rgba(0,201,177,.08)'" onmouseout="this.style.color='var(--muted)';this.style.background='transparent'">{{ $item[1] }}</a>
      @endforeach
    </div>

    <div class="flex items-center gap-3">
      <a href="{{ route('login') }}" class="hms-btn hms-btn-outline text-sm" style="padding:10px 20px">Login</a>
      <a href="#hms-appointment" class="hms-btn hms-btn-primary text-sm" style="padding:10px 20px">Book Now</a>
    </div>
  </div>
</nav>


{{-- ============================================================
     §2  HERO
     ============================================================ --}}
<section id="hms-hero" class="flex items-center" style="padding:140px 24px 80px">
  <div class="max-w-7xl mx-auto w-full">
    <div class="grid lg:grid-cols-2 gap-16 items-center">

      <div>
        <div class="hero-badge">
          <span></span>
          Now accepting new patients — 24/7 care available
        </div>
        <h1 class="hero-title">Advanced Care.<br>Modern Medicine.<br>Real Results.</h1>
        <p class="text-lg mb-10" style="color:var(--muted);max-width:500px;line-height:1.75">
          HMS Hospital brings together world-class physicians, cutting-edge diagnostics, and compassionate staff — all under one roof.
        </p>
        <div class="flex flex-wrap gap-4 mb-12">
          <a href="#hms-appointment" class="hms-btn hms-btn-primary">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
            Book Appointment
          </a>
          <a href="#hms-services" class="hms-btn hms-btn-outline">
            Explore Services →
          </a>
        </div>

        {{-- mini stats --}}
        <div class="grid grid-cols-3 gap-4">
          @foreach([['98%','Patient Satisfaction'],['250+','Specialists'],['40+','Departments']] as $s)
          <div class="hero-stat-card">
            <div class="num">{{ $s[0] }}</div>
            <div class="text-xs mt-1" style="color:var(--muted)">{{ $s[1] }}</div>
          </div>
          @endforeach
        </div>
      </div>

      {{-- right visual --}}
      <div class="relative hidden lg:block" style="height:540px">
        {{-- center blob --}}
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="rounded-3xl overflow-hidden w-80 h-80" style="background:linear-gradient(135deg,rgba(0,201,177,.15),rgba(26,107,255,.15));border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:120px">🏥</div>
        </div>

        <div class="hero-float" style="top:40px;left:0;animation-delay:0s">
          <div style="width:40px;height:40px;border-radius:10px;background:rgba(0,201,177,.2);display:flex;align-items:center;justify-content:center;font-size:18px">👨‍⚕️</div>
          <div><div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">Dr. Arif Rahman</div><div class="text-xs" style="color:var(--muted)">Cardiologist</div></div>
        </div>

        <div class="hero-float" style="bottom:80px;left:10px;animation-delay:1s">
          <div style="width:40px;height:40px;border-radius:10px;background:rgba(26,107,255,.2);display:flex;align-items:center;justify-content:center;font-size:18px">🧪</div>
          <div><div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">Lab Results</div><div class="text-xs" style="color:var(--muted)">Ready in 2h</div></div>
        </div>

        <div class="hero-float" style="top:60px;right:0;animation-delay:.5s">
          <div style="color:var(--rose);font-size:22px">❤️</div>
          <div><div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">Heart Rate</div><div class="text-xs" style="color:var(--teal)">72 bpm — Normal</div></div>
        </div>

        <div class="hero-float" style="bottom:40px;right:0;animation-delay:1.5s">
          <div style="font-size:22px">📅</div>
          <div><div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">Next Slot</div><div class="text-xs" style="color:var(--muted)">Today, 3:00 PM</div></div>
        </div>
      </div>

    </div>
  </div>
</section>


{{-- ============================================================
     §3  STATS BAND
     ============================================================ --}}
<section id="hms-stats">
  <div class="max-w-7xl mx-auto">
    <div class="grid grid-cols-2 lg:grid-cols-5">
      @foreach([['50K+','Patients Treated','🩺'],['250+','Medical Experts','👨‍⚕️'],['40+','Departments','🏥'],['24/7','Emergency Care','🚨'],['99%','Uptime Systems','💻']] as $s)
      <div class="stat-item py-10 px-6 text-center">
        <div style="font-size:28px;margin-bottom:8px">{{ $s[2] }}</div>
        <div class="text-3xl font-extrabold" style="font-family:'Syne',sans-serif;color:var(--teal)">{{ $s[0] }}</div>
        <div class="text-sm mt-1" style="color:var(--muted)">{{ $s[1] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §4  SERVICES
     ============================================================ --}}
<section id="hms-services" class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-16 fade-up">
      <div class="section-tag">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
        Our Services
      </div>
      <h2 class="text-4xl lg:text-5xl font-extrabold mb-4">Comprehensive Medical <span style="color:var(--teal)">Services</span></h2>
      <p class="text-lg max-w-2xl mx-auto" style="color:var(--muted)">From preventive care to complex surgeries, we deliver excellence across every discipline.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach([
        ['🫀','Cardiology','Advanced heart diagnostics & interventional cardiology.','rgba(245,66,99,.15)','rgba(245,66,99,.4)'],
        ['🧠','Neurology','Brain, spine & nervous system expertise.','rgba(26,107,255,.15)','rgba(26,107,255,.4)'],
        ['🦴','Orthopedics','Joint replacement, fractures & sports injuries.','rgba(245,166,35,.15)','rgba(245,166,35,.4)'],
        ['👁️','Ophthalmology','Eye care, LASIK & advanced retinal treatment.','rgba(0,201,177,.15)','rgba(0,201,177,.4)'],
        ['🍼','Pediatrics','Compassionate care for infants to teens.','rgba(160,90,255,.15)','rgba(160,90,255,.4)'],
        ['🧬','Oncology','Cancer screening, treatment & support.','rgba(245,66,99,.15)','rgba(245,66,99,.4)'],
        ['🫁','Pulmonology','Lung diseases, sleep disorders & respiratory care.','rgba(26,107,255,.15)','rgba(26,107,255,.4)'],
        ['🦷','Dental','Cosmetic & restorative dentistry.','rgba(0,201,177,.15)','rgba(0,201,177,.4)'],
      ] as $svc)
      <div class="glass-card p-6 fade-up">
        <div class="service-icon" style="background:{{ $svc[3] }};border:1px solid {{ $svc[4] }}">{{ $svc[0] }}</div>
        <h3 class="text-lg font-bold mb-2" style="font-family:'Syne',sans-serif">{{ $svc[1] }}</h3>
        <p class="text-sm leading-relaxed" style="color:var(--muted)">{{ $svc[2] }}</p>
        <a href="#" class="text-sm font-semibold mt-4 inline-block" style="color:var(--teal)">Learn more →</a>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §5  DEPARTMENTS
     ============================================================ --}}
<section id="hms-departments" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">🏢 Departments</div>
      <h2 class="text-4xl font-extrabold mb-4">Explore Our <span style="color:var(--teal)">Departments</span></h2>
    </div>

    <div class="flex flex-wrap justify-center gap-3 mb-10 fade-up">
      @foreach(['All','Medicine','Surgery','Diagnostics','Maternal','Emergency','Rehabilitation'] as $dept)
      <button class="dept-tab {{ $loop->first ? 'active' : '' }}">{{ $dept }}</button>
      @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach([
        ['🏥','Internal Medicine','Floor 2','12 specialists','Mon–Sat'],
        ['🔪','General Surgery','Floor 4','8 surgeons','24/7 On-call'],
        ['🧪','Pathology & Lab','Basement','20 technicians','24/7'],
        ['👶','Obstetrics & Gynecology','Floor 3','10 doctors','24/7'],
        ['🚑','Emergency & Trauma','Ground Floor','30 staff','24/7'],
        ['🧘','Physiotherapy','Floor 1','6 therapists','Sat–Thu'],
        ['🩻','Radiology & Imaging','Basement','5 radiologists','24/7'],
        ['💊','Pharmacy','Ground Floor','Registered Pharmacists','24/7'],
        ['🧑‍💻','ICU / CCU','Floor 5','Elite nursing team','24/7'],
      ] as $d)
      <div class="glass-card p-6 flex items-start gap-4 fade-up">
        <div class="text-3xl">{{ $d[0] }}</div>
        <div class="flex-1">
          <h3 class="font-bold mb-1" style="font-family:'Syne',sans-serif">{{ $d[1] }}</h3>
          <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
            <span class="text-xs" style="color:var(--muted)">📍 {{ $d[2] }}</span>
            <span class="text-xs" style="color:var(--muted)">👥 {{ $d[3] }}</span>
            <span class="text-xs" style="color:var(--teal)">🕐 {{ $d[4] }}</span>
          </div>
        </div>
        <a href="#" class="text-xs font-semibold" style="color:var(--teal);white-space:nowrap">View →</a>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §6  DOCTORS
     ============================================================ --}}
<section id="hms-doctors" class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row lg:items-end justify-between mb-14 gap-6">
      <div class="fade-up">
        <div class="section-tag">👨‍⚕️ Medical Team</div>
        <h2 class="text-4xl font-extrabold">Our <span style="color:var(--teal)">Specialists</span></h2>
      </div>
      <a href="{{ route('doctors.index') ?? '#' }}" class="hms-btn hms-btn-outline self-start lg:self-auto">View All Doctors →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach([
        ['AR','Dr. Arif Rahman','MBBS, MD','Senior Cardiologist','❤️','rgba(245,66,99,.15)','Mon Wed Fri'],
        ['SF','Dr. Samira Farooq','MBBS, FCPS','Neurologist','🧠','rgba(26,107,255,.15)','Sun Tue Thu'],
        ['KH','Dr. Karim Hossain','MS, FRCS','Orthopedic Surgeon','🦴','rgba(245,166,35,.15)','Sat Mon Wed'],
        ['RI','Dr. Rubana Islam','MBBS, DGO','Gynecologist','👶','rgba(160,90,255,.15)','Tue Thu Sat'],
      ] as $doc)
      <div class="glass-card team-card p-6 fade-up">
        <div class="flex items-center gap-4 mb-4">
          <div class="avatar" style="background:{{ $doc[5] }};color:var(--teal)">{{ $doc[0] }}</div>
          <div>
            <h4 class="font-bold leading-snug" style="font-family:'Syne',sans-serif;font-size:15px">{{ $doc[1] }}</h4>
            <p class="text-xs mt-0.5" style="color:var(--muted)">{{ $doc[2] }}</p>
          </div>
        </div>
        <div style="height:1px;background:var(--border);margin-bottom:16px"></div>
        <div class="flex items-center gap-2 mb-2">
          <span style="font-size:16px">{{ $doc[4] }}</span>
          <span class="text-sm font-semibold" style="font-family:'Syne',sans-serif">{{ $doc[3] }}</span>
        </div>
        <div class="text-xs mb-4" style="color:var(--muted)">📅 {{ $doc[6] }}</div>
        <div class="flex gap-2">
          <a href="#hms-appointment" class="hms-btn hms-btn-primary flex-1 justify-center" style="padding:10px 0;font-size:12px">Book</a>
          <a href="#" class="hms-btn hms-btn-outline flex-1 justify-center" style="padding:10px 0;font-size:12px">Profile</a>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §7  STAFF OVERVIEW (Nurses, Lab, Admin)
     ============================================================ --}}
<section id="hms-staff" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">🤝 Our Team</div>
      <h2 class="text-4xl font-extrabold">The People Behind <span style="color:var(--teal)">Your Care</span></h2>
      <p class="mt-4 max-w-xl mx-auto" style="color:var(--muted)">Nurses, lab technicians, admin staff — each role is vital to delivering exceptional patient experiences.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach([
        ['👩‍⚕️','Nursing Staff','120+','Registered nurses across all wards providing round-the-clock patient care.','rgba(0,201,177,.12)'],
        ['🔬','Lab Technicians','40+','Certified professionals handling diagnostics, culture, and pathology reports.','rgba(26,107,255,.12)'],
        ['🗂️','Admin & Front Desk','30+','Friendly coordinators managing appointments, billing, and patient queries.','rgba(245,166,35,.12)'],
        ['🚑','Paramedics','25+','Rapid-response teams trained for emergency and ambulance care.','rgba(245,66,99,.12)'],
      ] as $staff)
      <div class="glass-card p-6 text-center fade-up" style="background:{{ $staff[4] }}">
        <div class="text-5xl mb-4">{{ $staff[0] }}</div>
        <div class="text-3xl font-extrabold mb-1" style="font-family:'Syne',sans-serif;color:var(--teal)">{{ $staff[2] }}</div>
        <h3 class="font-bold mb-3" style="font-family:'Syne',sans-serif">{{ $staff[1] }}</h3>
        <p class="text-sm leading-relaxed" style="color:var(--muted)">{{ $staff[3] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §8  PATIENT PORTAL
     ============================================================ --}}
<section id="hms-patient" class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <div class="fade-up">
        <div class="section-tag">🩺 Patient Portal</div>
        <h2 class="text-4xl font-extrabold mb-6">Everything You Need,<br><span style="color:var(--teal)">In One Place</span></h2>
        <p class="text-lg mb-8" style="color:var(--muted);line-height:1.8">Access your health records, upcoming appointments, lab results, prescriptions and billing — anytime, anywhere.</p>

        <div class="space-y-4">
          @foreach([
            ['📋','Medical Records','Instant access to your complete medical history.'],
            ['🧪','Lab Results','Get notified the moment results are ready.'],
            ['💊','Prescriptions','View, renew & track your medication history.'],
            ['💳','Billing & Insurance','Clear invoices and insurance claim tracking.'],
          ] as $f)
          <div class="flex items-start gap-4 glass-card p-4" style="border-radius:14px">
            <div class="text-2xl">{{ $f[0] }}</div>
            <div>
              <div class="font-semibold" style="font-family:'Syne',sans-serif">{{ $f[1] }}</div>
              <div class="text-sm" style="color:var(--muted)">{{ $f[2] }}</div>
            </div>
          </div>
          @endforeach
        </div>

        <div class="flex gap-4 mt-8">
          <a href="{{ route('register') ?? '#' }}" class="hms-btn hms-btn-primary">Create Account</a>
          <a href="{{ route('login') ?? '#' }}" class="hms-btn hms-btn-outline">Patient Login</a>
        </div>
      </div>

      {{-- portal mockup --}}
      <div class="glass-card p-6 fade-up" style="border-radius:24px">
        <div class="flex items-center gap-3 mb-6">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl" style="background:rgba(0,201,177,.15)">👤</div>
          <div>
            <div class="font-bold" style="font-family:'Syne',sans-serif">Welcome, John Doe</div>
            <div class="text-xs" style="color:var(--muted)">Patient ID: #HMS-20024</div>
          </div>
          <div class="ml-auto text-xs px-3 py-1 rounded-full font-semibold" style="background:rgba(0,201,177,.15);color:var(--teal)">Active</div>
        </div>
        <div style="height:1px;background:var(--border);margin-bottom:20px"></div>

        @foreach([['Next Appointment','Dr. Arif Rahman — Today 3:00 PM','📅'],['Last Lab Test','CBC — Complete 2 days ago','🧪'],['Pending Bills','$120 — Invoice #4421','💳'],['Active Prescriptions','3 ongoing medications','💊']] as $item)
        <div class="flex items-center gap-3 p-3 rounded-xl mb-3" style="background:rgba(255,255,255,.03);border:1px solid var(--border)">
          <span class="text-xl">{{ $item[2] }}</span>
          <div>
            <div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">{{ $item[0] }}</div>
            <div class="text-xs" style="color:var(--muted)">{{ $item[1] }}</div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</section>


{{-- ============================================================
     §9  LAB TESTS
     ============================================================ --}}
<section id="hms-lab" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-14 gap-6">
      <div class="fade-up">
        <div class="section-tag">🔬 Laboratory</div>
        <h2 class="text-4xl font-extrabold">Featured <span style="color:var(--teal)">Lab Tests</span></h2>
        <p class="mt-3" style="color:var(--muted)">ISO-certified lab with results in as little as 2 hours.</p>
      </div>
      <a href="{{ route('lab.tests') ?? '#' }}" class="hms-btn hms-btn-outline">Browse All Tests →</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @forelse($featuredTests as $test)
      <div class="glass-card lab-card p-6 fade-up">
        <div class="text-2xl mb-3">🧬</div>
        <h3 class="font-bold mb-1" style="font-family:'Syne',sans-serif">{{ $test->name }}</h3>
        <p class="text-xs mb-3" style="color:var(--muted)">{{ $test->department->name ?? 'Laboratory' }}</p>
        <div class="flex items-center justify-between mb-4">
          <span class="text-xl font-extrabold" style="font-family:'Syne',sans-serif;color:var(--teal)">${{ $test->cost }}</span>
          <span class="text-xs px-2 py-1 rounded-md" style="background:rgba(0,201,177,.1);color:var(--teal)">~2–4 hrs</span>
        </div>
        <form action="{{ route('cart.add', $test) }}" method="POST">
          @csrf
          <button type="submit" class="hms-btn hms-btn-primary w-full justify-center" style="padding:11px 0;font-size:13px">
            🛒 Add to Cart
          </button>
        </form>
      </div>
      @empty
      @foreach([['CBC — Complete Blood Count','$18','Hematology'],['Lipid Profile','$24','Biochemistry'],['HbA1c','$20','Endocrinology'],['Thyroid Panel (T3/T4/TSH)','$35','Endocrinology']] as $t)
      <div class="glass-card lab-card p-6 fade-up">
        <div class="text-2xl mb-3">🧬</div>
        <h3 class="font-bold mb-1" style="font-family:'Syne',sans-serif">{{ $t[0] }}</h3>
        <p class="text-xs mb-3" style="color:var(--muted)">{{ $t[2] }}</p>
        <div class="flex items-center justify-between mb-4">
          <span class="text-xl font-extrabold" style="font-family:'Syne',sans-serif;color:var(--teal)">{{ $t[1] }}</span>
          <span class="text-xs px-2 py-1 rounded-md" style="background:rgba(0,201,177,.1);color:var(--teal)">~2–4 hrs</span>
        </div>
        <button class="hms-btn hms-btn-primary w-full justify-center" style="padding:11px 0;font-size:13px">🛒 Add to Cart</button>
      </div>
      @endforeach
      @endforelse
    </div>
  </div>
</section>


{{-- ============================================================
     §10  HOW IT WORKS
     ============================================================ --}}
<section id="hms-how" class="py-24 px-6">
  <div class="max-w-5xl mx-auto">
    <div class="text-center mb-16 fade-up">
      <div class="section-tag">🗺️ Process</div>
      <h2 class="text-4xl font-extrabold">How It <span style="color:var(--teal)">Works</span></h2>
      <p class="mt-4" style="color:var(--muted)">Getting care at HMS is simple, fast, and stress-free.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-0">
      @foreach([
        ['01','Register','Create your patient profile in under 2 minutes.','📝'],
        ['02','Book','Choose a doctor, date & time that suits you.','📅'],
        ['03','Consult','Visit in-person or connect via telemedicine.','🩺'],
        ['04','Follow-Up','Receive your reports and prescriptions digitally.','📊'],
      ] as $step)
      <div class="relative text-center px-6 pb-8 fade-up">
        @if(!$loop->last)
        <div class="absolute top-8 left-1/2 right-0 h-0.5 hidden md:block" style="background:linear-gradient(90deg,var(--teal),var(--border))"></div>
        @endif
        <div class="relative inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 text-2xl z-10" style="background:linear-gradient(135deg,rgba(0,201,177,.2),rgba(26,107,255,.2));border:1px solid rgba(0,201,177,.3)">{{ $step[3] }}</div>
        <div class="text-xs font-bold mb-2" style="color:var(--teal);font-family:'Syne',sans-serif;letter-spacing:.08em">{{ $step[0] }}</div>
        <h3 class="font-bold text-lg mb-2" style="font-family:'Syne',sans-serif">{{ $step[1] }}</h3>
        <p class="text-sm" style="color:var(--muted)">{{ $step[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §11  APPOINTMENT BOOKING
     ============================================================ --}}
<section id="hms-appointment" class="py-24 px-6">
  <div class="max-w-4xl mx-auto">
    <div class="text-center mb-12 fade-up">
      <div class="section-tag">📅 Booking</div>
      <h2 class="text-4xl font-extrabold">Book an <span style="color:var(--teal)">Appointment</span></h2>
      <p class="mt-4" style="color:var(--muted)">Fill in your details and we'll confirm your slot within 30 minutes.</p>
    </div>

    <div class="glass-card p-8 lg:p-10 fade-up" style="background:var(--navy2)">
      <form action="{{ route('appointments.store') ?? '#' }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Full Name</label>
            <input type="text" name="name" placeholder="John Doe" class="form-field" required>
          </div>
          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Phone Number</label>
            <input type="tel" name="phone" placeholder="+880 1xxx-xxxxxx" class="form-field" required>
          </div>
          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Department</label>
            <select name="department" class="form-field" required>
              <option value="">Select Department</option>
              @foreach(['Cardiology','Neurology','Orthopedics','Gynecology','Pediatrics','Oncology','Emergency','General Medicine'] as $dept)
              <option>{{ $dept }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Preferred Doctor</label>
            <select name="doctor_id" class="form-field">
              <option value="">Any Available</option>
            </select>
          </div>
          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Appointment Date</label>
            <input type="date" name="date" class="form-field" required>
          </div>
          <div>
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Preferred Time</label>
            <select name="time" class="form-field" required>
              @foreach(['09:00 AM','10:00 AM','11:00 AM','12:00 PM','02:00 PM','03:00 PM','04:00 PM','05:00 PM'] as $t)
              <option>{{ $t }}</option>
              @endforeach
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="text-xs font-semibold block mb-2" style="color:var(--muted);letter-spacing:.05em;text-transform:uppercase">Chief Complaint / Notes</label>
            <textarea name="notes" rows="3" placeholder="Briefly describe your symptoms or reason for visit..." class="form-field" style="resize:none"></textarea>
          </div>

          <div class="md:col-span-2">
            <button type="submit" class="hms-btn hms-btn-primary w-full justify-center" style="padding:16px;font-size:15px">
              📅 Confirm Appointment
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>


{{-- ============================================================
     §12  TESTIMONIALS
     ============================================================ --}}
<section id="hms-testimonials" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">💬 Testimonials</div>
      <h2 class="text-4xl font-extrabold">What Patients <span style="color:var(--teal)">Say</span></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach([
        ['Razia Begum','Cardiac Patient','The care I received at HMS was extraordinary. Dr. Rahman was thorough, patient, and truly compassionate. I am fully recovered now!','⭐⭐⭐⭐⭐','RB'],
        ['Imran Hossain','Lab & General Check-up','Results came back in under 3 hours. The staff was professional and the portal made it so easy to track everything online.','⭐⭐⭐⭐⭐','IH'],
        ['Nusrat Jahan','Post-Surgery Recovery','From admission to discharge, every nurse and doctor treated me with respect. The physiotherapy team was exceptional.','⭐⭐⭐⭐⭐','NJ'],
      ] as $t)
      <div class="glass-card p-7 fade-up">
        <div class="stars mb-4">{{ $t[3] }}</div>
        <p class="text-sm leading-relaxed mb-6" style="color:var(--muted)">"{{ $t[2] }}"</p>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold" style="background:linear-gradient(135deg,var(--teal),var(--blue));font-family:'Syne',sans-serif">{{ $t[4] }}</div>
          <div>
            <div class="font-bold text-sm" style="font-family:'Syne',sans-serif">{{ $t[0] }}</div>
            <div class="text-xs" style="color:var(--muted)">{{ $t[1] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §13  TELEMEDICINE
     ============================================================ --}}
<section id="hms-tele" class="py-24 px-6">
  <div class="max-w-6xl mx-auto">
    <div class="glass-card p-10 lg:p-14 fade-up" style="background:linear-gradient(135deg,rgba(0,201,177,.07),rgba(26,107,255,.07));border-color:rgba(0,201,177,.2)">
      <div class="grid lg:grid-cols-2 gap-12 items-center">
        <div>
          <div class="section-tag">📱 Telemedicine</div>
          <h2 class="text-4xl font-extrabold mb-6">Consult From <span style="color:var(--teal)">Anywhere</span></h2>
          <p class="mb-8" style="color:var(--muted);line-height:1.8">Can't make it in-person? Our telemedicine platform connects you with certified HMS physicians via secure video call — anytime, from any device.</p>
          <ul class="space-y-3 mb-8">
            @foreach(['End-to-end encrypted video consultations','Digital prescriptions issued instantly','Available for follow-ups & non-emergency care','Covered under major insurance plans'] as $pt)
            <li class="flex items-center gap-3 text-sm">
              <span style="color:var(--teal)">✓</span> {{ $pt }}
            </li>
            @endforeach
          </ul>
          <a href="#" class="hms-btn hms-btn-primary">Start Video Consult →</a>
        </div>
        <div class="text-center" style="font-size:120px">📱</div>
      </div>
    </div>
  </div>
</section>


{{-- ============================================================
     §14  INSURANCE & BILLING
     ============================================================ --}}
<section id="hms-insurance" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">💳 Insurance</div>
      <h2 class="text-4xl font-extrabold">Accepted Insurance <span style="color:var(--teal)">Partners</span></h2>
      <p class="mt-4" style="color:var(--muted)">We partner with all major insurance providers for hassle-free claims.</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-12">
      @foreach(['MetLife','Pragati','Delta Life','Guardian','Meghna Life','SBC Insurance'] as $ins)
      <div class="glass-card p-4 text-center fade-up">
        <div class="text-2xl mb-2">🛡️</div>
        <div class="text-xs font-semibold" style="font-family:'Syne',sans-serif">{{ $ins }}</div>
      </div>
      @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach([
        ['🏷️','Transparent Pricing','No hidden fees. Full itemized bills for every service.'],
        ['⚡','Fast Claims','Insurance claims processed within 3 working days.'],
        ['📞','Billing Support','Dedicated billing team available Mon–Sat 9AM–6PM.'],
      ] as $b)
      <div class="glass-card p-6 flex gap-4 items-start fade-up">
        <div class="text-3xl">{{ $b[0] }}</div>
        <div>
          <h4 class="font-bold mb-1" style="font-family:'Syne',sans-serif">{{ $b[1] }}</h4>
          <p class="text-sm" style="color:var(--muted)">{{ $b[2] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §15  EMERGENCY BAND
     ============================================================ --}}
<section id="hms-emergency">
  <div class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
      <div class="flex items-center gap-5">
        <div class="text-5xl" style="animation:pulse 1.5s infinite">🚨</div>
        <div>
          <div class="text-2xl font-extrabold mb-1" style="font-family:'Syne',sans-serif">24/7 Emergency Services</div>
          <p style="color:var(--muted)">Ambulance dispatch, trauma bay & emergency physicians on standby.</p>
        </div>
      </div>
      <div class="flex flex-wrap gap-4">
        <a href="tel:+8801700000000" class="hms-btn" style="background:var(--rose);color:#fff;box-shadow:0 8px 30px rgba(245,66,99,.4);font-size:18px;padding:16px 32px">
          📞 +880 1700-000000
        </a>
        <a href="tel:999" class="hms-btn hms-btn-outline" style="font-size:16px;padding:16px 28px">🚑 Dial 999</a>
      </div>
    </div>
  </div>
</section>


{{-- ============================================================
     §16  FACILITIES & TECHNOLOGY
     ============================================================ --}}
<section id="hms-tech" class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">⚙️ Technology</div>
      <h2 class="text-4xl font-extrabold">World-Class <span style="color:var(--teal)">Facilities</span></h2>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach([
        ['🩻','1.5T MRI Scanner','High-resolution neurological & body imaging.'],
        ['💊','Smart Pharmacy','Automated dispensing with zero-error verification.'],
        ['🏥','Modular OT','6 fully equipped operating theatres.'],
        ['🔬','PCR Lab','Real-time molecular diagnostics.'],
        ['🛏️','500 Beds','Private, semi-private & general ward options.'],
        ['🧊','Blood Bank','24/7 screened blood & components.'],
        ['📡','PACS System','Digital imaging accessible hospital-wide.'],
        ['🤖','AI Diagnostics','ML-assisted radiology reads for faster results.'],
      ] as $f)
      <div class="glass-card p-5 fade-up">
        <div class="text-3xl mb-3">{{ $f[0] }}</div>
        <h4 class="font-bold mb-1" style="font-family:'Syne',sans-serif;font-size:14px">{{ $f[1] }}</h4>
        <p class="text-xs" style="color:var(--muted)">{{ $f[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §17  NEWS & HEALTH TIPS
     ============================================================ --}}
<section id="hms-news" class="py-24 px-6" style="background:var(--navy2)">
  <div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-12 fade-up">
      <div>
        <div class="section-tag">📰 Health Blog</div>
        <h2 class="text-4xl font-extrabold">News & <span style="color:var(--teal)">Health Tips</span></h2>
      </div>
      <a href="#" class="hms-btn hms-btn-outline">All Articles →</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach([
        ['❤️','Cardiology','5 Warning Signs of Heart Disease','June 1, 2025'],
        ['🧠','Wellness','Why Sleep is Your Best Medicine','May 28, 2025'],
        ['🥗','Nutrition','Eating for a Stronger Immune System','May 20, 2025'],
      ] as $n)
      <div class="glass-card overflow-hidden fade-up">
        <div class="flex items-center justify-center text-5xl" style="background:linear-gradient(135deg,rgba(0,201,177,.1),rgba(26,107,255,.1));height:120px">{{ $n[0] }}</div>
        <div class="p-6">
          <span class="text-xs font-semibold" style="color:var(--teal)">{{ $n[1] }}</span>
          <h4 class="font-bold mt-2 mb-3" style="font-family:'Syne',sans-serif">{{ $n[2] }}</h4>
          <div class="flex items-center justify-between">
            <span class="text-xs" style="color:var(--muted)">{{ $n[3] }}</span>
            <a href="#" class="text-xs font-semibold" style="color:var(--teal)">Read →</a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>


{{-- ============================================================
     §18  CONTACT
     ============================================================ --}}
<section id="hms-contact" class="py-24 px-6">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-14 fade-up">
      <div class="section-tag">📍 Contact</div>
      <h2 class="text-4xl font-extrabold">Get In <span style="color:var(--teal)">Touch</span></h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      @foreach([
        ['📍','Address','123 Hospital Road, Dhanmondi, Dhaka 1205, Bangladesh'],
        ['📞','Phone','Emergency: +880 1700-000000<br>General: +880 2-9xxxxxx'],
        ['📧','Email','info@hmshospital.com<br>appointments@hmshospital.com'],
      ] as $c)
      <div class="glass-card p-7 text-center fade-up">
        <div class="text-4xl mb-4">{{ $c[0] }}</div>
        <h4 class="font-bold mb-3" style="font-family:'Syne',sans-serif">{{ $c[1] }}</h4>
        <p class="text-sm leading-relaxed" style="color:var(--muted)">{!! $c[2] !!}</p>
      </div>
      @endforeach
    </div>

    {{-- map placeholder --}}
    <div class="mt-8 rounded-2xl overflow-hidden fade-up" style="height:280px;background:linear-gradient(135deg,rgba(0,201,177,.07),rgba(26,107,255,.07));border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:50px">
      🗺️
    </div>
  </div>
</section>


{{-- ============================================================
     §19  FOOTER
     ============================================================ --}}
<footer id="hms-footer">
  <div class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">

      {{-- brand --}}
      <div class="lg:col-span-2">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-extrabold" style="background:linear-gradient(135deg,var(--teal),var(--blue));font-family:'Syne',sans-serif">H+</div>
          <span class="text-xl font-bold" style="font-family:'Syne',sans-serif">HMS Hospital</span>
        </div>
        <p class="text-sm mb-6" style="color:var(--muted);line-height:1.8;max-width:300px">Delivering advanced, compassionate healthcare since 1998. Accredited by JCIA & ISO 9001:2015.</p>
        <div class="flex gap-3">
          @foreach(['📘','📸','🐦','▶️'] as $social)
          <a href="#" class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:var(--glass);border:1px solid var(--border);font-size:16px" onmouseover="this.style.borderColor='var(--teal)'" onmouseout="this.style.borderColor='var(--border)'">{{ $social }}</a>
          @endforeach
        </div>
      </div>

      @foreach([
        ['Quick Links',['Home','Services','Departments','Doctors','Lab Tests','Careers']],
        ['Patients',['Book Appointment','Patient Portal','Lab Results','Prescriptions','Billing']],
        ['Legal',['Privacy Policy','Terms of Service','Cookie Policy','Accessibility']],
      ] as $col)
      <div>
        <h5 class="font-bold mb-4 text-sm" style="font-family:'Syne',sans-serif;letter-spacing:.05em;text-transform:uppercase">{{ $col[0] }}</h5>
        <ul class="space-y-2">
          @foreach($col[1] as $link)
          <li><a href="#" class="footer-link">{{ $link }}</a></li>
          @endforeach
        </ul>
      </div>
      @endforeach

    </div>

    <div style="height:1px;background:var(--border);margin-bottom:24px"></div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
      <p class="text-sm" style="color:var(--muted)">© {{ date('Y') }} HMS Hospital. All rights reserved.</p>
      <div class="flex items-center gap-2">
        <span class="w-2 h-2 rounded-full" style="background:var(--teal);animation:pulse 2s infinite;display:inline-block"></span>
        <span class="text-xs" style="color:var(--muted)">All systems operational</span>
      </div>
    </div>
  </div>
</footer>


{{-- scroll-reveal --}}
<script>
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('visible'); } });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>

@endsection
