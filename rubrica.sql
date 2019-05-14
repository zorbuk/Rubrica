/*
Navicat MySQL Data Transfer

Source Server         : Servidores
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : rubrica

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-03-21 20:56:00
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for cuentas
-- ----------------------------
DROP TABLE IF EXISTS `cuentas`;
CREATE TABLE `cuentas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `fRegistro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `baneado` varchar(255) NOT NULL DEFAULT '0',
  `textoEstado` varchar(255) DEFAULT 'Nuevo en Rubrica',
  `avatar` varchar(255) NOT NULL DEFAULT '../images/user.png',
  `rango` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
-- ----------------------------
-- Table structure for rubricas
-- ----------------------------
DROP TABLE IF EXISTS `rubricas`;
CREATE TABLE `rubricas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `idCreador` varchar(255) DEFAULT NULL,
  `puntuacion` varchar(255) DEFAULT '5',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for rubricas_preguntas
-- ----------------------------
DROP TABLE IF EXISTS `rubricas_preguntas`;
CREATE TABLE `rubricas_preguntas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRubrica` int(11) DEFAULT NULL,
  `pregunta` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for rubricas_respuestas
-- ----------------------------
DROP TABLE IF EXISTS `rubricas_respuestas`;
CREATE TABLE `rubricas_respuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPregunta` int(11) DEFAULT NULL,
  `respuesta` varchar(255) DEFAULT NULL,
  `idRubrica` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
