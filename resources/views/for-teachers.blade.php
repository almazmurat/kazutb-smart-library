@extends('layouts.public')

@section('title', 'Преподавателям — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow eyebrow--violet">Преподавателям</div>
      <h1>Ресурсы и поддержка для преподавателей</h1>
      <p>Библиотека КазУТБ помогает преподавателям в подборе учебной литературы, подготовке силлабусов, доступе к научным базам данных и методическом обеспечении дисциплин.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Что предлагает библиотека</h2>
          <p>Основные направления поддержки преподавательской и научной работы.</p>
        </div>
      </div>

      <div class="feature-grid teacher-grid">
        <div class="feature-card" style="border: 2px solid var(--violet, #7c3aed); background: linear-gradient(135deg, rgba(124,58,237,.03), rgba(124,58,237,.08));">
          <div class="icon">📋</div>
          <h3>Подборка литературы для силлабуса</h3>
          <p>Собирайте книги и электронные ресурсы в подборку — черновик списка литературы. Выберите формат, скопируйте текст или распечатайте готовый список для вставки в силлабус. Ваши черновики и статистика доступны в <a href="/account">личном кабинете</a>.</p>
          <a href="/shortlist" class="feature-link" style="font-weight:700;">Моя подборка →</a>
        </div>

        <div class="feature-card">
          <div class="icon">📚</div>
          <h3>Подбор литературы для дисциплин</h3>
          <p>Помощь в поиске и подборе учебной, научной и методической литературы для включения в силлабусы и рабочие программы дисциплин. Поиск по каталогу охватывает более 50 000 единиц фонда.</p>
          <a href="/catalog" class="feature-link">Открыть каталог →</a>
        </div>

        <div class="feature-card">
          <div class="icon">📐</div>
          <h3>Поиск по направлениям</h3>
          <p>Тематический поиск литературы по областям знаний: экономика, IT, право, инженерия, химия, строительство, языки и другие направления подготовки.</p>
          <a href="/discover" class="feature-link">Выбрать направление →</a>
        </div>

        <div class="feature-card">
          <div class="icon">🌐</div>
          <h3>Электронные ресурсы и базы данных</h3>
          <p>Доступ к лицензированным электронным библиотекам, международным научным базам данных и открытым образовательным ресурсам. Подписки действуют для студентов и преподавателей.</p>
          <a href="/resources" class="feature-link">Обзор ресурсов →</a>
        </div>

        <div class="feature-card">
          <div class="icon">🔬</div>
          <h3>Поддержка научных исследований</h3>
          <p>Консультации по поиску научных публикаций, работе с базами Scopus, Web of Science, РИНЦ. Помощь в формировании списков литературы для статей и диссертаций.</p>
          <a href="/resources" class="feature-link">Научные базы →</a>
        </div>

        <div class="feature-card">
          <div class="icon">📋</div>
          <h3>Методическое обеспечение</h3>
          <p>Информация о наличии рекомендованной литературы в фонде, подготовка справок по обеспеченности дисциплин учебной литературой, рекомендации по дополнению фонда.</p>
          <a href="/services" class="feature-link">Сервисы библиотеки →</a>
        </div>

        <div class="feature-card">
          <div class="icon">💡</div>
          <h3>Консультации библиографов</h3>
          <p>Индивидуальные консультации специалистов по поиску источников, работе с электронными ресурсами, оформлению библиографических списков и информационной грамотности.</p>
          <a href="/contacts" class="feature-link">Связаться →</a>
        </div>

        <div class="feature-card">
          <div class="icon">📦</div>
          <h3>Заявки на пополнение фонда</h3>
          <p>Преподаватели могут рекомендовать литературу для закупки и пополнения библиотечного фонда. Заявки рассматриваются отделом комплектования совместно с кафедрами.</p>
          <a href="/contacts" class="feature-link">Подать заявку →</a>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <h2>Подготовка силлабуса: как библиотека помогает</h2>
          <p>Пошаговый процесс работы с библиотекой при формировании списка литературы для учебной дисциплины.</p>
        </div>
      </div>

      <div class="syllabus-steps">
        <div class="step-card">
          <div class="step-number">1</div>
          <h3>Определите потребности</h3>
          <p>Сформулируйте перечень тем и направлений дисциплины. Используйте <a href="/discover">тематический поиск</a> для навигации по областям знаний. Определите, какие виды литературы нужны: учебники, монографии, справочные издания, научные журналы.</p>
        </div>

        <div class="step-card">
          <div class="step-number">2</div>
          <h3>Поиск в каталоге</h3>
          <p>Воспользуйтесь <a href="/catalog">электронным каталогом</a> для поиска имеющейся литературы. Фильтруйте по году, языку и наличию экземпляров.</p>
        </div>

        <div class="step-card">
          <div class="step-number">3</div>
          <h3>Проверьте электронные ресурсы</h3>
          <p>Изучите <a href="/resources">электронные ресурсы</a> — подписные базы и открытые коллекции могут дополнить или заменить печатные издания.</p>
        </div>

        <div class="step-card">
          <div class="step-number">4</div>
          <h3>Соберите подборку</h3>
          <p>Добавляйте найденные книги и электронные ресурсы в <a href="/shortlist">подборку литературы</a>. Из каталога и страниц книг нажимайте «В подборку» — все выбранные источники соберутся в один список. Дайте черновику название и добавьте заметки — это поможет вернуться к нему позже через <a href="/account">кабинет</a>.</p>
        </div>

        <div class="step-card">
          <div class="step-number">5</div>
          <h3>Экспортируйте список</h3>
          <p>В <a href="/shortlist">подборке</a> выберите формат списка литературы: нумерованный, по разделам или для силлабуса. Скопируйте текст одним нажатием или распечатайте готовый черновик для вставки в документ.</p>
        </div>

        <div class="step-card">
          <div class="step-number">6</div>
          <h3>Обратитесь к библиографу</h3>
          <p>Для углублённого подбора и проверки обеспеченности обратитесь в <a href="/contacts">информационно-библиографический отдел</a>. Специалисты помогут подготовить справку.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <div class="eyebrow eyebrow--cyan">Электронные ресурсы</div>
          <h2>Ключевые базы для преподавателей</h2>
          <p>Электронные ресурсы, доступные преподавателям и сотрудникам университета.</p>
        </div>
      </div>

      <div class="resource-highlights" id="teacher-ext-resources">
        <div class="card resource-highlight-card">
          <div class="eyebrow eyebrow--blue">Загрузка...</div>
          <h3>Электронные ресурсы</h3>
          <p class="text-body" style="margin: 0;">Загрузка данных о доступных ресурсах...</p>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <h2>Часто задаваемые вопросы</h2>
          <p>Ответы на типичные вопросы преподавателей о работе с библиотекой.</p>
        </div>
      </div>

      <div class="teacher-faq">
        <details class="faq-item">
          <summary class="faq-question">Как подобрать литературу для нового курса?</summary>
          <div class="faq-answer">
            <p>Начните с поиска в <a href="/catalog">электронном каталоге</a> по ключевым словам и темам дисциплины. Для углублённого подбора обратитесь в информационно-библиографический отдел — специалисты помогут найти подходящую литературу, проверить наличие в фонде и предложить альтернативы из электронных ресурсов.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary class="faq-question">Какие электронные ресурсы доступны для преподавателей?</summary>
          <div class="faq-answer">
            <p>Преподавателям доступны все подписные электронные ресурсы университета, включая IPR SMART, открытые научные коллекции и международные базы данных. Полный перечень и условия доступа — на странице <a href="/resources">Электронные ресурсы</a>.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary class="faq-question">Можно ли заказать литературу, которой нет в фонде?</summary>
          <div class="faq-answer">
            <p>Да. Преподаватели могут подать заявку на приобретение литературы через отдел комплектования. Заявки рассматриваются в рамках плана пополнения фонда. Обратитесь по <a href="/contacts">контактам библиотеки</a> для оформления заявки.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary class="faq-question">Как получить справку об обеспеченности дисциплины литературой?</summary>
          <div class="faq-answer">
            <p>Справки об обеспеченности готовит информационно-библиографический отдел. Подайте запрос с указанием названия дисциплины, направления подготовки и перечня рекомендуемой литературы. Свяжитесь с нами через <a href="/contacts">страницу контактов</a>.</p>
          </div>
        </details>

        <details class="faq-item">
          <summary class="faq-question">Есть ли удалённый доступ к электронным ресурсам?</summary>
          <div class="faq-answer">
            <p>Да. Авторизуйтесь через <a href="/account">личный кабинет</a> библиотеки для доступа к подписным электронным ресурсам из любой точки. Из кампуса доступ к ресурсам автоматический через сеть университета.</p>
          </div>
        </details>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <h2>Нужна помощь с подбором литературы?</h2>
      <p>Обратитесь к специалистам информационно-библиографического отдела для индивидуальной консультации.</p>
      <div class="cta-buttons">
        <a href="/contacts" class="btn btn-primary">Связаться с библиотекой</a>
        <a href="/catalog" class="btn btn-ghost">Открыть каталог</a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .teacher-grid {
    grid-template-columns: repeat(auto-fit, minmax(280px, 340px));
    justify-content: center;
  }

  .feature-link {
    display: inline-block;
    margin-top: 12px;
    font-size: 14px;
    font-weight: 700;
    color: var(--blue);
    text-decoration: none;
    transition: color .2s;
  }

  .feature-link:hover { color: var(--violet); }

  .syllabus-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    counter-reset: step;
  }

  .step-card {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px;
    position: relative;
  }

  .step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue), var(--cyan));
    color: var(--text-inverse);
    font-weight: 800;
    font-size: 16px;
    margin-bottom: 14px;
  }

  .step-card h3 {
    margin: 0 0 10px;
    font-size: 18px;
    font-weight: 700;
  }

  .step-card p {
    margin: 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.6;
  }

  .step-card a {
    color: var(--blue);
    font-weight: 600;
    text-decoration: none;
  }

  .step-card a:hover { text-decoration: underline; }

  .resource-highlights {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
  }

  .resource-highlight-card {
    padding: 28px;
  }

  .resource-highlight-card h3 {
    margin: 8px 0 12px;
    font-size: 20px;
    font-weight: 800;
  }

  .teacher-faq {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 12px;
  }

  .faq-item {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
  }

  .faq-question {
    padding: 20px 24px;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background .2s;
  }

  .faq-question::-webkit-details-marker { display: none; }

  .faq-question::after {
    content: '+';
    font-size: 20px;
    font-weight: 300;
    color: var(--muted);
    transition: transform .2s;
  }

  details[open] .faq-question::after {
    content: '−';
  }

  .faq-question:hover {
    background: var(--bg-soft);
  }

  .faq-answer {
    padding: 0 24px 20px;
  }

  .faq-answer p {
    margin: 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.7;
  }

  .faq-answer a {
    color: var(--blue);
    font-weight: 600;
    text-decoration: none;
  }

  .faq-answer a:hover { text-decoration: underline; }

  @media (max-width: 680px) {
    .syllabus-steps { grid-template-columns: 1fr; }
    .resource-highlights { grid-template-columns: 1fr; }
    .teacher-grid { grid-template-columns: 1fr; }
    .step-card { padding: 20px; }
    .resource-highlight-card { padding: 20px; }
    .faq-question { padding: 16px 20px; font-size: 15px; }
    .faq-answer { padding: 0 20px 16px; }
  }
</style>
@endsection

@section('scripts')
<script>
(function() {
  const API_URL = '/api/v1/external-resources';

  const eyebrowColorMap = {
    electronic_library: 'blue',
    research_database: 'violet',
    open_access: 'green',
    analytics: 'pink'
  };

  function escapeHtml(text) {
    if (!text) return '';
    const d = document.createElement('div');
    d.textContent = text;
    return d.innerHTML;
  }

  function formatExpiry(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    const months = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
    return `Действует до ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
  }

  async function loadTeacherResources() {
    const container = document.getElementById('teacher-ext-resources');
    try {
      const res = await fetch(API_URL, { headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error('API error');

      const json = await res.json();
      const resources = (json.data || []).filter(r => r.status === 'active');
      const categories = json.meta?.categories || {};
      const accessTypes = json.meta?.access_types || {};

      // Show up to 4 highlighted resources, prioritizing licensed over open
      const licensed = resources.filter(r => r.access_type !== 'open');
      const open = resources.filter(r => r.access_type === 'open');
      const highlighted = [...licensed.slice(0, 3), ...open.slice(0, 1)].slice(0, 4);

      if (highlighted.length === 0) {
        container.innerHTML = '<p style="color:var(--muted);">Информация о внешних ресурсах временно недоступна.</p>';
        return;
      }

      container.innerHTML = highlighted.map(r => {
        const catInfo = categories[r.category] || {};
        const accInfo = accessTypes[r.access_type] || {};
        const color = eyebrowColorMap[r.category] || 'blue';

        return `
          <div class="card resource-highlight-card">
            <div class="eyebrow eyebrow--${color}">${escapeHtml(catInfo.label || r.category)}</div>
            <h3>${escapeHtml(r.title)}</h3>
            <p class="text-body" style="margin: 0 0 12px;">${escapeHtml(r.description)}</p>
            ${r.expiry_date
              ? `<span class="badge">${formatExpiry(r.expiry_date)}</span>`
              : `<span class="badge">${escapeHtml(accInfo.label || 'Доступно')}</span>`
            }
            ${r.url ? `<a href="${escapeHtml(r.url)}" target="_blank" rel="noopener" class="feature-link" style="display:block; margin-top:8px;">Перейти к ресурсу ↗</a>` : ''}
          </div>
        `;
      }).join('') + `
        <div class="card resource-highlight-card" style="display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center;">
          <div style="font-size:36px; margin-bottom:12px;">📚</div>
          <h3>Все внешние ресурсы</h3>
          <p class="text-body" style="margin: 0 0 12px;">Полный перечень подписных баз, открытых коллекций и аналитических ресурсов.</p>
          <a href="/resources" class="feature-link">Открыть все ресурсы →</a>
        </div>
      `;
    } catch (e) {
      container.innerHTML = `
        <div class="card resource-highlight-card">
          <div class="eyebrow eyebrow--blue">Электронные ресурсы</div>
          <h3>Внешние платформы</h3>
          <p class="text-body" style="margin: 0 0 12px;">Подписные электронные библиотеки, научные базы данных и открытые образовательные ресурсы.</p>
          <a href="/resources" class="feature-link">Перейти к ресурсам →</a>
        </div>
      `;
    }
  }

  loadTeacherResources();
})();
</script>
@endsection
