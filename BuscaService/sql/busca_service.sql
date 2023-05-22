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

  INSERT INTO cliente (nome, email, senha, cpf, telefone, cep, estado, cidade, bairro, perfil, status, dataregcli)
  VALUES ('Admin','admin@email.com','202cb962ac59075b964b07152d234b70','123.123.123-12','(11)11111-1111','55555-555','UF','Cidade','Bairro','ADM',1,'2023-01-01 20:36:13');


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
