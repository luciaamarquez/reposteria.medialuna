-- Ejecutar una vez sobre una base medialuna ya creada desde database/medialuna (1).sql.
-- Corrige el nombre de columna original si todavía está escrito como id_ususario.
SET @renombrar_usuario = IF(
    EXISTS (
        SELECT 1
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = 'pedidos'
          AND COLUMN_NAME = 'id_ususario'
    ),
    'ALTER TABLE pedidos CHANGE COLUMN id_ususario id_usuario INT(11) NOT NULL',
    'SELECT 1'
);
PREPARE sentencia FROM @renombrar_usuario;
EXECUTE sentencia;
DEALLOCATE PREPARE sentencia;

ALTER TABLE pedidos
    ADD COLUMN IF NOT EXISTS mercado_pago_preference_id VARCHAR(100) NULL,
    ADD COLUMN IF NOT EXISTS mercado_pago_payment_id VARCHAR(100) NULL,
    ADD COLUMN IF NOT EXISTS email_enviado TINYINT(1) NOT NULL DEFAULT 0;

INSERT INTO categoria (nombre)
SELECT 'Donuts'
WHERE NOT EXISTS (SELECT 1 FROM categoria WHERE nombre = 'Donuts');

INSERT INTO categoria (nombre)
SELECT 'Muffins'
WHERE NOT EXISTS (SELECT 1 FROM categoria WHERE nombre = 'Muffins');