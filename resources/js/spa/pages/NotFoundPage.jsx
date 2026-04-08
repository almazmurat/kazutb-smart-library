import React from 'react';
import { Link } from 'react-router-dom';

export function NotFoundPage() {
  return (
    <div className="page-notfound">
      <div className="notfound-card">
        <div className="notfound-code">404</div>
        <h1>Страница не найдена</h1>
        <p>Маршрут отсутствует в текущем reader workspace. Вернитесь к каталогу или на основной сайт.</p>
        <div className="notfound-actions">
          <Link to="/catalog" className="notfound-link">
            ← Перейти в каталог
          </Link>
          <a href="/" className="notfound-link notfound-link--ghost">
            На главную
          </a>
        </div>
      </div>
    </div>
  );
}
