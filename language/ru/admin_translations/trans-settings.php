<?php
$txt_html_title                = "Настройки";
$txt_main_title                = "Настройки";
$txt_tab_general               = "Основные";
$txt_tab_email                 = "Электронный адрес";
$txt_tab_apis                  = "API-интерфейсы";
$txt_tab_payment               = "Оплата";
$txt_site_name                 = "Название сайта";
$txt_html_lang                 = "Язык HTML";
$txt_html_lang_explain         = "Значение для атрибута html языка";
$txt_country_name              = "Название страны";
$txt_country_name_explain      = "будет использоваться на странице списка результатов. (<em>e.g.</em> Restaurants in <em>the United States</em>)";
$txt_country_code              = "Код страны";
$txt_country_code_explain      = "ISO 3166-1 alpha-2</a> – двухбуквенный код страны также будет использоваться для построения обязательного url, поэтому не изменяйте его многократно";
$txt_default_city_id           = "Идентификатор города по умолчанию";
$txt_default_city_id_explain   = "Строка базы данных идентификатора города по умолчанию";
$txt_default_city_slug         = "Слаг города, используемого по умолчанию";
$txt_default_city_slug_explain = "Слаги, используемые в url, набраны заглавными строчными буквами города по умолчанию, без специальных символов или пробелов (используйте тире вместо этого)";
$txt_items_per_page            = "Позиций на странице";
$txt_items_per_page_explain    = "Сколько позиций показывать на каждой странице результатов";
$txt_max_pics                  = "Максимум изображений";
$txt_max_pics_explain          = "Сколько изображений может загрузить каждый бизнес";
$txt_default_cat               = "Категория по умолчанию";
$txt_timezone                  = "Часовой пояс";
$txt_timezone_explain          = "Введите ваш часовой пояс. <a href='http://php.net/manual/en/timezones.php' target='_blank'>Click here</a> для списка возможных значений.";
$txt_default_lat               = "Географическая широта по умолчанию";
$txt_default_lng               = "Географическая долгота по умолчанию ";
$txt_admin_email               = "Адрес электронной почты администратора";
$txt_dev_email                 = "Электронный адрес разработчика";
$txt_smtp_server               = "SMPT сервер";
$txt_smtp_user                 = "SMPT пользователь";
$txt_smtp_pass                 = "SMPT пароль";
$txt_smtp_port                 = "SMPT порт";
$txt_gmaps_key                 = "Ключ к картам Google maps";
$txt_gmaps_key_explain         = "Этот ключ API key требуется для отображения карт на вашем сайте.<br><a href='https://developers.google.com/maps/documentation/javascript/get-api-key' target='_blank'>Инструкция по получению ключа API key</a><br>Пожалуйста, убедитесь также в отсутствии оплаты за пользование API или убедитесь что используете его по бесплатной квоте. <a href='https://developers.google.com/maps/faq#usage-limits' target='_blank'>Нажмите здесь</a> для получения подробной информации об этом.";
$txt_paypal_mode               = "Paypal метод (прямо или Sandbox)";
$txt_live                      = "Прямо";
$txt_sandbox                   = "Sandbox";
$txt_paypal_merchant_id        = "Идентификатор получателя платежа Paypal";
$txt_paypal_sandbox_merch_id   = "Идентификатор получателя платежа Paypal Sandbox";
$txt_paypal_bn                 = "Paypal Bn";
$txt_paypal_bn_explain         = "Идентификатор источника, который построил код для кнопки, на которую кликает покупатель, порой известный как сложение построения. <a href='https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/' target='_blank'>See format here </a>. Вставьте только &lt;company&gt; элемент (Смотрите документацию о &lt;company&gt;. Не вставляйте _&lt;Service&gt;_&lt;Product&gt;_&lt;Country&gt;).";
$txt_paypal_checkout_logo_url  = "Отладка Url лого Paypal ";
$txt_paypal_checkout_logo_url_explain = "URL лого, который будет использован на странице оплаты Paypal.";
$txt_currency_code             = "Код валюты";
$txt_currency_code_explain 	   = "3-х символьный <a href='https://en.wikipedia.org/wiki/ISO_4217#Active_codes' target='_blank'>ISO-4217</a> валютный код. Будет   использоваться при отправке информации в Paypal.";
$txt_currency_symbol           = "Символ валюты";
$txt_paypal_locale			   = "Paypal локальная страница входа";
$txt_notify_url  			   = "Нотифицировать URL";
$txt_notify_url_explain 	   = "URL для обработки файла IPN Paypal";

// v.1.05
$txt_facebook_key              = "API key для Facebook";
$txt_facebook_key_explain      = "Используется для входа через социальные сети.";
$txt_facebook_secret           = "Facebook API Secret";
$txt_twitter_key               = "API key для Twitter";
$txt_twitter_key_explain       = "Используется для входа через социальные сети.";
$txt_twitter_secret            = "Twitter API secret";

// v.1.06
$txt_paypal_header             = "Настройки Paypal";
$txt_gateway_mode              = "Режим шлюза";
$txt_gateway_currency          = "Валюта";
$txt_2checkout_header          = "Настройки 2Checkout";
$txt_2checkout_sid             = "Идентификатор основных данных 2Checkout SID";
$txt_2checkout_sandbox_sid     = "Идентификатор основных данных 2Checkout Sandbox SID";
$txt_2checkout_secret          = "Секретное слово 2Checkout";
$txt_2checkout_lang            = "Язык 2Checkout";
$txt_2checkout_notify_url      = "Глобальный URL (нотификация) для 2Checkout";
$txt_mercadopago_header        = "Настройки MercadoPago";

// v.1.08
$txt_stripe_header             = "Настройки Stripe";
$txt_stripe_test_mode          = "Test";
$txt_test_secret_key           = "Test Secret Key";
$txt_test_publishable_key      = "Test Publishable Key";
$txt_live_secret_key           = "Live Secret Key";
$txt_live_publishable_key      = "Live Publishable Key";
$txt_stripe_currency_code      = "3-буквенный код ISO";
$txt_mail_after_post           = "Получать уведомления о публикации / редактировании?";

// v.1.10
$txt_mapbox_secret             = "MapBox Access Token";
$txt_tomtom_secret             = "TomTom Key";
$txt_here_key                  = "HERE App ID";
$txt_here_secret               = "HERE App Code";
$txt_map_provider              = "поставщик карт";

// v.1.11
$txt_default_coupon_qty        = "Купоны за объявление";

// v.1.17
$txt_city_autocomplete         = "Location Autocomplete";