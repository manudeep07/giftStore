# Software Requirements Specification

**Project Name:** GiftStore (Premium Custom Gift Platform)
**Course Code:** [Insert Course Code]
**Course Name:** [Insert Course Name]

**Student Names:**
- [Insert Student Name 1]
- [Insert Student Name 2]

**Student Registration Numbers:**
- [Insert Registration Number 1]
- [Insert Registration Number 2]

**Prepared for:** Continuous Assessment 3, Spring 2025

---

## Table of Contents
1. Introduction
   1.1 Purpose
   1.2 Scope
   1.3 Definitions, Acronyms, and Abbreviations
   1.4 References
   1.5 Overview
2. General Description
   2.1 Product Perspective
   2.2 Product Functions
   2.3 User Characteristics
   2.4 General Constraints
   2.5 Assumptions and Dependencies
3. Specific Requirements
   3.1 External Interface Requirements
       3.1.1 User Interfaces
       3.1.2 Hardware Interfaces
       3.1.3 Software Interfaces
       3.1.4 Communications Interfaces
   3.2 Functional Requirements
       3.2.1 Product Browsing and Searching
       3.2.2 Real-time Product Customization
       3.2.3 Shopping Cart and Checkout
   3.5 Non-Functional Requirements
       3.5.1 Performance
       3.5.2 Reliability
       3.5.3 Availability
       3.5.4 Security
       3.5.5 Maintainability
       3.5.6 Portability
   3.7 Design Constraints
   3.9 Other Requirements
4. Analysis Models
   4.1 Data Flow Diagrams (DFD)
5. Github link
6. DEPLOYED LINK
7. CLIENT APPROVAL PROOF
8. CLIENT LOCATION PROOF
9. TRANSACTION ID PROOF
10. EMAIL ACKNOWLEDGEMENT
11. GST No
A. Appendices
   A.1 Appendix 1
   A.2 Appendix 2

---

## 1. Introduction

### 1.1 Purpose
The purpose of this Software Requirements Specification (SRS) is to detail the technical and functional requirements for the **GiftStore** platform. This document serves as a comprehensive guide for the development, design, and testing of the application, ensuring that the final product aligns with the stakeholders' and clients' expectations. The intended audience includes software engineers, designers, project managers, and the evaluating faculty.

### 1.2 Scope
(1) **Software Product Name:** GiftStore (Premium Custom Gift Platform)
(2) **Product Functionality:** The GiftStore is a web-based e-commerce application that allows users to browse, customize, and purchase gifts. It provides a real-time, interactive customization visualizer, enabling users to see immediate visual changes (e.g., personalized text, custom design options) on product images. It will manage user accounts, shopping carts, product inventories, and process orders.
(3) **Application of the Software:**
   (a) The primary goal is to provide a dynamic "live-preview" user experience to increase customer engagement and conversion rates, replacing traditional static form-based interfaces with an interactive visualizer.
   (b) The platform is designed to handle an intuitive product discovery process, secure checkout, and comprehensive order management.

### 1.3 Definitions, Acronyms, and Abbreviations
*   **SRS:** Software Requirements Specification
*   **UI/UX:** User Interface / User Experience
*   **DBMS:** Database Management System
*   **Live-Preview:** A feature allowing users to see their personal customizations applied to a product image in real-time within the browser.
*   **MVC:** Model-View-Controller (Software architectural pattern)

### 1.4 References
*   IEEE Guide to Software Requirements Specifications (Std 830-1998)
*   GiftStore Project Proposal Document (Internal)
*   Laravel Framework Documentation

### 1.5 Overview
The rest of this SRS is organized into the following sections:
*   **Section 2:** Provides a general description of the system, including its perspective, user characteristics, and general constraints.
*   **Section 3:** Details the specific functional and non-functional requirements necessary for the development team, acting as the core blueprint for implementation.
*   **Section 4:** Presents the analysis models (e.g., Data Flow Diagrams) to visualize system operations.
*   **Sections 5-11 & Appendices:** Provide links, administrative proofs, and supplementary materials as required for Continuous Assessment 3.

---

## 2. General Description

### 2.1 Product Perspective
GiftStore is a standalone web application built using the Laravel (PHP) framework, utilizing Blade templates for the frontend and a relational database (e.g., MySQL) for data persistence. It represents a modernization of online gift shops by introducing a dynamic customization layer directly in the user's browser without requiring page reloads.

### 2.2 Product Functions
The application is divided into two distinct portals to serve its user base effectively:

**User Portal:**
*   **User Management:** Customer registration, secure login, profile management, and saving shipping addresses.
*   **Product Catalog:** Browsing products by category, viewing detailed descriptions, prices, and base images.
*   **Real-time Customization:** An interactive visualizer allowing users to input text, choose colors/fonts, and instantly see the result overlaid on the product image.
*   **Shopping Cart & Checkout:** Managing selected items, calculating subtotals/taxes, and processing orders securely.
*   **Order Tracking:** Viewing past order history and checking the real-time fulfillment status of current orders.

**Admin Portal:**
*   **Dashboard & Analytics:** A high-level overview of total sales, user sign-ups, and popular custom products.
*   **Order Management:** Viewing comprehensive order details including quantities, customer location details, and updating fulfillment statuses (e.g., Pending, Processing, Shipped).
*   **Inventory & Stock Control:** Tracking available stock for each base product and marking items as out-of-stock when necessary.
*   **Catalog Management:** Adding new products, categories, base images, and setting pricing.
*   **User Moderation:** Viewing customer profiles and handling customer support inquiries.

### 2.3 User Characteristics
*   **Customers:** Everyday web users looking for personalized gifts. They require an intuitive, responsive, and visually appealing interface without needing technical expertise.
*   **Administrators:** Store managers who need a secure, easy-to-navigate dashboard to manage the store's day-to-day operations.

### 2.4 General Constraints
*   **Web-Based:** The application must be accessible via modern web browsers (Chrome, Firefox, Safari, Edge).
*   **Responsiveness:** The user interface must be fully responsive, functioning seamlessly on desktops, tablets, and mobile devices.
*   **Technology Stack:** Must utilize the Laravel ecosystem (PHP, Blade) as per the existing project structure.

### 2.5 Assumptions and Dependencies
*   **Assumption:** Users have access to a stable internet connection.
*   **Dependency:** Reliance on external payment gateways (e.g., Stripe, Razorpay) for transaction processing.
*   **Dependency:** The hosting environment must support PHP 8.x and the selected database system.

---

## 3. Specific Requirements

### 3.1 External Interface Requirements

#### 3.1.1 User Interfaces
*   The system shall provide a modern, aesthetically pleasing frontend built with Blade templates, HTML, CSS, and JavaScript.
*   The interactive customizer shall reflect user input on the product image instantly.
*   Validation feedback and system alerts (e.g., "Item added to cart") shall be clearly displayed.

#### 3.1.2 Hardware Interfaces
*   The system shall not require any specialized hardware from the end-user other than a standard device (PC, smartphone) with a web browser.

#### 3.1.3 Software Interfaces
*   **Database:** The application shall interface with a relational database to store user, product, and order data securely.
*   **Payment Gateway:** The application shall integrate with an external payment API for processing financial transactions.

#### 3.1.4 Communications Interfaces
*   The system shall use standard HTTP/HTTPS protocols for communication between the client browser and the server.

### 3.2 Functional Requirements

#### 3.2.1 Product Browsing and Searching
*   **3.2.1.1 Introduction:** Users must be able to view and search for available gifts.
*   **3.2.1.2 Inputs:** Search queries, category filters, clicks on product cards.
*   **3.2.1.3 Processing:** The system queries the database for active products matching the search or category criteria.
*   **3.2.1.4 Outputs:** A paginated grid of products or a specific detailed product page.
*   **3.2.1.5 Error Handling:** Display a "No products found" message if the search yields zero results.

#### 3.2.2 Real-time Product Customization
*   **3.2.2.1 Introduction:** Users can personalize products and see a live preview before purchasing.
*   **3.2.2.2 Inputs:** Text input strings, font choices, color selections via UI controls.
*   **3.2.2.3 Processing:** Client-side JavaScript updates the DOM/Canvas to overlay the selected text and styles onto the base product image in real-time.
*   **3.2.2.4 Outputs:** An updated visual representation of the customized product on the screen.
*   **3.2.2.5 Error Handling:** Prevent inputs that exceed product constraints (e.g., character limits) and display a clear warning.

#### 3.2.3 Shopping Cart and Checkout
*   **3.2.3.1 Introduction:** Users can add customized items to a cart and place a final order.
*   **3.2.3.2 Inputs:** "Add to Cart" action with customization parameters, shipping address, and payment information.
*   **3.2.3.3 Processing:** Store cart data securely. Upon checkout, validate payment details via the gateway and generate an order record in the database.
*   **3.2.3.4 Outputs:** Order confirmation screen with an order ID, and an automated email receipt sent to the user.
*   **3.2.3.5 Error Handling:** Handle payment failures gracefully by alerting the user, keeping the cart intact, and not creating an order record.

#### 3.2.4 Admin Order & Location Management
*   **3.2.4.1 Introduction:** Admins must be able to view and manage customer orders and their shipping destinations.
*   **3.2.4.2 Inputs:** Selection of specific orders from the admin dashboard list.
*   **3.2.4.3 Processing:** The system retrieves order quantities, specific user customizations, and user location/shipping details from the database.
*   **3.2.4.4 Outputs:** A detailed view of the order, allowing the admin to process it and update its shipping status.
*   **3.2.4.5 Error Handling:** Prevent status updates for orders that have already been fulfilled or cancelled.

#### 3.2.5 Admin Inventory Control
*   **3.2.5.1 Introduction:** Admins need to track stock availability to prevent overselling of physical gift canvases.
*   **3.2.5.2 Inputs:** Manual adjustments to stock levels or automatic deductions upon successful user checkout.
*   **3.2.5.3 Processing:** The system updates the inventory count in the database. If stock hits zero, the item is flagged.
*   **3.2.5.4 Outputs:** Real-time stock alerts in the dashboard; out-of-stock items are visually indicated to users in the User Portal and cannot be added to the cart.
*   **3.2.5.5 Error Handling:** Prevent the system from allowing a negative stock count.

### 3.5 Non-Functional Requirements

#### 3.5.1 Performance
*   The website shall load the homepage in under 3 seconds on a standard broadband connection.
*   The live-preview customizer shall update visually within 200ms of user input to ensure a fluid, real-time experience.

#### 3.5.2 Reliability
*   The system shall securely maintain shopping cart state during an active session, ensuring users do not lose selected items.

#### 3.5.3 Availability
*   The platform shall aim for 99.9% uptime, excluding scheduled maintenance windows.

#### 3.5.4 Security
*   All user passwords must be hashed securely using standard cryptographic algorithms (e.g., bcrypt).
*   The system shall mandate HTTPS to encrypt data transmitted between the client and server.
*   The application shall be protected against SQL injection, Cross-Site Scripting (XSS), and Cross-Site Request Forgery (CSRF) utilizing Laravel's built-in security features.

#### 3.5.5 Maintainability
*   Code must be organized following the MVC (Model-View-Controller) architecture pattern.
*   Codebase shall include thorough inline documentation, clear variable naming, and follow PSR coding standards for PHP.

#### 3.5.6 Portability
*   The application must be easily deployable to any standard Linux server supporting PHP and Composer, or containerized using Docker.

### 3.7 Design Constraints
*   Must utilize the Laravel framework for backend logic.
*   Frontend must prioritize modern design aesthetics; avoiding generic template looks and focusing on a premium user experience.

### 3.9 Other Requirements
*   All forms must be accessible and include proper labeling and validation.

---

## 4. Analysis Models

### 4.1 Data Flow Diagrams (DFD)
*(Note: Please insert/draw your actual DFD images here before submission)*
*   **Level 0 Context Diagram:** Shows the entire GiftStore system as a single process interacting with external entities (Customer, Admin, Payment Gateway).
*   **Level 1 DFD:** Breaks down the system into major sub-processes:
    1. Manage User Account
    2. Browse & Customize Product
    3. Manage Cart & Checkout
    4. Manage Orders & Inventory (Admin)

---

## 5. Github link
[Insert Github Repository Link Here]

## 6. DEPLOYED LINK
[Insert Deployed Application Link Here]

## 7. CLIENT APPROVAL PROOF
*(Insert screenshot or link to Client Approval Proof Here)*

## 8. CLIENT LOCATION PROOF
*(Insert screenshot or link to Client Location Proof Here)*

## 9. TRANSACTION ID PROOF
*(Insert screenshot or link to Transaction ID Proof Here)*

## 10. EMAIL ACKNOWLEDGEMENT
*(Insert screenshot or link to Email Acknowledgement Here)*

## 11. GST No
[Insert GST Number Here]

---

## A. Appendices

### A.1 Appendix 1
*(Insert any additional project materials, initial UI mockups, or survey results here.)*

### A.2 Appendix 2
*(Insert meeting minutes or alternative architectural considerations here.)*
