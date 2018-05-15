-- MySQL:


        CREATE TABLE `ci_sessions` (
            `id` varchar(40) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
            `data` blob NOT NULL,
            KEY `ci_sessions_timestamp` (`timestamp`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1


-- CREATE TABLE calculadora.bonos (
--   id   int(11) unsigned NOT NULL AUTO_INCREMENT,
--   nombre varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   codigocaja varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   codigoisin double DEFAULT NULL,
--   monedacobro double DEFAULT NULL,
--   monedabono double DEFAULT NULL,
--   tipotasa varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   tipotasavariable varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   cer varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   cupon double DEFAULT NULL,
--   cantidadcuponanual int(11) unsigned DEFAULT NULL,
--   vencimiento varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   capitalresidual int(11) unsigned DEFAULT NULL,
--   ultimoprecio double DEFAULT NULL,
--   outstanding int(11) unsigned DEFAULT NULL,
--   proximointeres varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   proximoamortizacion varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   legislacion varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   denominacionminima int(11) unsigned DEFAULT NULL,
--   libro varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   hoja varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   emisor_id int(11) unsigned DEFAULT NULL,
--   tipobono_id int(11) unsigned DEFAULT NULL,
--   actualizacionAutomática int(11) unsigned DEFAULT NULL,
--   PRIMARY KEY (id)
--   KEY index_foreignkey_bono_emisor (emisor_id),
--   KEY index_foreignkey_bono_tipobono (tipobono_id),
--   CONSTRAINT cons_fk_bono_emisor_id_id FOREIGN KEY (emisor_id) REFERENCES emisor (id) ON DELETE SET NULL ON UPDATE SET NULL,
--   CONSTRAINT cons_fk_bono_tipobono_id_id FOREIGN KEY (tipobono_id) REFERENCES tipobono (id) ON DELETE SET NULL ON UPDATE SET NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci


-- CREATE TABLE `bono` (
--   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
--   `nombre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `codigocaja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `codigoisin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `monedacobro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `monedabono` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `tipotasa` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `tipotasavariable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `cer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `cupon` double DEFAULT NULL,
--   `cantidadcuponanual` int(11) unsigned DEFAULT NULL,
--   `vencimiento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `capitalresidual` int(11) unsigned DEFAULT NULL,
--   `ultimoprecio` double DEFAULT NULL,
--   `oustanding` int(11) unsigned DEFAULT NULL,
--   `proximointeres` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `proximoamortizacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `legislacion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `denominacionminima` int(11) unsigned DEFAULT NULL,
--   `libro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `hoja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   `emisor_id` int(11) unsigned DEFAULT NULL,
--   `tipobono_id` int(11) unsigned DEFAULT NULL,
--   PRIMARY KEY (`id`),
--   KEY `index_foreignkey_bono_emisor` (`emisor_id`),
--   KEY `index_foreignkey_bono_tipobono` (`tipobono_id`),
--   CONSTRAINT `cons_fk_bono_emisor_id_id` FOREIGN KEY (`emisor_id`) REFERENCES `emisor` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
--   CONSTRAINT `cons_fk_bono_tipobono_id_id` FOREIGN KEY (`tipobono_id`) REFERENCES `tipobono` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
-- ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci















-- emisor
-- 
-- feriados
-- 
-- flujo
-- 
-- moneda
-- 
-- tipobono
-- 
-- tipobono_id

-- CREATE TABLE calculadora.dato (
--   id   int(11) unsigned NOT NULL AUTO_INCREMENT,
--   bono varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   fecha varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   valorNominalActualizado double DEFAULT NULL,
--   valorResidualActualizado double DEFAULT NULL,
--   cuponAmortizacion double DEFAULT NULL,
--   cuponInteres varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   totalFlujo varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
--   fechaActualizacion date DEFAULT NULL,
--   
--   PRIMARY KEY (id)
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ALTER TABLE calculadora.bono ADD COLUMN actualizacionAutomatica int(11);

