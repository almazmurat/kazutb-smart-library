@extends('layouts.public')

@section('title', 'Тематический поиск — Библиотека КазУТБ')

@section('content')
  <section class="page-hero">
    <div class="container">
      <div class="eyebrow eyebrow--cyan">По направлениям</div>
      <h1>Тематический поиск литературы</h1>
      <p>Выберите направление подготовки или область знаний, чтобы найти учебную, научную и методическую литературу в фонде библиотеки и электронных ресурсах.</p>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head">
        <div>
          <h2>Направления и области знаний</h2>
          <p>Основные тематические области фонда университетской библиотеки, ориентированные на образовательные программы КазУТБ.</p>
        </div>
      </div>

      <div class="discover-grid">

        <div class="discover-card">
          <div class="discover-icon">💻</div>
          <h3>Информатика и IT</h3>
          <p>Программирование, информационные системы, базы данных, сети, кибербезопасность, искусственный интеллект.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=программирование" class="keyword-chip">программирование</a>
            <a href="/catalog?q=информационные+системы" class="keyword-chip">информационные системы</a>
            <a href="/catalog?q=базы+данных" class="keyword-chip">базы данных</a>
            <a href="/catalog?q=информатика" class="keyword-chip">информатика</a>
          </div>
          <a href="/catalog?q=информатика" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">📊</div>
          <h3>Экономика и менеджмент</h3>
          <p>Микро- и макроэкономика, финансы, бухгалтерский учёт, управление, маркетинг, предпринимательство, бизнес-планирование.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=экономика" class="keyword-chip">экономика</a>
            <a href="/catalog?q=менеджмент" class="keyword-chip">менеджмент</a>
            <a href="/catalog?q=финансы" class="keyword-chip">финансы</a>
            <a href="/catalog?q=маркетинг" class="keyword-chip">маркетинг</a>
          </div>
          <a href="/catalog?q=экономика" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">⚖️</div>
          <h3>Право и юриспруденция</h3>
          <p>Гражданское, уголовное, административное, трудовое, международное право, правовая система Республики Казахстан.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=право" class="keyword-chip">право</a>
            <a href="/catalog?q=юриспруденция" class="keyword-chip">юриспруденция</a>
            <a href="/catalog?q=гражданское+право" class="keyword-chip">гражданское право</a>
            <a href="/catalog?q=уголовное+право" class="keyword-chip">уголовное право</a>
          </div>
          <a href="/catalog?q=право" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">🎓</div>
          <h3>Педагогика и образование</h3>
          <p>Методика преподавания, дидактика, психология обучения, управление образованием, инклюзивное образование.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=педагогика" class="keyword-chip">педагогика</a>
            <a href="/catalog?q=методика+преподавания" class="keyword-chip">методика преподавания</a>
            <a href="/catalog?q=психология" class="keyword-chip">психология</a>
            <a href="/catalog?q=образование" class="keyword-chip">образование</a>
          </div>
          <a href="/catalog?q=педагогика" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">⚙️</div>
          <h3>Инженерия и технологии</h3>
          <p>Машиностроение, электротехника, автоматизация, энергетика, транспорт, промышленные технологии.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=инженерия" class="keyword-chip">инженерия</a>
            <a href="/catalog?q=технология" class="keyword-chip">технология</a>
            <a href="/catalog?q=машиностроение" class="keyword-chip">машиностроение</a>
            <a href="/catalog?q=автоматизация" class="keyword-chip">автоматизация</a>
          </div>
          <a href="/catalog?q=технология" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">🧪</div>
          <h3>Химия и пищевые технологии</h3>
          <p>Органическая и неорганическая химия, пищевая промышленность, технология продуктов питания, безопасность пищевой продукции.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=химия" class="keyword-chip">химия</a>
            <a href="/catalog?q=пищевая+технология" class="keyword-chip">пищевая технология</a>
            <a href="/catalog?q=продукты+питания" class="keyword-chip">продукты питания</a>
          </div>
          <a href="/catalog?q=химия" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">🏗️</div>
          <h3>Строительство и архитектура</h3>
          <p>Строительные конструкции, архитектурное проектирование, строительные материалы, градостроительство, сейсмостойкость.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=строительство" class="keyword-chip">строительство</a>
            <a href="/catalog?q=архитектура" class="keyword-chip">архитектура</a>
            <a href="/catalog?q=проектирование" class="keyword-chip">проектирование</a>
          </div>
          <a href="/catalog?q=строительство" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">🗣️</div>
          <h3>Языки и филология</h3>
          <p>Казахский язык, русский язык, английский язык, лингвистика, литературоведение, переводоведение, межкультурная коммуникация.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=казахский+язык" class="keyword-chip">қазақ тілі</a>
            <a href="/catalog?q=русский+язык" class="keyword-chip">русский язык</a>
            <a href="/catalog?q=английский+язык" class="keyword-chip">английский язык</a>
            <a href="/catalog?q=лингвистика" class="keyword-chip">лингвистика</a>
          </div>
          <a href="/catalog?q=язык" class="feature-link">Искать в каталоге →</a>
        </div>

        <div class="discover-card">
          <div class="discover-icon">🔬</div>
          <h3>Естественные науки</h3>
          <p>Математика, физика, биология, экология, география, науки о Земле.</p>
          <div class="discover-keywords">
            <span class="keyword-label">Искать:</span>
            <a href="/catalog?q=математика" class="keyword-chip">математика</a>
            <a href="/catalog?q=физика" class="keyword-chip">физика</a>
            <a href="/catalog?q=биология" class="keyword-chip">биология</a>
            <a href="/catalog?q=экология" class="keyword-chip">экология</a>
          </div>
          <a href="/catalog?q=математика" class="feature-link">Искать в каталоге →</a>
        </div>

      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="section-head section-head-centered">
        <div>
          <div class="eyebrow eyebrow--violet">Для преподавателей</div>
          <h2>Как использовать тематический поиск для силлабуса</h2>
          <p>Пошаговый подход к формированию списка литературы по дисциплине.</p>
        </div>
      </div>

      <div class="workflow-steps">
        <div class="workflow-step">
          <div class="step-marker">1</div>
          <div class="step-content">
            <h3>Определите область</h3>
            <p>Выберите тематическое направление выше, соответствующее вашей дисциплине. Используйте ключевые слова для перехода в каталог.</p>
          </div>
        </div>

        <div class="workflow-step">
          <div class="step-marker">2</div>
          <div class="step-content">
            <h3>Уточните поиск в каталоге</h3>
            <p>В <a href="/catalog">каталоге</a> добавьте специфические термины вашего курса, фильтруйте по году, языку и наличию экземпляров.</p>
          </div>
        </div>

        <div class="workflow-step">
          <div class="step-marker">3</div>
          <div class="step-content">
            <h3>Проверьте электронные ресурсы</h3>
            <p>На странице <a href="/resources">электронных ресурсов</a> проверьте доступность в IPR SMART, научных базах и открытых коллекциях.</p>
          </div>
        </div>

        <div class="workflow-step">
          <div class="step-marker">4</div>
          <div class="step-content">
            <h3>Сформируйте список</h3>
            <p>Объедините найденные источники в список литературы для силлабуса. При необходимости <a href="/contacts">обратитесь к библиографу</a> за помощью.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="page-section">
    <div class="container">
      <div class="info-banner">
        <div class="info-banner-icon">📌</div>
        <div class="info-banner-text">
          <h3>О тематической классификации</h3>
          <p>Представленные направления ориентированы на образовательные программы КазУТБ и основные области фонда библиотеки. В будущем тематический поиск будет расширен с использованием УДК (Универсальной десятичной классификации) для более точной навигации по фонду.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <h2>Не нашли нужное направление?</h2>
      <p>Воспользуйтесь полнотекстовым поиском по каталогу или обратитесь к библиографам за помощью в подборе литературы.</p>
      <div class="cta-buttons">
        <a href="/catalog" class="btn btn-primary">Открыть каталог</a>
        <a href="/for-teachers" class="btn btn-ghost">Ресурсы для преподавателей</a>
      </div>
    </div>
  </section>
@endsection

@section('head')
<style>
  .discover-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
    gap: 24px;
  }

  .discover-card {
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 28px;
    display: flex;
    flex-direction: column;
    transition: border-color .2s, box-shadow .2s;
  }

  .discover-card:hover {
    border-color: var(--blue);
    box-shadow: 0 4px 20px rgba(99,102,241,.08);
  }

  .discover-icon {
    font-size: 32px;
    margin-bottom: 12px;
  }

  .discover-card h3 {
    margin: 0 0 8px;
    font-size: 19px;
    font-weight: 800;
  }

  .discover-card > p {
    margin: 0 0 16px;
    color: var(--muted);
    font-size: 14.5px;
    line-height: 1.65;
    flex: 1;
  }

  .discover-keywords {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-bottom: 14px;
  }

  .keyword-label {
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  .keyword-chip {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    background: var(--bg-soft);
    color: var(--blue);
    border: 1px solid var(--border);
    text-decoration: none;
    transition: background .15s, border-color .15s;
  }

  .keyword-chip:hover {
    background: var(--blue);
    color: var(--text-inverse);
    border-color: var(--blue);
  }

  .feature-link {
    display: inline-block;
    font-size: 14px;
    font-weight: 700;
    color: var(--blue);
    text-decoration: none;
    transition: color .2s;
  }

  .feature-link:hover { color: var(--violet); }

  /* Workflow steps */
  .workflow-steps {
    max-width: 720px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 0;
  }

  .workflow-step {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    padding: 24px 0;
    border-bottom: 1px solid var(--border);
  }

  .workflow-step:last-child { border-bottom: none; }

  .step-marker {
    flex-shrink: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--blue), var(--cyan));
    color: var(--text-inverse);
    font-weight: 800;
    font-size: 16px;
  }

  .step-content h3 {
    margin: 0 0 6px;
    font-size: 17px;
    font-weight: 700;
  }

  .step-content p {
    margin: 0;
    color: var(--muted);
    font-size: 15px;
    line-height: 1.65;
  }

  .step-content a {
    color: var(--blue);
    font-weight: 600;
    text-decoration: none;
  }

  .step-content a:hover { text-decoration: underline; }

  /* Info banner */
  .info-banner {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    background: var(--surface-glass);
    border: 1px solid var(--border);
    border-left: 4px solid var(--blue);
    border-radius: var(--radius-lg);
    padding: 24px 28px;
  }

  .info-banner-icon {
    font-size: 24px;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .info-banner-text h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 700;
  }

  .info-banner-text p {
    margin: 0;
    color: var(--muted);
    font-size: 14.5px;
    line-height: 1.65;
  }

  @media (max-width: 680px) {
    .discover-grid { grid-template-columns: 1fr; }
    .discover-card { padding: 20px; }
    .workflow-step { gap: 14px; padding: 18px 0; }
    .step-marker { width: 32px; height: 32px; font-size: 14px; }
    .info-banner { flex-direction: column; gap: 12px; padding: 20px; }
  }
</style>
@endsection
