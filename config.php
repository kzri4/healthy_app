<?php

define('DSN', 'mysql:host=db; dbname=healthy_db; charset=utf8');
define('USER', 'healthy_admin');
define('PASSWORD', '1234');

define('MSG_MEAS_DATE_REQUIRED', '検温日が入力されていません');
define('MSG_BODY_TEMP_REQUIRED', '体温が入力されていません');
define('MSG_MEAS_DATE_SAME', '入力された検温日のデータは既に存在します');