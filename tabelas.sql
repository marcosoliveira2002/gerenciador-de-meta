
CREATE TABLE
  Usuarios (
    id_usuario VARCHAR(36) PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

CREATE TABLE
  Metas (
    id_meta SERIAL PRIMARY KEY, 
    id_usuario VARCHAR(36) NOT NULL,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    status CHAR(1) CHECK (status IN ('P', 'C')), 
    data_criacao DATE DEFAULT CURRENT_DATE,
    data_conclusao DATE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios (id_usuario) ON DELETE CASCADE
  );

CREATE TABLE
  Progresso (
    id_progresso SERIAL PRIMARY KEY, 
    id_meta INT NOT NULL, 
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    descricao_progresso TEXT,
    FOREIGN KEY (id_meta) REFERENCES Metas (id_meta) ON DELETE CASCADE
  );