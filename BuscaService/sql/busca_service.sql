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
  PRIMARY KEY (idcli),
  UNIQUE KEY unique_cpf_cli (cpf)
  );

  -- Inserindo dados na tabela cliente

  INSERT INTO cliente (nome, email, senha, cpf, telefone, cep, estado, cidade, bairro, perfil, status)
  VALUES 
    ('Admin','admin@email.com','202cb962ac59075b964b07152d234b70','123.123.123-12','(11)11111-1111','55555-555','UF','Cidade','Bairro','ADM',1),
    ('Mariana', 'mariana@email.com', MD5('123456'), '113.242.383-54', '(11)98765-4321', '71645-110', 'DF', 'Brasília', 'Lago Sul', 'CLI', 1),
    ('Lucas', 'lucas@email.com', MD5('123456'), '212.323.464-50', '(22)87654-3210', '72140-100', 'DF', 'Brasília', 'Taguatinga Norte (Taguatinga)', 'CLI', 1),
    ('Ana Carolina', 'anacarolina@email.com', MD5('123456'), '334.414.565-76', '(33)76543-2109', '71882-027', 'DF', 'Riacho Fundo II', 'Barreiro', 'CLI', 1),
    ('Rafael', 'rafael@email.com', MD5('123456'), '434.565.676-77', '(44)65432-1098', '71741-605', 'DF', 'Brasília', 'Park Way', 'CLI', 1),
    ('Carolina', 'carolina@email.com', MD5('123456'), '855.466.787-88', '(55)54321-0987', '72308-718', 'DF', 'Brasília', 'Samambaia Norte (Samambaia)', 'CLI', 1),
    ('Fernanda', 'fernanda@email.com', MD5('123456'), '636.797.808-19', '(66)43210-9876', '72220-407', 'DF', 'Brasília', 'Ceilândia Sul (Ceilândia)', 'CLI', 1),
    ('Gustavo', 'gustavo@email.com', MD5('123456'), '757.868.919-80', '(77)32109-8765', '70847-010', 'DF', 'Brasília', 'Asa Norte', 'CLI', 1);


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
  fotoprin VARCHAR(255) NOT NULL,
  fotosec VARCHAR(255) NULL DEFAULT NULL,
  fotosec2 VARCHAR(255) NULL DEFAULT NULL,
  perfil VARCHAR(3) NOT NULL DEFAULT 'PRO' COMMENT 'ADM=Administrador\\nPRO=Profissional\\nCLI=Cliente',
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '\"0\" = Inativo / \"1\" = Ativo',
  dataregpro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (idpro),
  UNIQUE KEY unique_cpf_pro (cpf)
  );

 -- Inserindo dados na tabela profissional

  INSERT INTO `profissional` (`idpro`, `nome`, `email`, `senha`, `cpf`, `telefone`, `telefone2`, `cep`, `estado`, `cidade`, `bairro`, `titulo`, `descricaonegocio`, `fotoprin`, `fotosec`, `fotosec2`, `perfil`, `status`, `dataregpro`)
  VALUES 
    (1, 'Pedro Henrique', 'pedro@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '123.456.789-10', '(61)98765-4321', '(61)12345-6789', '72427010 ', 'DF', 'Gama', 'Ponte Alta', 'Pedrão dos Serviços', 'Me chamam de Pedrão e sou muito bom no que faço.', '../../uploads/pedreiro_perfil.jpg', '../../uploads/limpaterreno.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
    (2, 'João Silva', 'joao@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '234.567.890-12', '(61)06549-8987', '(61)16987-9749', '71725‑015', 'DF', 'Condomínio Residencial Park Way', 'Candangolândia', 'Jô da Construção', 'Trabalho a anos no ramo de construções.', '../../uploads/servicos.jpg', '../../uploads/encanador.jpeg', '../../uploads/eletricista.jpg', 'PRO', 1, '2023-06-12 10:42:49'),
    (3, 'Maria Souza', 'maria@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '345.678.901-23', '(61)98653-7852', '(61)32899-9995', '72429-005', 'DF', 'Brasília', 'Gama', 'Mary Diarista', 'Sou muito boa no que faço!', '../../uploads/diarista.jpg', '../../uploads/empregada-domestica.png', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
    (4, 'Carlos Oliveira', 'carlos@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '456.789.012-34', '(61)19841-4697', '(61)06598-9365', '70650-110', 'DF', 'Brasília', 'Cruzeiro Novo', 'Carlão - Serviços', 'Precisando de algum serviço? Ligue Carlão - Serviços!', '../../uploads/instalador.jpg', '../../uploads/Conserto-e-instalacao-de-Portao-Eletronico.jpg', '../../uploads/instaladorarcondicionado.png', 'PRO', 1, '2023-06-12 10:42:49'),
    (5, 'Ana Santos', 'ana@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '567.890.123-45', '(61)35898-7389', '(61)13569-5832', '72735-510', 'DF', 'Brasília', 'Vila São José (Brazlândia)', 'Help Serviços de Beleza', 'Dedicação é a minha principal motivação.', '../../uploads/beleza_perfil.jpg', '../../uploads/maquiador.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
    (6, 'Roberto Fernandes', 'roberto@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '678.901.234-56', '(61)16578-9123', '(61)23869-8923', '70070-120', 'DF', 'Brasília', 'Asa Sul', 'Berto Marido de Aluguel', 'Se tem uma coisa que amo é trabalhar. Me contate!', '../../uploads/eletricista_perfil.png', '../../uploads/eletricista_trabalho.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
    (7, 'Sandra Costa', 'sandra@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '789.012.345-67', '(61)98653-7852', '(61)32165-4689', '70722-500', 'DF', 'Brasília', 'Asa Norte', 'Entregue Saúde e Nutrição', 'Me chamo Sandra e sou experiente na área de fisioterapia e nutrição. ', '../../uploads/nutricionista_perfil.jpg', '../../uploads/nutricionista_trabalho.jpg', '../../uploads/fisio.webp', 'PRO', 1, '2023-06-12 10:42:49'),
    (8, 'Marcos da silva ', 'marcosgesso@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '068.479.879-87', '(61)98653-7852', '(13)69869-8364', '72220-225', 'DF', 'Brasília', 'Ceilândia Sul (Ceilândia)', 'Marcos do Gesso', 'Sou profissional qualificado em gesso a mais de três anos. Faça um orçamento sem compromisso. Gesseiro com 3 anos de experiência em Brasília.', '../../uploads/gesseiro.jpg', '../../uploads/gesso_parede.jpg', '', 'PRO', 1, '2023-06-12 11:15:56'),
    (9, 'Tiago Alves', 'tiagomecanica@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '065.498.798-71', '(61)98653-7852', '(61)06598-9365', '71705-002', 'DF', 'Brasília', 'Núcleo Bandeirante', 'Mecânico e Borracheiro', 'Sou profissional qualificado e pronto a atender!', '../../uploads/mecanico_perfil.jpg', NULL, NULL, 'PRO', 1, '2023-06-14 09:41:34'),
    (10, 'Mario', 'mario@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '098.765.432-10', '(61)98123-4567', '(61)99123-4567', '72600-200', 'DF', 'Brasília', 'Recanto das Emas', 'Encanador Mario', 'Realizo serviços de encanamento residencial e comercial. Conte comigo para resolver seus problemas!', '../../uploads/encanador2_perfil.jpg', NULL, NULL, 'PRO', 1, '2023-06-16 17:30:18'),
    (11, 'Carolina Mendes', 'carolina@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '032.654.987-21', '(61)98765-4321', '(61)99654-3210', '72429-010', 'DF', 'Brasília', 'Gama', 'Carol Embelezy', 'Cuide de sua beleza com a melhor profissional da região. Entre em contato para agendar uma sessão!', '../../uploads/embelezamento_perfil.jpg', NULL, NULL, 'PRO', 1, '2023-06-18 09:15:59'),
    (12, 'Paulo Oliveira', 'paulo@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '076.543.210-98', '(61)98123-4567', '(61)99999-9999', '70200-001', 'DF', 'Brasília', 'Asa Sul', 'Paulo Elétrica', 'Especializado em instalações elétricas. Conte comigo para resolver problemas de energia!', '../../uploads/eletricista2_perfil.jpg', NULL, NULL, 'PRO', 1, '2023-06-20 12:40:27'),
    (13, 'Camila Rodrigues', 'camila@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '012.345.678-90', '(61)98765-4321', '(61)98765-4321', '72770-100', 'DF', 'Brasília', 'Brazlândia', 'Camila Tech', 'Problemas com o seu dispositivo eletrônico? Me contate e poderemos resolvê-los. Também sou desenvolvedora e designer!', '../../uploads/tecnologia2_perfil.jpg', '../../uploads/techtrabalho1.jpg', '../../uploads/desenvolvedora2.jpg', 'PRO', 1, '2023-06-22 16:55:14');

CREATE TABLE servico(
  idserv INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(45) NOT NULL,
  categoria VARCHAR(45) NOT NULL,
  status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '\"0\" = Inativo / \"1\" = Ativo',
  dataregserv DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (idserv)
    );

 -- Inserindo dados na tabela servico

  INSERT INTO `servico` (`idserv`, `nome`, `categoria`, `status`, `dataregserv`)
  VALUES
    (1, 'Pedreiro', 'Construção', 1, '2023-06-12 10:42:50'),
    (2, 'Limpador de Terrenos', 'Construção', 1, '2023-06-12 10:42:50'),
    (3, 'Encanador', 'Construção', 1, '2023-06-12 10:42:50'),
    (4, 'Eletricista', 'Construção', 1, '2023-06-12 10:42:50'),
    (5, 'Cabeleireiro', 'Beleza', 1, '2023-06-12 10:42:50'),
    (6, 'Manicure/Pedicure', 'Beleza', 1, '2023-06-12 10:42:50'),
    (7, 'Esteticista', 'Beleza', 1, '2023-06-12 10:42:50'),
    (8, 'Maquiador', 'Beleza', 1, '2023-06-12 10:42:50'),
    (9, 'Dentista', 'Saúde', 1, '2023-06-12 10:42:50'),
    (10, 'Psicólogo', 'Saúde', 1, '2023-06-12 10:42:50'),
    (11, 'Fisioterapeuta', 'Saúde', 1, '2023-06-12 10:42:50'),
    (12, 'Nutricionista', 'Saúde', 1, '2023-06-12 10:42:50'),
    (13, 'Instalação de Câmeras de Segurança', 'Manutenção', 1, '2023-06-12 10:42:50'),
    (14, 'Reparo de Telhados', 'Manutenção', 1, '2023-06-12 10:42:50'),
    (15, 'Reparo de Portões Eletrônicos', 'Manutenção', 1, '2023-06-12 10:42:50'),
    (16, 'Reparo de Ar-Condicionado', 'Manutenção', 1, '2023-06-12 10:42:50'),
    (17, 'Técnico de Informática', 'Informática', 1, '2023-06-12 10:42:50'),
    (18, 'Desenvolvedor Web', 'Informática', 1, '2023-06-12 10:42:50'),
    (19, 'Conserto de Celulares', 'Informática', 1, '2023-06-12 10:42:50'),
    (20, 'Designer Gráfico', 'Informática', 1, '2023-06-12 10:42:50'),
    (21, 'Pintor', 'Reforma', 1, '2023-06-12 10:42:50'),
    (22, 'Azulejista', 'Reforma', 1, '2023-06-12 10:42:50'),
    (23, 'Marceneiro', 'Reforma', 1, '2023-06-12 10:42:50'),
    (24, 'Serralheiro', 'Reforma', 1, '2023-06-12 10:42:50'),
    (25, 'Gesseiro', 'Reforma', 1, '2023-06-12 11:20:50'),
    (26, 'Mecânico Automotivo', 'Automotivo', 1, '2023-06-12 10:42:50'),
    (27, 'Lavagem e Polimento de Carros', 'Automotivo', 1, '2023-06-12 10:42:50'),
    (28, 'Troca de Óleo', 'Automotivo', 1, '2023-06-12 10:42:50'),
    (29, 'Funilaria e Pintura', 'Automotivo', 1, '2023-06-12 10:42:50'),
    (30, 'Limpeza Residencial', 'Limpeza', 1, '2023-06-12 10:42:50'),
    (31, 'Limpeza Comercial', 'Limpeza', 1, '2023-06-12 10:42:50'),
    (32, 'Limpeza de Carpetes e Estofados', 'Limpeza', 1, '2023-06-12 10:42:50'),
    (33, 'Limpeza Pós-Obra', 'Limpeza', 1, '2023-06-12 10:42:50');



CREATE TABLE IF NOT EXISTS avaliacao (
  idava INT(11) NOT NULL AUTO_INCREMENT,
  pontuacao INT(11) NOT NULL,
  data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  comentario VARCHAR(255),
  idcli INT(11) NOT NULL,
  idpro INT(11) NOT NULL,
  PRIMARY KEY (idava),
  FOREIGN KEY (idcli)
    REFERENCES cliente (idcli)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (idpro)
    REFERENCES profissional (idpro)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    CONSTRAINT unique_avaliacao_cliente_profissional UNIQUE (idcli, idpro)
);

 -- Inserir dados na tabela avaliacao

  INSERT INTO `avaliacao` (`idava`, `pontuacao`, `data`, `comentario`, `idcli`, `idpro`)
  VALUES
    (1, 5, '2023-06-12 11:32:39', 'Ótimo serviço e profissional!', 5, 8),
    (2, 4, '2023-06-14 13:03:01', 'É um bom profissional. ', 7, 1),
    (3, 5, '2023-06-14 13:05:02', 'Gostei dos serviços dessa profissional.', 3, 13),
    (4, 5, '2023-06-14 13:09:42', 'Prestou um ótimo serviço, me atendeu muito bem. Atencioso. ', 2, 2),
    (5, 5, '2023-06-14 13:10:49', 'Gostei bastante do serviço e o profissional é bom no que faz. ', 2, 1),
    (6, 5, '2023-06-14 13:14:08', 'Gostei bastante do serviço!', 7, 8),
    (7, 1, '2023-06-14 13:24:12', 'Não recomendo essa Diarista pois ela deixou a minha casa mal limpa. Se pudesse não daria nenhuma estrela. ', 4, 3),
    (8, 5, '2023-06-14 13:24:45', 'Recomendo essa profissional!', 6, 5),
    (9, 5, '2023-06-14 13:25:33', 'O Carlão é o cara. É bom no que faz. ', 3, 4),
    (10, 3, '2023-06-14 13:27:38', 'Contratei seus serviços e não foi lá essas coisas, mas dá pro gasto!', 7, 6),
    (11, 5, '2023-06-14 13:28:43', 'Recomendo bastante o serviço da Sandra. Me ajudou muito na fisioterapia!', 5, 7),
    (12, 5, '2023-04-14 08:28:43', 'Faz de tudo essa moça, parabéns!', 6, 13),
    (13, 5, '2023-05-14 13:40:51', 'Camila resolveu o problema de tela do meu notebook. Nota 10!', 7, 13),
    (14, 2, '2023-06-14 19:03:36', 'Não tive a mesma sorte que os demais dos comentários, mas não merece 1 estrela.', 4, 13);
  
  

  CREATE TABLE profissional_has_servico (
  idpro INT(11) NOT NULL,
  idserv INT(11) NOT NULL,
  PRIMARY KEY (idpro, idserv),
  FOREIGN KEY (idpro)
    REFERENCES profissional (idpro)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (idserv)
    REFERENCES servico (idserv)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


 -- Inserindo dados na tabela profissional_has_servico

 INSERT INTO `profissional_has_servico` (`idpro`, `idserv`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 3),
(2, 4),
(3, 29),
(4, 13),
(4, 14),
(4, 15),
(4, 16),
(5, 5),
(5, 8),
(6, 1),
(6, 4),
(6, 15),
(7, 11),
(7, 12),
(8, 33),
(10, 3),
(13, 17),
(13, 18),
(13, 19),
(13, 20);
