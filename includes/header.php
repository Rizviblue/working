<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Courier Management System'; ?></title>
    
    <!-- Custom CSS - Load this first so it can be overridden -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Enhanced fallback styles -->
    <style>
        /* Ensure basic styling even if external CSS fails */
        :root {
            --primary-color: #1e40af;
            --primary-light: #3b82f6;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .container { 
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 15px; 
        }
        
        /* Login specific styles */
        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            margin-bottom: 2rem;
        }
        
        .demo-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            width: 100%;
            max-width: 400px;
        }
        
        .login-title {
            color: var(--dark-color);
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .login-subtitle {
            color: #6b7280;
            margin-bottom: 2rem;
        }
        
        .btn { 
            display: inline-block; 
            padding: 0.6rem 1rem; 
            margin-bottom: 0; 
            font-size: 1rem; 
            line-height: 1.5; 
            text-align: center; 
            white-space: nowrap; 
            vertical-align: middle; 
            cursor: pointer; 
            border: 1px solid transparent; 
            border-radius: 0.375rem; 
            text-decoration: none;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .btn-primary { 
            color: #fff; 
            background-color: var(--primary-color); 
            border-color: var(--primary-color); 
        }
        
        .btn-primary:hover { 
            background-color: var(--primary-light); 
            border-color: var(--primary-light); 
            transform: translateY(-1px);
        }
        
        .form-control { 
            display: block; 
            width: 100%; 
            padding: 0.6rem 0.75rem; 
            font-size: 1rem; 
            line-height: 1.5; 
            color: #495057; 
            background-color: #fff; 
            border: 2px solid #e2e8f0; 
            border-radius: 0.375rem; 
            transition: border-color 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(30, 64, 175, 0.1);
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .input-group {
            position: relative;
            display: flex;
        }
        
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.6rem 0.75rem;
            font-size: 1rem;
            background-color: #f8f9fa;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 0.375rem 0 0 0.375rem;
            color: #6b7280;
        }
        
        .input-group .form-control {
            border-left: none;
            border-radius: 0 0.375rem 0.375rem 0;
        }
        
        .alert { 
            padding: 0.75rem 1rem; 
            margin-bottom: 1rem; 
            border: 1px solid transparent; 
            border-radius: 0.375rem; 
            font-size: 0.9rem;
        }
        
        .alert-danger { 
            color: #dc2626; 
            background-color: #fef2f2; 
            border-color: #fecaca; 
        }
        
        .alert-info { 
            color: #0369a1; 
            background-color: #eff6ff; 
            border-color: #bfdbfe; 
        }
        
        .text-center { text-align: center; }
        .text-muted { color: #6b7280; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-3 { margin-top: 1rem; }
        .d-grid { display: grid; }
        .gap-2 { gap: 0.5rem; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .flex-column { flex-direction: column; }
        
        .demo-btn {
            background: white;
            border: 2px solid #e2e8f0;
            color: #374151;
            text-align: left;
            padding: 1rem;
        }
        
        .demo-btn:hover {
            border-color: var(--primary-color);
            background-color: #f8fafc;
        }
        
        /* Icon fallbacks when Font Awesome doesn't load */
        .fas:before, .fa:before {
            font-family: 'Font Awesome 6 Free', serif;
        }
        
        /* If Font Awesome fails to load, hide icons or show fallback */
        .fas.fa-envelope:before { content: "✉"; font-family: serif; }
        .fas.fa-lock:before { content: "🔒"; font-family: serif; }
        .fas.fa-user-shield:before { content: "👤"; font-family: serif; }
        .fas.fa-user-tie:before { content: "👔"; font-family: serif; }
        .fas.fa-user:before { content: "👤"; font-family: serif; }
        .fas.fa-info-circle:before { content: "ℹ"; font-family: serif; }
        .fas.fa-exclamation-triangle:before { content: "⚠"; font-family: serif; }
    </style>
</head>
<body>