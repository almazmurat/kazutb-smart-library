import React from 'react';
import { Link } from 'react-router-dom';

export function NotFoundPage() {
  return (
    <div className="page-notfound">
      <div className="notfound-card">
        <div className="notfound-code">404</div>
        <h1>Страница не найдена</h1>
        <p>Запрошенная страница не существует в приложении.</p>
        <Link to="/catalog" className="notfound-link">
          ← Перейти в каталог
        </Link>
      </div>
    </div>
  );
}
