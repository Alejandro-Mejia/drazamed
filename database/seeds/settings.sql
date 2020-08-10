--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `group`, `key`, `value`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'site', 'app_name', 'DRAZAMED', 'TEXT', 1, NULL, NULL),
(2, 'site', 'logo', '/assets/img/logo.png', 'IMAGE', 1, NULL, NULL),
(3, 'site', 'mail', 'info@drazamed.com', 'TEXT', 1, NULL, NULL),
(4, 'site', 'website', 'drazamed.com', 'TEXT', 1, NULL, NULL),
(5, 'site', 'address', 'Carrera 6 No. 1-20 Cajicá - Cundinamarca', 'TEXT', 1, NULL, NULL),
(6, 'site', 'timezone', 'UTC', 'TEXT', 1, NULL, NULL),
(7, 'site', 'phone', '1', 'TEXT', 1, NULL, NULL),
(8, 'site', 'discount', '0', 'FLOAT', 1, NULL, NULL),
(9, 'site', 'currency', '$', 'TEXT', 1, NULL, NULL),
(10, 'site', 'curr_position', 'BEFORE', 'TEXT', 1, NULL, NULL),
(11, 'mail', 'username', 'AKIATCMG66637CWVMJPH', 'TEXT', 1, NULL, NULL),
(12, 'mail', 'password', 'BCV0B32dlwclfRaryJ+2gSUJ+356u72C40qm9JxHuVMC', 'TEXT', 1, NULL, NULL),
(13, 'mail', 'address', 'gerencia@drazamed.com', 'TEXT', 1, NULL, NULL),
(14, 'mail', 'name', 'Juan Pablo Pedraza', 'TEXT', 1, NULL, NULL),
(15, 'mail', 'port', '587', 'TEXT', 1, NULL, NULL),
(16, 'mail', 'host', 'email-smtp.us-east-1.amazonaws.com', 'TEXT', 1, NULL, NULL),
(17, 'mail', 'driver', 'smtp', 'TEXT', 1, NULL, NULL),
(18, 'payment', 'mode', '2', 'TEXT', 1, NULL, NULL),
(19, 'payment', 'type', 'TEST', 'TEXT', 1, NULL, NULL);
