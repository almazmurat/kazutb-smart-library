@extends('layouts.public')

@section('title', 'Электронные ресурсы — Библиотека КазУТБ')

@section('head')
<style>
  .resource-hero-stats {
    display: flex;
    justify-content: center;
    gap: 48px;
    margin-top: 36px;
  }
  .resource-hero-stats .rh-stat {
    text-align: center;
  }
  .resource-hero-stats .rh-stat strong {
    display: block;
    font-size: 36px;
    font-weight: 900;
    letter-spacing: -1px;
    background: linear-gradient(135deg, var(--blue), var(--violet));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }
  .resource-hero-stats .rh-stat span {
    font-size: 14px;
    color: var(--muted);
    font-weight: 600;
  }

  .resource-section-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: start;
  }
  .resource-section-info {
    padding: 8px 0;
  }
  .resource-section-info h2 {
    margin: 0 0 12px;
    font-size: clamp(26px, 3.5vw, 38px);
    font-weight: 900;
    letter-spacing: -1px;
  }
  .resource-section-info p {
    color: var(--muted);
    font-size: 16px;
    line-height: 1.75;
    margin: 0 0 20px;
  }
  .resource-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    gap: 14px;
  }
  .resource-list-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius-md);
    background: var(--surface-glass);
    border: 1px solid var(--border);
    transition: transform .2s, box-shadow .2s;
  }
  .resource-list-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-soft);
  }
  .resource-list-item .rli-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: grid;
    place-items: center;
    font-size: 22px;
    flex-shrink: 0;
    color: #fff;
  }
  .resource-list-item .rli-icon--blue { background: linear-gradient(135deg, var(--blue), var(--cyan)); }
  .resource-list-item .rli-icon--violet { background: linear-gradient(135deg, var(--violet), var(--pink)); }
  .resource-list-item .rli-icon--green { background: linear-gradient(135deg, var(--green), var(--cyan)); }
  .resource-list-item .rli-icon--pink { background: linear-gradient(135deg, var(--pink), #f97316); }
  .resource-list-item h4 { margin: 0 0 4px; font-size: 17px; font-weight: 700; }
  .resource-list-item p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }

  .access-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }
  .access-card {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 32px 28px;
    text-align: center;
    transition: transform .25s, box-shadow .25s;
  }
  .access-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow);
  }
  .access-card .ac-icon {
    width: 64px;
    height: 64px;
    border-radius: 20px;
    display: grid;
    place-items: center;
    font-size: 28px;
    margin: 0 auto 18px;
    color: #fff;
  }
  .access-card h3 { margin: 0 0 10px; font-size: 20px; font-weight: 800; }
  .access-card p { margin: 0 0 18px; color: var(--muted); font-size: 15px; line-height: 1.65; }
  .access-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
  }
  .access-badge--campus { background: rgba(59,130,246,.1); color: var(--blue); }
  .access-badge--remote { background: rgba(124,58,237,.1); color: var(--violet); }
  .access-badge--open { background: rgba(34,197,94,.1); color: var(--green); }

  .faq-list {
    display: grid;
    gap: 16px;
    max-width: 800px;
    margin: 32px auto 0;
  }
  .faq-item {
    padding: 24px;
    border-radius: var(--radius-md);
    background: var(--surface-glass);
    border: 1px solid var(--border);
  }
  .faq-item h4 { margin: 0 0 8px; font-size: 17px; font-weight: 700; }
  .faq-item p { margin: 0; color: var(--muted); font-size: 15px; line-height: 1.65; }

  @media (max-width: 900px) {
    .resource-section-grid { grid-template-columns: 1fr; }
    .access-cards { grid-template-columns: 1fr; }
    .resource-hero-stats { gap: 24px; flex-wrap: wrap; }
    .access-card { padding: 24px 20px; }
  }

  @media (max-width: 680px) {
    .resource-hero-stats { gap: 16px; justify-content: space-around; }
    .resource-hero-stats .rh-stat strong { font-size: 28px; }
    .resource-hero-stats .rh-stat span { font-size: 12px; }
    .resource-list-item { padding: 16px; gap: 12px; }
    .resource-list-item .rli-icon { width: 40px; height: 40px; font-size: 18px; border-radius: 12px; }
    .resource-list-item h4 { font-size: 15px; }
    .resource-list-item p { font-size: 13px; }
    .access-card { padding: 20px 16px; }
    .access-card .ac-icon { width: 52px; height: 52px; font-size: 24px; }
    .access-card h3 { font-size: 18px; }
    .access-card p { font-size: 14px; }
    .faq-item { padding: 18px; }
    .faq-item h4 { font-size: 15px; }
    .faq-item p { font-size: 14px; }
    .faq-list { margin-top: 20px; gap: 12px; }
  }

  @media (max-width: 480px) {
    .resource-hero-stats { flex-direction: column; gap: 8px; align-items: center; }
    .resource-hero-stats .rh-stat { display: flex; gap: 8px; align-items: baseline; }
    .resource-hero-stats .rh-stat strong { font-size: 24px; }
    .resource-list-item .rli-icon { width: 36px; height: 36px; font-size: 16px; }
    .access-card { padding: 16px; }
  }
</style>
@endsection

@section('content')
<div class="page-hero">
  <div class="container">
    <div class="eyebrow">Электронные ресурсы</div>
    <h1>Цифровые коллекции и научные базы данных</h1>
    <p>Доступ к электронным учебникам, международным научным базам, лицензированным платформам и открытым образовательным ресурсам.</p>
    <div class="resource-hero-stats">
      <div class="rh-stat"><strong>10+</strong><span>научных баз</span></div>
      <div class="rh-stat"><strong>50 000+</strong><span>электронных документов</span></div>
      <div class="rh-stat"><strong>24/7</strong><span>удалённый доступ</span></div>
      <div class="rh-stat"><strong>3</strong><span>режима доступа</span></div>
    </div>
  </div>
</div>

<section class="page-section">
  <div class="container resource-section-grid">
    <div class="resource-section-info">
      <h2>Электронная библиотека</h2>
      <p>Полнотекстовая коллекция учебной и научной литературы в цифровом формате. Доступ из кампуса и удалённо через личный кабинет.</p>
      <p>Включает учебники, учебные пособия, монографии, методические материалы и периодические издания по всем направлениям подготовки университета.</p>
      <a href="/catalog" class="btn btn-primary" style="margin-top:8px;">Перейти в каталог</a>
    </div>
    <div class="resource-list">
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--blue">📖</div>
        <div><h4>Учебная литература</h4><p>Электронные версии учебников и пособий по направлениям: IT, бизнес, экономика, технологии, право.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--violet">📑</div>
        <div><h4>Научные издания</h4><p>Монографии, диссертации, авторефераты и сборники конференций университета.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--green">📋</div>
        <div><h4>Методические материалы</h4><p>Рабочие программы, методические указания и рекомендации кафедр.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--pink">📰</div>
        <div><h4>Периодика</h4><p>Подписка на научные журналы, вестники и периодические издания.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container resource-section-grid">
    <div class="resource-section-info">
      <h2>Научные базы данных</h2>
      <p>Международные и национальные базы данных для научных исследований, подготовки публикаций и работы с первоисточниками.</p>
      <p>Доступ к большинству ресурсов предоставляется из сети кампуса. Некоторые базы доступны удалённо через авторизацию.</p>
    </div>
    <div class="resource-list">
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--blue">🔬</div>
        <div><h4>Республиканская межвузовская электронная библиотека (РМЭБ)</h4><p>Электронные версии диссертаций, монографий и научных трудов казахстанских учёных.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--violet">📊</div>
        <div><h4>Электронная научная библиотека eLIBRARY.RU</h4><p>Крупнейшая научная электронная библиотека с индексами цитирования и полнотекстовыми статьями.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--green">🌍</div>
        <div><h4>Polpred.com</h4><p>Обзоры прессы и аналитика по экономике, бизнесу, праву и другим направлениям.</p></div>
      </div>
      <div class="resource-list-item">
        <div class="rli-icon rli-icon--pink">📚</div>
        <div><h4>IPR SMART</h4><p>Цифровая образовательная платформа с учебниками, монографиями и научными журналами.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <div class="section-head" style="justify-content:center; text-align:center; flex-direction:column; align-items:center;">
      <div>
        <div class="eyebrow">Режимы доступа</div>
        <h2 style="margin-top:8px;">Как получить доступ к ресурсам</h2>
        <p>Выберите подходящий способ доступа в зависимости от типа ресурса и вашего местоположения.</p>
      </div>
    </div>

    <div class="access-cards">
      <div class="access-card">
        <div class="ac-icon" style="background:linear-gradient(135deg, var(--blue), var(--cyan));">🏫</div>
        <h3>Из кампуса</h3>
        <p>Свободный доступ ко всем подписным ресурсам с компьютеров читальных залов и Wi-Fi сети университета.</p>
        <span class="access-badge access-badge--campus">Автоматически</span>
      </div>
      <div class="access-card">
        <div class="ac-icon" style="background:linear-gradient(135deg, var(--violet), var(--pink));">🌐</div>
        <h3>Удалённо</h3>
        <p>Авторизуйтесь через личный кабинет библиотеки для доступа к электронным ресурсам из любой точки.</p>
        <span class="access-badge access-badge--remote">По авторизации</span>
      </div>
      <div class="access-card">
        <div class="ac-icon" style="background:linear-gradient(135deg, var(--green), var(--cyan));">🔓</div>
        <h3>Открытый доступ</h3>
        <p>Ресурсы Open Access доступны без ограничений: репозитории, открытые журналы, образовательные материалы.</p>
        <span class="access-badge access-badge--open">Свободно</span>
      </div>
    </div>
  </div>
</section>

<section class="page-section">
  <div class="container">
    <div class="section-head" style="justify-content:center; text-align:center; flex-direction:column; align-items:center;">
      <div>
        <h2>Частые вопросы</h2>
        <p>Ответы на основные вопросы о работе с электронными ресурсами библиотеки.</p>
      </div>
    </div>

    <div class="faq-list">
      <div class="faq-item">
        <h4>Как получить удалённый доступ к электронным ресурсам?</h4>
        <p>Зарегистрируйтесь в библиотеке и войдите в личный кабинет. После авторизации подписные ресурсы будут доступны из любой точки.</p>
      </div>
      <div class="faq-item">
        <h4>Можно ли скачивать статьи и книги?</h4>
        <p>Возможность скачивания зависит от условий лицензии конкретного ресурса. Некоторые материалы доступны только для просмотра.</p>
      </div>
      <div class="faq-item">
        <h4>Какие ресурсы доступны бесплатно?</h4>
        <p>Ресурсы Open Access, электронная библиотека университета и некоторые национальные базы данных доступны без дополнительной оплаты.</p>
      </div>
      <div class="faq-item">
        <h4>Кто может пользоваться электронными ресурсами?</h4>
        <p>Студенты, преподаватели и сотрудники университета. Для доступа необходима регистрация в библиотечной системе.</p>
      </div>
    </div>
  </div>
</section>

<section class="page-section" style="border-top:none;">
  <div class="container" style="text-align:center;">
    <h2 style="margin:0 0 12px; font-size:clamp(28px,4vw,42px); font-weight:900; letter-spacing:-1px;">Нужна помощь с ресурсами?</h2>
    <p style="color:var(--muted); font-size:17px; line-height:1.7; max-width:600px; margin:0 auto 28px;">Обратитесь к библиографам за консультацией по поиску источников и работе с базами данных.</p>
    <div style="display:flex; justify-content:center; gap:14px; flex-wrap:wrap;">
      <a href="/contacts" class="btn btn-primary">Связаться с нами</a>
      <a href="/catalog" class="btn btn-ghost">Перейти в каталог</a>
    </div>
  </div>
</section>
@endsection
