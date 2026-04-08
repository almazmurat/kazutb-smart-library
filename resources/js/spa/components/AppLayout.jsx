import React from 'react';
import { NavLink, Outlet } from 'react-router-dom';

const NAV_ITEMS = [
  { to: '/catalog', label: 'Каталог', icon: '📚', description: 'Поиск по фонду' },
];

const QUICK_LINKS = [
  { href: '/catalog', label: 'Печатный каталог' },
  { href: '/shortlist', label: 'Подборка литературы' },
  { href: '/resources', label: 'Электронные ресурсы' },
];

export function AppLayout() {
  return (
    <div className="app-shell">
      <aside className="app-sidebar">
        <div className="sidebar-header">
          <a href="/" className="sidebar-brand" aria-label="Вернуться на сайт библиотеки">
            <span className="brand-icon">📖</span>
            <span className="brand-text">
              Digital Library
              <small>Reader workspace</small>
            </span>
          </a>
          <p className="sidebar-copy">
            Единое пространство для поиска, отбора и быстрого перехода к карточке книги.
          </p>
          <div className="sidebar-status">● Live catalog</div>
        </div>

        <nav className="sidebar-nav" aria-label="Навигация приложения">
          {NAV_ITEMS.map(({ to, label, icon, description }) => (
            <NavLink
              key={to}
              to={to}
              className={({ isActive }) =>
                `nav-link${isActive ? ' nav-link--active' : ''}`
              }
            >
              <span className="nav-icon">{icon}</span>
              <span className="nav-copy">
                <span className="nav-label">{label}</span>
                <span className="nav-description">{description}</span>
              </span>
            </NavLink>
          ))}
        </nav>

        <div className="sidebar-panel">
          <div className="sidebar-panel-title">Быстрые переходы</div>
          <div className="sidebar-shortcuts">
            {QUICK_LINKS.map(({ href, label }) => (
              <a key={href} href={href} className="sidebar-shortcut">
                {label}
              </a>
            ))}
          </div>
        </div>

        <div className="sidebar-footer">
          <a href="/" className="nav-link nav-link--back">
            ← На сайт
          </a>
        </div>
      </aside>

      <main className="app-main">
        <div className="app-topbar">
          <div>
            <div className="app-topbar-kicker">Smart discovery</div>
            <div className="app-topbar-title">Найти нужную литературу быстрее</div>
          </div>
          <div className="app-topbar-actions">
            <a href="/account" className="topbar-chip">Кабинет</a>
            <a href="/resources" className="topbar-chip">Базы данных</a>
          </div>
        </div>
        <Outlet />
      </main>
    </div>
  );
}
