# Hospital Management System (HMS) — Laravel 13 Web Application

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=flat&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)

A modern, multi-role Hospital Management System (HMS) and Clinic Management web application built with Laravel 13 and Tailwind CSS. Features public patient self-service portals, doctor EMR/prescriptions, nurse station, front desk reception triage, laboratory diagnostics, pharmacy inventory, ward bed occupancy, and billing management.

## Tech stack

- PHP 8.3+ / Laravel 13
- Spatie Laravel Permission (roles)
- Tailwind CSS (CDN)
- SQLite or MySQL via standard Laravel `.env`
- Optional biometric device webhook (CSRF-exempt)

## Quick start

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Demo accounts

| Role | Email | Password |
|---|---|---|
| Admin | `admin@hospital.local` | `ProductionSecurePass2026!` |
| Doctor | `sarah.j@hospital.local` | `DoctorSecure2026!` |
| Doctor | `alex.m@hospital.local` | `DoctorSecure2026!` |
| Doctor | `elena.r@hospital.local` | `DoctorSecure2026!` |
| Reception | `reception@hospital.local` | `StaffSecure2026!` |
| Cashier | `billing@hospital.local` | `StaffSecure2026!` |
| Lab technician | `lab.tech@hospital.local` | `StaffSecure2026!` |
| Report giver | `report.giver@hospital.local` | `StaffSecure2026!` |
| Nurse | `nurse@hospital.local` | `StaffSecure2026!` |
| Pharmacist | `pharmacy@hospital.local` | `StaffSecure2026!` |
| Patient | `bruce@waynecorp.com` | `PatientSecure2026!` |

Walk-in patients registered at reception also receive `PatientSecure2026!` until they change it in **Profile**.

After login, `/dashboard` sends each user to the correct portal.

---

## Feature catalog

### Public website

- Home, about, services, departments, doctors directory
- Guest appointment request (name + phone, pending until reception confirms)
- Lab test catalog with cart, checkout, and mock payment
- Contact form stored in the admin inbox (with reply notes)
- Public ambulance request (priority: standard / urgent / critical)
- Public notice board
- Careers, insurance, privacy, terms, accessibility, anti-fraud pages
- Emergency call button and ambulance shortcut

### Authentication & accounts

- Patient self-registration (profile: age, gender, blood group, phone)
- Role-based login redirect
- Inactive users cannot sign in
- Profile name / email / password update
- Password-help page (IT / reception reset process)
- Internal messaging (inbox, unread badge, compose)
- Staff/patient notice board (audience: public, patient, staff, all)

### Super admin

- Operational dashboard (census, revenue, queues, low-stock, ambulance, blood, inbox)
- Users, doctors, patients, staff profiles
- Departments, rooms, beds
- Appointments and doctor weekly schedules
- Medical records and prescriptions
- Lab tests and lab reports
- Admissions
- Invoices and payments
- Medicines (pharmacy master data)
- Blood units and blood requests (fulfill from matching stock)
- Ambulance requests
- Notices
- Contact inbox
- Patient feedback (publish testimonials)
- Biometric devices and attendance logs

### Reception / front desk

- Today’s appointment list with status changes
- Quick patient registration
- Book / confirm appointments with token numbers
- Live bed occupancy map
- Admit and discharge (releases the bed)
- Ambulance dispatch (vehicle, driver, status)

### Doctors

- Today’s clinic list
- Appointment chart: vitals, diagnosis, clinical notes
- Structured prescriptions (drug, dose, frequency, duration)
- Follow-up context from prior records
- Patient lab history
- Personal weekly schedule

### Nurses

- Nursing station dashboard
- Active ward / admission list
- Vitals rounds (temperature, pulse, RR, BP, SpO2, weight, notes)

### Laboratory

- Queue of paid / open diagnostic orders
- Sample collection
- Result entry + PDF/image upload
- Report handover to the patient

### Billing / cashier

- Open invoice list and remaining balance
- Partial or full payments (cash, card, mobile banking, insurance)
- Create a lab invoice for a patient/test
- Daily collection snapshot

### Pharmacy

- Inventory with low-stock and near-expiry alerts
- Dispense against a patient (optional prescription link)
- Stock decrement on dispense
- Admin medicine CRUD (price, manufacturer, expiry)

### Blood bank

- Bag inventory by group and component (whole blood, plasma, platelets, RBC)
- Blood requests with urgency
- Issue the oldest matching available unit

### Ambulance & emergency

- Public and patient-portal requests
- Reception/admin dispatch workflow
- Status: requested → dispatched → en route → arrived → completed / cancelled

### Patient portal

- Dashboard (upcoming visits, recent labs, notices)
- Book and cancel appointments
- Buy lab tests (invoice created automatically)
- Lab reports and file download when attached
- Medical records and prescriptions
- Invoice ledger
- Feedback (1–5 stars, category, optional doctor)
- Personal ambulance request
- Messages and notices
- Profile

### IoT / HR attendance

- Biometric webhook `POST /webhook/biometric-punch` with `X-Device-Token`
- Maps hardware `biometric_id` to a user
- Logs unmapped punches as errors
- Staff “my attendance” page

---

## Roles

`admin`, `doctor`, `nurse`, `receptionist`, `cashier`, `lab_technician`, `report_giver`, `pharmacist`, `patient`

## Typical clinical flow

1. Patient registers (web or reception) and books a doctor.
2. Doctor records notes, diagnosis, vitals, and a prescription.
3. Patient or cashier orders lab tests; payment unlocks the lab queue.
4. Technician collects the sample, enters results, and hands over the report.
5. Pharmacist dispenses stock against the prescription.
6. Reception admits to a bed when needed; nurses log vitals; discharge frees the bed.
7. Emergency: ambulance request → dispatch → arrival.

## Project layout

```
app/Http/Controllers/   Role portals, admin CRUD, public pages
app/Models/             Clinical, billing, pharmacy, blood, ambulance
app/Support/Portal.php  Role home URL and sidebar
app/Services/           Lab invoicing and doctor stats
database/migrations/    Schema
database/seeders/       Demo hospital data
resources/views/        Public site + role portals
routes/web.php          All HTTP routes
```

## Notes

- Lab checkout uses a simulated payment page (no live gateway).
- Guest web bookings create appointments without a user account; reception converts them during confirmation.
- Change demo passwords before any real deployment.
- Run `php artisan storage:link` so uploaded lab PDFs are downloadable.

---

## Let's build something exceptional

I'm actively open to: **Remote Senior Full-Stack Roles · Freelance Contracts · Technical Partnerships · Long-Term Collaborations**

in Laravel · WordPress · React/Next.js · AI-powered Platforms · Security Audits · SaaS Architecture

- Timezone: **UTC+6 (Dhaka/Rangpur)** — flexible overlap for US, EU & Asia
- Available: **Immediately · Production-first · Fast delivery · Transparent communication**

| Platform | Link |
|---|---|
| Portfolio | [imrandev.bd](https://imrandev.bd) |
| LinkedIn | [linkedin.com/in/imranbru99](https://linkedin.com/in/imranbru99) |
| GitHub | [github.com/imranbru99](https://github.com/imranbru99) |
| X / Twitter | [@imrandev_bd](https://x.com/imrandev_bd) |
| YouTube | [@ImranDevBD](https://youtube.com/@ImranDevBD) |
| Instagram | [@imranbru99](https://instagram.com/imranbru99) |
| Facebook | [ExpertImranDev](https://facebook.com/ExpertImranDev) |
| TikTok | [@imrandev_bd](https://tiktok.com/@imrandev_bd) |
| Threads | [@imranbru99](https://www.threads.net/@imranbru99) |
| Pinterest | [@imrandev_bd](https://pinterest.com/imrandev_bd) |
| WhatsApp | [+880 1576-918420](https://wa.me/8801576918420) |
| Email | [me@imrandev.bd](mailto:me@imrandev.bd) |
| All links | [linktr.ee/ExpertImranDev](https://linktr.ee/ExpertImranDev) |

> "Security isn't an add-on — it's the foundation. Scale, speed, and trust drive every line of code I write."
>
> — **Imran Ahmed**
