<?php

use App\Services\Library\BookDetailReadService;
use App\Services\Library\CatalogReadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Route changes are intentionally tracked by the vault hook automation.
// This file doubles as the final validation target for route-aware vault logging.
// Route-aware state snapshots are now part of the vault workflow.

$internalStaffView = static function (Request $request, string $view) {
    $user = $request->session()->get('library.user');
    $role = is_array($user) ? mb_strtolower(trim((string) ($user['role'] ?? ''))) : '';

    // Belt-and-braces: route group also has library.auth applied at the group level.
    abort_unless(in_array($role, ['librarian', 'admin'], true), 403);

    return view($view, [
        'internalStaffUser' => $user,
    ]);
};

// Role-based post-login landing destination.
// Canonical page map (PROJECT_CONTEXT §30) expects: admin -> /admin,
// librarian -> /librarian, member -> /dashboard (interim: /account).
$postLoginDestination = static function (array $user): string {
    $role = mb_strtolower(trim((string) ($user['role'] ?? '')));

    return match ($role) {
        'admin' => '/admin',
        'librarian' => '/librarian',
        default => '/account',
    };
};

$adminView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'internalStaffUser' => $request->session()->get('library.user'),
    ], $data));
};

$librarianView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'librarianStaffUser' => $request->session()->get('library.user'),
    ], $data));
};

$memberView = static function (Request $request, string $view, array $data = []) {
    return view($view, array_merge([
        'memberReader' => $request->session()->get('library.user'),
    ], $data));
};

// Phase 3.3 — seeded public news catalog.
// Representative content for the canonical /news index + /news/{slug} detail.
// The structure is deliberately DB-replaceable later: an array of article
// records keyed by `slug`, each carrying trilingual copy, metadata, and an
// optional long-form body for the detail route. Ordering of the index is
// controlled by $orderedSlugs (most recent first).
$newsSeedProvider = static function (): array {
    $articles = [
        'global-symposium-archival-integrity' => [
            'slug' => 'global-symposium-archival-integrity',
            'featured' => true,
            'published_at' => '2026-04-14',
            'published_display' => [
                'ru' => '14 апреля 2026',
                'kk' => '2026 жылғы 14 сәуір',
                'en' => 'April 14, 2026',
            ],
            'category' => [
                'ru' => 'Главный материал',
                'kk' => 'Басты материал',
                'en' => 'Featured Report',
            ],
            'title' => [
                'ru' => 'Международный симпозиум по целостности архивов прошёл в Астане',
                'kk' => 'Астанада мұрағат тұтастығы бойынша халықаралық симпозиум өтті',
                'en' => 'Global Symposium on Archival Integrity Concludes in Astana',
            ],
            'excerpt' => [
                'ru' => 'Исследователи и специалисты по цифровому сохранению обсудили задачи обеспечения исторической непрерывности в эпоху быстро меняющихся цифровых форматов.',
                'kk' => 'Зерттеушілер мен цифрлық сақтау мамандары жылдам өзгеретін цифрлық форматтар дәуіріндегі тарихи сабақтастықты қамтамасыз ету мәселесін талқылады.',
                'en' => 'Researchers and digital preservation specialists addressed the challenge of maintaining historical continuity in an era of rapidly evolving digital formats.',
            ],
            'hero' => [
                'image' => 'images/news/campus-library.jpg',
                'alt' => [
                    'ru' => 'Университетский читальный зал с участниками симпозиума',
                    'kk' => 'Университеттің оқу залы, симпозиум қатысушылары',
                    'en' => 'University reading room during the symposium',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library провёл международный симпозиум по целостности архивов — площадку для обсуждения практик долговременного сохранения цифровых и гибридных коллекций в университетских библиотеках.'],
                    ['type' => 'h2', 'text' => 'Темы программы'],
                    ['type' => 'p', 'text' => 'Программа объединила библиотекарей, архивистов и исследователей. В центре обсуждения — связность метаданных, устойчивость форматов и контролируемый доступ к институциональному архиву.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Метаданные и провенанс', 'text' => 'Сохранение контекста и источников при переводе коллекций в цифровую среду.'],
                        ['term' => 'Устойчивость форматов', 'text' => 'Долговременное хранение материалов и планирование миграций.'],
                        ['term' => 'Контролируемый доступ', 'text' => 'Политики читательского доступа к закрытым и лицензируемым материалам.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Итоги и следующие шаги'],
                    ['type' => 'p', 'text' => 'По итогам симпозиума библиотека публикует методические рекомендации по работе с институциональным архивом и расширяет программу проверенных поступлений в научном репозитории университета.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library университет кітапханаларындағы цифрлық және гибридті жинақтарды ұзақ мерзімді сақтау тәжірибесін талқылауға арналған мұрағат тұтастығы жөніндегі халықаралық симпозиумды өткізді.'],
                    ['type' => 'h2', 'text' => 'Бағдарлама тақырыптары'],
                    ['type' => 'p', 'text' => 'Бағдарлама кітапханашылар, архивистер мен зерттеушілерді біріктірді. Негізгі тақырыптар: метадерек байланыстылығы, формат тұрақтылығы және институционалдық мұрағатқа бақыланатын қолжетімділік.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Метадерек және провенанс', 'text' => 'Жинақтарды цифрлық ортаға көшіру кезіндегі контекст пен дереккөздерді сақтау.'],
                        ['term' => 'Формат тұрақтылығы', 'text' => 'Материалдарды ұзақ мерзімде сақтау және миграцияны жоспарлау.'],
                        ['term' => 'Бақыланатын қолжетімділік', 'text' => 'Шектеулі және лицензияланған материалдарға оқырман қолжетімділігінің саясаты.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Қорытындылар және келесі қадамдар'],
                    ['type' => 'p', 'text' => 'Симпозиум қорытындысы бойынша кітапхана институционалдық мұрағатпен жұмысқа арналған әдістемелік ұсынымдарды жариялайды және университеттің ғылыми репозиторийіндегі тексерілген түсімдер бағдарламасын кеңейтеді.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'KazUTB Smart Library hosted an international symposium on archival integrity — a working space to discuss long-term preservation practice for digital and hybrid collections in university libraries.'],
                    ['type' => 'h2', 'text' => 'Programme themes'],
                    ['type' => 'p', 'text' => 'The programme brought together librarians, archivists, and researchers. Central themes were metadata continuity, format resilience, and controlled access to the institutional archive.'],
                    ['type' => 'list', 'items' => [
                        ['term' => 'Metadata and provenance', 'text' => 'Preserving context and sources as collections move into digital environments.'],
                        ['term' => 'Format resilience', 'text' => 'Long-term preservation of materials and planned migrations across formats.'],
                        ['term' => 'Controlled access', 'text' => 'Reader access policies for restricted and licensed materials.'],
                    ]],
                    ['type' => 'h2', 'text' => 'Outcomes and next steps'],
                    ['type' => 'p', 'text' => 'Following the symposium the library is publishing guidance for working with the institutional archive and is expanding the reviewed-intake programme of the university scholarly repository.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Продолжить работу', 'body' => 'Перейдите в научный репозиторий KazUTB Smart Library, чтобы ознакомиться с проверенными публикациями.', 'label' => 'Открыть репозиторий', 'href' => '/discover'],
                'kk' => ['heading' => 'Жұмысты жалғастыру', 'body' => 'Тексерілген жарияланымдармен танысу үшін KazUTB Smart Library ғылыми репозиторийіне өтіңіз.', 'label' => 'Репозиторийді ашу', 'href' => '/discover'],
                'en' => ['heading' => 'Continue from here', 'body' => 'Open the KazUTB Smart Library scholarly repository to browse reviewed publications.', 'label' => 'Open the repository', 'href' => '/discover'],
            ],
        ],
        'eurasian-manuscripts-integration' => [
            'slug' => 'eurasian-manuscripts-integration',
            'featured' => false,
            'published_at' => '2026-04-10',
            'published_display' => [
                'ru' => '10 апреля 2026',
                'kk' => '2026 жылғы 10 сәуір',
                'en' => 'April 10, 2026',
            ],
            'category' => [
                'ru' => 'Обновления фонда',
                'kk' => 'Қор жаңартулары',
                'en' => 'Collection Updates',
            ],
            'title' => [
                'ru' => 'Цифровая интеграция евразийских рукописей XIX века',
                'kk' => 'XIX ғасырдың еуразиялық қолжазбаларының цифрлық интеграциясы',
                'en' => 'Integration of the 19th-Century Eurasian Manuscripts',
            ],
            'excerpt' => [
                'ru' => 'Более четырёх тысяч оцифрованных рукописей из центральных степных архивов индексированы и доступны для академического поиска в каталоге.',
                'kk' => 'Орталық дала мұрағаттарынан алынған төрт мыңнан астам цифрландырылған қолжазба академиялық іздеуге дайын және каталогта қолжетімді.',
                'en' => 'More than four thousand digitised manuscripts from the central steppe archives are indexed and available for academic search in the catalog.',
            ],
            'hero' => [
                'image' => 'images/news/classics-event.jpg',
                'alt' => [
                    'ru' => 'Стеллажи с архивными материалами',
                    'kk' => 'Мұрағат материалдарының сөрелері',
                    'en' => 'Shelves of archival materials',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'Библиотека завершила базовый этап цифровой интеграции коллекции евразийских рукописей XIX века в институциональный архив KazUTB Smart Library.'],
                    ['type' => 'p', 'text' => 'Материалы прошли проверку метаданных, получили устойчивые идентификаторы и связаны с каталогом. Читатели и преподаватели могут обращаться к коллекции через обычный академический поиск.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'Кітапхана XIX ғасырдың еуразиялық қолжазбалар жинағын KazUTB Smart Library институционалдық мұрағатына цифрлық интеграциялаудың базалық кезеңін аяқтады.'],
                    ['type' => 'p', 'text' => 'Материалдар метадеректер тексеруінен өтті, тұрақты идентификаторларды алды және каталогпен байланыстырылды. Оқырмандар мен оқытушылар жинаққа қалыпты академиялық іздеу арқылы өте алады.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'The library has completed the foundation stage of digital integration for the 19th-century Eurasian manuscript collection into the KazUTB Smart Library institutional archive.'],
                    ['type' => 'p', 'text' => 'Materials have been validated against metadata standards, assigned stable identifiers, and linked to the catalog. Readers and faculty can now reach the collection through ordinary academic search.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Открыть коллекцию', 'body' => 'Каталог KazUTB Smart Library содержит проиндексированные материалы — используйте тематическую навигацию по УДК.', 'label' => 'Открыть каталог', 'href' => '/catalog'],
                'kk' => ['heading' => 'Жинақты ашу', 'body' => 'KazUTB Smart Library каталогында индекстелген материалдар бар — ӘОЖ бойынша тақырыптық навигацияны пайдаланыңыз.', 'label' => 'Каталогты ашу', 'href' => '/catalog'],
                'en' => ['heading' => 'Open the collection', 'body' => 'The KazUTB Smart Library catalog contains the indexed materials — use UDC subject navigation to explore.', 'label' => 'Open the catalog', 'href' => '/catalog'],
            ],
        ],
        'digital-access-partner-institutions' => [
            'slug' => 'digital-access-partner-institutions',
            'featured' => false,
            'published_at' => '2026-04-05',
            'published_display' => [
                'ru' => '5 апреля 2026',
                'kk' => '2026 жылғы 5 сәуір',
                'en' => 'April 5, 2026',
            ],
            'category' => [
                'ru' => 'Цифровой доступ',
                'kk' => 'Цифрлық қолжетімділік',
                'en' => 'Digital Access',
            ],
            'title' => [
                'ru' => 'Расширение цифрового доступа для внешних академических партнёров',
                'kk' => 'Сыртқы академиялық серіктестер үшін цифрлық қолжетімділікті кеңейту',
                'en' => 'Expanded Digital Access for External Academic Partners',
            ],
            'excerpt' => [
                'ru' => 'Обновлены механизмы контролируемого доступа для партнёрских университетов — снижены барьеры для международных исследователей без ослабления политик библиотеки.',
                'kk' => 'Серіктес университеттер үшін бақыланатын қолжетімділік механизмдері жаңартылды — халықаралық зерттеушілер үшін тосқауылдар азайды, бірақ саясат босаңсымайды.',
                'en' => 'Controlled-access mechanisms for partner universities have been updated — lower the barrier for international researchers without weakening library policy.',
            ],
            'hero' => [
                'image' => 'images/news/author-visit.jpg',
                'alt' => [
                    'ru' => 'Читатель работает с цифровыми материалами',
                    'kk' => 'Оқырман цифрлық материалдармен жұмыс істеуде',
                    'en' => 'Reader working with digital materials',
                ],
            ],
            'body' => [
                'ru' => [
                    ['type' => 'lead', 'text' => 'Библиотека расширила программу цифрового доступа для партнёрских университетов. Политика доступа и журнал обращений остаются под полным контролем KazUTB Smart Library.'],
                    ['type' => 'p', 'text' => 'Обновлённые потоки доступа поддерживают существующие уровни контроля и работают только в рамках подтверждённых академических соглашений.'],
                ],
                'kk' => [
                    ['type' => 'lead', 'text' => 'Кітапхана серіктес университеттер үшін цифрлық қолжетімділік бағдарламасын кеңейтті. Қолжетімділік саясаты мен өтініш журналы толығымен KazUTB Smart Library бақылауында қалады.'],
                    ['type' => 'p', 'text' => 'Жаңартылған қолжетімділік ағындары қолданыстағы бақылау деңгейлерін қолдайды және тек расталған академиялық келісімдер шеңберінде жұмыс істейді.'],
                ],
                'en' => [
                    ['type' => 'lead', 'text' => 'The library has expanded its digital-access programme for partner universities. Access policy and the request journal remain fully under KazUTB Smart Library control.'],
                    ['type' => 'p', 'text' => 'Updated access flows respect existing control levels and operate only within confirmed academic agreements.'],
                ],
            ],
            'cta' => [
                'ru' => ['heading' => 'Проверить доступ', 'body' => 'Если вы представляете партнёрскую организацию, свяжитесь с библиотекой, чтобы уточнить маршруты доступа.', 'label' => 'Связаться с библиотекой', 'href' => '/contacts'],
                'kk' => ['heading' => 'Қолжетімділікті тексеру', 'body' => 'Серіктес ұйымның өкілі болсаңыз, қолжетімділік маршруттарын нақтылау үшін кітапханаға хабарласыңыз.', 'label' => 'Кітапханамен байланысу', 'href' => '/contacts'],
                'en' => ['heading' => 'Confirm your access', 'body' => 'If you represent a partner institution, contact the library to confirm the right access routes for your team.', 'label' => 'Contact the library', 'href' => '/contacts'],
            ],
        ],
    ];

    $orderedSlugs = [
        'global-symposium-archival-integrity',
        'eurasian-manuscripts-integration',
        'digital-access-partner-institutions',
    ];

    return ['articles' => $articles, 'ordered' => $orderedSlugs];
};

// Phase 3 Cluster B.1 — seeded public leadership content for /leadership.
//
// Scoped strictly to this page; structure mirrors $newsSeedProvider so a
// future backend phase can replace the closure with a DB-backed source.
// Content is role-first (no invented personal names). Real names, titles,
// bios, and portrait assets are populated by library leadership once
// approved per Cluster B Content Contract §4 (ownership matrix) and §5
// (trilingual requirements).
$leadershipSeedProvider = static function (): array {
    $header = [
        'ru' => [
            'eyebrow' => 'Руководство библиотеки',
            'headline' => 'Руководство KazUTB Smart Library',
            'lede' => 'Руководство библиотеки отвечает за институциональный фонд, электронные коллекции, научный архив и читательские сервисы KazUTB Smart Library. Контакты и маршруты обращения ведутся через официальные каналы университета.',
        ],
        'kk' => [
            'eyebrow' => 'Кітапхана басшылығы',
            'headline' => 'KazUTB Smart Library басшылығы',
            'lede' => 'Кітапхана басшылығы KazUTB Smart Library-дің институционалдық қорын, электрондық жинақтарын, ғылыми мұрағатын және оқырман сервистерін үйлестіреді. Байланыс пен өтініш маршруттары университеттің ресми арналары арқылы жүргізіледі.',
        ],
        'en' => [
            'eyebrow' => 'Library Leadership',
            'headline' => 'Leadership of KazUTB Smart Library',
            'lede' => 'Library leadership stewards the institutional collection, digital materials, scholarly archive, and reader services of KazUTB Smart Library. Contact and inquiry routes are maintained through official university channels.',
        ],
    ];

    $mandate = [
        'ru' => [
            'eyebrow' => 'Институциональный мандат',
            'title' => 'Ответственность библиотеки',
            'paragraph' => 'Руководство библиотеки отвечает за сохранность и развитие институционального фонда, качество метаданных каталога, контролируемый доступ к электронным материалам, ведение научного репозитория университета и поддержку читателей в рабочих маршрутах. Решения согласуются с академической и административной политикой университета.',
            'reports_to_label' => 'Подчинённость',
            'reports_to_value' => 'Администрация КазУТБ',
        ],
        'kk' => [
            'eyebrow' => 'Институционалдық мандат',
            'title' => 'Кітапхананың жауапкершілігі',
            'paragraph' => 'Кітапхана басшылығы институционалдық қордың сақталуы мен дамуына, каталог метадерегінің сапасына, электрондық материалдарға бақыланатын қолжетімділікке, университеттің ғылыми репозиторийін жүргізуге және оқырмандарды жұмыс маршруттарында қолдауға жауапты. Шешімдер университеттің академиялық және әкімшілік саясатымен үйлестіріледі.',
            'reports_to_label' => 'Бағыныстылық',
            'reports_to_value' => 'КазУТБ әкімшілігі',
        ],
        'en' => [
            'eyebrow' => 'Institutional mandate',
            'title' => 'Scope of library responsibility',
            'paragraph' => 'Library leadership is responsible for preservation and development of the institutional collection, catalog metadata quality, controlled access to digital materials, stewardship of the university scholarly repository, and support for readers throughout their workflows. Decisions are aligned with the academic and administrative policy of the university.',
            'reports_to_label' => 'Reports to',
            'reports_to_value' => 'KazUTB university administration',
        ],
    ];

    // v1 directory: role-based slots. `full_name` intentionally omitted —
    // per Cluster B Content Contract §9 risk R-B1.2, real names are populated
    // only after director approval. Portraits are local assets; a null
    // portrait triggers the initial-letter fallback treatment in the view.
    $profiles = [
        [
            'slug' => 'director',
            'order' => 1,
            'portrait' => null,
            'portrait_initials' => [
                'ru' => 'Д',
                'kk' => 'Д',
                'en' => 'D',
            ],
            'role_title' => [
                'ru' => 'Директор библиотеки',
                'kk' => 'Кітапхана директоры',
                'en' => 'Library Director',
            ],
            'role_scope_line' => [
                'ru' => 'Общее руководство и стратегия',
                'kk' => 'Жалпы басшылық және стратегия',
                'en' => 'Overall leadership and strategy',
            ],
            'role_description' => [
                'ru' => 'Отвечает за стратегию развития библиотеки, институциональный фонд и согласование академической и административной политики. Представляет библиотеку во внутренних и внешних академических процессах.',
                'kk' => 'Кітапхананың даму стратегиясы, институционалдық қор және академиялық пен әкімшілік саясатты үйлестіру үшін жауап береді. Кітапхананы ішкі және сыртқы академиялық процестерде таныстырады.',
                'en' => 'Responsible for library strategy, the institutional collection, and alignment of academic and administrative policy. Represents the library in internal and external academic processes.',
            ],
        ],
        [
            'slug' => 'digital-collections',
            'order' => 2,
            'portrait' => null,
            'portrait_initials' => [
                'ru' => 'ЦК',
                'kk' => 'ЦЖ',
                'en' => 'DC',
            ],
            'role_title' => [
                'ru' => 'Заведующий электронными коллекциями',
                'kk' => 'Электрондық жинақтар жетекшісі',
                'en' => 'Head of Digital Collections',
            ],
            'role_scope_line' => [
                'ru' => 'Электронные ресурсы и репозиторий',
                'kk' => 'Электрондық ресурстар және репозиторий',
                'en' => 'Digital materials and repository',
            ],
            'role_description' => [
                'ru' => 'Ведёт работу с электронными материалами, лицензируемыми ресурсами и институциональным научным репозиторием. Отвечает за качество метаданных и корректную работу контролируемого читательского доступа.',
                'kk' => 'Электрондық материалдармен, лицензияланған ресурстармен және институционалдық ғылыми репозиториймен жұмысты жүргізеді. Метадерек сапасы мен бақыланатын оқырман қолжетімділігінің дұрыс жұмысы үшін жауапты.',
                'en' => 'Leads work with digital materials, licensed resources, and the institutional scholarly repository. Responsible for metadata quality and correct operation of controlled reader access.',
            ],
        ],
        [
            'slug' => 'reader-services',
            'order' => 3,
            'portrait' => null,
            'portrait_initials' => [
                'ru' => 'ЧС',
                'kk' => 'ОС',
                'en' => 'RS',
            ],
            'role_title' => [
                'ru' => 'Координатор читательских сервисов',
                'kk' => 'Оқырман сервистерінің үйлестірушісі',
                'en' => 'Reader Services Coordinator',
            ],
            'role_scope_line' => [
                'ru' => 'Выдача, возврат и сопровождение читателей',
                'kk' => 'Беру, қайтару және оқырмандарды қолдау',
                'en' => 'Circulation and reader support',
            ],
            'role_description' => [
                'ru' => 'Координирует повседневные читательские процессы: выдачу и возврат, работу с подборками, консультации преподавателей и студентов, а также маршруты обращения к библиотекарю.',
                'kk' => 'Күнделікті оқырман процестерін үйлестіреді: беру мен қайтару, іріктемелермен жұмыс, оқытушылар мен студенттерге кеңес беру және кітапханашыға жүгіну маршруттары.',
                'en' => 'Coordinates day-to-day reader workflows: circulation, shortlist and reservation support, consultations for faculty and students, and routes of inquiry to the librarian-on-duty.',
            ],
        ],
    ];

    $supportCta = [
        'ru' => [
            'eyebrow' => 'Связаться с библиотекой',
            'heading' => 'Общие обращения и академические запросы',
            'body' => 'Для общих вопросов, обращений преподавателей и внешних академических запросов используйте официальные контакты KazUTB Smart Library.',
            'label' => 'Перейти к контактам',
            'href' => '/contacts',
        ],
        'kk' => [
            'eyebrow' => 'Кітапханамен байланысу',
            'heading' => 'Жалпы өтініштер мен академиялық сұраулар',
            'body' => 'Жалпы сұрақтар, оқытушылардың өтініштері және сыртқы академиялық сұраулар бойынша KazUTB Smart Library-дің ресми байланыс арналарын пайдаланыңыз.',
            'label' => 'Байланыс бетіне өту',
            'href' => '/contacts',
        ],
        'en' => [
            'eyebrow' => 'Contact the library',
            'heading' => 'General inquiries and academic requests',
            'body' => 'For general questions, faculty inquiries, and external academic requests, please use the official contact routes of KazUTB Smart Library.',
            'label' => 'Open contacts',
            'href' => '/contacts',
        ],
    ];

    return [
        'header' => $header,
        'mandate' => $mandate,
        'profiles' => $profiles,
        'support_cta' => $supportCta,
        'last_reviewed_at' => '2026-04-22',
    ];
};

// Phase 3 Cluster B.2 — seeded public library-rules content for /rules.
//
// Scoped strictly to this page; structure mirrors $newsSeedProvider and
// $leadershipSeedProvider so a future backend phase can replace the
// closure with a DB-backed policy source. Section order is frozen per
// Cluster B Content Contract §2 and the anchor IDs (#general, #borrowing,
// #digital, #conduct, #penalties) are a public contract — they MUST remain
// stable.
$rulesSeedProvider = static function (): array {
    $header = [
        'ru' => [
            'eyebrow' => 'Официальный документ библиотеки',
            'headline' => 'Правила пользования библиотекой',
            'subtitle_secondary_lang' => 'Library Usage Rules',
            'preamble' => 'Настоящие правила регулируют пользование помещениями, фондами и электронными ресурсами KazUTB Smart Library. Документ обеспечивает равный доступ к коллекциям, сохранность фонда и академическую среду, пригодную для учебной и исследовательской работы.',
            'effective_label' => 'Действует с',
            'effective_date' => '2026-04-01',
            'reviewed_label' => 'Последняя проверка',
        ],
        'kk' => [
            'eyebrow' => 'Кітапхананың ресми құжаты',
            'headline' => 'Кітапхананы пайдалану ережелері',
            'subtitle_secondary_lang' => 'Library Usage Rules',
            'preamble' => 'Осы ережелер KazUTB Smart Library ғимараттарын, қорларын және электрондық ресурстарын пайдалану тәртібін реттейді. Құжат жинаққа тең қолжетімділікті, қордың сақталуын және оқу мен ғылыми жұмысқа қолайлы академиялық ортаны қамтамасыз етеді.',
            'effective_label' => 'Күшіне енген күні',
            'effective_date' => '2026-04-01',
            'reviewed_label' => 'Соңғы тексеру',
        ],
        'en' => [
            'eyebrow' => 'Official library policy document',
            'headline' => 'Library Usage Rules',
            'subtitle_secondary_lang' => 'Правила пользования библиотекой',
            'preamble' => 'These rules govern use of the facilities, collections, and digital resources of KazUTB Smart Library. The document supports equitable access to the collection, preservation of holdings, and an academic environment suitable for study and research.',
            'effective_label' => 'Effective from',
            'effective_date' => '2026-04-01',
            'reviewed_label' => 'Last reviewed',
        ],
    ];

    $toc = [
        'ru' => [
            'label' => 'Содержание',
            'items' => [
                ['href' => '#general', 'label' => '1. Общие положения'],
                ['href' => '#borrowing', 'label' => '2. Выдача и возврат'],
                ['href' => '#digital', 'label' => '3. Электронный доступ'],
                ['href' => '#conduct', 'label' => '4. Правила поведения'],
                ['href' => '#penalties', 'label' => '5. Нарушения и взыскания'],
            ],
        ],
        'kk' => [
            'label' => 'Мазмұны',
            'items' => [
                ['href' => '#general', 'label' => '1. Жалпы ережелер'],
                ['href' => '#borrowing', 'label' => '2. Беру және қайтару'],
                ['href' => '#digital', 'label' => '3. Электрондық қолжетімділік'],
                ['href' => '#conduct', 'label' => '4. Мінез-құлық ережелері'],
                ['href' => '#penalties', 'label' => '5. Бұзушылықтар мен шаралар'],
            ],
        ],
        'en' => [
            'label' => 'Contents',
            'items' => [
                ['href' => '#general', 'label' => '1. General provisions'],
                ['href' => '#borrowing', 'label' => '2. Borrowing and returns'],
                ['href' => '#digital', 'label' => '3. Digital access'],
                ['href' => '#conduct', 'label' => '4. Code of conduct'],
                ['href' => '#penalties', 'label' => '5. Violations and penalties'],
            ],
        ],
    ];

    $general = [
        'ru' => [
            'number' => '1',
            'eyebrow' => 'Раздел 1',
            'title' => 'Общие положения',
            'lede' => 'KazUTB Smart Library обслуживает академическое сообщество университета и обеспечивает доступ к печатным и электронным коллекциям через единые институциональные правила.',
            'items' => [
                'Библиотека обслуживает студентов, преподавателей, научных сотрудников и авторизованных исследователей KazUTB.',
                'Действующее удостоверение университета служит основным читательским документом; использование чужого удостоверения не допускается.',
                'Читательские права не передаются третьим лицам, включая членов семьи.',
                'Сотрудники библиотеки вправе запросить предъявление удостоверения и подтверждение академического статуса.',
            ],
        ],
        'kk' => [
            'number' => '1',
            'eyebrow' => '1-бөлім',
            'title' => 'Жалпы ережелер',
            'lede' => 'KazUTB Smart Library университеттің академиялық қауымдастығына қызмет көрсетеді және баспа мен электрондық жинақтарға бірыңғай институционалдық ережелер арқылы қол жеткізуді қамтамасыз етеді.',
            'items' => [
                'Кітапхана KazUTB студенттеріне, оқытушыларына, ғылыми қызметкерлеріне және уәкілетті зерттеушілеріне қызмет көрсетеді.',
                'Университеттің қолданыстағы жеке куәлігі оқырманның негізгі құжаты болып табылады; басқа адамның куәлігін пайдалануға тыйым салынады.',
                'Оқырман құқықтары үшінші тұлғаларға, оның ішінде отбасы мүшелеріне берілмейді.',
                'Кітапхана қызметкерлері жеке куәлікті көрсетуді және академиялық мәртебені растауды сұрауға құқылы.',
            ],
        ],
        'en' => [
            'number' => '1',
            'eyebrow' => 'Section 1',
            'title' => 'General provisions',
            'lede' => 'KazUTB Smart Library serves the university academic community and provides access to print and digital collections under a unified set of institutional rules.',
            'items' => [
                'The library serves enrolled students, faculty, research staff, and authorized researchers of KazUTB.',
                'A valid university ID serves as the primary library credential; use of another person\'s ID is not permitted.',
                'Library privileges are non-transferable, including to family members.',
                'Library staff may request presentation of a valid ID and confirmation of academic status.',
            ],
        ],
    ];

    $borrowing = [
        'ru' => [
            'number' => '2',
            'eyebrow' => 'Раздел 2',
            'title' => 'Выдача и возврат',
            'lede' => 'Лимиты выдачи, сроки пользования и продления зависят от академического статуса читателя. Возврат в срок — базовое условие равного доступа для других читателей.',
            'groups' => [
                [
                    'audience' => 'Студенты бакалавриата',
                    'icon' => 'menu_book',
                    'rows' => [
                        ['label' => 'Одновременно', 'value' => 'до 5 единиц'],
                        ['label' => 'Срок выдачи', 'value' => '14 дней'],
                        ['label' => 'Продление', 'value' => '1 раз, если нет очереди'],
                    ],
                ],
                [
                    'audience' => 'Магистранты и докторанты',
                    'icon' => 'school',
                    'rows' => [
                        ['label' => 'Одновременно', 'value' => 'до 10 единиц'],
                        ['label' => 'Срок выдачи', 'value' => '21 день'],
                        ['label' => 'Продление', 'value' => '2 раза, если нет очереди'],
                    ],
                ],
                [
                    'audience' => 'Преподаватели и научные сотрудники',
                    'icon' => 'auto_stories',
                    'rows' => [
                        ['label' => 'Одновременно', 'value' => 'до 15 единиц'],
                        ['label' => 'Срок выдачи', 'value' => '30 дней'],
                        ['label' => 'Продление', 'value' => '2 раза, если нет очереди'],
                    ],
                ],
            ],
            'notes' => [
                'Редкие, справочные и учебно-обязательные издания могут выдаваться только в читальном зале.',
                'Продление невозможно, если книга уже забронирована другим читателем.',
                'Читатель обязан проверить физическое состояние издания при получении.',
            ],
        ],
        'kk' => [
            'number' => '2',
            'eyebrow' => '2-бөлім',
            'title' => 'Беру және қайтару',
            'lede' => 'Беру лимиттері, пайдалану мерзімі және ұзарту оқырманның академиялық мәртебесіне байланысты. Уақытында қайтару — басқа оқырмандарға тең қолжетімділіктің негізгі шарты.',
            'groups' => [
                [
                    'audience' => 'Бакалавриат студенттері',
                    'icon' => 'menu_book',
                    'rows' => [
                        ['label' => 'Бір мезгілде', 'value' => '5 данаға дейін'],
                        ['label' => 'Беру мерзімі', 'value' => '14 күн'],
                        ['label' => 'Ұзарту', 'value' => 'кезек болмаса — 1 рет'],
                    ],
                ],
                [
                    'audience' => 'Магистранттар мен докторанттар',
                    'icon' => 'school',
                    'rows' => [
                        ['label' => 'Бір мезгілде', 'value' => '10 данаға дейін'],
                        ['label' => 'Беру мерзімі', 'value' => '21 күн'],
                        ['label' => 'Ұзарту', 'value' => 'кезек болмаса — 2 рет'],
                    ],
                ],
                [
                    'audience' => 'Оқытушылар мен ғылыми қызметкерлер',
                    'icon' => 'auto_stories',
                    'rows' => [
                        ['label' => 'Бір мезгілде', 'value' => '15 данаға дейін'],
                        ['label' => 'Беру мерзімі', 'value' => '30 күн'],
                        ['label' => 'Ұзарту', 'value' => 'кезек болмаса — 2 рет'],
                    ],
                ],
            ],
            'notes' => [
                'Сирек, анықтамалық және міндетті оқу басылымдары тек оқу залында беріледі.',
                'Егер кітапты басқа оқырман брондаған болса, ұзарту мүмкін емес.',
                'Оқырман басылымның физикалық күйін алған кезде тексеруге міндетті.',
            ],
        ],
        'en' => [
            'number' => '2',
            'eyebrow' => 'Section 2',
            'title' => 'Borrowing and returns',
            'lede' => 'Borrowing limits, loan periods, and renewals depend on the reader\'s academic status. Timely return is the baseline condition for equitable access for other readers.',
            'groups' => [
                [
                    'audience' => 'Undergraduate students',
                    'icon' => 'menu_book',
                    'rows' => [
                        ['label' => 'At one time', 'value' => 'up to 5 items'],
                        ['label' => 'Loan period', 'value' => '14 days'],
                        ['label' => 'Renewals', 'value' => '1, if not reserved by another reader'],
                    ],
                ],
                [
                    'audience' => 'Master\'s and doctoral students',
                    'icon' => 'school',
                    'rows' => [
                        ['label' => 'At one time', 'value' => 'up to 10 items'],
                        ['label' => 'Loan period', 'value' => '21 days'],
                        ['label' => 'Renewals', 'value' => '2, if not reserved by another reader'],
                    ],
                ],
                [
                    'audience' => 'Faculty and research staff',
                    'icon' => 'auto_stories',
                    'rows' => [
                        ['label' => 'At one time', 'value' => 'up to 15 items'],
                        ['label' => 'Loan period', 'value' => '30 days'],
                        ['label' => 'Renewals', 'value' => '2, if not reserved by another reader'],
                    ],
                ],
            ],
            'notes' => [
                'Rare, reference, and core-curriculum items may be consulted in the reading room only.',
                'Renewal is not available when the item is already reserved by another reader.',
                'Readers are expected to check the physical condition of an item at the time of checkout.',
            ],
        ],
    ];

    $digital = [
        'ru' => [
            'number' => '3',
            'eyebrow' => 'Раздел 3',
            'title' => 'Электронный доступ',
            'lede' => 'Электронные материалы и лицензионные базы данных предоставляются для академического и некоммерческого использования через институциональную аутентификацию.',
            'items' => [
                'Электронные материалы библиотеки открываются в контролируемом просмотрщике без возможности скачивания.',
                'Лицензионные базы данных и электронные журналы используются исключительно в академических и некоммерческих целях.',
                'Массовое скачивание, автоматизированная выгрузка и систематическое копирование содержимого запрещены и могут привести к блокировке доступа университета.',
                'Удалённый доступ предоставляется только через официальную институциональную аутентификацию (SSO KazUTB).',
                'Передача учётных данных, в том числе в пределах одной рабочей группы, не допускается.',
            ],
        ],
        'kk' => [
            'number' => '3',
            'eyebrow' => '3-бөлім',
            'title' => 'Электрондық қолжетімділік',
            'lede' => 'Электрондық материалдар мен лицензияланған дерекқорлар институционалдық аутентификация арқылы академиялық және коммерциялық емес мақсатта ұсынылады.',
            'items' => [
                'Кітапхананың электрондық материалдары жүктеу мүмкіндігінсіз бақыланатын қарау құралында ашылады.',
                'Лицензияланған дерекқорлар мен электрондық журналдар тек академиялық және коммерциялық емес мақсатта пайдаланылады.',
                'Жаппай жүктеу, автоматтандырылған көшіру және мазмұнды жүйелі түрде сақтау шектеулі және университеттің қол жеткізу құқығының тоқтатылуына әкелуі мүмкін.',
                'Қашықтан қолжетімділік тек ресми институционалдық аутентификация арқылы (KazUTB SSO) беріледі.',
                'Есептік жазба деректерін беруге, оның ішінде бір жұмыс тобы шегінде беруге тыйым салынады.',
            ],
        ],
        'en' => [
            'number' => '3',
            'eyebrow' => 'Section 3',
            'title' => 'Digital access',
            'lede' => 'Digital materials and licensed databases are provided for academic and non-commercial use via institutional authentication.',
            'items' => [
                'Library digital materials are opened in a controlled viewer with no download path.',
                'Licensed databases and e-journals are used strictly for academic and non-commercial purposes.',
                'Bulk downloading, automated harvesting, and systematic copying of content are prohibited and may result in suspension of university-wide access.',
                'Remote access is available only through the official institutional authentication (KazUTB SSO).',
                'Sharing of credentials, including within a working group, is not permitted.',
            ],
        ],
    ];

    $conduct = [
        'ru' => [
            'number' => '4',
            'eyebrow' => 'Раздел 4',
            'title' => 'Правила поведения',
            'lede' => 'В библиотеке поддерживается академическая среда, уважительное отношение к персоналу и сохранность фонда.',
            'items' => [
                'В читальных зонах сохраняется тихий режим работы; для совместного обсуждения используются выделенные пространства.',
                'Приём пищи запрещён; допускается вода в закрытой ёмкости вне читальных зон.',
                'Мобильные устройства переводятся в беззвучный режим; разговоры по телефону — вне читальных зон.',
                'Материалы не помечаются, не подчёркиваются и не складываются корешком наружу; бережное обращение обязательно.',
                'Любые формы притеснения и нарушения академической среды не допускаются.',
                'Требования сотрудников библиотеки в рамках настоящих правил обязательны к исполнению.',
            ],
        ],
        'kk' => [
            'number' => '4',
            'eyebrow' => '4-бөлім',
            'title' => 'Мінез-құлық ережелері',
            'lede' => 'Кітапханада академиялық орта, қызметкерлерге құрметпен қарау және қордың сақталуы қолдау табады.',
            'items' => [
                'Оқу аймақтарында тыныш режим сақталады; бірлескен талқылау үшін арнайы кеңістіктер пайдаланылады.',
                'Тамақтануға тыйым салынады; оқу аймақтарынан тыс жерде жабық ыдыстағы суға рұқсат етіледі.',
                'Мобильді құрылғылар үнсіз режимге ауыстырылады; телефонмен сөйлесу тек оқу аймақтарынан тыс жерде рұқсат етіледі.',
                'Материалдарға белгі қою, астын сызу және жырынды сыртқа қаратып жинауға тыйым салынады; ұқыпты пайдалану міндетті.',
                'Қысым көрсету мен академиялық ортаны бұзудың кез келген түрі рұқсат етілмейді.',
                'Кітапхана қызметкерлерінің осы ережелер шеңберіндегі талаптары міндетті түрде орындалуға тиіс.',
            ],
        ],
        'en' => [
            'number' => '4',
            'eyebrow' => 'Section 4',
            'title' => 'Code of conduct',
            'lede' => 'The library maintains an academic environment, respectful interaction with staff, and preservation of the collection.',
            'items' => [
                'Reading areas are quiet zones; designated spaces are provided for group discussion.',
                'Eating is not permitted; water in a closed container is allowed outside reading areas.',
                'Mobile devices are kept on silent mode; phone calls are taken outside reading areas.',
                'Do not mark, underline, or shelve items spine-out; careful handling is required.',
                'Harassment and any behavior that degrades the academic environment are not tolerated.',
                'Requests from library staff made within these rules are to be followed.',
            ],
        ],
    ];

    $penalties = [
        'ru' => [
            'number' => '5',
            'eyebrow' => 'Раздел 5',
            'title' => 'Нарушения и взыскания',
            'lede' => 'При нарушениях применяются пропорциональные меры, направленные на восстановление доступа и сохранность фонда, а не на наказание.',
            'items' => [
                'Задержка возврата приводит к временной приостановке прав на новые выдачи до возврата издания.',
                'Повреждение издания возмещается по текущей восстановительной стоимости, определяемой библиотекой.',
                'Утеря издания возмещается по восстановительной стоимости либо равноценной заменой, согласованной с библиотекой.',
                'Нарушения электронного доступа (массовое скачивание, передача учётных данных, коммерческое использование) влекут временное приостановление доступа и эскалацию в университет.',
                'Повторные или умышленные нарушения рассматриваются вместе с администрацией университета.',
            ],
            'suspension_ladder_label' => 'Шкала приостановки доступа',
            'suspension_ladder' => [
                ['level' => 'Напоминание', 'detail' => 'Первое обращение сотрудника библиотеки, без ограничений доступа.'],
                ['level' => 'Временная приостановка', 'detail' => 'Приостановка новых выдач и брони до устранения нарушения.'],
                ['level' => 'Эскалация', 'detail' => 'Передача вопроса в администрацию университета при повторных или серьёзных нарушениях.'],
            ],
            'appeal_label' => 'Право обжалования',
            'appeal_text' => 'Читатель вправе обратиться к руководству библиотеки через страницу /leadership или на официальную почту, указанную на странице /contacts, для пересмотра применённой меры.',
        ],
        'kk' => [
            'number' => '5',
            'eyebrow' => '5-бөлім',
            'title' => 'Бұзушылықтар мен шаралар',
            'lede' => 'Бұзушылықтар болған жағдайда жаза емес, қолжетімділікті қалпына келтіруге және қордың сақталуына бағытталған мөлшерлес шаралар қолданылады.',
            'items' => [
                'Қайтарудың кешіктірілуі басылым қайтарылғанға дейін жаңа беруге арналған құқықтардың уақытша тоқтатылуына әкеледі.',
                'Басылымның зақымдалуы кітапхана белгілеген қалпына келтіру құнымен өтеледі.',
                'Басылымның жоғалуы қалпына келтіру құнымен немесе кітапханамен келісілген тең бағалы алмастырумен өтеледі.',
                'Электрондық қолжетімділіктің бұзылуы (жаппай жүктеу, есептік деректерді беру, коммерциялық пайдалану) қолжетімділіктің уақытша тоқтатылуына және университетке эскалацияға әкеледі.',
                'Қайталанатын немесе қасақана бұзушылықтар университет әкімшілігімен бірге қаралады.',
            ],
            'suspension_ladder_label' => 'Қолжетімділікті тоқтата тұру сатылары',
            'suspension_ladder' => [
                ['level' => 'Ескерту', 'detail' => 'Кітапхана қызметкерінің бірінші хабарласуы, қолжетімділік шектелмейді.'],
                ['level' => 'Уақытша тоқтата тұру', 'detail' => 'Бұзушылық жойылғанға дейін жаңа беру мен брондауды тоқтата тұру.'],
                ['level' => 'Эскалация', 'detail' => 'Қайталанатын немесе елеулі бұзушылықтар кезінде мәселе университет әкімшілігіне беріледі.'],
            ],
            'appeal_label' => 'Шағымдану құқығы',
            'appeal_text' => 'Оқырман қолданылған шараны қайта қарау үшін /leadership бетінде немесе /contacts бетінде көрсетілген ресми пошта арқылы кітапхана басшылығына жүгінуге құқылы.',
        ],
        'en' => [
            'number' => '5',
            'eyebrow' => 'Section 5',
            'title' => 'Violations and penalties',
            'lede' => 'Responses are proportionate and aimed at restoring access and preserving the collection, not at punishment.',
            'items' => [
                'Overdue returns result in a temporary hold on new checkouts until the item is returned.',
                'Damage is settled at the current replacement value determined by the library.',
                'Loss is settled at the replacement value or by an equivalent replacement agreed with the library.',
                'Digital-access violations (bulk downloading, credential sharing, commercial use) lead to temporary suspension of access and escalation to the university.',
                'Repeated or intentional violations are reviewed jointly with the university administration.',
            ],
            'suspension_ladder_label' => 'Access suspension ladder',
            'suspension_ladder' => [
                ['level' => 'Reminder', 'detail' => 'First notice from library staff, no access restriction.'],
                ['level' => 'Temporary hold', 'detail' => 'Hold on new checkouts and reservations until the issue is resolved.'],
                ['level' => 'Escalation', 'detail' => 'Matter referred to the university administration for repeated or serious violations.'],
            ],
            'appeal_label' => 'Right of appeal',
            'appeal_text' => 'Readers may request review of a measure through the /leadership page or by writing to the official address listed on /contacts.',
        ],
    ];

    $footerMeta = [
        'ru' => [
            'eyebrow' => 'Вопросы по документу',
            'heading' => 'Вопросы по настоящим правилам',
            'body' => 'По вопросам толкования настоящих правил, предложений по уточнению или обжалования применённых мер обращайтесь в библиотеку.',
            'contacts_label' => 'Перейти к контактам',
            'contacts_href' => '/contacts',
            'leadership_label' => 'Руководство библиотеки',
            'leadership_href' => '/leadership',
            'version_label' => 'Версия документа',
            'version_value' => 'v1.0 (2026-04-22)',
        ],
        'kk' => [
            'eyebrow' => 'Құжат бойынша сұрақтар',
            'heading' => 'Осы ережелер бойынша сұрақтар',
            'body' => 'Осы ережелерді түсіндіру, нақтылау бойынша ұсыныстар немесе қолданылған шараларды шағымдану мәселелері бойынша кітапханаға жүгініңіз.',
            'contacts_label' => 'Байланыс бетіне өту',
            'contacts_href' => '/contacts',
            'leadership_label' => 'Кітапхана басшылығы',
            'leadership_href' => '/leadership',
            'version_label' => 'Құжат нұсқасы',
            'version_value' => 'v1.0 (2026-04-22)',
        ],
        'en' => [
            'eyebrow' => 'Questions about this document',
            'heading' => 'Questions about these rules',
            'body' => 'For questions of interpretation, suggestions for clarification, or appeals of applied measures, please contact the library.',
            'contacts_label' => 'Open contacts',
            'contacts_href' => '/contacts',
            'leadership_label' => 'Library leadership',
            'leadership_href' => '/leadership',
            'version_label' => 'Document version',
            'version_value' => 'v1.0 (2026-04-22)',
        ],
    ];

    return [
        'header' => $header,
        'toc' => $toc,
        'general' => $general,
        'borrowing' => $borrowing,
        'digital' => $digital,
        'conduct' => $conduct,
        'penalties' => $penalties,
        'footer_meta' => $footerMeta,
        'last_reviewed_at' => '2026-04-22',
    ];
};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/catalog', function (Request $request, CatalogReadService $catalogReadService) {
    $uiSort = (string) $request->query('sort', 'relevance');
    $materialType = (string) $request->query('material_type', 'all');
    $yearBounds = $catalogReadService->yearBounds();
    $apiSortMap = [
        'relevance' => 'popular',
        'title' => 'title',
        'year_desc' => 'newest',
        'year_asc' => 'newest',
        'popular' => 'popular',
        'newest' => 'newest',
        'author' => 'author',
    ];

    $catalogBootstrap = $catalogReadService->search(
        query: trim((string) $request->query('q', '')),
        title: $request->filled('title') ? trim((string) $request->query('title')) : null,
        author: $request->filled('author') ? trim((string) $request->query('author')) : null,
        publisher: $request->filled('publisher') ? trim((string) $request->query('publisher')) : null,
        isbn: $request->filled('isbn') ? trim((string) $request->query('isbn')) : null,
        udc: $request->filled('udc') ? trim((string) $request->query('udc')) : null,
        language: $request->filled('language') ? (string) $request->query('language') : null,
        page: max((int) $request->query('page', 1), 1),
        limit: 10,
        sort: $apiSortMap[$uiSort] ?? 'popular',
        yearFrom: $request->filled('year_from') && is_numeric((string) $request->query('year_from')) ? (int) $request->query('year_from') : $yearBounds['min'],
        yearTo: $request->filled('year_to') && is_numeric((string) $request->query('year_to')) ? (int) $request->query('year_to') : $yearBounds['max'],
        availableOnly: $request->boolean('available_only'),
        physicalOnly: $request->boolean('physical_only'),
        institution: $request->filled('institution') ? (string) $request->query('institution') : null,
    );

    if ($uiSort === 'year_asc' && is_array($catalogBootstrap['data'] ?? null)) {
        usort($catalogBootstrap['data'], static fn (array $left, array $right): int => ((int) ($left['publicationYear'] ?? 0)) <=> ((int) ($right['publicationYear'] ?? 0)));
    }

    if ($materialType !== 'all' && is_array($catalogBootstrap['data'] ?? null)) {
        $catalogBootstrap['data'] = array_values(array_filter(
            $catalogBootstrap['data'],
            static function (array $item) use ($materialType): bool {
                $subjects = array_map(
                    static fn (array $subject): string => mb_strtolower(trim((string) ($subject['label'] ?? ''))),
                    $item['classification'] ?? []
                );
                $subjectText = implode(' ', $subjects);
                $total = (int) ($item['copies']['total'] ?? 0);
                $available = (int) ($item['copies']['available'] ?? 0);
                $kind = str_contains($subjectText, 'диссер') || str_contains($subjectText, 'thesis') || str_contains($subjectText, 'archive')
                    ? 'archive'
                    : ($total > 0 ? ($available > 0 ? 'physical' : 'archive') : 'digital');

                return $kind === $materialType;
            }
        ));
        $catalogBootstrap['meta']['total'] = count($catalogBootstrap['data']);
        $catalogBootstrap['meta']['total_pages'] = 1;
        $catalogBootstrap['meta']['totalPages'] = 1;
        $catalogBootstrap['meta']['page'] = 1;
    }

    return view('catalog', [
        'catalogBootstrap' => $catalogBootstrap,
        'catalogYearBounds' => $yearBounds,
    ]);
});

Route::get('/book/{isbn}', function (Request $request, string $isbn, BookDetailReadService $bookDetailReadService) {
    return view('book', [
        'bookIsbn' => $isbn,
        'bookBootstrap' => $bookDetailReadService->findByIdentifier($isbn),
    ]);
});

Route::get('/digital-viewer/{materialId}', function (Request $request, string $materialId) {
    return view('digital-viewer', ['materialId' => $materialId]);
});

Route::get('/account', function (Request $request) {
    $user = $request->session()->get('library.user');
    $lang = $request->query('lang', 'ru');
    $lang = in_array($lang, ['ru', 'kk', 'en'], true) ? $lang : 'ru';
    $accountPath = $lang === 'ru' ? '/account' : ('/account?lang='.$lang);

    if (! is_array($user)) {
        $loginPath = '/login?redirect='.urlencode($accountPath);
        if ($lang !== 'ru') {
            $loginPath .= '&lang='.$lang;
        }

        return redirect($loginPath);
    }

    return view('account', ['sessionUser' => $user]);
});

Route::post('/login', function (Request $request) use ($postLoginDestination) {
    $validated = $request->validate([
        'email' => ['nullable', 'string', 'email', 'required_without:login'],
        'login' => ['nullable', 'string', 'required_without:email'],
        'password' => ['required', 'string'],
        'device_name' => ['nullable', 'string', 'max:255'],
    ]);

    $loginIdentifier = trim((string) ($validated['login'] ?? $validated['email'] ?? 'unknown'));

    if ((bool) config('demo_auth.enabled')) {
        foreach (config('demo_auth.identities', []) as $slug => $identity) {
            $demoLogin = trim((string) ($identity['login'] ?? ''));
            $demoQuickLogin = trim((string) ($identity['quick_fill_login'] ?? ''));
            $demoEmail = trim((string) ($identity['email'] ?? ''));
            $demoPassword = (string) ($identity['password'] ?? '');

            $identifierMatches = $loginIdentifier !== ''
                && in_array(mb_strtolower($loginIdentifier), array_filter([
                    $demoLogin !== '' ? mb_strtolower($demoLogin) : null,
                    $demoQuickLogin !== '' ? mb_strtolower($demoQuickLogin) : null,
                    $demoEmail !== '' ? mb_strtolower($demoEmail) : null,
                ]), true);

            if ($identifierMatches && $demoPassword !== '' && hash_equals($demoPassword, (string) $validated['password'])) {
                $user = [
                    'id' => (string) ($identity['id'] ?? ''),
                    'name' => (string) ($identity['name'] ?? ''),
                    'email' => (string) ($identity['email'] ?? ''),
                    'login' => (string) ($identity['login'] ?? ''),
                    'ad_login' => (string) ($identity['ad_login'] ?? ''),
                    'role' => (string) ($identity['role'] ?? 'reader'),
                    'title' => (string) ($identity['title'] ?? ''),
                    'phone_extension' => (string) ($identity['phone_extension'] ?? ''),
                    'profile_type' => (string) ($identity['profile_type'] ?? ''),
                ];

                $request->session()->regenerate();
                $request->session()->put('library.crm_token', 'demo-token-' . $slug);
                $request->session()->put('library.user', $user);
                $request->session()->put('library.authenticated_at', now()->toIso8601String());
                $request->session()->put('library.demo_identity', $slug);

                return redirect($postLoginDestination($user));
            }
        }
    }

    $authApiUrl = config('services.external_auth.login_url', 'http://crm.local/api/login');

    $response = Http::timeout(12)->acceptJson()->post($authApiUrl, [
        'email' => $validated['email'] ?? null,
        'login' => $validated['login'] ?? null,
        'password' => $validated['password'],
        'device_name' => $validated['device_name'] ?? 'web',
    ]);

    if ($response->successful()) {
        $payload = $response->json();
        $token = (string) ($payload['token'] ?? $payload['access_token'] ?? '');

        if ($token === '') {
            return back()->withErrors(['login' => 'Authentication service returned an unexpected response.']);
        }

        $rawUser = $payload['user'] ?? $payload['data']['user'] ?? [];
        $user = is_array($rawUser) ? $rawUser : [];

        $request->session()->regenerate();
        $request->session()->put('library.crm_token', $token);
        $request->session()->put('library.user', $user);
        $request->session()->put('library.authenticated_at', now()->toIso8601String());

        return redirect($postLoginDestination($user));
    }

    return back()->withErrors(['login' => 'Invalid credentials or authentication failed.']);
});

// Session-based sign-out used by the member shell (form POST + CSRF).
// Admin and librarian shells use `fetch('/api/v1/logout')` for their JS-driven
// header buttons; this web route exists for the member shell's form control and
// keeps sign-out working without JavaScript. Accepts both POST (canonical form
// submit) and GET (safety net for accidental navigation) — always clears the
// library session keys and redirects to /login.
Route::match(['POST', 'GET'], '/logout', function (Request $request) {
    $request->session()->forget([
        'library.user',
        'library.crm_token',
        'library.authenticated_at',
        'library.demo_identity',
    ]);
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

Route::get('/login', function (Request $request) {
    $lang = $request->query('lang', 'ru');
    $lang = in_array($lang, ['ru', 'kk', 'en'], true) ? $lang : 'ru';

    if (is_array($request->session()->get('library.user'))) {
        return redirect($lang === 'ru' ? '/account' : ('/account?lang='.$lang));
    }

    $demoEnabled = (bool) config('demo_auth.enabled');
    $demoIdentities = [];
    if ($demoEnabled) {
        $demoTranslations = [
            'student' => [
                'ru' => ['label' => 'Студент', 'description' => 'Демо-доступ — поиск, каталог, подборка, кабинет'],
                'kk' => ['label' => 'Студент', 'description' => 'Демо-қолжетімділік — іздеу, каталог, іріктеме, кабинет'],
                'en' => ['label' => 'Student', 'description' => 'Demo access — search, catalog, shortlist, account'],
            ],
            'teacher' => [
                'ru' => ['label' => 'Преподаватель', 'description' => 'Демо-доступ — силлабус, подборка, рабочий стол'],
                'kk' => ['label' => 'Оқытушы', 'description' => 'Демо-қолжетімділік — силлабус, іріктеме, жұмыс аймағы'],
                'en' => ['label' => 'Faculty', 'description' => 'Demo access — syllabus, shortlist, teaching workspace'],
            ],
            'librarian' => [
                'ru' => ['label' => 'Библиотекарь', 'description' => 'Демо-доступ — выдача, возврат, фонд, рецензирование'],
                'kk' => ['label' => 'Кітапханашы', 'description' => 'Демо-қолжетімділік — беру, қайтару, қор, сараптау'],
                'en' => ['label' => 'Librarian', 'description' => 'Demo access — circulation, returns, holdings, review'],
            ],
            'admin' => [
                'ru' => ['label' => 'Администратор', 'description' => 'Демо-доступ — управление, настройки, отчёты'],
                'kk' => ['label' => 'Әкімші', 'description' => 'Демо-қолжетімділік — басқару, баптаулар, есептер'],
                'en' => ['label' => 'Administrator', 'description' => 'Demo access — management, settings, reports'],
            ],
        ];

        foreach (config('demo_auth.identities', []) as $slug => $identity) {
            $localizedIdentity = $demoTranslations[$slug][$lang] ?? null;

            $demoIdentities[] = [
                'slug' => $slug,
                'label' => $localizedIdentity['label'] ?? ($identity['label'] ?? $slug),
                'description' => $localizedIdentity['description'] ?? ($identity['description'] ?? ''),
                'icon' => $identity['icon'] ?? '👤',
                'role' => $identity['role'] ?? 'reader',
                'login' => $identity['login'] ?? '',
                'quickFillLogin' => $identity['quick_fill_login'] ?? ($identity['login'] ?? ''),
                'email' => $identity['email'] ?? '',
                'password' => $identity['password'] ?? '',
            ];
        }
    }

    $copy = [
        'ru' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Вход в библиотечную систему',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Быстрый вход',
            'demoSub' => 'Для проверки можно использовать подготовленные демо-профили.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
        'kk' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Кітапхана жүйесіне кіру',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Жедел кіру',
            'demoSub' => 'Тексеру үшін дайын демо-профильдерді қолдануға болады.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
        'en' => [
            'title' => 'Secure Access | KazUTB Digital Library',
            'brand' => 'KazUTB Digital Library',
            'legacyHero' => 'Sign in to the library system',
            'displayHeadline' => 'Preserving Knowledge, Empowering Research.',
            'lead' => 'Welcome to the official repository of the Kazakh University of Technology and Business. Access our curated collections of scientific journals, architectural archives, and economic research.',
            'accessHeading' => 'Secure Access',
            'accessValue' => 'Multi-factor authentication is active. Your connection to the digital repository is encrypted using institutional-grade protocols.',
            'supportHeading' => 'Support',
            'supportValue' => 'Encountering issues? Contact the IT help desk at support@kazutb.edu.kz for institutional access recovery.',
            'formTitle' => 'Access KazUTB Digital Library',
            'formSubtitle' => 'Sign in using your institutional credentials to explore our scientific archives.',
            'loginLabel' => 'Institutional ID',
            'loginPlaceholder' => 'Enter your faculty or student ID',
            'passwordLabel' => 'Password',
            'passwordPlaceholder' => '••••••••••••',
            'forgot' => 'Forgot?',
            'keepSigned' => 'Keep me signed in for 30 days',
            'submit' => 'Log in',
            'divider' => 'or access via',
            'sso' => 'Institutional SSO',
            'securityNotice' => 'Unauthorized access is prohibited. All activity is logged for security and auditing purposes as per institutional policy.',
            'demoTitle' => 'Quick access',
            'demoSub' => 'Prepared demo identities remain available for QA and review.',
            'footerLegal' => '© 2024 KazUTB Digital Library. All rights reserved.',
            'footerLinks' => [
                ['label' => 'Institutional Access', 'href' => '/login'],
                ['label' => 'Privacy Policy', 'href' => '/about'],
                ['label' => 'Terms of Service', 'href' => '/about'],
                ['label' => 'Help Desk', 'href' => '/contacts'],
            ],
        ],
    ];
    return view('auth', [
        'demoEnabled' => $demoEnabled,
        'demoIdentities' => $demoIdentities,
        'copy' => $copy,
        'lang' => $lang,
    ]);
})->name('login');

// Consolidated pages: /services removed — redirect to prevent 404s.
// Phase 3.3 — /news reinstated as the canonical public news index.
Route::get('/services', fn () => redirect('/', 301));

Route::get('/news', function () use ($newsSeedProvider) {
    $seed = $newsSeedProvider();
    $articles = array_map(
        static fn (string $slug) => $seed['articles'][$slug],
        $seed['ordered']
    );

    return view('news.index', [
        'activePage' => 'news',
        'newsArticles' => $articles,
    ]);
});

Route::get('/news/{slug}', function (string $slug) use ($newsSeedProvider) {
    $seed = $newsSeedProvider();
    abort_unless(isset($seed['articles'][$slug]), 404);

    $article = $seed['articles'][$slug];
    $related = array_values(array_filter(
        array_map(static fn (string $relatedSlug) => $seed['articles'][$relatedSlug], $seed['ordered']),
        static fn (array $candidate) => $candidate['slug'] !== $slug,
    ));

    return view('news.show', [
        'activePage' => 'news',
        'article' => $article,
        'relatedArticles' => $related,
    ]);
})->where('slug', '[a-z0-9-]+');

// Phase 3 Cluster B.6 — seeded public contacts content for /contacts.
//
// Scoped strictly to this page; structure mirrors $rulesSeedProvider and
// $leadershipSeedProvider so a future backend phase can replace the
// closure with a DB-backed source. The three v1 fund rooms (1/200,
// 1/202, 1/203) are the public wayfinding truth and MUST remain stable.
$contactsSeedProvider = static function (): array {
    $departmentOptions = [
        'ru' => [
            'faculty' => 'Преподавателям / исследователям',
            'student' => 'Студентам / магистрантам',
            'college' => 'Колледж',
            'other' => 'Другое / гость',
        ],
        'kk' => [
            'faculty' => 'Оқытушылар / зерттеушілер',
            'student' => 'Студенттер / магистранттар',
            'college' => 'Колледж',
            'other' => 'Басқа / қонақ',
        ],
        'en' => [
            'faculty' => 'Faculty / researchers',
            'student' => 'Students / master\'s candidates',
            'college' => 'College',
            'other' => 'Other / guest',
        ],
    ];

    $supportChannels = [
        'ru' => [
            [
                'slug' => 'research',
                'icon' => 'local_library',
                'title' => 'Библиотекарь-консультант',
                'body' => 'Помощь в поиске литературы, работе с подпиской и базами данных, оформлении списков источников и цитирований.',
                'email' => 'library@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
            [
                'slug' => 'technical',
                'icon' => 'computer',
                'title' => 'Техническая поддержка',
                'body' => 'Вход через корпоративный аккаунт, доступ к цифровой библиотеке, ошибки в каталоге и кабинете читателя.',
                'email' => 'support@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
        ],
        'kk' => [
            [
                'slug' => 'research',
                'icon' => 'local_library',
                'title' => 'Кітапханашы-кеңесші',
                'body' => 'Әдебиет іздеу, жазылымдар мен дерекқорлармен жұмыс, дереккөздер тізімі мен сілтемелерді ресімдеуге көмек.',
                'email' => 'library@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
            [
                'slug' => 'technical',
                'icon' => 'computer',
                'title' => 'Техникалық қолдау',
                'body' => 'Корпоративтік аккаунт арқылы кіру, цифрлық кітапханаға қолжетімділік, каталог пен оқырман кабинетіндегі қателер.',
                'email' => 'support@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
        ],
        'en' => [
            [
                'slug' => 'research',
                'icon' => 'local_library',
                'title' => 'Librarian consultation',
                'body' => 'Help with finding literature, working with subscriptions and databases, preparing reference lists and citations.',
                'email' => 'library@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
            [
                'slug' => 'technical',
                'icon' => 'computer',
                'title' => 'Technical support',
                'body' => 'Institutional sign-in, access to the digital library, and issues in the catalog or reader workspace.',
                'email' => 'support@kazutb.edu.kz',
                'phone' => '+7 (7172) 64-58-58',
            ],
        ],
    ];

    $fundRooms = [
        'ru' => [
            [
                'room' => '1/200',
                'floor' => 'Корпус 1 · 2 этаж',
                'fund_label' => 'Технологический фонд',
                'short_description' => 'Учебная и научная литература по инженерным, ИТ и прикладным направлениям.',
                'access_note' => 'Открытый доступ для читателей университета.',
            ],
            [
                'room' => '1/202',
                'floor' => 'Корпус 1 · 2 этаж',
                'fund_label' => 'Фонд колледжа',
                'short_description' => 'Учебные материалы и методические пособия для программ колледжа КазУТБ.',
                'access_note' => 'Приоритетный доступ для студентов колледжа.',
            ],
            [
                'room' => '1/203',
                'floor' => 'Корпус 1 · 2 этаж',
                'fund_label' => 'Экономический фонд',
                'short_description' => 'Литература по экономике, менеджменту, финансам и праву.',
                'access_note' => 'Открытый доступ для читателей университета.',
            ],
        ],
        'kk' => [
            [
                'room' => '1/200',
                'floor' => '1-корпус · 2-қабат',
                'fund_label' => 'Технологиялық қор',
                'short_description' => 'Инженерлік, IT және қолданбалы бағыттар бойынша оқу және ғылыми әдебиет.',
                'access_note' => 'Университет оқырмандары үшін ашық қолжетімділік.',
            ],
            [
                'room' => '1/202',
                'floor' => '1-корпус · 2-қабат',
                'fund_label' => 'Колледж қоры',
                'short_description' => 'ҚазУТБ колледжі бағдарламалары үшін оқу материалдары мен әдістемелік құралдар.',
                'access_note' => 'Колледж студенттеріне басым қолжетімділік.',
            ],
            [
                'room' => '1/203',
                'floor' => '1-корпус · 2-қабат',
                'fund_label' => 'Экономикалық қор',
                'short_description' => 'Экономика, менеджмент, қаржы және құқық бойынша әдебиет.',
                'access_note' => 'Университет оқырмандары үшін ашық қолжетімділік.',
            ],
        ],
        'en' => [
            [
                'room' => '1/200',
                'floor' => 'Building 1 · Level 2',
                'fund_label' => 'Technology fund',
                'short_description' => 'Teaching and research materials for engineering, IT, and applied programmes.',
                'access_note' => 'Open access for university readers.',
            ],
            [
                'room' => '1/202',
                'floor' => 'Building 1 · Level 2',
                'fund_label' => 'College fund',
                'short_description' => 'Teaching materials and methodological guides for KazUTB college programmes.',
                'access_note' => 'Priority access for college students.',
            ],
            [
                'room' => '1/203',
                'floor' => 'Building 1 · Level 2',
                'fund_label' => 'Economics fund',
                'short_description' => 'Literature for economics, management, finance, and law programmes.',
                'access_note' => 'Open access for university readers.',
            ],
        ],
    ];

    $hoursRows = [
        'ru' => [
            ['days' => 'Пн – Пт', 'hours' => '09:00 – 18:00'],
            ['days' => 'Сб', 'hours' => '10:00 – 15:00'],
            ['days' => 'Вс', 'hours' => 'Закрыто'],
        ],
        'kk' => [
            ['days' => 'Дс – Жм', 'hours' => '09:00 – 18:00'],
            ['days' => 'Сб', 'hours' => '10:00 – 15:00'],
            ['days' => 'Жс', 'hours' => 'Жабық'],
        ],
        'en' => [
            ['days' => 'Mon – Fri', 'hours' => '09:00 – 18:00'],
            ['days' => 'Sat', 'hours' => '10:00 – 15:00'],
            ['days' => 'Sun', 'hours' => 'Closed'],
        ],
    ];

    return [
        'ru' => [
            'hero_title_a' => 'Прямые обращения',
            'hero_title_b' => 'и академическая поддержка.',
            'hero_body' => 'Свяжитесь с командой KazUTB Smart Library — консультации по фонду, помощь с цифровой подпиской, техническая поддержка доступа и административные вопросы.',
            'support_heading' => 'Каналы поддержки',
            'support_channels' => $supportChannels['ru'],
            'form_title' => 'Отправить запрос',
            'form_note' => 'Заполните форму — она откроет письмо в почтовом клиенте с темой запроса. Мы отвечаем в рабочие часы библиотеки.',
            'form_label_name' => 'Имя и фамилия',
            'form_placeholder_name' => 'например, Айгерим Омарова',
            'form_label_email' => 'Академическая почта',
            'form_placeholder_email' => 'username@kazutb.edu.kz',
            'form_label_department' => 'Факультет / категория',
            'form_placeholder_department' => 'Выберите вариант',
            'form_departments' => $departmentOptions['ru'],
            'form_label_message' => 'Сообщение',
            'form_placeholder_message' => 'Опишите ваш запрос как можно подробнее…',
            'form_submit' => 'Отправить запрос',
            'location_title' => 'Физический адрес',
            'location_address_line_a' => 'Астана, ул. Кайыма Мухамедханова, 37A',
            'location_address_line_b' => 'Главный корпус КазУТБ · Читательские залы — 2 этаж, корпус 1',
            'location_phone' => '+7 (7172) 64-58-58',
            'location_email' => 'library@kazutb.edu.kz',
            'location_directions_cta' => 'Открыть в Google Maps',
            'hours_label' => 'Режим работы',
            'hours_rows' => $hoursRows['ru'],
            'wayfinding_title' => 'Фонды и навигация по залам',
            'wayfinding_body' => 'В главном корпусе университета работают три читательских фонда, каждый поддерживает свою академическую программу. Ниже — коды залов, их тематическое назначение и условия доступа.',
            'map_label' => 'Главный корпус КазУТБ, Астана',
            'map_caption' => 'Статический ориентир: читательские залы и фонды расположены на 2 этаже корпуса 1.',
            'room_prefix' => 'Зал',
            'fund_rooms' => $fundRooms['ru'],
            'visit_title' => 'Перед визитом',
            'visit_body' => 'Перед первым посещением рекомендуем ознакомиться с правилами работы библиотеки и узнать, к кому обращаться по профильным вопросам.',
            'visit_link_rules_title' => 'Правила библиотеки',
            'visit_link_rules_body' => 'Условия записи, выдачи, пользования фондом и доступа к цифровым ресурсам.',
            'visit_link_leadership_title' => 'Руководство библиотеки',
            'visit_link_leadership_body' => 'Роли и зоны ответственности — для профильных запросов и эскалации.',
        ],
        'kk' => [
            'hero_title_a' => 'Тікелей сұраулар',
            'hero_title_b' => 'және академиялық қолдау.',
            'hero_body' => 'KazUTB Smart Library командасына хабарласыңыз — қор бойынша кеңестер, цифрлық жазылыммен көмек, қолжетімділіктің техникалық қолдауы және әкімшілік сұрақтар.',
            'support_heading' => 'Қолдау арналары',
            'support_channels' => $supportChannels['kk'],
            'form_title' => 'Сұрау жіберу',
            'form_note' => 'Форманы толтырыңыз — ол пошта клиентінде тақырыбы көрсетілген хатты ашады. Біз кітапхананың жұмыс сағаттарында жауап береміз.',
            'form_label_name' => 'Аты-жөні',
            'form_placeholder_name' => 'мысалы, Айгерім Омарова',
            'form_label_email' => 'Академиялық пошта',
            'form_placeholder_email' => 'username@kazutb.edu.kz',
            'form_label_department' => 'Факультет / санат',
            'form_placeholder_department' => 'Нұсқаны таңдаңыз',
            'form_departments' => $departmentOptions['kk'],
            'form_label_message' => 'Хабарлама',
            'form_placeholder_message' => 'Сұрауыңызды мүмкіндігінше толық сипаттаңыз…',
            'form_submit' => 'Сұрау жіберу',
            'location_title' => 'Физикалық мекенжай',
            'location_address_line_a' => 'Астана, Қайым Мұхамедханов көшесі, 37A',
            'location_address_line_b' => 'ҚазУТБ басты корпусы · Оқу залдары — 2-қабат, 1-корпус',
            'location_phone' => '+7 (7172) 64-58-58',
            'location_email' => 'library@kazutb.edu.kz',
            'location_directions_cta' => 'Google Maps-те ашу',
            'hours_label' => 'Жұмыс режимі',
            'hours_rows' => $hoursRows['kk'],
            'wayfinding_title' => 'Қорлар және залдар бойынша навигация',
            'wayfinding_body' => 'Университеттің басты корпусында үш оқу қоры жұмыс істейді, әрқайсысы өз академиялық бағдарламасын қолдайды. Төменде — залдардың кодтары, тақырыптық бағыты және қолжетімділік шарттары.',
            'map_label' => 'ҚазУТБ басты корпусы, Астана',
            'map_caption' => 'Статикалық ориентир: оқу залдары мен қорлар 1-корпустың 2-қабатында орналасқан.',
            'room_prefix' => 'Зал',
            'fund_rooms' => $fundRooms['kk'],
            'visit_title' => 'Келмес бұрын',
            'visit_body' => 'Алғашқы келер алдында кітапхана жұмысының ережелерімен танысып, бағытты сұрақтар бойынша кімге жүгіну керегін білген жөн.',
            'visit_link_rules_title' => 'Кітапхана ережелері',
            'visit_link_rules_body' => 'Тіркелу, беру, қорды пайдалану және цифрлық ресурстарға қолжетімділік шарттары.',
            'visit_link_leadership_title' => 'Кітапхана басшылығы',
            'visit_link_leadership_body' => 'Рөлдер мен жауапкершілік аймақтары — бағытты сұраулар мен эскалация үшін.',
        ],
        'en' => [
            'hero_title_a' => 'Direct Inquiries',
            'hero_title_b' => '& Academic Support.',
            'hero_body' => 'Connect with the KazUTB Smart Library team — collection consultations, digital subscription help, access troubleshooting, and administrative inquiries.',
            'support_heading' => 'Support Channels',
            'support_channels' => $supportChannels['en'],
            'form_title' => 'Submit an Inquiry',
            'form_note' => 'Fill in the form — it opens a pre-filled email in your mail client. We respond during library working hours.',
            'form_label_name' => 'Full name',
            'form_placeholder_name' => 'e.g. Aigerim Omarova',
            'form_label_email' => 'Academic email',
            'form_placeholder_email' => 'username@kazutb.edu.kz',
            'form_label_department' => 'Department / category',
            'form_placeholder_department' => 'Select an option',
            'form_departments' => $departmentOptions['en'],
            'form_label_message' => 'Message',
            'form_placeholder_message' => 'Please describe your inquiry in as much detail as possible…',
            'form_submit' => 'Send inquiry',
            'location_title' => 'Physical Location',
            'location_address_line_a' => '37A Kayym Mukhamedkhanov Street, Astana',
            'location_address_line_b' => 'KazUTB main building · Reading rooms — 2nd floor, Building 1',
            'location_phone' => '+7 (7172) 64-58-58',
            'location_email' => 'library@kazutb.edu.kz',
            'location_directions_cta' => 'Open in Google Maps',
            'hours_label' => 'Opening hours',
            'hours_rows' => $hoursRows['en'],
            'wayfinding_title' => 'Fund Guidance & Wayfinding',
            'wayfinding_body' => 'Three reading funds operate on the 2nd floor of the main building, each supporting a specific academic programme. Codes, thematic coverage, and access notes are listed below.',
            'map_label' => 'KazUTB main building, Astana',
            'map_caption' => 'Static reference: reading rooms and funds are on the 2nd floor of Building 1.',
            'room_prefix' => 'Room',
            'fund_rooms' => $fundRooms['en'],
            'visit_title' => 'Before you visit',
            'visit_body' => 'Before a first visit we recommend reviewing the library usage rules and checking who to contact for specialised questions.',
            'visit_link_rules_title' => 'Library usage rules',
            'visit_link_rules_body' => 'Registration, loans, use of the collection, and access to digital resources.',
            'visit_link_leadership_title' => 'Library leadership',
            'visit_link_leadership_body' => 'Roles and areas of responsibility — for specialised inquiries and escalation.',
        ],
    ];
};

Route::get('/about', function () {
    return view('about', ['activePage' => 'about']);
});

// Phase 3 Cluster B.6 — canonical-exact /contacts page.
// Replaces the previous shared-view activePage='contacts' branch on
// resources/views/about.blade.php with a standalone canonical page that
// mirrors docs/design-exports/contacts_canonical + integrates the three
// v1 fund rooms (1/200, 1/202, 1/203) as public wayfinding truth.
Route::get('/contacts', function () use ($contactsSeedProvider) {
    return view('contacts', [
        'activePage' => 'contacts',
        'contacts' => $contactsSeedProvider(),
    ]);
});

// Phase 3 Cluster B.1 — standalone public leadership surface.
// Content is driven by $leadershipSeedProvider (trilingual, role-first).
// Per Cluster B Content Contract §8 this route is NOT added to the primary
// navbar; global access is via the footer and (later) the Institutional
// Directory block on /about.
Route::get('/leadership', function () use ($leadershipSeedProvider) {
    return view('leadership', [
        'activePage' => 'leadership',
        'leadership' => $leadershipSeedProvider(),
    ]);
});

// Phase 3 Cluster B.2 — standalone public library-rules surface.
// Content is driven by $rulesSeedProvider (trilingual; frozen section
// order + stable anchor IDs per Cluster B Content Contract §2). Per
// contract §8 this route is NOT added to the primary navbar; global
// access is via the footer.
Route::get('/rules', function () use ($rulesSeedProvider) {
    return view('rules', [
        'activePage' => 'rules',
        'rules' => $rulesSeedProvider(),
    ]);
});

Route::get('/resources', function () {
    $externalResourceService = app(\App\Services\ExternalResourceService::class);
    $resources = $externalResourceService->list();
    $categories = $externalResourceService->categories();
    
    return view('resources', [
        'activePage' => 'resources',
        'resources' => $resources,
        'categories' => $categories,
    ]);
});

Route::get('/for-teachers', fn () => redirect('/resources', 301));

Route::get('/discover', function () {
    return view('discover', ['activePage' => 'discover']);
});

Route::get('/shortlist', function () {
    return view('shortlist', ['activePage' => 'shortlist']);
});

// WS1 convergence freeze:
// Transitional reader route retained for controlled migration only.
// Do not add new callers; canonical public detail path is /book/{isbn}.
Route::get('/book/{isbn}/read', function ($isbn) {
    return view('reader', ['isbn' => $isbn]);
})->name('reader.transitional');

// Phase 1.4 — transitional compatibility layer.
// Canonical destinations live under /librarian/*; these paths 301-redirect so that
// deep-links and bookmarks keep working while operational traffic migrates. The
// canonical /librarian/* routes enforce their own auth + role gating, so the
// redirects themselves are intentionally public.
Route::permanentRedirect('/internal/dashboard', '/librarian');
Route::permanentRedirect('/internal/circulation', '/librarian/circulation');
Route::permanentRedirect('/internal/stewardship', '/librarian/data-cleanup');

Route::prefix('internal')->middleware(['library.auth'])->group(function () use ($internalStaffView) {
    // Remaining transitional surfaces — no confirmed canonical /librarian/* destination yet.
    // /internal/review — "Quality Issues Overview" (DB-backed read-only review); candidate
    //   canonical target is undecided between /librarian/data-cleanup and /librarian/repository.
    // /internal/ai-chat — experimental staff AI assistant; no canonical surface in roadmap yet.
    Route::get('/review', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-review');
    });
    Route::get('/ai-chat', function (Request $request) use ($internalStaffView) {
        return $internalStaffView($request, 'internal-ai-chat');
    });
});

Route::prefix('librarian')->middleware(['library.auth', 'librarian.staff'])->name('librarian.')->group(function () use ($librarianView) {
    // Librarian Overview — canonical landing for library staff (PROJECT_CONTEXT §30).
    Route::get('/', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.overview');
    })->name('overview');

    // Phase 1.2 — canonical librarian screens (ported from design exports).
    Route::get('/circulation', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.circulation');
    })->name('circulation');

    Route::get('/data-cleanup', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.data-cleanup');
    })->name('data-cleanup');

    Route::get('/repository', function (Request $request) use ($librarianView) {
        return $librarianView($request, 'librarian.repository');
    })->name('repository');
});

// Phase 2a — canonical member-facing shell for ordinary users (role='reader').
// Librarians and admins are rejected by the `member.reader` middleware and
// redirected to their own operational shells via the standard 403 flow.
// The transitional /account route is retained and unchanged for now.
Route::prefix('dashboard')->middleware(['library.auth', 'member.reader'])->name('member.')->group(function () use ($memberView) {
    Route::get('/', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.dashboard');
    })->name('dashboard');

    Route::get('/reservations', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.reservations');
    })->name('reservations');

    Route::get('/list', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.list');
    })->name('list');

    Route::get('/history', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.history');
    })->name('history');

    Route::get('/notifications', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.notifications');
    })->name('notifications');

    Route::get('/messages', function (Request $request) use ($memberView) {
        return $memberView($request, 'member.messages');
    })->name('messages');
});

Route::prefix('admin')->middleware(['library.auth', 'admin.staff'])->name('admin.')->group(function () use ($adminView) {
    Route::get('/', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.overview');
    })->name('overview');

    Route::get('/users', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.users');
    })->name('users');

    Route::get('/logs', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.governance');
    })->name('logs');

    Route::get('/news', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.news');
    })->name('news');

    Route::get('/feedback', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.feedback');
    })->name('feedback');

    Route::get('/settings', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.settings');
    })->name('settings');

    Route::get('/reports', function (Request $request) use ($adminView) {
        return $adminView($request, 'admin.reports');
    })->name('reports');
});

// SPA shell — React Router handles client-side routing under /app
Route::get('/app/{any?}', function () {
    return view('spa');
})->where('any', '.*');
