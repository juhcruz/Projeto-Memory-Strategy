<?php
// codigo/painel.php
require_once 'db.php';
include 'includes/header.php';

// Requisito: Verificação de sessão no topo de cada página restrita
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erro = '';
$sucesso = '';

// Requisito: DELETE com WHERE e prepared statement
if (isset($_GET['apagar'])) {
    $modulo_id = $_GET['apagar'];
    
    try {
        $stmt = $pdo->prepare("DELETE FROM modulos WHERE id = ? AND user_id = ?");
        $stmt->execute([$modulo_id, $user_id]);
        $sucesso = "Módulo apagado com sucesso!";
    } catch (PDOException $e) {
        $erro = "Erro ao apagar o módulo.";
    }
}

// Requisito: Pesquisa ou filtro na listagem (Funcionalidade 4 do Dossier)
$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : '';

if (!empty($pesquisa)) {
    $stmt = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ? AND nome LIKE ? ORDER BY id DESC");
    $stmt->execute([$user_id, "%$pesquisa%"]);
} else {
    // Requisito: SELECT 
    $stmt = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ? ORDER BY id DESC");
    $stmt->execute([$user_id]);
}
$modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="painel-wrapper">
    <div class="painel-header">
        <h2>Painel de Controlo - Gestão de Módulos</h2>
        <div>
            <span>Bem-vindo, <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong></span> | 
            <a href="logout.php" class="btn-sair">Terminar Sessão</a>
        </div>
    </div>

    <?php if (!empty($erro)): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>
    <?php if (!empty($sucesso)): ?>
        <div class="alert sucesso"><?php echo htmlspecialchars($sucesso); ?></div>
    <?php endif; ?>

    <div class="acoes-painel">
        <!-- Grupo de Botões Azuis à esquerda -->
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="criar_palavra.php" class="btn">+ Adicionar Novo Módulo / Vocabulário</a>
            <a href="estudo_ativo.php" class="btn">Estudo Inteligente / Ativo</a>
        </div>

        <!-- Formulário de Pesquisa / Filtro (Funcionalidade 4) -->
        <form action="painel.php" method="GET" class="form-pesquisa">
            <input type="text" name="pesquisa" placeholder="Pesquisar módulo..." value="<?php echo htmlspecialchars($pesquisa); ?>">
            <button type="submit" class="btn-secundario">Filtrar</button>
        </form>
    </div>

    <h3>Os Meus Módulos de Estudo</h3>
    <?php if (count($modulos) > 0): ?>
        <table class="tabela-dados">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Módulo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($modulos as $m): ?>
                <tr>
                    <td><?php echo $m['id']; ?></td>
                    <td><?php echo htmlspecialchars($m['nome']); ?></td>
                    <td><?php echo htmlspecialchars($m['descricao']); ?></td>
                    <td>
                        <!-- Botão Ver (Tela 4 - Estudo Ativo) -->
                        <a href="estudo_ativo.php?modulo_id=<?php echo $m['id']; ?>" class="btn-acao ver">Ver / Estudar</a>
                        <!-- Botão Deletar -->
                        <a href="painel.php?apagar=<?php echo $m['id']; ?>" class="btn-acao apagar" onclick="return confirm('Tem a certeza que deseja apagar este módulo e todas as suas palavras?');">Deletar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ainda não tem módulos criados. Clique em "+ Adicionar Novo Módulo / Vocabulário" para começar.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>