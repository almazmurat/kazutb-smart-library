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
            'name' => 'Айдана Нурланова',
            'email' => 'aidana.nurlanova@demo.kazutb.kz',
            'login' => 'demo_student',
            'ad_login' => 'demo_student',
            'role' => 'reader',
            'profile_type' => 'student',
            'label' => 'Студент',
            'description' => 'Читатель — поиск, каталог, подборка, кабинет',
            'icon' => '🎓',
        ],
        'teacher' => [
            'id' => 'demo-teacher-001',
            'name' => 'Болат Серікұлы',
            'email' => 'bolat.serikuly@demo.kazutb.kz',
            'login' => 'demo_teacher',
            'ad_login' => 'demo_teacher',
            'role' => 'reader',
            'profile_type' => 'teacher',
            'label' => 'Преподаватель',
            'description' => 'Преподаватель — силлабус, подборка, рабочий стол',
            'icon' => '📚',
        ],
        'librarian' => [
            'id' => 'demo-librarian-001',
            'name' => 'Гүлнар Тұрсынова',
            'email' => 'gulnar.tursynova@demo.kazutb.kz',
            'login' => 'demo_librarian',
            'ad_login' => 'demo_librarian',
            'role' => 'librarian',
            'label' => 'Библиотекарь',
            'description' => 'Сотрудник — выдача, возврат, фонд, рецензирование',
            'icon' => '📖',
        ],
        'admin' => [
            'id' => 'demo-admin-001',
            'name' => 'Дархан Әлиев',
            'email' => 'darkhan.aliev@demo.kazutb.kz',
            'login' => 'demo_admin',
            'ad_login' => 'demo_admin',
            'role' => 'admin',
            'label' => 'Администратор',
            'description' => 'Полный доступ — управление, настройки, отчёты',
            'icon' => '🛡️',
        ],
    ],

];
