<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo / Dev Quick-Login
    |--------------------------------------------------------------------------
    |
    | When enabled, the login page shows quick-login cards for predefined
    | demo identities. This allows developers and demo presenters to
    | instantly log in under different roles without real CRM credentials.
    |
    | MUST be disabled in production. Gated by APP_DEMO_LOGIN env flag.
    |
    */

    'enabled' => (bool) env('APP_DEMO_LOGIN', false),

    'identities' => [
        'student' => [
            'id' => 'demo-student-001',
            'name' => 'Demo Student',
            'email' => 'student@digital-library.demo',
            'login' => 'demo_student',
            'ad_login' => 'demo_student',
            'role' => 'reader',
            'profile_type' => 'student',
            'label' => 'Студент',
            'description' => 'Демо-доступ — поиск, каталог, подборка, кабинет',
            'icon' => '🎓',
        ],
        'teacher' => [
            'id' => 'demo-teacher-001',
            'name' => 'Demo Faculty',
            'email' => 'faculty@digital-library.demo',
            'login' => 'demo_teacher',
            'ad_login' => 'demo_teacher',
            'role' => 'reader',
            'profile_type' => 'teacher',
            'label' => 'Преподаватель',
            'description' => 'Демо-доступ — силлабус, подборка, рабочий стол',
            'icon' => '📚',
        ],
        'librarian' => [
            'id' => 'demo-librarian-001',
            'name' => 'Панкей Ж.',
            'email' => 'zh.pankey@kaztbu.edu.kz',
            'login' => 'zh.pankey',
            'ad_login' => 'zh.pankey',
            'role' => 'librarian',
            'title' => 'Директор',
            'phone_extension' => '112',
            'label' => 'Библиотекарь',
            'description' => 'Демо-доступ — staff workspace, очереди, доступ, фонд, рецензирование',
            'icon' => '📖',
        ],
        'admin' => [
            'id' => 'demo-admin-001',
            'name' => 'Demo Admin',
            'email' => 'admin@digital-library.demo',
            'login' => 'demo_admin',
            'ad_login' => 'demo_admin',
            'role' => 'admin',
            'label' => 'Администратор',
            'description' => 'Демо-доступ — управление, настройки, отчёты',
            'icon' => '🛡️',
        ],
    ],

];
