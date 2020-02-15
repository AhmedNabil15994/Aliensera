<?php return array (
  'app' => 
  array (
    'IMAGE_BASE' => 'http://backend.aliensera.loc/',
    'name' => 'AlienSera',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => 'base64:ksY2GRT531zYk5PZNDeUCELHt5WgdDGeuHAzEFDoX4M=',
    'cipher' => 'AES-256-CBC',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Watson\\Active\\ActiveServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\SettingsServiceProvider',
      25 => 'App\\Providers\\AuthServiceProvider',
      26 => 'App\\Providers\\BroadcastServiceProvider',
      27 => 'App\\Providers\\EventServiceProvider',
      28 => 'App\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'Active' => 'Watson\\Active\\Facades\\Active',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api' => 
      array (
        'driver' => 'token',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => '',
        'secret' => '',
        'app_id' => '',
        'options' => 
        array (
          'cluster' => 'eu',
          'encrypted' => true,
          'useTLS' => true,
        ),
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/var/www/Server/Projects/Aliensera/backend/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
      ),
    ),
    'prefix' => 'aliensera_cache',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'aliensera',
        'prefix' => '',
        'foreign_key_constraints' => true,
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'aliensera',
        'username' => 'root',
        'password' => 'toor',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'modes' => 
        array (
          0 => 'STRICT_TRANS_TABLES',
          1 => 'NO_ZERO_IN_DATE',
          2 => 'NO_ZERO_DATE',
          3 => 'ERROR_FOR_DIVISION_BY_ZERO',
          4 => 'NO_AUTO_CREATE_USER',
          5 => 'NO_ENGINE_SUBSTITUTION',
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'aliensera',
        'username' => 'root',
        'password' => 'toor',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'aliensera',
        'username' => 'root',
        'password' => 'toor',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
      'cache' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 1,
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/Server/Projects/Aliensera/backend/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/Server/Projects/Aliensera/backend/storage/app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
        'url' => NULL,
      ),
    ),
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => 10,
    ),
    'argon' => 
    array (
      'memory' => 1024,
      'threads' => 2,
      'time' => 2,
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'daily',
        ),
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => '/var/www/Server/Projects/Aliensera/backend/storage/logs/laravel.log',
        'level' => 'debug',
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => '/var/www/Server/Projects/Aliensera/backend/storage/logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'critical',
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'formatter' => NULL,
        'with' => 
        array (
          'stream' => 'php://stderr',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => '587',
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Example',
    ),
    'encryption' => 'tls',
    'username' => 'sayrat.com@gmail.com',
    'password' => 'cars!131313',
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/var/www/Server/Projects/Aliensera/backend/resources/views/vendor/mail',
      ),
    ),
    'log_channel' => NULL,
  ),
  'permissions' => 
  array (
    'DashboardControllers@Dashboard' => 'general',
    'DashboardControllers@getChartData' => 'general',
    'ChatControllers@index' => 'general',
    'ChatControllers@getOne' => 'general',
    'ChatControllers@newMessage' => 'general',
    'ChatControllers@chatWith' => 'general',
    'ChatControllers@uploadAttachment' => 'general',
    'AuthControllers@login' => 'login',
    'AuthControllers@doLogin' => 'doLogin',
    'AuthControllers@logout' => 'logout',
    'UsersControllers@index' => 'list-users',
    'UsersControllers@edit' => 'edit-user',
    'UsersControllers@view' => 'view-user',
    'UsersControllers@update' => 'edit-user',
    'UsersControllers@add' => 'add-user',
    'UsersControllers@create' => 'add-user',
    'UsersControllers@delete' => 'delete-user',
    'UsersControllers@restore' => 'restore-user',
    'UsersControllers@getProfile' => 'update-profile',
    'UsersControllers@updateProfile' => 'update-profile',
    'GroupsControllers@index' => 'list-groups',
    'GroupsControllers@edit' => 'edit-group',
    'GroupsControllers@update' => 'edit-group',
    'GroupsControllers@add' => 'add-group',
    'GroupsControllers@create' => 'add-group',
    'GroupsControllers@delete' => 'delete-group',
    'CoursesControllers@index' => 'list-courses',
    'CoursesControllers@edit' => 'edit-course',
    'CoursesControllers@view' => 'view-course',
    'CoursesControllers@update' => 'edit-course',
    'CoursesControllers@add' => 'add-course',
    'CoursesControllers@create' => 'add-course',
    'CoursesControllers@delete' => 'delete-course',
    'CoursesControllers@restore' => 'restore-course',
    'CoursesControllers@addImage' => 'add-course-image',
    'CoursesControllers@imageDelete' => 'delete-course-image',
    'CoursesControllers@deleteReview' => 'delete-course-review',
    'CoursesControllers@getUniversities' => 'get-universities',
    'CoursesControllers@getFaculties' => 'get-faculties',
    'UniversityControllers@index' => 'list-universities',
    'UniversityControllers@edit' => 'edit-university',
    'UniversityControllers@update' => 'edit-university',
    'UniversityControllers@add' => 'add-university',
    'UniversityControllers@create' => 'add-university',
    'UniversityControllers@delete' => 'delete-university',
    'FacultyControllers@index' => 'list-faculties',
    'FacultyControllers@edit' => 'edit-faculty',
    'FacultyControllers@update' => 'edit-faculty',
    'FacultyControllers@add' => 'add-faculty',
    'FacultyControllers@create' => 'add-faculty',
    'FacultyControllers@delete' => 'delete-faculty',
    'FieldControllers@index' => 'list-fields',
    'FieldControllers@edit' => 'edit-field',
    'FieldControllers@update' => 'edit-field',
    'FieldControllers@add' => 'add-field',
    'FieldControllers@create' => 'add-field',
    'FieldControllers@delete' => 'delete-field',
    'LessonControllers@index' => 'list-lessons',
    'LessonControllers@edit' => 'edit-lesson',
    'LessonControllers@update' => 'edit-lesson',
    'LessonControllers@add' => 'add-lesson',
    'LessonControllers@create' => 'add-lesson',
    'LessonControllers@delete' => 'delete-lesson',
    'LessonControllers@uploadVideo' => 'add-lesson-video',
    'LessonControllers@removeVideo' => 'delete-lesson-video',
    'LessonControllers@addQuestion' => 'add-lesson-question',
    'LessonControllers@removeQuestion' => 'delete-lesson-question',
    'LessonControllers@addComment' => 'add-video-comment',
    'LessonControllers@removeComment' => 'delete-video-comment',
    'VariablesControllers@index' => 'list-variables',
    'VariablesControllers@edit' => 'edit-variable',
    'VariablesControllers@update' => 'edit-variable',
    'VariablesControllers@add' => 'add-variable',
    'VariablesControllers@create' => 'add-variable',
    'VariablesControllers@delete' => 'delete-variable',
    'PagesControllers@index' => 'list-pages',
    'PagesControllers@edit' => 'edit-page',
    'PagesControllers@update' => 'edit-page',
    'PagesControllers@add' => 'add-page',
    'PagesControllers@create' => 'add-page',
    'PagesControllers@delete' => 'delete-page',
    'RequestControllers@index' => 'list-student-requests',
    'RequestControllers@update' => 'edit-student-request',
    'RequestControllers@delete' => 'delete-student-request',
    'CourseStudentControllers@index' => 'list-course-students',
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => NULL,
      'secret' => NULL,
      'endpoint' => 'api.mailgun.net',
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
      'webhook' => 
      array (
        'secret' => NULL,
        'tolerance' => 300,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => '120',
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/var/www/Server/Projects/Aliensera/backend/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'aliensera_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
    'same_site' => NULL,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/var/www/Server/Projects/Aliensera/backend/resources/views',
      1 => '/var/www/Server/Projects/Aliensera/backend/app/Modules',
    ),
    'compiled' => '/var/www/Server/Projects/Aliensera/backend/storage/framework/views',
  ),
  'vimeo' => 
  array (
    'default' => 'main',
    'connections' => 
    array (
      'main' => 
      array (
        'client_id' => '768de31caaa059ee2ae606b37beb51150e0d1ede',
        'client_secret' => 'vZ8jJy/h/oQyHYfE1cbH8CEx7G9hJg3mCxWd60NCK6sLELmQ2L3O6uHcW6nKQiK2fJixQvfxBocWBh33OkOz4WVGWVIcJAHNoJllvk7Mxzor4Nv3QLTui9j9DkJ46pDd',
        'access_token' => '2459d81ad89f48966c811689929d8496',
      ),
    ),
  ),
  'debug-server' => 
  array (
    'host' => 'tcp://127.0.0.1:9912',
  ),
  'active' => 
  array (
    'class' => 'active',
  ),
  'trustedproxy' => 
  array (
    'proxies' => NULL,
    'headers' => 30,
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'dont_alias' => 
    array (
    ),
  ),
);
