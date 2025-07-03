<style>
    :root {
        --primary-color: #8e44ad;
        --primary-gradient-start: #9b59b6;
        --primary-gradient-end: #8e44ad;
        --secondary-color: #7f8c8d;
        --success-color: #27ae60;
        --info-color: #3498db;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --light-color: #f8f9fc;
        --dark-color: #34495e;
        --border-radius: 0.5rem;
        --card-border-radius: 0.75rem;
        --box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fa;
        color: #444;
        transition: all 0.3s ease;
        overflow-x: hidden;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c5c5c5;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--primary-color);
    }

    /* Glassmorphism effect */
    .glassmorphism {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    /* Sidebar styling */
    .sidebar {
        min-height: 100vh;
        background: linear-gradient(135deg, var(--primary-gradient-end) 0%, var(--primary-gradient-start) 100%);
        box-shadow: var(--box-shadow);
        z-index: 1040;
        position: fixed;
        width: 280px;
        transition: all 0.3s ease-in-out;
    }

    .sidebar-brand {
        height: 5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
    }

    .sidebar-brand h3 {
        color: white;
        font-weight: 700;
        font-size: 1.4rem;
        margin-bottom: 0;
        letter-spacing: 1px;
    }

    .sidebar-brand p {
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0;
        letter-spacing: 1px;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.15);
        margin: 0 1.5rem;
    }

    .nav-header {
        color: rgba(255, 255, 255, 0.5);
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 1.5rem;
        padding-left: 1.5rem;
    }

    .nav-item {
        position: relative;
        padding: 0 0.5rem;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        padding: 1rem;
        border-radius: var(--border-radius);
        margin: 0.2rem 0;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: white;
        transform: scaleY(0);
        transition: transform 0.3s, opacity 0.3s;
        transform-origin: top;
        opacity: 0;
        border-radius: 0 2px 2px 0;
    }

    .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.15);
        color: white;
        transform: translateX(5px);
    }

    .nav-link:hover::before {
        transform: scaleY(1);
        opacity: 1;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        color: white;
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
    }

    .nav-link.active::before {
        transform: scaleY(1);
        opacity: 1;
    }

    .nav-link i {
        margin-right: 0.8rem;
        font-size: 1.1rem;
        width: 1.5rem;
        text-align: center;
        transition: all 0.3s;
    }

    /* Main content */
    .main-content {
        margin-left: 280px;
        transition: all 0.3s ease-in-out;
        min-height: 100vh;
        background-color: #f5f7fa;
        padding: 2rem;
        position: relative;
        z-index: 1;
    }

    .main-content::before {
        content: '';
        position: fixed;
        top: 0;
        left: 280px;
        right: 0;
        height: 100vh;
        background: radial-gradient(circle at top right, rgba(155, 89, 182, 0.1) 0%, transparent 70%);
        z-index: -1;
    }

    /* Topbar */
    .topbar {
        height: 4.375rem;
        background-color: white;
        box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
        border-radius: var(--card-border-radius);
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .topbar::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), var(--info-color), var(--success-color), var(--warning-color), var(--danger-color));
        background-size: 500% 500%;
        animation: gradient 10s ease infinite;
    }

    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .topbar h1 {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0;
    }

    .topbar-divider {
        width: 0;
        border-right: 1px solid #e3e6f0;
        height: 2rem;
        margin: auto 1rem;
    }

    .topbar-nav {
        display: flex;
        align-items: center;
        margin-left: auto;
        gap: 0.5rem;
    }

    .topbar-item {
        position: relative;
    }

    .topbar-nav .nav-link {
        color: var(--secondary-color);
        padding: 0.7rem;
        border-radius: 50%;
        height: 2.5rem;
        width: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        overflow: visible;
        background-color: #f8f9fc;
        transition: all 0.3s;
    }

    .topbar-nav .nav-link::before {
        display: none;
    }

    .topbar-nav .nav-link:hover {
        background-color: var(--primary-color);
        color: white;
        transform: translateY(-3px);
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        padding: 0.2rem 0.5rem;
        border-radius: 50%;
        font-size: 0.6rem;
        background-color: var(--danger-color);
        color: white;
        font-weight: bold;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    .user-profile {
        display: flex;
        align-items: center;
        margin-left: 1rem;
        cursor: pointer;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius);
        transition: all 0.3s;
    }

    .user-profile:hover {
        background-color: #f8f9fc;
    }

    .user-profile img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .user-info {
        margin-left: 0.8rem;
    }

    .user-info h6 {
        margin-bottom: 0;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .user-info small {
        color: var(--secondary-color);
        font-size: 0.75rem;
    }

    /* Cards */
    .card {
        border: none;
        border-radius: var(--card-border-radius);
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.07);
        transition: all 0.3s ease-in-out;
        background-color: white;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(58, 59, 69, 0.15);
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
        border-top-left-radius: var(--card-border-radius) !important;
        border-top-right-radius: var(--card-border-radius) !important;
        font-weight: 600;
        color: var(--dark-color);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Buttons */
    .btn {
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        border-radius: var(--border-radius);
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.1);
        z-index: -1;
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.3s;
    }

    .btn:hover::after {
        transform: scaleY(1);
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        box-shadow: 0 5px 15px rgba(155, 89, 182, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        border: none;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border: none;
    }

    .btn-info:hover {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        border: none;
    }

    .btn-danger:hover {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
    }

    /* Progress bars */
    .progress {
        border-radius: 1rem;
        height: 0.6rem;
        margin-bottom: 0.5rem;
        background-color: #eaecf4;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 1rem;
        position: relative;
        overflow: hidden;
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.3) 50%, rgba(255, 255, 255, 0) 100%);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    /* Tables */
    .table-responsive {
        overflow-x: auto;
        border-radius: 0.5rem;
    }

    table.dataTable {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table th {
        font-weight: 600;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
        color: var(--dark-color);
        padding: 1rem;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .table tbody tr {
        transition: all 0.2s;
    }

    .table tbody tr:hover {
        background-color: #f8f9fc;
        transform: scale(1.01);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    /* Badge */
    .badge {
        padding: 0.5rem 0.8rem;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Forms */
    .form-control,
    .form-select {
        padding: 0.7rem 1rem;
        font-size: 0.9rem;
        border: 1px solid #e5e7eb;
        border-radius: var(--border-radius);
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: rgba(155, 89, 182, 0.5);
        box-shadow: 0 0 0 0.25rem rgba(155, 89, 182, 0.25);
    }

    .input-group {
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .input-group-text {
        background-color: #f8f9fc;
        border-color: #e5e7eb;
        color: var(--secondary-color);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    /* Stat cards */
    .stat-card {
        border-left: 0.25rem solid var(--primary-color);
        border-radius: var(--card-border-radius);
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary-color), transparent);
    }

    .stat-card.primary {
        border-left-color: var(--primary-color);
    }

    .stat-card.primary::after {
        background: linear-gradient(90deg, var(--primary-color), transparent);
    }

    .stat-card.success {
        border-left-color: var(--success-color);
    }

    .stat-card.success::after {
        background: linear-gradient(90deg, var(--success-color), transparent);
    }

    .stat-card.warning {
        border-left-color: var(--warning-color);
    }

    .stat-card.warning::after {
        background: linear-gradient(90deg, var(--warning-color), transparent);
    }

    .stat-card.danger {
        border-left-color: var(--danger-color);
    }

    .stat-card.danger::after {
        background: linear-gradient(90deg, var(--danger-color), transparent);
    }

    .stat-card .icon {
        font-size: 2rem;
        color: rgba(155, 89, 182, 0.1);
        transition: all 0.3s;
    }

    .stat-card:hover .icon {
        transform: scale(1.2);
        color: rgba(155, 89, 182, 0.2);
    }

    /* Footer */
    .footer {
        padding: 1rem 0;
        color: var(--secondary-color);
        font-size: 0.9rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .sidebar {
            width: 75px;
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 1.5rem 0.5rem;
        }

        .sidebar .sidebar-brand h3,
        .sidebar .sidebar-brand p,
        .sidebar .nav-header,
        .sidebar .nav-link span {
            display: none;
        }

        .nav-link {
            padding: 1rem 0;
            display: flex;
            justify-content: center;
        }

        .nav-link i {
            margin: 0;
            font-size: 1.2rem;
        }

        .nav-link:hover {
            transform: none;
        }

        .main-content {
            margin-left: 75px;
        }

        .main-content::before {
            left: 75px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 1.5rem;
        }

        .topbar {
            margin-bottom: 1.5rem;
        }

        .user-profile {
            display: none;
        }

        .sidebar {
            width: 0;
            transform: translateX(-100%);
        }

        .main-content {
            margin-left: 0;
        }

        .main-content::before {
            left: 0;
        }

        .sidebar.show {
            width: 240px;
            transform: translateX(0);
        }

        .sidebar.show+.main-content {
            margin-left: 0;
        }

        .sidebar.show .sidebar-brand h3,
        .sidebar.show .sidebar-brand p,
        .sidebar.show .nav-header,
        .sidebar.show .nav-link span {
            display: block;
        }

        .sidebar.show .nav-link {
            padding: 1rem;
            justify-content: flex-start;
        }

        .sidebar.show .nav-link i {
            margin-right: 0.8rem;
        }

        .sidebar-toggle {
            display: none !important;
        }
    }

    /* Sidebar toggle */
    .sidebar-toggle {
        display: none;
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        color: white;
        border-radius: 50%;
        height: 3rem;
        width: 3rem;
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        z-index: 1050;
        text-align: center;
        line-height: 3rem;
        font-size: 1.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        cursor: pointer;
        transition: all 0.3s;
    }

    .sidebar-toggle:hover {
        transform: scale(1.1);
    }

    /* Improved navbar hamburger for sidebar */
    .navbar-toggler {
        background-color: transparent;
        border: none;
        padding: 0;
        margin-right: 1rem;
        display: none;
    }

    .navbar-toggler-icon {
        color: var(--dark-color);
        font-size: 1.5rem;
    }

    @media (max-width: 768px) {
        .navbar-toggler {
            display: block;
        }
    }

    /* Mobile responsive text adjustments */
    @media (max-width: 767.98px) {
        body {
            font-size: 0.9rem;
        }

        h1 {
            font-size: 1.5rem;
        }

        h4 {
            font-size: 1.2rem;
        }

        h5,
        h6 {
            font-size: 1rem;
        }

        .card-header {
            padding: 1rem;
        }

        .card-body {
            padding: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }

        .h3 {
            font-size: 1.5rem;
        }

        .small,
        small {
            font-size: 80%;
        }

        .topbar h1 {
            font-size: 1.25rem;
        }

        .badge {
            padding: 0.35rem 0.65rem;
            font-size: 0.7rem;
        }

        .form-label {
            font-size: 0.85rem;
        }

        .form-control,
        .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.85rem;
        }

        .modal-dialog {
            margin: 1rem;
            max-width: calc(100% - 2rem);
        }

        .dataTables_length,
        .dataTables_filter {
            text-align: left !important;
            display: block;
            width: 100%;
        }

        .dataTables_length select {
            min-width: 60px;
            padding: 0.35rem;
            border-radius: 0.25rem;
            margin: 0 5px;
            display: inline-block;
        }

        .dataTables_filter input {
            margin-left: 0;
            width: 100%;
            margin-top: 0.25rem;
        }
    }

    .btnClose {
        background-color: #e74c3c;
        color: white;
        border: none;
    }
</style>