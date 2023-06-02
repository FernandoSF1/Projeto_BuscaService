-- -----------------------------------------------------
-- Schema busca_service
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `busca_service` DEFAULT CHARACTER SET utf8 ;
USE `busca_service`;

CREATE TABLE cliente (
  idcli INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  email VARCHAR(255) NOT NULL,
  senha VARCHAR(60) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  telefone VARCHAR(18) NOT NULL,
  cep VARCHAR(9) NOT NULL,
  estado VARCHAR(45) NOT NULL,
  cidade VARCHAR(45) NOT NULL,
  bairro VARCHAR(45) NOT NULL,
  perfil VARCHAR(3) NOT NULL DEFAULT 'CLI' COMMENT 'ADM=Administrador\\nPRO=Profissional\\nCLI=Cliente',
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '\"0\" = Inativo / \"1\" = Ativo',
  dataregcli DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (idcli)
  );

  -- Inserindo dados na tabela cliente

  INSERT INTO cliente (nome, email, senha, cpf, telefone, cep, estado, cidade, bairro, perfil, status)
  VALUES ('Admin','admin@email.com','202cb962ac59075b964b07152d234b70','123.123.123-12','(11)11111-1111','55555-555','UF','Cidade','Bairro','ADM',1),
    ('Mariana', 'mariana@example.com', MD5('123456'), '113.242.383-54', '(11)98765-4321', '12345-678', 'SP', 'São Paulo', 'Penha', 1, 'CLI'),
    ('Lucas', 'lucas@example.com', MD5('123456'), '212.323.464-50', '(22)87654-3210', '23456-789', 'RJ', 'Rio de Janeiro', 'Jacarepaguá', 1, 'CLI'),
    ('Ana Carolina', 'anacarolina@example.com', MD5('123456'), '334.414.565-76', '(33)76543-2109', '34567-890', 'MG', 'Belo Horizonte', 'Barreiro', 1, 'CLI'),
    ('Rafael', 'rafael@example.com', MD5('123456'), '434.565.676-77', '(44)65432-1098', '45678-901', 'PR', 'Curitiba', 'Batel', 1, 'CLI'),
    ('Carolina', 'carolina@example.com', MD5('123456'), '855.466.787-88', '(55)54321-0987', '56789-012', 'RS', 'Porto Alegre', 'Santana', 1, 'CLI'),
    ('Fernanda', 'fernanda@example.com', MD5('123456'), '636.797.808-19', '(66)43210-9876', '67890-123', 'BA', 'Salvador', 'Candeal', 1, 'CLI'),
    ('Gustavo', 'gustavo@example.com', MD5('123456'), '757.868.919-80', '(77)32109-8765', '78901-234', 'PE', 'Recife', 'Água Fria', 1, 'CLI');


CREATE TABLE IF NOT EXISTS profissional(
  idpro INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  email VARCHAR(255) NOT NULL,
  senha VARCHAR(60) NOT NULL,
  cpf VARCHAR(14) NOT NULL,
  telefone VARCHAR(18) NOT NULL,
  telefone2 VARCHAR(18) NULL DEFAULT NULL,
  cep VARCHAR(9) NOT NULL,
  estado VARCHAR(45) NOT NULL,
  cidade VARCHAR(45) NOT NULL,
  bairro VARCHAR(45) NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  descricaonegocio TEXT NOT NULL,
  fotoprin BLOB NOT NULL,
  fotosec BLOB NULL DEFAULT NULL,
  fotosec2 BLOB NULL DEFAULT NULL,
  perfil VARCHAR(3) NOT NULL DEFAULT 'PRO' COMMENT 'ADM=Administrador\\nPRO=Profissional\\nCLI=Cliente',
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '\"0\" = Inativo / \"1\" = Ativo',
  dataregpro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (idpro)
  );

 -- Inserindo dados na tabela profissional

  INSERT INTO profissional (nome, titulo, email, senha, cpf, telefone, telefone2, cep, estado, cidade, bairro, fotoprin, descricaonegocio, fotosec, fotosec2, status, perfil)
VALUES
    ('Pedro Henrique', 'Pedrão dos Serviços', 'pedro@example.com', MD5('12345'), '123.456.789-10', '(11)98765-4321', '(11)12345-6789', '12345-678', 'SP', 'São Paulo', 'Lapa', 'foto1.jpg', 'Sou chamado de Pedrão e sou muito bom no que faço.', 'foto1_sec.jpg', 'foto1_sec2.jpg', 1, 'PRO'),
    ('João Silva', 'Jô da Construção', 'joao@example.com', MD5('12345'), '234.567.890-12', '(22)87654-3210', '(22)54321-6789', '23456-789', 'RJ', 'Rio de Janeiro', 'Leblon', 'foto2.jpg', 'Trabalho a anos no ramo de construções.', 'foto2_sec.jpg', 'foto2_sec2.jpg', 1, 'PRO'),
    ('Maria Souza', 'Serviços Mary', 'maria@example.com', MD5('12345'), '345.678.901-23', '(33)76543-2109', '(33)98765-4321', '34567-890', 'MG', 'Belo Horizonte', 'Lourdes', 'foto3.jpg', 'Sou muito boa no que faço!', 'foto3_sec.jpg', 'foto3_sec2.jpg', 1, 'PRO'),
    ('Carlos Oliveira', 'Carlota - Serviços', 'carlos@example.com', MD5('12345'), '456.789.012-34', '(44)65432-1098', '(44)87654-3210', '45678-901', 'PR', 'Curitiba', 'Ecoville', 'foto4.jpg', 'Precisando de algum serviço? Ligue Carlota - Serviços!', 'foto4_sec.jpg', 'foto4_sec2.jpg', 1, 'PRO'),
    ('Ana Santos', 'Help Services', 'ana@example.com', MD5('12345'), '567.890.123-45', '(55)54321-0987', '(55)10987-6543', '56789-012', 'RS', 'Porto Alegre', 'Jardim Botânico', 'foto5.jpg', 'Dedicação é o meu principal motivo.', 'foto5_sec.jpg', 'foto5_sec2.jpg', 1, 'PRO'),
    ('Roberto Fernandes', 'Berto da Construção', 'roberto@example.com', MD5('12345'), '678.901.234-56', '(66)43210-9876', '(66)09876-5432', '67890-123', 'BA', 'Salvador', 'Brotas', 'foto6.jpg', 'Se tem uma coisa que gosto, é trabalhar. Me contate!', 'foto6_sec.jpg', 'foto6_sec2.jpg', 1, 'PRO'),
    ('Sandra Costa', 'Entregue Saúde', 'sandra@example.com', MD5('12345'), '789.012.345-67', '(77)32109-8765', '(77)76543-2109', '78901-234', 'PE', 'Recife', 'Pina', 'foto7.jpg', 'Me chamo Sandra e sou experiente nessas áreas.', 'foto7_sec.jpg', 'foto7_sec2.jpg', 1, 'PRO');



CREATE TABLE servico(
  idserv INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  categoria VARCHAR(45) NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '\"0\" = Inativo / \"1\" = Ativo',
  dataregserv DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  idcli INT(11) NULL,
  PRIMARY KEY (idserv),  
    FOREIGN KEY (idcli)
    REFERENCES cliente (idcli)
    );

 -- Inserindo dados na tabela servico

    INSERT INTO servico (nome, categoria, status)
VALUES
    ('Pedreiro', 'Construção', 1),
    ('Pintor', 'Construção', 1),
    ('Encanador', 'Construção', 1),
    ('Eletricista', 'Construção', 1),
    ('Cabeleireiro', 'Beleza', 1),
    ('Manicure/Pedicure', 'Beleza', 1),
    ('Esteticista', 'Beleza', 1),
    ('Maquiador', 'Beleza', 1),
    ('Dentista', 'Saúde', 1),
    ('Psicólogo', 'Saúde', 1),
    ('Fisioterapeuta', 'Saúde', 1),
    ('Nutricionista', 'Saúde', 1),
    ('Técnico de Informática', 'Manutenção', 1),
    ('Instalação de Câmeras de Segurança', 'Manutenção', 1),
    ('Reparo de Telhados', 'Manutenção', 1),
    ('Reparo de Portões Eletrônicos', 'Manutenção', 1);


CREATE TABLE IF NOT EXISTS pagamento(
  idpag INT(11) NOT NULL AUTO_INCREMENT,
  data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  valor DECIMAL(9,2) NOT NULL,
  numero INT(11) NOT NULL,
  link VARCHAR(255) NOT NULL,
  idcli INT(11) NOT NULL,
  PRIMARY KEY (idpag),
  FOREIGN KEY (idcli)  REFERENCES cliente (idcli)
  );

CREATE TABLE profissional_has_servico(
  idpro INT(11) NOT NULL,
  idserv INT(11) NOT NULL,
  PRIMARY KEY (idpro, idserv),
	FOREIGN KEY (idpro)
    REFERENCES profissional (idpro),
   FOREIGN KEY (idserv)
    REFERENCES servico (idserv)
);

 -- Inserindo dados na tabela profissional_has_servico

 INSERT INTO profissional_has_servico (idpro, idserv)
VALUES

    (1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
    (2, 3), (2, 10), (2, 9), (2, 8),  
    (3, 14), (3, 15), (3, 16), (3, 13),  
    (4, 10), (4, 12), (4, 13), (4, 7), 
    (5, 3), (5, 5), (5, 4), (5, 8),  
    (6, 1), (6, 4), (6, 15), (6, 9),
    (7, 2), (7, 16), (7, 13), (7, 11);