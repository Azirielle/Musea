# Project Code Overview

This document provides a high-level overview of the **Musea** codebase, including its directory structure, technology stack, and key modules. It is designed to help developers navigate and understand the application architecture.

## Technology Stack

-   **Backend**: Laravel (PHP)
-   **Frontend**: Vue.js 3 with Inertia.js
-   **Database**: MySQL
-   **Styling**: Tailwind CSS
-   **Build Tool**: Vite

## Project Structure

The project follows a standard Laravel structure with Inertia.js modifications.

### Key Directories

-   **`app/`**: Contains the core backend logic.
    -   `Http/Controllers/`: Handles incoming requests.
    -   `Models/`: Eloquent ORM classes representing database tables.
    -   `Enums/`: PHP Enums for fixed values (e.g., `ArtworkCategory`).
-   **`resources/js/`**: Contains the Vue.js frontend application.
    -   `Pages/`: Views corresponding to routes (Inertia pages).
    -   `Components/`: Reusable Vue components.
    -   `Layouts/`: Main page structures (Navigation, Footers).
-   **`routes/`**: Defines URL routes.
    -   `web.php`: Primary route file for both Public and Admin interfaces.
-   **`database/`**: Database management.
    -   `migrations/`: Schema definitions.
    -   `seeders/`: Dummy data generators.

## Core Modules & Features

### 1. Authentication & Roles
The application has two distinct user types managed via Multi-Port setup (see `README.md`):
-   **Public (Port 8000)**: Artists and Buyers. Uses `User` model.
-   **Admin (Port 8001)**: Administrators. Uses `Admin` model.

### 2. Shop & Artworks
-   **Model**: `Artwork`
-   **Controller**: `ShopController`, `ArtworkController` (Artist Dashboard)
-   **Key Features**:
    -   Browsing & Filtering (Category, Price, Size).
    -   Search (Artworks and Artists).
    -   Artist Profile Pages.

### 3. E-commerce & Checkout
-   **Models**: `Cart`, `Order`, `OrderItem`, `Coupon`
-   **Controllers**: `CartController`, `CheckoutController`, `OrderController`
-   **Flow**: Users add items to session-based cart -> Checkout (Payment Simulation) -> Order creation.

### 4. Social & Interaction
-   **Models**: `Review`, `JournalPost`, `Conversation`, `ChatMessage`, `Report`
-   **Features**:
    -   **Messaging**: Real-time chat between Buyers and Artists.
    -   **Journal**: Blog-like updates for Artists.
    -   **Reviews**: Ratings and comments on Artworks.
    -   **Reporting**: Users can report inappropriate content.

### 5. Artist Finance & Wallet
-   **Models**: `WithdrawalRequest`
-   **Controllers**: `WalletController`, `Admin/WithdrawalController`
-   **Features**:
    -   **Balance**: Tracked on `User` model (`balance` column).
    -   **Withdrawals**: Artists request payout (GCash/Bank) -> Admins Approve/Reject.

### 6. Admin Panel
-   **Namespace**: `App\Http\Controllers\Admin\`
-   **Features**:
    -   **Approvals**: Review and approve newly submitted artworks.
    -   **Withdrawals**: Manage payout requests.
    -   **User Management**: Suspend/Verify users.
    -   **Reports**: Resolve reported content.

## Database Schema Highlights

| Model | Key Responsibilities |
| :--- | :--- |
| **User** | Authentication, Profile, Balance, Role (Artist/Member) |
| **Admin** | Admin Panel Authentication |
| **Artwork** | Title, Price, Image, Status (Pending/Active/Sold) |
| **Order** | Transaction record, Shipping Status |
| **WithdrawalRequest** | Finance requests from Artists |
| **Conversation** | Chat threads between two users |

## Development Workflows

-   **Routing**: Defined in `routes/web.php`. Admin routes are grouped under `admin.` name prefix and `/admin` URL prefix.
-   **Validation**: Performed in Controllers (e.g., `WithdrawalController` verifies balance).
-   **Frontend State**: Managed via Inertia Props passed from Controllers.
