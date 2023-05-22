-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Maio-2023 às 17:49
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `busca_service1`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `idcli` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(18) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `cidade` varchar(45) NOT NULL,
  `bairro` varchar(45) NOT NULL,
  `perfil` varchar(3) NOT NULL DEFAULT 'CLI' COMMENT 'ADM=Administrador\\nPRO=Profissional\\nCLI=Cliente',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '"0" = Inativo / "1" = Ativo',
  `dataregcli` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissional`
--

CREATE TABLE `profissional` (
  `idpro` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `titulo` varchar(45) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(30) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(18) NOT NULL,
  `telefone2` varchar(18) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `estado` varchar(45) NOT NULL,
  `cidade` varchar(45) NOT NULL,
  `bairro` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `profissional`
--

INSERT INTO `profissional` (`idpro`, `nome`, `titulo`, `email`, `senha`, `cpf`, `telefone`, `telefone2`, `cep`, `estado`, `cidade`, `bairro`) VALUES
(1, 'Teste Profissional', 'Empresa de Teste', 'testepro@email.com', '827ccb0eea8a706c4c34a16891f84e', '698.547.123-65', '(52)35765-4357', '(23)56765-4323', '72220-240', 'DF', 'Brasília', 'Ceilândia Sul (Ceilândia)');

-- --------------------------------------------------------

--
-- Estrutura da tabela `profissional_has_servico`
--

CREATE TABLE `profissional_has_servico` (
  `idpro` int(11) NOT NULL,
  `idserv` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `servico`
--

CREATE TABLE `servico` (
  `idserv` int(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `categoria` varchar(45) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '"0" = Inativo / "1" = Ativo',
  `dataregserv` datetime NOT NULL DEFAULT current_timestamp(),
  `idcli` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `servico`
--

INSERT INTO `servico` (`idserv`, `nome`, `categoria`, `status`, `dataregserv`, `idcli`) VALUES
(3, 'Pedreiro', 'Construção', 1, '2023-05-20 23:27:30', NULL),
(4, 'Eletricista', 'Reformas', 1, '2023-05-20 23:27:30', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`idcli`);

--
-- Índices para tabela `profissional`
--
ALTER TABLE `profissional`
  ADD PRIMARY KEY (`idpro`);

--
-- Índices para tabela `profissional_has_servico`
--
ALTER TABLE `profissional_has_servico`
  ADD PRIMARY KEY (`idpro`,`idserv`),
  ADD KEY `idserv` (`idserv`);

--
-- Índices para tabela `servico`
--
ALTER TABLE `servico`
  ADD PRIMARY KEY (`idserv`),
  ADD KEY `idcli` (`idcli`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idcli` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `profissional`
--
ALTER TABLE `profissional`
  MODIFY `idpro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `servico`
--
ALTER TABLE `servico`
  MODIFY `idserv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `profissional_has_servico`
--
ALTER TABLE `profissional_has_servico`
  ADD CONSTRAINT `profissional_has_servico_ibfk_1` FOREIGN KEY (`idpro`) REFERENCES `profissional` (`idpro`),
  ADD CONSTRAINT `profissional_has_servico_ibfk_2` FOREIGN KEY (`idserv`) REFERENCES `servico` (`idserv`);

--
-- Limitadores para a tabela `servico`
--
ALTER TABLE `servico`
  ADD CONSTRAINT `servico_ibfk_1` FOREIGN KEY (`idcli`) REFERENCES `cliente` (`idcli`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
