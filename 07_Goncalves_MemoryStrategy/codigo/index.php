<?php

require_once 'db.php';
include 'includes/header.php';

$erro = '';
$sucesso = '';

// Processamento do Registo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'registar') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Requisito: password_hash()
            $passwordHash = password_hash($pass, PASSWORD_DEFAULT);

            try {
                // Requisito: Consultas com prepare() e execute() (contra SQL Injection)
                $stmt = $pdo->prepare("INSERT INTO utilizadores (email, password) VALUES (?, ?)");
                $stmt->execute([$email, $passwordHash]);
                $sucesso = "Registo efetuado com sucesso! Pode fazer login.";
            } catch (PDOException $e) {
                // Requisito: Restrição UNIQUE na base de dados (email duplicado)
                if ($e->getCode() == 23000) {
                    $erro = "Este email já se encontra registado.";
                } else {
                    $erro = "Erro ao registar: " . $e->getMessage();
                }
            }
        } else {
            $erro = "Por favor, introduza um email válido.";
        }
    } else {
        $erro = "Preencha todos os campos para o registo.";
    }
}

// Processamento do Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];

    if (!empty($email) && !empty($pass)) {
        $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        // Requisito: password_verify()
        if ($utilizador && password_verify($pass, $utilizador['password'])) {
            $_SESSION['user_id'] = $utilizador['id'];
            $_SESSION['user_email'] = $utilizador['email'];
            
            // Requisito: header('Location: ...'); exit; depois de cada POST
            header('Location: painel.php');
            exit;
        } else {
            $erro = "Credenciais inválidas. Verifique o email e a password.";
        }
    } else {
        $erro = "Preencha todos os campos para iniciar sessão.";
    }
}
?>

<div class="auth-wrapper">
    <?php if (!empty($erro)): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if (!empty($sucesso)): ?>
        <div class="alert sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <div class="auth-grid">
        <!-- Formulário de Login -->
        <div class="auth-card">
            <h2>Iniciar Sessão</h2>
            <form action="index.php" method="POST" id="formLogin">
                <input type="hidden" name="acao" value="login">
                
                <label for="login_email">Email:</label>
                <input type="email" id="login_email" name="email" required>

                <label for="login_password">Password:</label>
                <input type="password" id="login_password" name="password" required>

                <button type="submit" class="btn">Entrar</button>
            </form>
        </div>

        <!-- Formulário de Registo -->
        <div class="auth-card">
            <h2>Criar Conta</h2>
            <form action="index.php" method="POST" id="formRegisto">
                <input type="hidden" name="acao" value="registar">
                
                <label for="reg_email">Email:</label>
                <input type="email" id="reg_email" name="email" required>

                <label for="reg_password">Password:</label>
                <input type="password" id="reg_password" name="password" required>

                <div class="termos-group">
                    <input type="checkbox" id="termos" name="termos" required>
                    <label for="termos">Aceito a política de privacidade e termos de uso[cite: 5].</label>
                </div>

                <button type="submit" class="btn">Registar</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>