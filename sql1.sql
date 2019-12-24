ALTER TABLE `tecnicos` ADD COLUMN `uid` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `tecnicos` ADD COLUMN `matsiape` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `tecnicos` CHANGE COLUMN `NOME DO TECNICO ADMINISTRATIVO` `nome` varchar(255) NULL DEFAULT '';
ALTER TABLE `tecnicos` CHANGE COLUMN `SETOR DE LOTACAO` `setor` varchar(255) NULL DEFAULT '';
ALTER TABLE `tecnicos` CHANGE COLUMN `ANO DE INGRESSO` `ingresso` int(11) NULL DEFAULT NULL;
ALTER TABLE `tecnicos` CHANGE COLUMN `REGIME DE TRABALHO` `regime_trabalho` varchar(255) NULL DEFAULT '';
ALTER TABLE `tecnicos` CHANGE COLUMN `TITULACAO` `titulacao` varchar(255) NULL DEFAULT '';
ALTER TABLE `tecnicos` CHANGE COLUMN `CARGO` `cargo` varchar(255) NULL DEFAULT '';




ALTER TABLE `tecnicos` ADD COLUMN `uid` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `tecnicos` ADD COLUMN `matsiape` varchar(255) NULL DEFAULT NULL;
ALTER TABLE `alunos` CHANGE COLUMN `NOME DO ALUNO` `nome` varchar(255) NULL DEFAULT '';
ALTER TABLE `alunos` CHANGE COLUMN `CURSO` `curso` varchar(255) NULL DEFAULT '';
ALTER TABLE `alunos` CHANGE COLUMN `PERIODO DE INGRESSO` `periodo_ingresso` varchar(255) NULL DEFAULT '';
ALTER TABLE `alunos` CHANGE COLUMN `ANO DE INGRESSO` `ano_ingresso` int(11) NULL DEFAULT NULL;




UPDATE tecnicos as TEC
	INNER JOIN ldap as LDAP ON (TEC.nome=LDAP.displayname)
SET
	TEC.uid=LDAP.uid,
	TEC.matsiape=LDAP.matsiape
WHERE
	TEC.nome=LDAP.displayname



UPDATE alunos as A
	INNER JOIN ldap as LDAP ON (A.nome=LDAP.displayname)
SET
	A.uid=LDAP.uid,
	A.matgrad=LDAP.matgrad
WHERE
	A.nome=LDAP.displayname