# Graph Report - Find&Found  (2026-08-30)

## Corpus Check
- 60 files · ~251,319 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 97 nodes · 164 edges · 14 communities (10 shown, 4 thin omitted)
- Extraction: 68% EXTRACTED · 32% INFERRED · 0% AMBIGUOUS · INFERRED: 52 edges (avg confidence: 0.89)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Admin UI & Item Workflow
- Lo-Fi Wireframes (Public & User)
- Tech Stack & PRD Core
- Database Schema & Moderation
- User Authentication & Hi-Fi User Screens
- Platform Identity & Design System
- Survey Research & Feature Requirements
- Backend Architecture & API
- Survey Raw Data
- Obsidian Vault
- About Page Wireframe
- Profile Page Wireframe
- README

## God Nodes (most connected - your core abstractions)
1. `PRD & Arsitektur Sistem Find & Found v2.1` - 27 edges
2. `Item Entity (Lost/Found Item Data Model)` - 8 edges
3. `Authenticated User Sidebar Navigation` - 8 edges
4. `PRD Find & Found (Ringkas / Concise Version)` - 7 edges
5. `Admin Panel (System Management Interface)` - 7 edges
6. `Find & Found Survey Analysis (50 Respondents)` - 6 edges
7. `Decoupled SPA Architecture (Vue 3 + Laravel 12 REST API)` - 6 edges
8. `Vue.js 3 Frontend (Composition API, SPA)` - 6 edges
9. `Item Management (Lost/Found Items CRUD)` - 6 edges
10. `Claim Workflow (Submit, Review, Approve/Reject)` - 6 edges

## Surprising Connections (you probably didn't know these)
- `Product Requirement Document v1.0 (TemuBarang)` --semantically_similar_to--> `PRD & Arsitektur Sistem Find & Found v2.1`  [INFERRED] [semantically similar]
  Product Requirement Document.md → 4.1_Tugas_Perancangan_Find_and_Found_PRD.md
- `Hi-Fi Wireframe: Admin Claims (/admin/claims)` --conceptually_related_to--> `Ownership Verification & Claim System`  [INFERRED]
  High-fidelity wireframe/Admin_ Claims (/admin/claims) - Hi-Fi.png → 4.1_Tugas_Perancangan_Find_and_Found_PRD.md
- `PRD & Arsitektur Sistem Find & Found v2.1` --semantically_similar_to--> `PRD Find & Found (Ringkas / Concise Version)`  [INFERRED] [semantically similar]
  4.1_Tugas_Perancangan_Find_and_Found_PRD.md → PRD_Find_and_Found_Ringkas.md
- `Hi-Fi Wireframe: Admin Categories (/admin/categories)` --conceptually_related_to--> `Priority Document Badge (KTP/SIM/Paspor High Priority)`  [INFERRED]
  High-fidelity wireframe/Admin_ Categories (/admin/categories) - Hi-Fi.png → 4.1_Tugas_Perancangan_Find_and_Found_PRD.md
- `Hi-Fi Wireframe: Admin Dashboard (/admin/dashboard)` --implements--> `Find & Found Design System (Colors, Typography, Components)`  [INFERRED]
  High-fidelity wireframe/Admin_ Dashboard (/admin/dashboard) - Hi-Fi.png → 4.1_Tugas_Perancangan_Find_and_Found_PRD.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Survey Research → Feature Requirements → Platform Implementation Pipeline** — survey_50_respondents, find_and_found_md_survey_analysis, prd_41_find_and_found, find_and_found_platform [EXTRACTED 1.00]
- **Relational Database Schema (users, items, claims, categories, item_photos)** — db_users_table, db_items_table, db_claims_table, db_categories_table, db_item_photos_table [EXTRACTED 1.00]
- **Claim Verification → Approval → WhatsApp/Instagram Handoff Flow** — ownership_verification_concept, whatsapp_instagram_handoff, reward_reputation_concept [EXTRACTED 1.00]
- **Claim Management Screens (User + Admin)** — high_fidelity_wireframe_user_my_claims_my_claims_hi_fi, high_fidelity_wireframe_user_incoming_claims_incoming_claims_hi_fi, low_fidelity_wireframe_admin_claims_admin_claims [INFERRED 0.95]
- **Report Submission Forms (Lost & Found)** — high_fidelity_wireframe_user_report_found_report_found_hi_fi, high_fidelity_wireframe_user_report_lost_report_lost_hi_fi, high_fidelity_wireframe_user_my_reports_my_reports_hi_fi [INFERRED 0.95]
- **Admin Management Screens** — high_fidelity_wireframe_admin_items_admin_items_hi_fi, high_fidelity_wireframe_admin_users_admin_users_hi_fi, low_fidelity_wireframe_admin_categories_admin_categories, low_fidelity_wireframe_admin_claims_admin_claims, low_fidelity_wireframe_admin_dashboard_admin_dashboard [INFERRED 0.95]
- **Authenticated User Pages sharing Sidebar Navigation** — low_fidelity_wireframe_user_dashboard_dashboard, low_fidelity_wireframe_user_my_reports_my_reports, low_fidelity_wireframe_user_my_claims_my_claims, low_fidelity_wireframe_user_incoming_claims_incoming_claims, low_fidelity_wireframe_user_notifications_notifications, low_fidelity_wireframe_user_profile_profile, low_fidelity_wireframe_user_report_found_report_found, low_fidelity_wireframe_user_report_lost_report_lost [EXTRACTED 1.00]
- **Public Pages sharing Public Navigation Bar** — low_fidelity_wireframe_public_home, low_fidelity_wireframe_public_about_about, low_fidelity_wireframe_public_explore_explore, low_fidelity_wireframe_public_item_detail_items_id, low_fidelity_wireframe_public_login_login, low_fidelity_wireframe_public_register_register, low_fidelity_wireframe_public_priority_documents_priority_documents [EXTRACTED 1.00]
- **Admin Management Pages sharing Admin Panel Layout** — low_fidelity_wireframe_admin_items_adminitems, low_fidelity_wireframe_admin_users_adminusers, concept_admin_panel [EXTRACTED 1.00]

## Communities (14 total, 4 thin omitted)

### Community 0 - "Admin UI & Item Workflow"
Cohesion: 0.17
Nodes (22): Admin Panel (System Management Interface), Claim Workflow (Submit, Review, Approve/Reject), Item Category (Classification Taxonomy), Item Management (Lost/Found Items CRUD), Report Submission (Lost & Found Forms), User Dashboard (Personal Activity Hub), Hi-Fi Wireframe: Admin Items (/admin/items), Hi-Fi Wireframe: Public Explore (/explore) (+14 more)

### Community 1 - "Lo-Fi Wireframes (Public & User)"
Cohesion: 0.26
Nodes (17): Claim Entity (Ownership Claim Data Model), Item Entity (Lost/Found Item Data Model), Notification System, Public Navigation Bar, Authenticated User Sidebar Navigation, Public: About Page (\about), Public: Explore Page (\explore), Public: Home Page (\) (+9 more)

### Community 2 - "Tech Stack & PRD Core"
Cohesion: 0.25
Nodes (11): Job Sheet: PRD, Arsitektur & Design System Web, Leaflet.js / OpenStreetMap Integration, Pinia Store: useAuthStore, Pinia Store: useClaimStore, Pinia Store: useItemStore, PRD & Arsitektur Sistem Find & Found v2.1, User Persona: Administrator Sistem / Pengelola Wilayah, User Persona: Penemu Barang (Finder/Good Samaritan/Satpam) (+3 more)

### Community 3 - "Database Schema & Moderation"
Cohesion: 0.22
Nodes (9): Admin Panel Moderation (FR-12), Database Table: categories, Database Table: claims, Database Table: item_photos, Database Table: items, Database Table: users, Hi-Fi Wireframe: Admin Categories (/admin/categories), Hi-Fi Wireframe: Admin Claims (/admin/claims) (+1 more)

### Community 4 - "User Authentication & Hi-Fi User Screens"
Cohesion: 0.28
Nodes (9): User Authentication (Login/Register), User Entity (User Account Data Model), Hi-Fi Wireframe: Admin Users (/admin/users), Hi-Fi Wireframe: Public Login (/login), Hi-Fi Wireframe: Public Register (/register), Admin: Users Page (\admin\users), Public: Login Page (\login), Public: Register Page (\register) (+1 more)

### Community 5 - "Platform Identity & Design System"
Cohesion: 0.40
Nodes (6): Find & Found Design System (Colors, Typography, Components), Find & Found Platform, Find & Found Landing Page (HTML), Product Requirement Document v1.0 (TemuBarang), Stitch Prompts for Find & Found (UI/UX, Architecture, Wireframes), TemuBarang (Legacy Name / v1 PRD)

### Community 6 - "Survey Research & Feature Requirements"
Cohesion: 0.40
Nodes (6): Find & Found Survey Analysis (50 Respondents), Ownership Verification & Claim System, PRD Find & Found (Ringkas / Concise Version), Priority Document Badge (KTP/SIM/Paspor High Priority), Finder Reward & Reputation System (Honest Finder Badge), Smart Match Alert (FR-11) – Automated Item Matching Notification

### Community 7 - "Backend Architecture & API"
Cohesion: 0.40
Nodes (5): Find & Found — Arsitektur Sistem Diagram (HTML/Archify), Decoupled SPA Architecture (Vue 3 + Laravel 12 REST API), Laravel 12 Backend REST API (PHP 8.3+), Laravel Sanctum Token-Based Authentication, External Handoff via WhatsApp/Instagram (Deep Link) Pattern

### Community 8 - "Survey Raw Data"
Cohesion: 0.50
Nodes (5): Find & Found Survey Responses (Excel/Table), Find & Found Survey Raw Responses (Text), Find & Found Survey Responses (Graphify Converted), Kabupaten Pati & Sekitarnya (Target User Area), Survey 50 Respondents Research Data

## Knowledge Gaps
- **14 isolated node(s):** `Job Sheet: PRD, Arsitektur & Design System Web`, `Find & Found Survey Responses (Graphify Converted)`, `README – Find-Found`, `Find & Found — Arsitektur Sistem Diagram (HTML/Archify)`, `F&F Vault Welcome Note (Obsidian)` (+9 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **4 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Admin Panel (System Management Interface)` connect `Admin UI & Item Workflow` to `User Authentication & Hi-Fi User Screens`?**
  _High betweenness centrality (0.145) - this node is a cross-community bridge._
- **Why does `PRD & Arsitektur Sistem Find & Found v2.1` connect `Tech Stack & PRD Core` to `Database Schema & Moderation`, `Platform Identity & Design System`, `Survey Research & Feature Requirements`, `Backend Architecture & API`, `Survey Raw Data`?**
  _High betweenness centrality (0.141) - this node is a cross-community bridge._
- **Why does `Admin: Items Page (\admin\items)` connect `Admin UI & Item Workflow` to `Lo-Fi Wireframes (Public & User)`?**
  _High betweenness centrality (0.088) - this node is a cross-community bridge._
- **Are the 2 inferred relationships involving `PRD & Arsitektur Sistem Find & Found v2.1` (e.g. with `PRD Find & Found (Ringkas / Concise Version)` and `Product Requirement Document v1.0 (TemuBarang)`) actually correct?**
  _`PRD & Arsitektur Sistem Find & Found v2.1` has 2 INFERRED edges - model-reasoned connections that need verification._
- **Are the 4 inferred relationships involving `Admin Panel (System Management Interface)` (e.g. with `Hi-Fi Wireframe: Admin Items (/admin/items)` and `Hi-Fi Wireframe: Admin Users (/admin/users)`) actually correct?**
  _`Admin Panel (System Management Interface)` has 4 INFERRED edges - model-reasoned connections that need verification._
- **What connects `Job Sheet: PRD, Arsitektur & Design System Web`, `Find & Found Survey Responses (Graphify Converted)`, `README – Find-Found` to the rest of the system?**
  _14 weakly-connected nodes found - possible documentation gaps or missing edges._