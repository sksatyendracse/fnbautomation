SET FOREIGN_KEY_CHECKS=0;

--
-- Update from 1.05 to 1.06
--

-- 34 config
ALTER TABLE `config` CHANGE `property` `property` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '';

-- 35 config
INSERT INTO `config` (`type`, `property`, `value`) VALUES
('payment', 'mercadopago_mode', '1'),
('payment', 'mercadopago_client_id', 'MERCADO_PAGO_ID'),
('payment', 'mercadopago_client_secret', 'MERCADO_PAGO_CLIENT_SECRET'),
('payment', 'mercadopago_currency_id', 'BRL'),
('payment', 'mercadopago_notification_url', 'http://example.com/ipn-mercadopago.php');

SET FOREIGN_KEY_CHECKS=1;