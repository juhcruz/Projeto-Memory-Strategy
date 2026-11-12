<?php
// codigo/criar_palavra.php
require_once 'db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$erro = '';

// Buscar módulos do utilizador para o select
$stmt_mod = $pdo->prepare("SELECT * FROM modulos WHERE user_id = ?");
$stmt_mod->execute([$user_id]);
$lista_modulos = $stmt_mod->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_modulo = trim($_POST['nome_modulo']);
    $descricao = trim($_POST['descricao']);
    $palavra_en = trim($_POST['palavra_en']);
    $traducao_pt = trim($_POST['traducao_pt']);
    $frase_exemplo = trim($_POST['frase_exemplo']);

    if (!empty($nome_modulo) && !empty($palavra_en) && !empty($traducao_pt) && !empty($frase_exemplo)) {
        try {
            $pdo->beginTransaction();

            // Verificar se o módulo já existe para este user ou criar um novo
            $stmt_chk = $pdo->prepare("SELECT id FROM modulos WHERE user_id = ? AND nome = ?");
            $stmt_chk->execute([$user_id, $nome_modulo]);
            $mod = $stmt_chk->fetch(PDO::FETCH_ASSOC);

            if ($mod) {
                $modulo_id = $mod['id'];
            } else {
                $stmt_ins_mod = $pdo->prepare("INSERT INTO modulos (user_id, nome, descricao) VALUES (?, ?, ?)");
                $stmt_ins_mod->execute([$user_id, $nome_modulo, $descricao]);
                $modulo_id = $pdo->lastInsertId();
            }

            // Requisito: INSERT com prepare() e execute()
            $stmt_palavra = $pdo->prepare("INSERT INTO palavras (modulo_id, palavra_en, traducao_pt, frase_exemplo) VALUES (?, ?, ?, ?)");
            $stmt_palavra->execute([$modulo_id, $palavra_en, $traducao_pt, $frase_exemplo]);

            $pdo->commit();

            // Requisito: header Location após POST
            header('Location: painel.php');
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $erro = "Erro ao guardar dados: " . $e->getMessage();
        }
    } else {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    }
}
?>

<div class="form-wrapper">
    <h2>Criar Módulo e Adicionar Vocabulário</h2>
    <a href="painel.php" class="btn-voltar">← Voltar ao Painel</a>

    <?php if (!empty($erro)): ?>
        <div class="alert erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form action="criar_palavra.php" method="POST" id="formCriarPalavra" class="card-form">
        <fieldset>
            <legend>Informação do Módulo</legend>
            <label for="nome_modulo">Nome do Módulo / Categoria:</label>
            <input type="text" id="nome_modulo" name="nome_modulo" required placeholder="Ex: Tecnologia IT">

            <label for="descricao">Descrição (Opcional):</label>
            <input type="text" id="descricao" name="descricao" placeholder="Ex: Vocabulário técnico de redes">
        </fieldset>

        <fieldset>
            <legend>Detalhes da Palavra</legend>
            <label for="palavra_en">Palavra em Inglês:</label>
            <input type="text" id="palavra_en" name="palavra_en" required placeholder="Ex: Resilient">

            <label for="traducao_pt">Tradução em Português:</label>
            <input type="text" id="traducao_pt" name="traducao_pt" required placeholder="Ex: Resiliente">

            <label for="frase_exemplo">Frase de Exemplo (Contexto):</label>
            <textarea id="frase_exemplo" name="frase_exemplo" required rows="3" placeholder="Ex: Our system is robust and resilient."></textarea>
        </fieldset>

        <button type="submit" class="btn">Salvar Vocabulário</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>