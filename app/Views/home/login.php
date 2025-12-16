<div style="max-width: 480px; margin: 0 auto; padding: 20px;">
    <h2>Connexion</h2>

    <div id="login-message" style="display: none; padding: 10px; margin-bottom: 20px; border-radius: 4px;"></div>

    <form id="loginForm" style="display: flex; flex-direction: column; gap: 15px;">
        <div>
            <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                placeholder="exemple@email.com"
            >
        </div>

        <div>
            <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="6"
                style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"
                placeholder="Votre mot de passe"
            >
        </div>

        <button
            type="submit"
            style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;"
            onmouseover="this.style.backgroundColor='#0056b3'"
            onmouseout="this.style.backgroundColor='#007bff'"
        >
            Se connecter
        </button>
    </form>

    <div style="margin-top: 20px;">
        <a href="/users/create" style="color: #007bff; text-decoration: none;">Créer un compte</a>
        <br>
        <a href="/" style="color: #007bff; text-decoration: none;">← Retour à l'accueil</a>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;

    const messageDiv = document.getElementById('login-message');
    messageDiv.style.display = 'block';
    messageDiv.style.backgroundColor = '#d1ecf1';
    messageDiv.style.color = '#0c5460';
    messageDiv.textContent = 'Connexion en cours...';

    try {
        const response = await fetch('/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            messageDiv.style.backgroundColor = '#d4edda';
            messageDiv.style.color = '#155724';
            messageDiv.textContent = '✅ ' + data.message;
            setTimeout(() => {
                window.location.href = '/';
            }, 600);
        } else {
            messageDiv.style.backgroundColor = '#f8d7da';
            messageDiv.style.color = '#721c24';
            messageDiv.textContent = '❌ ' + (data.error || 'Identifiants invalides');
        }
    } catch (error) {
        messageDiv.style.backgroundColor = '#f8d7da';
        messageDiv.style.color = '#721c24';
        messageDiv.textContent = '❌ Erreur de connexion : ' + error.message;
    }
});
</script>

