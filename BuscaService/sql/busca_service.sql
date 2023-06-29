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

  INSERT INTO `cliente` (`idcli`, `nome`, `email`, `senha`, `cpf`, `telefone`, `cep`, `estado`, `cidade`, `bairro`, `perfil`, `status`, `dataregcli`) VALUES
  (1, 'Admin', 'admin@email.com', '202cb962ac59075b964b07152d234b70', '123.123.123-12', '(11)11111-1111', '55555-555', 'UF', 'Cidade', 'Bairro', 'ADM', 1, '2023-06-12 10:42:49'),
  (2, 'Mariana', 'mariana@email.com', 'e10adc3949ba59abbe56e057f20f883e', '113.242.383-54', '(61)82353-6836', '72622-200', 'DF', 'Brasília', 'Recanto das Emas', 'CLI', 1, '2023-06-12 10:42:49'),
  (3, 'Lucas', 'lucas@email.com', 'e10adc3949ba59abbe56e057f20f883e', '212.323.464-50', '(61)35898-6546', '72115-750', 'DF', 'Brasília', 'Taguatinga Norte (Taguatinga)', 'CLI', 1, '2023-06-12 10:42:49'),
  (4, 'Ana Carolina', 'anacarolina@email.com', 'e10adc3949ba59abbe56e057f20f883e', '334.414.565-76', '(61)38956-8769', '72302-302', 'DF', 'Brasília', 'Samambaia Sul (Samambaia)', 'CLI', 1, '2023-06-12 10:42:49'),
  (5, 'Rafael', 'rafael@email.com', 'e10adc3949ba59abbe56e057f20f883e', '434.565.676-77', '(61)45486-9856', '71691-087', 'DF', 'Brasília', 'Centro (São Sebastião)', 'CLI', 1, '2023-06-12 10:42:49'),
  (6, 'Carolina', 'carolina@email.com', 'e10adc3949ba59abbe56e057f20f883e', '855.466.787-88', '(61)25968-9986', '71676-250', 'DF', 'Brasília', 'Setor de Habitações Individuais Sul', 'CLI', 1, '2023-06-12 10:42:49'),
  (7, 'Fernanda', 'fernanda@email.com', 'e10adc3949ba59abbe56e057f20f883e', '636.797.808-19', '(61)25476-9845', '72500-101', 'DF', 'Brasília', 'Santa Maria', 'CLI', 1, '2023-06-12 10:42:49'),
  (8, 'Gustavo', 'gustavo@email.com', 'e10adc3949ba59abbe56e057f20f883e', '757.868.919-80', '(61)54896-8954', '71805-100', 'DF', 'Brasília', 'Riacho Fundo I', 'CLI', 1, '2023-06-12 10:42:49'),
  (9, 'Selma Alves', 'selma@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '027.671.961-14', '(61)99206-6241', '72325-200', 'DF', 'Brasília', 'Samambaia Norte (Samambaia)', 'CLI', 1, '2023-06-12 11:07:34'),
  (11, 'Angelica ferreira', 'angelica@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '068.479.879-87', '(61)99206-6241', '72325-200', 'DF', 'Brasília', 'Samambaia Norte (Samambaia)', 'CLI', 1, '2023-06-12 11:31:06');


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
  descricaonegocio VARCHAR(255) NOT NULL,
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

  INSERT INTO `profissional` (`idpro`, `nome`, `email`, `senha`, `cpf`, `telefone`, `telefone2`, `cep`, `estado`, `cidade`, `bairro`, `titulo`, `descricaonegocio`, `fotoprin`, `fotosec`, `fotosec2`, `perfil`, `status`, `dataregpro`) VALUES
(1, 'Pedro Henrique', 'pedro@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '123.456.789-10', '(61)98765-4321', '(61)12345-6789', '72427010 ', 'DF', 'Gama', 'Ponte Alta', 'Pedrão dos Serviços', 'Me chamam de Pedrão e sou muito bom no que faço.', '../../uploads/pedreiro_perfil.jpg', '../../uploads/limpaterreno.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
(2, 'João Silva', 'joao@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '234.567.890-12', '(61)06549-8987', '(61)16987-9749', '71725‑015', 'DF', 'Condomínio Residencial Park Way', 'Candangolândia', 'Jô Serviços', 'Trabalho a anos no ramo de construções.', '../../uploads/servicos.jpg', '../../uploads/encanador.jpeg', '../../uploads/eletricista.jpg', 'PRO', 1, '2023-06-12 10:42:49'),
(3, 'Maria Souza', 'maria@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '345.678.901-23', '(61)98653-7852', '(61)32899-9995', '72429-005', 'DF', 'Brasília', 'Gama', 'Mary Diarista', 'Sou muito boa no que faço!', '../../uploads/diarista.jpg', '../../uploads/empregada-domestica.png', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
(4, 'Carlos Oliveira', 'carlos@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '456.789.012-34', '(61)19841-4697', '(61)06598-9365', '70650-110', 'DF', 'Brasília', 'Cruzeiro Novo', 'Carlão - Serviços', 'Precisando de algum serviço? Ligue Carlão - Serviços!', '../../uploads/instalador.jpg', '../../uploads/Conserto-e-instalacao-de-Portao-Eletronico.jpg', '../../uploads/instaladorarcondicionado.png', 'PRO', 1, '2023-06-12 10:42:49'),
(5, 'Ana Santos', 'ana@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '567.890.123-45', '(61)35898-7389', '(61)13569-5832', '72735-510', 'DF', 'Brasília', 'Vila São José (Brazlândia)', 'Help Serviços de Beleza', 'Dedicação é a minha principal motivação.', '../../uploads/beleza_perfil.jpg', '../../uploads/maquiador.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
(6, 'Roberto Fernandes', 'roberto@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '678.901.234-56', '(61)16578-9123', '(61)23869-8923', '70070-120', 'DF', 'Brasília', 'Asa Sul', 'Berto Marido de Aluguel', 'Se tem uma coisa que amo é trabalhar. Me contate!', '../../uploads/eletricista_perfil.png', '../../uploads/eletricista_trabalho.jpg', NULL, 'PRO', 1, '2023-06-12 10:42:49'),
(7, 'Sandra Costa', 'sandra@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '789.012.345-67', '(61)98653-7852', '(61)32165-4689', '70722-500', 'DF', 'Brasília', 'Asa Norte', 'Entregue Saúde e Nutrição', 'Me chamo Sandra e sou experiente na área de fisioterapia e nutrição. ', '../../uploads/nutricionista_perfil.jpg', '../../uploads/nutricionista_trabalho.jpg', '../../uploads/fisio.webp', 'PRO', 1, '2023-06-12 10:42:49'),
(8, 'Marcos da silva ', 'marcosgesso@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '068.479.879-87', '(61)98653-7852', '(13)69869-8364', '72220-225', 'DF', 'Brasília', 'Ceilândia Sul (Ceilândia)', 'Marcos do Gesso', 'Sou profissional qualificado em gesso a mais de três anos. Faça um orçamento sem compromisso. Gesseiro com 3 anos de experiência em Brasília.', '../../uploads/gesseiro.jpg', '../../uploads/gesso_parede.jpg', '', 'PRO', 1, '2023-06-12 11:15:56'),
(13, 'Tiago Alves', 'tiagomecanica@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '065.498.798-71', '(61)98653-7852', '(61)06598-9365', '71705-002', 'DF', 'Brasília', 'Núcleo Bandeirante', 'Mecânico e Borracheiro', 'Sou profissional qualificado e pronto a atender!', '../../uploads/mecanico_perfil.jpg', '', '', 'PRO', 1, '2023-06-14 09:41:34'),
(14, 'Eduardo dos Santos', 'eduardoti@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '056.876.971-46', '(61)65965-4691', '(61)35287-9654', '73391-730', 'DF', 'Brasília', 'Planaltina', 'Técnico em informática', 'Desenvolvo softwares e soluções para sistemas. Técnico em informática com vasta experiência no mercado de trabalho. ', '../../uploads/profissionalti.jpg', '../../uploads/solucaoti.jpg', '', 'PRO', 1, '2023-06-14 14:25:31'),
(15, 'Mirtes Araújo ', 'mirtesfaxina@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '368.654.69', '(61)36534-6836', '(61)35698-3663', '72622-200', 'DF', 'Brasília', 'Recanto das Emas', 'Diarista e Empregada Doméstica', 'Com experiência a mais de 15 anos, sou diarista, faxineira e pau pra toda obra! \r\nMe contrate e não se arrependa!', '../../uploads/limpezaresidencial_perfil.jpg', '../../uploads/limpezaresidencial_trabalho.jpg', '../../uploads/limpezaresidencial_trabalho2.jpg', 'PRO', 1, '2023-06-14 14:34:00'),
(16, 'Maria Menezes', 'mariaunhas@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '368.695.498-36', '(61)36879-8695', '(61)38931-2469', '71587-242', 'DF', 'Brasília', 'Paranoá Parque (Paranoá)', 'Manicure e Pedicure', 'Sou especialista em unhas de Gel. Faço pé e mão com perfeição. \r\nEntre em contato para orçamentos, estou `a disposição.', '../../uploads/manicure_trabalho.jpg', '../../uploads/Manicure-Pedicure.jpg', '', 'PRO', 1, '2023-06-14 14:52:40'),
(17, 'Antônio', 'antoniomarceneiro@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '368.863.258-69', '(61)36858-9368', '(61)36868-9965', '71805-100', 'DF', 'Brasília', 'Riacho Fundo I', 'Marcenaria do Antônio', 'Sou Marceneiro e faço serviços sob encomenda. \r\nContrate meu serviço e fique satisfeito!', '../../uploads/marcenaria.webp', '../../uploads/marcenariaa.webp', '', 'PRO', 1, '2023-06-14 15:17:36'),
(18, 'Artur Gomes', 'arturpintor@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '856.528.653-68', '(61)36586-9865', '(61)36868-3693', '72115-300', 'DF', 'Brasília', 'Taguatinga Norte (Taguatinga)', 'Pintor e Serralheiro Profissional', 'Amor pela profissão! ', '../../uploads/pintura.webp', '../../uploads/pintor.webp', '', 'PRO', 1, '2023-06-14 15:30:52'),
(19, 'Marina Tavares', 'marinapsicologa@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '895.386.336-56', '(61)68612-5968', '(61)36869-8456', '72302-309', 'DF', 'Brasília', 'Samambaia Sul (Samambaia)', 'Clinica de psicologia', 'Tradição a mais de 10 anos!', '../../uploads/psicologia-2.webp', '../../uploads/psicologia .webp', '', 'PRO', 1, '2023-06-14 16:25:38'),
(20, 'Milena Pereira', 'milenadentista@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '895.669.856-53', '(61)36534-6836', '(61)36868-9965', '71587-240', 'DF', 'Brasília', 'Paranoá Parque (Paranoá)', 'Clinica Dentão ', 'Venha conhecer nossas novas instalações. Cuide do seu sorriso! Faça nos uma visita. ', '../../uploads/dentista.jpeg', '../../uploads/dentes.png', '', 'PRO', 1, '2023-06-14 16:27:46'),
(21, 'Mario', 'mario@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '768.768.977-87', '(61)98657-5675', '(61)96756-3456', '72325-209', 'DF', 'Brasília', 'Samambaia Norte (Samambaia)', 'Encanador Mario', 'Sou um bom encanador!', '../../uploads/encanador.jpeg', '', '', 'PRO', 1, '2023-06-16 11:25:49'),
(22, 'Camila Oliveira', 'camila@email.com', '827ccb0eea8a706c4c34a16891f84e7b', '456.756.756-75', '(62)54765-7567', '(56)37656-7567', '72325-200', 'DF', 'Brasília', 'Samambaia Norte (Samambaia)', 'Tech Ca', 'Problemas com o seu dispositivo eletrônico? Me contate!', '../../uploads/tecnologia2_perfil.jpg', '../../uploads/desenvolvedora2.jpg', '../../uploads/techtrabalho1.jpg', 'PRO', 1, '2023-06-16 11:29:58');


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

  INSERT INTO `avaliacao` (`idava`, `pontuacao`, `data`, `comentario`, `idcli`, `idpro`) VALUES
  (1, 5, '2023-06-12 11:32:39', 'Ótimo serviço e profissional!', 11, 8),
  (2, 4, '2023-06-14 13:03:01', 'É um bom profissional. ', 9, 1),
  (3, 5, '2023-06-14 13:05:02', 'Gostei dos serviços desse profissional.', 9, 13),
  (4, 5, '2023-06-14 13:09:42', 'Prestou um ótimo serviço, me atendeu muito bem. Atencioso. ', 2, 2),
  (5, 5, '2023-06-14 13:10:49', 'Gostei bastante do serviço e o profissional é bom no que faz. ', 2, 1),
  (6, 5, '2023-06-14 13:14:08', 'Gostei bastante do serviço!', 2, 8),
  (7, 1, '2023-06-14 13:24:12', 'Não recomendo essa Diarista pois ela deixou a minha casa mal limpa. Se pudesse não daria nenhuma estrela. ', 3, 3),
  (8, 5, '2023-06-14 13:24:45', 'Recomendo essa profissional!', 3, 5),
  (9, 5, '2023-06-14 13:25:33', 'O Carlão é o cara. É bom no que faz. ', 3, 4),
  (10, 3, '2023-06-14 13:27:38', 'Contratei seus serviços e não foi lá essas coisas, mas dá pro gasto!', 8, 6),
  (11, 5, '2023-06-14 13:28:43', 'Recomendo bastante o serviço da Sandra. Me ajudou muito na fisioterapia!', 8, 7),
  (12, 5, '2023-06-14 14:27:24', 'Contratei os serviços do Eduardo Santos e não me arrependi. Top!', 7, 14),
  (13, 1, '2023-06-14 14:28:26', 'Realmente essa diarista não está com nada.', 7, 3),
  (14, 5, '2023-06-14 14:28:57', 'Muito bom! Eu recomendo. ', 7, 8),
  (15, 5, '2023-06-14 14:43:58', 'Contratei a Mirtes para fazer uma faxina em minha casa e ela foi super caprichosa, arrasou.', 4, 15),
  (16, 5, '2023-06-14 14:45:07', 'Estava precisando de uma solução para a minha empresa e o Edu foi o melhor profissional que encontrei. Valeu muito a pena!', 4, 14),
  (17, 5, '2023-06-14 15:03:35', 'Boa no que faz. ', 8, 16),
  (18, 4, '2023-06-14 15:04:17', 'Está de parabéns!', 8, 13),
  (19, 5, '2023-06-14 15:09:35', 'Unhas lindas, ótimo trabalho!', 5, 16),
  (20, 5, '2023-06-14 15:10:17', 'Adorei!', 5, 15),
  (21, 3, '2023-06-14 15:19:32', 'Não gostei muito dos móveis que ele fez pra mim. Não é muito profissional. ', 11, 17),
  (22, 4, '2023-06-14 15:20:18', 'Gostei do serviço!', 11, 16),
  (23, 3, '2023-06-14 15:33:28', 'Mais ou menos!', 3, 18),
  (24, 3, '2023-06-14 15:44:53', 'Não gostei!', 9, 18),
  (25, 4, '2023-06-14 15:45:28', 'Gostei!', 9, 2),
  (26, 3, '2023-06-14 16:32:42', 'Mais ou menos. ', 4, 19),
  (27, 4, '2023-06-14 16:33:19', 'Gostei da nova loja, mas poderia ser mais perto da minha cidade. ', 4, 20);

  
  

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
(13, 25),
(13, 27),
(14, 17),
(14, 18),
(15, 29),
(15, 30),
(15, 32),
(16, 6),
(17, 23),
(17, 24),
(18, 21),
(18, 24),
(19, 10),
(20, 9),
(21, 3),
(22, 17),
(22, 18),
(22, 19),
(22, 20);