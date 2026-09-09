# Stitch Prompts for Find & Found (Web App)

These prompts are ready to be used in Stitch by Google to generate assets for the Find & Found project.

---

## 1. Medium-Fidelity Wireframes Prompt

**Prompt:**
> Please generate a complete medium-fidelity wireframe set for a web application named "Find & Found", a centralized platform for reporting and claiming lost and found items. 
> 
> **Target Audience:** High school students, university students, and the general public (aged 15-20) in Pati Regency, Indonesia. They are mobile-first users who need quick, intuitive interactions.
> 
> **Core Features to Include:**
> 1. Form to report lost/found items with photo upload and location pinning (map).
> 2. Search & Explore feed with dynamic multi-filters (category, location/district, date).
> 3. Priority highlighting for critical documents (ID cards, driver's licenses, passports).
> 4. Ownership verification claim system (form with secret details and proof).
> 5. Direct communication (In-app chat and WhatsApp direct link button).
> 
> **Screens/Pages Required (Mobile & Desktop layouts):**
> - **Home/Landing Page:** Hero section, quick search bar, and a feed highlighting recently lost items and priority documents.
> - **Explore Feed:** Grid layout of item cards with a robust sidebar/top-bar for filtering.
> - **Item Detail Page:** Large photo view, map location, item description, and a clear "Claim as Mine" or "I Found This" call-to-action button.
> - **Report Item Wizard:** A step-by-step form (Type -> Details -> Photo/Map -> Submit).
> - **User Dashboard:** History of reported items, active claims, and a notification center for matching items.
> 
> **Style/Tone:** Clean, structured, highly readable, focusing on layout and information architecture rather than visual styling. Use standard UI placeholders (boxes, crossed rectangles for images, lorem ipsum for text) with an 8px grid system.

---

## 2. UI/UX Design (High-Fidelity) Prompt

**Prompt:**
> Act as an expert UI/UX Designer. Please design high-fidelity mockups for a web application named "Find & Found" (a lost and found community platform).
> 
> **Target Audience:** Gen-Z students (15-20 years old) and general public in Pati Regency. The design must be mobile-first, highly responsive, and thumb-friendly.
> 
> **Brand & Style Tone:** Trustworthy, helpful, modern, clean, and community-centric.
> - **Colors:** Deep Trust Blue (Primary), Emerald Green (for successful claims/resolved status), Warning Amber (for lost items), and Crimson Red/Soft Pink background (for urgent priority ID documents). Include a WhatsApp Green (#25D366) button.
> - **Typography:** 'Plus Jakarta Sans' or 'Inter' (modern sans-serif, clean hierarchy).
> - **Design System:** Use rounded corners (12px-16px for cards/modals), soft elevation shadows for depth, and clean whitespace based on an 8px grid.
> 
> **Screens Required:**
> 1. **Explore Feed (Mobile & Desktop):** Show a modern grid of item cards. Each card should have a 4:3 image, a status badge (LOST/FOUND), a special red badge for "KTP/SIM" if it's a priority document, location name, and relative time. Include a sticky filter bar on mobile.
> 2. **Item Detail & Claim Modal (Mobile):** Show the item detail screen with a map integration (Leaflet style) and an open bottom-sheet modal where a user is filling out secret details to prove ownership.
> 3. **Chat Room / Success State:** A chat interface showing the finder and owner coordinating a meetup, featuring a prominent "Contact via WhatsApp" action button and a "Give Reward/Badge" prompt upon success.
> 
> Please provide the designs in a way that can be easily translated into Tailwind CSS utility classes and Vue 3 components.

---

## 3. User Flow Visual/Diagram Prompt

**Prompt:**
> Please generate a clear, visually appealing User Flow diagram for the main "Report, Claim, and Handover" journey in the "Find & Found" web application.
> 
> **Target Audience Context:** Tech-savvy students who want a fast, secure way to get their lost items back without a complicated bureaucratic process.
> 
> **Flow Requirements:**
> 1. **Start:** User (Owner) logs in -> Clicks "Report Lost Item" -> Fills form, uploads reference photo, pins location -> Submits.
> 2. **Matching/Finding:** Another User (Finder) browses the feed OR gets a "Smart Match Alert" -> Views the lost item detail.
> 3. **Verification:** Finder clicks "I Found This" (or Owner claims a "Found" item) -> Submits proof/secret details -> The other party reviews the proof.
> 4. **Decision Node:** 
>    - If Rejected: Notification sent, flow returns to item page.
>    - If Approved: Contact details (WhatsApp link) and In-App Chat room are unlocked.
> 5. **Resolution:** Both parties chat -> Meet up in person (e.g., at a security post) -> Owner confirms receipt -> Owner gives a "Honest Finder" reward/reputation badge -> Item status changes to RESOLVED.
> 
> **Style:** Use standard flowchart symbols but with modern UI aesthetics (rounded edges, brand colors: Blue, Green, Amber). Make it easy to read for developers and stakeholders.

---

## 4. Application Architecture (System Flow & Vue Components) Prompt

**Prompt:**
> Please generate a technical System Architecture and Component Structure diagram for "Find & Found", a modern SPA (Single Page Application).
> 
> **Product Context:** A decoupled Lost and Found platform catering to students in Pati Regency, focusing on fast performance, real-time filtering, and secure verification.
> 
> **Technical Stack & Architecture:**
> - **Frontend:** Vue 3 (Composition API), Vue Router 4, Pinia (State Management), Tailwind CSS.
> - **Backend:** Laravel 12 (RESTful API), MySQL, Sanctum Authentication.
> 
> **Diagram Requirements:**
> 1. **High-Level System Architecture:** Show the decoupled interaction between the Client (Vue 3 Browser/Mobile Web), the API Gateway (Laravel 12), the Database (MySQL), and external services (WhatsApp Cloud API / Deep links, Leaflet OSM Maps).
> 2. **Frontend Vue Structure Tree:** Visually map out the directory structure covering:
>    - **Pinia Stores:** `useAuthStore`, `useItemStore`, `useClaimStore`, `useChatStore`.
>    - **Router:** Public routes vs Authenticated guarded routes.
>    - **Components Hierarchy:** Show how a page like `ExploreView.vue` is composed of `ItemFilterBar.vue`, `ItemGrid.vue`, and `ItemCard.vue`. Include shared components like `StatusBadge.vue`, `PriorityBadge.vue`, and `WhatsAppDirectBtn.vue`.
> 
> **Style:** Technical but modern. Use box-and-arrow diagrams (or C4 model styling) that clearly delineate the separation of concerns between state, routing, UI components, and backend endpoints.
