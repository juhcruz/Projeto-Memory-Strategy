CREATE DATABASE IF NOT EXISTS memory_strategy 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE memory_strategy;

-- 1. Tabela de Utilizadores
CREATE TABLE IF NOT EXISTS utilizadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    data_registo TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao VARCHAR(255),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES utilizadores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 3. Tabela de Palavras (Entidade Relacionada 1:N - 1 Módulo tem várias Palavras)
CREATE TABLE IF NOT EXISTS palavras (
    id INT AUTO_INCREMENT PRIMARY KEY,
    modulo_id INT NOT NULL,
    palavra_en VARCHAR(100) NOT NULL,
    traducao_pt VARCHAR(100) NOT NULL,
    frase_exemplo TEXT NOT NULL,
    FOREIGN KEY (modulo_id) REFERENCES modulos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =========================================================================
-- DADOS DE TESTE (Obrigatório na Secção 7 do Dossier para o Formador testar)
-- Utilizador: teste@memorystrategy.com | Password: Password123!
-- =========================================================================
INSERT INTO utilizadores (id, email, password) VALUES 
(1, 'teste@memorystrategy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO modulos (id, user_id, nome, descricao) VALUES 
(1, 1, 'Tecnologia e Programação', 'Vocabulário essencial de IT e desenvolvimento de software'),
(2, 1, 'Inglês Corporativo', 'Termos usados em reuniões e ambientes de trabalho');

INSERT INTO palavras (modulo_id, palavra_en, traducao_pt, frase_exemplo) VALUES 
(1, 'Resilient', 'Resiliente', 'Our cloud architecture is designed to be highly resilient.'),
(1, 'Deploy', 'Implementar / Publicar', 'We will deploy the new PHP application to the server tomorrow.'),
(2, 'Deadline', 'Prazo limite', 'The final deadline for the CINEL project is next Friday.');