# 🎓 Club Portfolio SaaS Platform

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![TypeScript](https://img.shields.io/badge/TypeScript-5.2-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Three.js](https://img.shields.io/badge/Three.js-0.182-000000?style=for-the-badge&logo=three.js&logoColor=white)

**A comprehensive SaaS platform for university clubs in Bangladesh**

[🌐 Live Demo](https://mbstusc.eksofts.xyz) • [📚 Documentation](#-usage-guide) • [🐛 Report Bug](https://github.com/theCoder505/mbstu_science_club/issues) • [✨ Request Feature](https://github.com/theCoder505/mbstu_science_club/issues)

</div>

---

## 🌟 Overview

## 🌟 Overview

A comprehensive SaaS platform designed for co-curricular activity clubs and organizations in Bangladeshi universities to showcase their work, manage certificates, and maintain a professional online presence.

This platform empowers university clubs and organizations to establish a national and international standard portfolio without technical expertise. Currently showcasing the **Science Club of Mawlana Bhashani Science & Technology University (MBSTU)**, the system provides:

### ✨ Core Features

<table>
<tr>
<td width="50%">

#### 📜 Certificate Management
- Automated generation & verification
- Multi-step approval workflow
- Template designer with Fabric.js
- PDF generation & email delivery

</td>
<td width="50%">

#### 🎨 Professional Portfolio
- Dynamic content management
- Team member profiles
- Gallery & event showcase
- SEO-optimized pages

</td>
</tr>
<tr>
<td width="50%">

#### 👥 Multi-Role System
- Admin panel (Full control)
- Advisor portal (Certificate approval)
- Public interface (Applications & tracking)

</td>
<td width="50%">

#### 📊 Analytics & Insights
- Page view statistics
- Daily/Weekly/Monthly reports
- Bar chart visualizations
- AdSense ready

</td>
</tr>
</table>

### 🔗 Quick Links

**Live Demo:** [https://mbstusc.eksofts.xyz](https://mbstusc.eksofts.xyz)

**Repository:** [https://github.com/theCoder505/mbstu_science_club](https://github.com/theCoder505/mbstu_science_club)

---

## 📸 Screenshots

<div align="center">

### 🏠 Landing Page
*Professional homepage showcasing club achievements and statistics*

### 📜 Certificate System
*Streamlined application and approval workflow*

### 👨‍💼 Admin Dashboard
*Comprehensive analytics and management tools*

### 🎨 Template Designer
*Built-in certificate template creation tool*

> **Note**: Visit [https://mbstusc.eksofts.xyz](https://mbstusc.eksofts.xyz) to see the platform in action!

</div>

---

## 📑 Table of Contents

- [🌟 Overview](#-overview)
- [🎯 Key Features](#-key-features)
- [🛠️ Technology Stack](#️-technology-stack)
- [📋 System Requirements](#-system-requirements)
- [🚀 Installation](#-installation)
- [📁 Project Structure](#-project-structure)
- [🔑 Key Models](#-key-models)
- [🔐 Authentication & Authorization](#-authentication--authorization)
- [📧 Email Notifications](#-email-notifications)
- [📊 Analytics & Statistics](#-analytics--statistics)
- [🎨 Certificate Template Designer](#-certificate-template-designer)
- [🎯 TypeScript Integration](#-typescript-integration)
- [🌐 Three.js Integration](#-threejs-integration)
- [🌐 Routes Overview](#-routes-overview)
- [🔧 Configuration](#-configuration)
- [🎯 Usage Guide](#-usage-guide)
- [🔒 Security Features](#-security-features)
- [📈 Future Enhancements](#-future-enhancements)
- [🤝 Contributing](#-contributing)

---

## ⚡ Features at a Glance

<div align="center">

| Feature | Description | Status |
|---------|-------------|--------|
| 📜 | **Certificate System** | ✅ Fully Functional |
| 🎨 | **Template Designer** | ✅ Fabric.js Powered |
| 👥 | **Multi-Role Auth** | ✅ Admin + Advisor |
| 📊 | **Analytics Dashboard** | ✅ Real-time Stats |
| 🌐 | **3D Visualizations** | ✅ Three.js Ready |
| 💾 | **TypeScript** | ✅ Full Coverage |
| 📧 | **Email System** | ✅ SMTP + OTP |
| 🎯 | **SEO Optimized** | ✅ Meta Tags |
| 📱 | **Responsive Design** | ✅ Mobile First |
| 🔒 | **Security** | ✅ Enterprise Grade |

</div>

---

## 🎯 Key Features

### Certificate Management System
- **Member Application Portal**: Members can apply for club certificates with their contributions
- **Ticketing Workflow**: 
  1. Member submits application
  2. Admin receives email notification
  3. Admin verifies and customizes certificate
  4. Advisor/Moderator approves
  5. Certificate automatically sent to member
- **Status Tracking**: Applicants can track certificate status (Pending, Verified, Approved, Declined, In Revision)
- **Template Designer**: Built-in canvas-based certificate template creator
- **Position Customization**: Adjustable text positions for different templates
- **Dual Delivery**: Certificates sent via email and downloadable from tracking page

### Admin Panel Features
- **Dashboard**: Real-time statistics and page view analytics (daily, weekly, monthly, yearly)
- **Certificate Applications**: Review, verify, and manage all certificate requests
- **Template Management**: Create, upload, and manage certificate templates
- **Advisor/Moderator Management**: Add professors with role assignments
- **Team Management**: Showcase key members with detailed profiles
- **Executive Committee**: Display committee members by period
- **Gallery Management**: CRUD operations with position control and show/hide status
- **FAQ Management**: Maintain frequently asked questions
- **Website Settings**: Configure About Us, Mission, Vision, Values, Social Links, Privacy Policy, Terms, Constitution
- **Security**: OTP verification for email/password changes (10-minute expiration)
- **Appearance**: Dark/Light mode support

### Advisor Panel Features
- **Minimal Interface**: Designed for busy professors with limited time
- **Certificate Review**: View assigned certificate applications
- **One-Click Approval**: Quick approval via email links or dashboard
- **Profile Management**: Update signature, image, department, social links
- **Authentication**: Separate login system with credentials managed by admin

### Surface Website Features
- **Landing Page**: Hero section, statistics, club journey, mission/vision
- **Team Profiles**: Individual member pages with detailed portfolios
- **Advisor Showcase**: Display distinguished professors
- **Committee Gallery**: Interactive committee member display
- **FAQ Section**: Frequently asked questions
- **Facebook Integration**: Live feed from club's Facebook page
- **Contact Form**: OTP-verified email system to prevent spam
- **Certificate Application**: Public form for members to apply
- **Certificate Tracking**: Track application status and download certificates
- **Dynamic Content**: About Us, Privacy Policy, Terms, Constitution pages

---

## 🛠️ Technology Stack

<div align="center">

### Backend Stack
| Technology | Version | Purpose |
|------------|---------|---------|
| ![Laravel](https://img.shields.io/badge/-Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white) | 12 (Latest) | PHP Framework |
| ![SQLite](https://img.shields.io/badge/-SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white) | Latest | Database |
| ![Eloquent](https://img.shields.io/badge/-Eloquent%20ORM-FF2D20?style=flat-square&logo=laravel&logoColor=white) | Latest | Database ORM |

### Frontend Stack
| Technology | Version | Purpose |
|------------|---------|---------|
| ![Vue.js](https://img.shields.io/badge/-Vue.js-4FC08D?style=flat-square&logo=vue.js&logoColor=white) | 3 (Latest) | JavaScript Framework |
| ![TypeScript](https://img.shields.io/badge/-TypeScript-3178C6?style=flat-square&logo=typescript&logoColor=white) | 5.2+ | Type Safety |
| ![Tailwind](https://img.shields.io/badge/-Tailwind%20CSS-06B6D4?style=flat-square&logo=tailwind-css&logoColor=white) | 4 (Latest) | CSS Framework |
| ![Inertia](https://img.shields.io/badge/-Inertia.js-9553E9?style=flat-square&logo=inertia&logoColor=white) | 2.1+ | Laravel-Vue Bridge |
| ![Three.js](https://img.shields.io/badge/-Three.js-000000?style=flat-square&logo=three.js&logoColor=white) | 0.182.0 | 3D Graphics |

</div>

### Backend
- **Framework**: Laravel 12 (Latest)
- **Architecture**: MVC Pattern with Eloquent ORM
- **Database**: SQLite (MySQL/PostgreSQL supported)
- **Authentication**: Custom middleware for Admin and Advisor guards
- **Email**: Laravel Mail facade with SMTP

### Frontend
- **Framework**: Vue.js 3 (Latest)
- **Language**: TypeScript (Latest) - Full type safety and modern JavaScript features
- **Styling**: Tailwind CSS 4 (Latest) + Custom Media CSS
- **UI Components**: shadcn/ui, Reka UI
- **Bridge**: Inertia.js for seamless Laravel-Vue integration
- **3D Graphics**: Three.js (v0.182.0) - For advanced visualizations and interactive elements

### Additional Libraries
- **3D Graphics**: Three.js v0.182.0 (Advanced 3D visualizations)
- **Canvas Manipulation**: Fabric.js (Certificate template designer)
- **Rich Text Editor**: Quill Editor + @vueup/vue-quill (Website content management)
- **Charts**: Chart.js + Vue-Chartjs (Analytics visualization)
- **PDF Generation**: jsPDF
- **HTML to Image**: html2canvas, html-to-image
- **Notifications**: SweetAlert2 + Vue-SweetAlert2
- **Type Definitions**: Full TypeScript support with @types packages
- **Utilities**: Lodash, UUID, VueUse Core
- **Input Components**: Vue Input OTP
- **Animation**: tw-animate-css

### Development Tools
- **Build Tool**: Vite 7+
- **Package Manager**: npm
- **Code Quality**: ESLint, Prettier
- **Type Checking**: TypeScript, vue-tsc

---

## 📋 System Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (or MySQL/PostgreSQL if preferred)
- Mail server credentials (SMTP)

---

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/theCoder505/mbstu_science_club.git
cd mbstu_science_club
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file and configure:
```env
APP_NAME="Your Club Name"
APP_URL=http://localhost

DB_CONNECTION=sqlite
# For SQLite, ensure database/database.sqlite exists

MAIL_DRIVER=sendmail
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@domain.com
MAIL_MAILER=smtp
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Database Setup
```bash
touch database/database.sqlite  # For SQLite
php artisan migrate
php artisan db:seed  # If seeders are available
```

### 6. Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## 🚀 Quick Start Guide

### First-Time Setup Checklist

- [ ] Clone repository and install dependencies
- [ ] Configure `.env` file with database and mail settings
- [ ] Run migrations to create database tables
- [ ] Create storage symlink
- [ ] Build frontend assets
- [ ] Start development server
- [ ] Access admin panel and configure website settings
- [ ] Create first advisor profile
- [ ] Upload certificate templates
- [ ] Test certificate application workflow

### Default Access
```
Admin Panel: http://localhost:8000/login
Advisor Panel: http://localhost:8000/advisor/login
```

> **Note**: You'll need to seed the database or manually create the first admin user.

---

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AdminProfileController.php
│   │   │   ├── AdminWebTasksController.php
│   │   │   ├── AdvisorActionsController.php
│   │   │   ├── AdvisorController.php
│   │   │   ├── ApplicationController.php
│   │   │   ├── Committee.php
│   │   │   ├── FAQController.php
│   │   │   ├── GalleryController.php
│   │   │   ├── TeamController.php
│   │   │   ├── TemplateController.php
│   │   │   └── WebPagesController.php
│   │   └── Middleware/
│   │       ├── AdvisorMiddleware.php
│   │       ├── HandleInertiaRequests.php
│   │       └── StatisticsMeasure.php
│   ├── Models/
│   │   ├── Apply.php
│   │   ├── Advisor.php
│   │   ├── Template.php
│   │   ├── Team.php
│   │   ├── WebSettings.php
│   │   └── Statistic.php
│   └── Providers/
│       └── AppServiceProvider.php
├── resources/
│   ├── js/
│   │   ├── app.ts
│   │   ├── Pages/
│   │   └── Components/
│   └── views/
├── routes/
│   ├── web.php
│   └── settings.php
├── database/
│   └── migrations/
├── public/
└── storage/
```

---

## 🔑 Key Models

### Apply Model
Certificate application with fields:
- Applicant information (name, email, designation)
- Membership period (member_since, member_till)
- Impact description
- Certificate data (text, status, template_id, positions, file)
- Admin notes and issue date

### Advisor Model
Professor/Moderator profiles with:
- Personal information (name, email, password)
- Professional details (department, designation)
- Social links (Facebook, LinkedIn, ResearchGate, Google Scholar)
- Signature and image
- Role (Advisor/Moderator)

### Template Model
Certificate templates with:
- Template name
- Template image file

### Team Model
Club members with:
- Profile image and personal info
- Department and designation
- Membership period
- Short and detailed descriptions
- Social media links

---

## 🔐 Authentication & Authorization

### Admin Authentication
- Standard Laravel authentication
- Access to full admin panel
- Protected by `auth` middleware

### Advisor Authentication
- Custom guard: `advisor`
- Separate login portal: `/advisor/login`
- Protected by `advisor_auth` middleware
- Credentials managed by admin

### Middleware
1. **HandleInertiaRequests**: Shares auth state and flash messages
2. **AdvisorMiddleware**: Protects advisor routes
3. **StatisticsMeasure**: Records page view statistics
4. **HandleAppearance**: Manages dark/light mode preferences

---

## 📧 Email Notifications

The system sends automated emails for:
- Certificate application submission (to admin)
- Certificate verification (to advisor)
- Certificate approval (to applicant with certificate attachment)
- Certificate declined/revision (to applicant with notes)
- Contact form submissions (OTP verification required)
- Admin email/password change (OTP verification)

---

## 📊 Analytics & Statistics

The platform tracks:
- Total advisors, team members, committees
- Total applications, gallery images, FAQs
- Page view statistics (daily, weekly, monthly, yearly, overall)
- Bar chart visualization in admin dashboard
- Supports Google AdSense integration planning

---

## 🎨 Certificate Template Designer

Built-in canvas-based designer with features:
- Add images/logos
- Draw shapes (rectangles, circles, lines)
- Add and style text
- Rotate and resize elements
- Layer management (bring forward/send backward)
- Export to image
- Upload custom templates from external sources

---

## 🎯 TypeScript Integration

<div align="center">

![TypeScript](https://img.shields.io/badge/TypeScript-5.2+-3178C6?style=for-the-badge&logo=typescript&logoColor=white)

**Full type safety across the entire Vue.js frontend**

</div>

### Why TypeScript?

The entire frontend is built with TypeScript to provide:

| Feature | Benefit |
|---------|---------|
| 🔒 **Type Safety** | Catch errors during development, not production |
| 🧠 **Better IDE Support** | Enhanced autocomplete, IntelliSense, and code navigation |
| 📖 **Self-Documenting** | Code documents itself with explicit type definitions |
| 🔄 **Refactoring Confidence** | Safe and reliable code refactoring |
| ⚡ **Modern JavaScript** | Use latest ES features with full type checking |
| 🐛 **Fewer Bugs** | Many runtime errors caught at compile time |

### Type Definitions Included

All major libraries have full type support:

```typescript
// Full type safety for Vue components
import { defineComponent, ref, computed } from 'vue'
import type { PropType } from 'vue'

// Type-safe Inertia props
import { router } from '@inertiajs/vue3'
import type { PageProps } from '@/types'

// Canvas manipulation with types
import { fabric } from 'fabric'
import type { Canvas, Object as FabricObject } from 'fabric'

// 3D graphics with full typing
import * as THREE from 'three'
import type { Scene, Camera, Renderer } from 'three'

// Form validation and utilities
import { useForm } from '@inertiajs/vue3'
import type { InertiaForm } from '@inertiajs/vue3'
```

### Available Type Packages

- `@types/node` - Node.js core modules
- `@types/fabric` - Fabric.js canvas library
- `@types/html2canvas` - HTML to canvas conversion
- `@types/three` - Three.js 3D graphics
- `@types/uuid` - UUID generation
- `@types/lodash` - Utility functions
- `@vue/eslint-config-typescript` - ESLint TypeScript config

### TypeScript Configuration

The project uses strict TypeScript settings for maximum safety:

```json
{
  "compilerOptions": {
    "strict": true,
    "noImplicitAny": true,
    "strictNullChecks": true,
    "strictFunctionTypes": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true
  }
}
```

### Type Checking Commands

```bash
# Type check without emitting files
npx vue-tsc --noEmit

# Watch mode for continuous type checking
npx vue-tsc --noEmit --watch

# Type check with Vite build
npm run build
```

---

## 🌐 Three.js Integration

<div align="center">

![Three.js](https://img.shields.io/badge/Three.js-0.182.0-000000?style=for-the-badge&logo=three.js&logoColor=white)

**Bringing 3D graphics and interactive visualizations to the platform**

</div>

### What is Three.js?

Three.js is a powerful JavaScript 3D library that makes WebGL accessible. Version 0.182.0 is integrated into the platform for creating immersive 3D experiences.

### Current & Potential Use Cases

<table>
<tr>
<td width="50%">

#### 🎨 **Current Features**
- Interactive 3D certificate previews
- Animated transitions and effects
- 3D gallery presentations
- Visual enhancements for UI elements

</td>
<td width="50%">

#### 🚀 **Future Possibilities**
- Virtual club tours (3D spaces)
- Interactive event timelines
- 3D data visualizations
- Immersive project showcases

</td>
</tr>
</table>

### Three.js Capabilities

```typescript
// Example: Creating a 3D scene
import * as THREE from 'three'

// Scene setup
const scene = new THREE.Scene()
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000)
const renderer = new THREE.WebGLRenderer({ antialias: true })

// Add objects
const geometry = new THREE.BoxGeometry()
const material = new THREE.MeshPhongMaterial({ color: 0x00ff00 })
const cube = new THREE.Mesh(geometry, material)
scene.add(cube)

// Add lighting
const light = new THREE.DirectionalLight(0xffffff, 1)
light.position.set(5, 5, 5)
scene.add(light)

// Animation loop
function animate() {
  requestAnimationFrame(animate)
  cube.rotation.x += 0.01
  cube.rotation.y += 0.01
  renderer.render(scene, camera)
}
animate()
```

### Key Features Available

| Feature | Description |
|---------|-------------|
| 🎭 **Scenes** | Create and manage 3D environments |
| 📷 **Cameras** | Multiple camera types (Perspective, Orthographic) |
| 💡 **Lighting** | Ambient, Directional, Point, Spot lights |
| 🎨 **Materials** | Various material types (Basic, Phong, Standard, Physical) |
| 📐 **Geometries** | Built-in shapes and custom geometry creation |
| 🎬 **Animation** | Keyframe animations and morph targets |
| 🖱️ **Controls** | OrbitControls, TrackballControls (via CDN) |
| 🎯 **Raycasting** | Interactive object selection |
| 📦 **Loaders** | Import 3D models (GLTF, OBJ, FBX) |

### Integration with Vue.js & TypeScript

```typescript
// Vue component with Three.js
import { defineComponent, onMounted, ref } from 'vue'
import * as THREE from 'three'

export default defineComponent({
  name: 'ThreeScene',
  setup() {
    const canvasRef = ref<HTMLCanvasElement>()
    
    onMounted(() => {
      if (!canvasRef.value) return
      
      const scene = new THREE.Scene()
      const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight)
      const renderer = new THREE.WebGLRenderer({ canvas: canvasRef.value })
      
      // Full TypeScript support
      const geometry: THREE.BoxGeometry = new THREE.BoxGeometry()
      const material: THREE.Material = new THREE.MeshBasicMaterial({ color: 0x00ff00 })
      const mesh: THREE.Mesh = new THREE.Mesh(geometry, material)
      
      scene.add(mesh)
      camera.position.z = 5
      
      const animate = () => {
        requestAnimationFrame(animate)
        mesh.rotation.x += 0.01
        mesh.rotation.y += 0.01
        renderer.render(scene, camera)
      }
      animate()
    })
    
    return { canvasRef }
  }
})
```

### Performance Optimization

Three.js in this project is optimized for:
- ✅ Efficient memory management
- ✅ Lazy loading of 3D assets
- ✅ Responsive canvas resizing
- ✅ Frame rate optimization
- ✅ Progressive enhancement (fallback for older browsers)

### Example Use Cases in Platform

#### 1. **Certificate Preview 3D**
Display certificates in an interactive 3D space where users can rotate and zoom.

#### 2. **Gallery Visualization**
Create an immersive 3D gallery where images are displayed on virtual walls.

#### 3. **Data Visualization**
Transform analytics data into engaging 3D charts and graphs.

#### 4. **Event Timeline**
Build an interactive 3D timeline showing club events and milestones.

### Learning Resources

- [Three.js Documentation](https://threejs.org/docs/)
- [Three.js Examples](https://threejs.org/examples/)
- [Three.js Journey](https://threejs-journey.com/)

---

## 🌐 Routes Overview

### Public Routes
- `/` - Homepage
- `/about-us` - About the club
- `/application` - Apply for certificate
- `/track-certificate` - Track application status
- `/teammate/{id}/{name}` - Member profile
- `/privacy-policy`, `/terms-and-conditions`, `/code-of-conduct`

### Admin Routes (Protected)
- `/dashboard` - Admin dashboard
- `/applications` - Certificate applications
- `/advisors-and-moderators` - Manage advisors
- `/manage-team` - Manage team members
- `/our-committee` - Committee management
- `/templates` - Certificate templates
- `/gallery` - Gallery management
- `/faqs` - FAQ management
- `/website-settings` - Website configuration

### Advisor Routes (Protected)
- `/advisor/login` - Advisor login
- `/advisor/dashboard` - Advisor dashboard
- `/advisor/profile` - Advisor profile management

---

## 🔧 Configuration

### Email Settings (`.env`)
```env
MAIL_DRIVER=sendmail
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email
```

### Database
SQLite is used by default. To switch to MySQL/PostgreSQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

---

## 🎯 Usage Guide

### For Administrators

#### Setting Up the Website
1. Login to admin panel
2. Navigate to "Website Settings"
3. Configure:
   - President name and signature
   - Social media links
   - About Us, Mission, Vision, Values
   - Privacy Policy, Terms, Constitution
   - Member joining form link
   - Contact information

#### Managing Certificate Applications
1. Go to "Applications"
2. Review application details
3. Customize certificate text (bold, italic, underline)
4. Select template and adjust positions
5. Set status: Verified (sends to advisor), Declined, or In Revision
6. Add notes for applicant feedback

#### Creating Certificate Templates
1. Navigate to "Templates"
2. Click "Design A Template"
3. Use canvas tools to create design
4. Download and save locally
5. Upload as new template with name
6. Or upload templates from external sources

#### Managing Advisors
1. Go to "Advisors and Moderators"
2. Add advisor with credentials
3. Assign role (Advisor/Moderator)
4. Share credentials with professor
5. Advisors can update their own profiles

### For Advisors

#### Logging In
1. Visit `/advisor/login`
2. Use credentials provided by admin
3. Access dashboard

#### Approving Certificates
1. Receive email notification
2. Click link in email (auto-login if already logged in)
3. Review certificate details
4. Click "Approve" button
5. Certificate automatically sent to applicant

#### Managing Profile
1. Go to "Profile"
2. Update signature, image
3. Add social links (LinkedIn, ResearchGate, etc.)
4. Change email/password (no OTP required)

### For Members (Public Users)

#### Applying for Certificate
1. Visit "Apply for Certificate"
2. Fill in details:
   - Name, email
   - Designation
   - Membership period
   - Impact/contributions
3. Submit application
4. Receive email confirmation

#### Tracking Certificate
1. Visit "Track Certificate"
2. Enter email address
3. View status:
   - Pending
   - Verified (sent to advisor)
   - Approved (download available)
   - Declined/In Revision (see notes)
4. Download certificate if approved
5. Resubmit if in revision

---

## 🔒 Security Features

- OTP verification for admin email/password changes (10-minute expiration)
- OTP verification for contact form (prevents spam)
- Separate authentication guards for admin and advisors
- CSRF protection on all forms
- Encrypted cookies (except appearance and sidebar state)
- SQL injection protection via Eloquent ORM
- XSS protection via Laravel's blade templating

---

## 📈 Future Enhancements

Potential features for future versions:
- Multi-club SaaS deployment with subdomain management
- Payment integration for premium features
- Event management and RSVP system
- Member login portal for certificate history
- Automated certificate renewal reminders
- Integration with university databases
- Mobile app for iOS and Android
- Advanced analytics with Google Analytics integration
- SEO optimization tools
- Multi-language support

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards
- Follow PSR-12 for PHP code
- Use ESLint and Prettier for Vue/TypeScript code
- Write descriptive commit messages
- Add comments for complex logic
- Update documentation for new features

---

## 🐛 Bug Reports & Feature Requests

Please use the [GitHub Issues](https://github.com/theCoder505/mbstu_science_club/issues) page to report bugs or request features.

When reporting bugs, include:
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots (if applicable)
- Browser and OS information

---

## 📝 Development Commands

### Server Management
```bash
# Start Laravel development server
php artisan serve

# Start on custom port
php artisan serve --port=8080
```

### Asset Compilation
```bash
# Development mode with hot reload
npm run dev

# Production build (optimized)
npm run build

# Build with SSR support
npm run build:ssr
```

### Database Management
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration (drop all tables)
php artisan migrate:fresh

# Run seeders
php artisan db:seed
```

### Cache Management
```bash
# Clear all caches
php artisan optimize:clear

# Clear specific caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
```

### Code Quality
```bash
# Format code with Prettier
npm run format

# Check formatting
npm run format:check

# Lint and fix code
npm run lint

# TypeScript type checking
npx vue-tsc --noEmit
```

### Storage
```bash
# Create symbolic link for storage
php artisan storage:link

# Clear storage logs
php artisan log:clear
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).

---

## 👥 Authors

- **Developer**: [theCoder505](https://github.com/theCoder505)
- **Organization**: Mawlana Bhashani Science & Technology University Science Club

---

## 🙏 Acknowledgments

- Laravel Framework
- Vue.js Team
- Inertia.js
- Tailwind CSS
- All open-source contributors

---

## 📞 Support

For support and inquiries:
- **Email**: Contact via the platform's contact form
- **Website**: [https://mbstusc.eksofts.xyz](https://mbstusc.eksofts.xyz)
- **GitHub**: [Issues Page](https://github.com/theCoder505/mbstu_science_club/issues)

---

## 📚 Documentation

For detailed documentation on specific features:
- [Laravel Documentation](https://laravel.com/docs)
- [Vue.js Documentation](https://vuejs.org/guide/)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

<div align="center">

## 🌟 Built With Modern Technologies

![Laravel](https://img.shields.io/badge/-Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/-Vue.js-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)
![TypeScript](https://img.shields.io/badge/-TypeScript-3178C6?style=for-the-badge&logo=typescript&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/-Tailwind-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Three.js](https://img.shields.io/badge/-Three.js-000000?style=for-the-badge&logo=three.js&logoColor=white)
![Inertia](https://img.shields.io/badge/-Inertia.js-9553E9?style=for-the-badge&logo=inertia&logoColor=white)

---

### Made with ❤️ for Bangladeshi University Clubs

**Empowering clubs with professional portfolios and trustworthy certification systems**

---

### 📞 Get in Touch

[![Website](https://img.shields.io/badge/Website-mbstusc.eksofts.xyz-blue?style=for-the-badge)](https://mbstusc.eksofts.xyz)
[![GitHub](https://img.shields.io/badge/GitHub-theCoder505-181717?style=for-the-badge&logo=github)](https://github.com/theCoder505)
[![Issues](https://img.shields.io/badge/Report-Issues-red?style=for-the-badge&logo=github)](https://github.com/theCoder505/mbstu_science_club/issues)

---

**© 2025 MBSTU Science Club Portfolio Platform**

*Licensed under MIT License*

</div>