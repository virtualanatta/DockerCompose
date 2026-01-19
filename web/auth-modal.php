<!DOCTYPE html>
<html>
<head>
    <style>
        .auth-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050; background: rgba(0,0,0,0.6); align-items: center; justify-content: center; }
        .auth-modal.show { display: flex; }
        .auth-container { background: white; border-radius: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); width: 100%; max-width: 420px; padding: 40px; position: relative; }
        .auth-close { position: absolute; top: 20px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; }
        .auth-tabs { display: flex; border-bottom: 2px solid #f0f0f0; margin-bottom: 30px; gap: 20px; }
        .auth-tab { flex: 1; padding: 12px 0; text-align: center; font-weight: 600; color: #999; cursor: pointer; border-bottom: 3px solid transparent; }
        .auth-tab.active { color: #32b8c6; border-bottom-color: #32b8c6; }
        .form-content { display: none; }
        .form-content.active { display: block; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .form-group input:focus { outline: none; border-color: #32b8c6; box-shadow: 0 0 0 3px rgba(50,184,198,0.1); }
        .btn-submit { width: 100%; padding: 12px; background: #32b8c6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: #2aa5b5; }
    </style>
</head>
<body>
    <div class="auth-modal" id="authModal">
        <div class="auth-container">
            <button class="auth-close" id="closeAuth">&times;</button>
            <div class="auth-tabs">
                <div class="auth-tab active" data-tab="login">Login</div>
                <div class="auth-tab" data-tab="register">Register</div>
            </div>
            <form id="loginForm" class="form-content active" action="acceso.php" method="POST">
                <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
            <form id="registerForm" class="form-content" action="registro.php" method="POST">
                <div class="form-group"><label>Name</label><input type="text" name="name" required></div>
                <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required minlength="6"></div>
                <button type="submit" class="btn-submit">Sign Up</button>
            </form>
        </div>
    </div>
    <script>
        const authModal = document.getElementById('authModal');
        const tabs = document.querySelectorAll('.auth-tab');
        const forms = document.querySelectorAll('.form-content');
        
        function openAuthModal(tab = 'login') { authModal.classList.add('show'); switchTab(tab); }
        function closeAuthModal() { authModal.classList.remove('show'); }
        function switchTab(tab) {
            tabs.forEach(t => t.classList.remove('active'));
            document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
            forms.forEach(f => f.classList.remove('active'));
            document.getElementById(tab === 'login' ? 'loginForm' : 'registerForm').classList.add('active');
        }
        
        document.getElementById('closeAuth').addEventListener('click', closeAuthModal);
        authModal.addEventListener('click', (e) => { if(e.target === authModal) closeAuthModal(); });
        tabs.forEach(tab => { tab.addEventListener('click', () => switchTab(tab.dataset.tab)); });
        window.openAuthModal = openAuthModal;
    </script>
</body>
</html>
