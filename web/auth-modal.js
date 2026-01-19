// Auth Modal Controller
(function() {
  const modal = document.createElement('div');
  modal.id = 'authModal';
  modal.innerHTML = `
    <div class="auth-overlay" onclick="if(event.target.id === 'authModal') closeAuthModal()">
      <div class="auth-card">
        <button class="auth-close" onclick="closeAuthModal()">&times;</button>
        
        <div class="auth-header">
          <h2>Welcome</h2>
          <p>Login to your account or create a new one</p>
        </div>

        <div class="auth-tabs">
          <button class="auth-tab-btn active" onclick="switchAuthTab('login')">Login</button>
          <button class="auth-tab-btn" onclick="switchAuthTab('register')">Register</button>
        </div>

        <!-- LOGIN FORM -->
        <form id="loginAuthForm" class="auth-form active" action="acceso.php" method="POST">
          <div class="auth-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="auth-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="auth-btn">Sign In</button>
          <p class="auth-toggle">Don't have an account? <a onclick="switchAuthTab('register')">Sign Up</a></p>
        </form>

        <!-- REGISTER FORM -->
        <form id="registerAuthForm" class="auth-form" action="registro.php" method="POST">
          <div class="auth-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name" required>
          </div>
          <div class="auth-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="auth-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password" required minlength="6">
          </div>
          <div class="auth-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required minlength="6">
          </div>
          <button type="submit" class="auth-btn">Create Account</button>
          <p class="auth-toggle">Already have an account? <a onclick="switchAuthTab('login')">Sign In</a></p>
        </form>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  
  window.openAuthModal = function() {
    document.getElementById('authModal').classList.add('show');
  };
  
  window.closeAuthModal = function() {
    document.getElementById('authModal').classList.remove('show');
  };
  
  window.switchAuthTab = function(tab) {
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
    document.querySelectorAll('.auth-tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById(tab === 'login' ? 'loginAuthForm' : 'registerAuthForm').classList.add('active');
    event.target.classList.add('active');
  };
})();
