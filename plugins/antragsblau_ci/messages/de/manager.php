<?php

return [
    'title_install'           => 'Antragsblau einrichten',
    'err_settings_ro'         => 'Die Einstellungen können nicht bearbeitet werden, da die Datei config/config.json nicht bearbeitbar ist.
    <br>Das lässt sich mit folgendem Befehl (oder ähnlich, je nach Betriebssystem) auf der Kommandozeile beheben:',
    'err_php_version'         => 'Antragsblau benötigt PHP mindestens in der Version %MIN_VERSION%. Aktuell läuft die Version %CURR_VERSION%. Kontaktieren Sie die oder den Systemadministrator*in, um auf eine aktuelle PHP-Version zu aktualisieren.',
    'language'                => 'Sprache',
    'default_dir'             => 'Standard-Verzeichnis',
    'tmp_dir'                 => 'Temporäres Verzeichis',
    'path_lualatex'           => 'Ort von lualatex',
    'email_settings'          => 'E-Mail-Einstellungen',
    'email_from_address'      => 'E-Mail-Absender - Adresse',
    'email_from_name'         => 'E-Mail-Absender - Name',
    'email_transport'         => 'Versandart',
    'email_sendmail'          => 'Sendmail (lokal)',
    'email_none'              => 'E-Mail-Versand deaktivieren',
    'email_smtp'              => 'SMTP (externer Mailserver)',
    'email_mandrill'          => 'Mandrill',
    'email_mailjet'           => 'Mailjet',
    'email_mailgun'           => 'Mailgun',
    'mailjet_api_key'         => 'Mailjet\'s API-Key',
    'mailjet_secret'          => 'Mailjet\'s Secret Key',
    'mandrill_api'            => 'Mandrill\'s API-Key',
    'mailgun_api'             => 'Mailgun\'s API-Key',
    'mailgun_domain'          => 'E-Mail-Domain',
    'smtp_server'             => 'SMTP Server',
    'smtp_port'               => 'SMTP Port',
    'smtp_login'              => 'SMTP Login-Typ',
    'smtp_tls'                => 'TLS',
    'smtp_login_none'         => 'Kein Login',
    'smtp_username'           => 'SMTP Benutzer*innenname',
    'smtp_password'           => 'SMTP Passwort',
    'confirm_email_addresses' => 'E-Mail-Adressen von neuen Benutzer*innen überprüfen (empfohlen!)',
    'save'                    => 'Speichern',
    'saved'                   => 'Gespeichert.',
    'msg_site_created'        => 'Die Datenbank wurde angelegt.',
    'msg_config_saved'        => 'Konfiguration gespeichert.',
    'created_goon_std_config' => 'Weiter zur allgemeinen Konfiguration',
    'already_created_reinit'  => 'Die Seite wurde bereits konfiguriert.<br>
            Um die Grundinstallation erneut aufzurufen, lege bitte folgende Datei an:<br>
            %FILE%',
    'sidebar_curr_uses'       => 'Aktuelle Einsatzorte',
    'sidebar_old_uses'        => 'Frühere Einsatzorte',
    'sidebar_old_uses_show'   => 'Alle anzeigen',
    'config_finished'         => 'Die Grundkonfiguration ist abgeschlossen.',
    'config_create_tables'    => '<strong>Die Datenbanktabellen sind allerdings noch nicht angelegt.</strong>
            Um das zu erledigen, nutze entweder die Funktion unten, oder rufe den Kommandozeilenbefehl auf:
            <pre>./yii database/create</pre>
            Die SQL-Skripte, um die Tabellen händisch zu erzeugen, liegen hier:
            <pre>assets/db/create.sql</pre>',
    'config_lang'             => 'Sprache',
    'config_db'               => 'Datenbank',
    'config_db_type'          => 'Datenbank-Typ',
    'config_db_host'          => 'Servername',
    'config_db_username'      => 'Benutzername',
    'config_db_password'      => 'Passwort',
    'config_db_password_unch' => 'Unverändert lassen',
    'config_db_no_password'   => 'Kein Passwort',
    'config_db_dbname'        => 'Datenbank-Name',
    'config_db_test'          => 'Datenbank testen',
    'config_db_testing'       => 'Prüfe',
    'config_db_test_succ'     => 'Erfolg',
    'config_db_create'        => 'Notwendige Datenbanktabellen automatisch anlegen',
    'config_db_create_hint'   => '(nicht nötig, bereits vorhanden; aber auch nicht schädlich)',
    'config_admin'            => 'Admin-Zugang',
    'config_admin_already'    => 'Bereits angelegt.',
    'config_admin_alreadyh'   => 'Falls das ein Fehler ist: entferne die "adminUserIds"-Einträge in der config/config.json.',
    'config_admin_email'      => 'Benutzername (E-Mail)',
    'config_admin_pwd'        => 'Passwort',
    'the_site'                => 'Die Seite',
    'finish_install'          => 'Installationsmodus beenden',
    'welcome'                 => 'Willkommen!',
    'site_err_subdomain'      => 'Diese Subdomain wird bereits verwendet.',
    'site_err_contact'        => 'Du musst eine Kontaktadresse angeben.',

    'done_title'      => 'Antragsblau installieren',
    'done_no_del_msg' => 'Um den Installationsmodus zu beenden, lösche die Datei config/INSTALLING.
                Je nach Betriebssystem könnte der Befehl dazu z.B. folgendermaßen lauten:<pre>%DELCMD%</pre>
                Rufe danach diese Seite hier neu auf.',
    'done_nextstep'   => 'Alles klar! Du kannst nun im Folgenden noch ein paar Detaileinstellungen vornehmen.
                Die Antragsblau-Version ist nun unter folgender Adresse erreichbar: %LINK%',
    'done_goto_site'  => 'Zur Seite',
];
