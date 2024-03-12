<?php

return [
'title_install' => 'Instal·la Antragsgrün',
'err_settings_ro' => 'No es poden canviar les configuracions ja que config/config.json no es pot escriure.
<br>Pots solucionar-ho amb la següent comanda (o una similar) a la línia de comandes:',
'err_php_version' => 'Antragsgrün necessita una versió de PHP d’almenys %MIN_VERSION%. Actualment, està instal·lada la versió %CURR_VERSION%. Si us plau, contacta amb l’administrador del sistema sobre com actualitzar a una versió actual de PHP.',
'language' => 'Idioma',
'default_dir' => 'Directori per defecte',
'tmp_dir' => 'Directori temporal',
'path_lualatex' => 'Ubicació de lualatex',
'email_settings' => 'Configuracions d’e-mail',
'email_from_address' => 'E-mail des de - Adreça',
'email_from_name' => 'E-mail des de - Nom',
'email_transport' => 'Transport',
'email_sendmail' => 'Sendmail (local)',
'email_none' => 'Desactivar e-mail',
'email_smtp' => 'SMTP (servidor extern)',
'email_mandrill' => 'Mandrill',
'email_mailjet' => 'Mailjet',
'mandrill_api' => 'Clau API de Mandrill',
'mailjet_api_key' => 'Clau API de Mailjet',
'mailjet_secret' => 'Clau Secreta de Mailjet',
'smtp_server' => 'Servidor SMTP',
'smtp_port' => 'Port SMTP',
'smtp_login' => 'Tipus d’inici de sessió SMTP',
'smtp_tls' => 'TLS',
'smtp_login_none' => 'Sense inici de sessió',
'smtp_username' => 'Usuari SMTP',
'smtp_password' => 'Contrasenya SMTP',
'confirm_email_addresses' => 'Confirma les adreces d’e-mail dels nous usuaris (recomanat!)',
'save' => 'Desa',
'saved' => 'Desat.',
'msg_site_created' => 'La base de dades ha estat creada.',
'msg_config_saved' => 'Configuració desada.',
'created_goon_std_config' => 'Continua amb la configuració regular',
'already_created_reinit' => 'El lloc ja ha estat instal·lat.<br>
            Per obrir de nou l’instal·lador, si us plau crea el següent fitxer:<br>
            %FILE%',
'sidebar_curr_uses' => 'Utilitzat actualment',
'sidebar_old_uses' => 'Utilitzat anteriorment',
'sidebar_old_uses_show' => 'Mostra tot',
'config_finished' => 'La instal·lació bàsica està acabada.',
'config_create_tables' => '<strong>Les taules de la base de dades encara no estan creades.</strong>
            Per crear-les, si us plau utilitza la funció de sota o crida la següent comanda de línia de comandes:
            <pre>./yii database/create</pre>
            Els scripts SQL per crear-les manualment es troben aquí:
            <pre>assets/db/create.sql</pre>',
'config_lang' => 'Idioma',
'config_db' => 'Base de dades',
'config_db_type' => 'Tipus de base de dades',
'config_db_host' => 'Nom del servidor',
'config_db_username' => 'Nom d’usuari',
'config_db_password' => 'Contrasenya',
'config_db_password_unch' => 'Deixa sense canvis',
'config_db_no_password' => 'Sense contrasenya',
'config_db_dbname' => 'Nom de la base de dades',
'config_db_test' => 'Prova la base de dades',
'config_db_testing' => 'Provant',
'config_db_test_succ' => 'Èxit',
'config_db_create' => 'Crea automàticament les taules necessàries',
'config_db_create_hint' => '(no necessari si ja existeixen; tampoc fa mal)',
'config_admin' => 'Compte d’administrador',
'config_admin_already' => 'Ja creat.',
'config_admin_alreadyh' => 'Si això és un error: elimina les entrades "adminUserIds" al fitxer config/config.json.',
'config_admin_email' => 'Nom d’usuari (E-mail)',
'config_admin_pwd' => 'Contrasenya',
'the_site' => 'El lloc',
'finish_install' => 'Sortir de l’Instal·lador',
'welcome' => 'Benvingut!',
'site_err_subdomain' => 'Aquest subdomini ja està en ús.',
'site_err_contact' => 'Has d’introduir una adreça de contacte.',
'email_mailgun' => 'Mailgun',
'mailgun_api' => 'Clau API de Mailgun',
'mailgun_domain' => 'Domini d’e-mail',

'done_title' => 'Antragsgrün instal·lat',
'done_no_del_msg' => 'Si us plau, elimina el fitxer config/INSTALLING per acabar la instal·lació.
                Depenent del sistema operatiu, la comanda per això és alguna cosa com:<pre>%DELCMD%</pre>
                Després de fer-ho, recarrega aquesta pàgina.',
'done_nextstep' => 'Genial! Ara pots configurar alguns detalls addicionals.
                Antragsgrün ja està disponible en la següent adreça: %LINK%',
'done_goto_site' => 'Ves al lloc',
];
