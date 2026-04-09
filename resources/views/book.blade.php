@php
    $lang = app()->getLocale();
    $bookPageTitle = [
        'ru' => 'Библиографическая запись — Digital Library',
        'kk' => 'Библиографиялық жазба — Digital Library',
        'en' => 'Bibliographic record — Digital Library',
    ][$lang] ?? 'Библиографическая запись — Digital Library';
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $bookPageTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Newsreader:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/shell.css">
    <style>
        :root {
            --bg: #f8f9fa;
            --surface: #ffffff;
            --surface-soft: #f3f4f5;
            --border: rgba(195, 198, 209, .55);
            --text: #191c1d;
            --muted: #43474f;
            --blue: #001e40;
            --cyan: #14696d;
            --violet: #453000;
            --pink: #2a1c00;
            --gold: #e9c176;
            --success: #14696d;
            --warning: #5d4201;
            --shadow: 0 12px 32px rgba(25, 28, 29, .04);
            --shadow-soft: 0 6px 16px rgba(25, 28, 29, .03);
            --radius-xl: 8px;
            --radius-lg: 6px;
            --radius-md: 4px;
            --container: 1280px;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Manrope', system-ui, sans-serif;
            color: var(--text);
            background: #f8f9fa;
        }

        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }

        .container {
            width: min(100% - 32px, var(--container));
            margin: 0 auto;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(255,255,255,.82);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid var(--border);
        }

        .nav {
            min-height: 84px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 900;
            letter-spacing: -.3px;
            cursor: pointer;
        }

        .brand small {
            display: block;
            color: var(--muted);
            margin-top: 3px;
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
            font-weight: 600;
            color: #334155;
        }

        .nav-links a:hover { color: var(--blue); }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            border: 0;
            cursor: pointer;
            font: inherit;
            border-radius: var(--radius-lg);
            padding: 14px 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: transform .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
            font-weight: 700;
        }

        .btn:hover { transform: translate3d(0, -1px, 0); }

        .btn-primary {
            color: white;
            background: linear-gradient(135deg, var(--blue), #003366);
            box-shadow: var(--shadow-soft);
        }

        .btn-secondary {
            color: white;
            background: linear-gradient(135deg, var(--cyan), #1b6d71);
            box-shadow: var(--shadow-soft);
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            box-shadow: none;
        }

        .page {
            padding: 34px 0 70px;
        }

        .breadcrumbs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 22px;
        }

        .breadcrumbs span:last-child {
            color: var(--text);
            font-weight: 600;
        }

        .layout {
            display: grid;
            grid-template-columns: .88fr 1.12fr;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: rgba(255,255,255,.98);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-soft);
            border-radius: var(--radius-xl);
            position: relative;
            overflow: hidden;
            transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .card:hover {
            transform: translate3d(0, -2px, 0);
            box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
            border-color: rgba(0, 30, 64, .12);
        }

        .book-panel {
            padding: 26px;
            position: sticky;
            top: var(--shell-sticky-offset);
        }

        .book-cover-wrap {
            position: relative;
            border-radius: var(--radius-xl);
            min-height: 580px;
            padding: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at top right, rgba(20,105,109,.10), transparent 24%),
                radial-gradient(circle at bottom left, rgba(0,30,64,.08), transparent 24%),
                linear-gradient(180deg, #f3f4f5 0%, #edeeef 100%);
            overflow: hidden;
            perspective: 1400px;
        }

        .book-cover-wrap::after {
            content: "";
            position: absolute;
            inset: 18px;
            border-radius: calc(var(--radius-xl) - 2px);
            border: 1px solid rgba(255,255,255,.46);
            pointer-events: none;
        }

        .book-mockup {
            width: 310px;
            max-width: 100%;
            height: 450px;
            border-radius: var(--radius-lg);
            padding: 26px 24px 26px 30px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            position: relative;
            background: linear-gradient(180deg, #003366 0%, #001e40 100%);
            box-shadow: 0 24px 44px rgba(25, 28, 29, .12);
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform .32s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .32s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .book-panel:hover .book-mockup {
            transform: translate3d(0, -4px, 0) rotateY(-4deg) rotateX(1deg);
            box-shadow: 0 22px 42px rgba(25, 28, 29, .10);
        }

        .book-mockup::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 14px;
            background: rgba(0,0,0,.18);
        }

        .book-mockup::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,0));
        }

        .cover-top {
            position: absolute;
            top: 28px;
            left: 30px;
            right: 24px;
            z-index: 1;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
        }

        .cover-title {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #f1d08e;
            font-size: 40px;
            line-height: .95;
            letter-spacing: -1.3px;
            max-width: 220px;
        }

        .cover-author {
            position: relative;
            z-index: 1;
            margin-top: 18px;
            color: rgba(255,255,255,.72);
            font-size: 15px;
            font-weight: 500;
        }

        .cover-badge {
            position: absolute;
            right: 22px;
            top: 22px;
            z-index: 1;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,.12);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            backdrop-filter: blur(10px);
        }

        .mini-actions {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 18px;
        }

        .mini-action {
            padding: 14px 12px;
            border-radius: var(--radius-lg);
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
            border: 1px solid var(--border);
            text-align: center;
            color: #334155;
            box-shadow: var(--shadow-soft);
        }

        .mini-action strong {
            display: block;
            font-size: 18px;
            color: var(--blue);
            letter-spacing: -.03em;
        }

        .mini-action span {
            display: block;
            margin-top: 4px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .details-card {
            padding: 28px;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
        }

        .badge {
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .badge-blue {
            background: rgba(0,30,64,.06);
            color: var(--blue);
            border-color: rgba(0,30,64,.10);
        }

        .badge-green {
            background: rgba(20,105,109,.08);
            color: var(--success);
            border-color: rgba(20,105,109,.12);
        }

        .title {
            margin: 0;
            font-size: clamp(34px, 5vw, 56px);
            line-height: .98;
            letter-spacing: -1.8px;
            max-width: 760px;
        }

        .subtitle {
            margin: 14px 0 0;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.8;
            max-width: 840px;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin: 28px 0;
        }

        .meta-item {
            padding: 18px;
            border-radius: var(--radius-xl);
            background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(243,244,245,.94));
            border: 1px solid var(--border);
            transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .meta-item:hover {
            transform: translate3d(0, -2px, 0);
            box-shadow: 0 12px 26px rgba(25, 28, 29, .04);
            border-color: rgba(20,105,109,.18);
        }

        .meta-label {
            display: block;
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 6px;
        }

        .meta-value {
            display: block;
            font-weight: 800;
            font-size: 17px;
            color: var(--text);
        }

        .section-title {
            margin: 0 0 14px;
            font-size: 24px;
            letter-spacing: -.6px;
        }

        .text-block {
            color: var(--muted);
            line-height: 1.9;
            font-size: 16px;
        }

        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 24px 0 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-top: 24px;
        }

        .info-card {
            padding: 22px;
        }

        .info-list {
            display: grid;
            gap: 14px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child { border-bottom: 0; padding-bottom: 0; }

        .info-row span:first-child {
            color: var(--muted);
        }

        .info-row span:last-child {
            font-weight: 700;
            text-align: right;
        }

        .status-box {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border-radius: var(--radius-xl);
            margin-top: 20px;
            background: linear-gradient(135deg, rgba(22,163,74,.08), rgba(6,182,212,.06));
            border: 1px solid rgba(22,163,74,.12);
        }

        .status-box.unavailable {
            background: linear-gradient(135deg, rgba(239,68,68,.08), rgba(236,72,153,.06));
            border-color: rgba(239,68,68,.12);
        }

        .status-box strong {
            display: block;
            margin-bottom: 6px;
            font-size: 18px;
        }

        .status-box p {
            margin: 0;
            color: var(--muted);
            line-height: 1.7;
        }

        .status-pill {
            padding: 10px 14px;
            border-radius: 999px;
            background: #fff;
            color: var(--success);
            font-weight: 800;
            white-space: nowrap;
            border: 1px solid rgba(22,163,74,.12);
        }

        .status-pill.unavailable {
            color: #dc2626;
            border-color: rgba(239,68,68,.12);
        }

        .cards-section {
            margin-top: 22px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .cards-section .book-card:nth-child(2) {
            transform: translate3d(0, 10px, 0);
        }

        .cards-section .book-card:nth-child(2):hover {
            transform: translate3d(0, 4px, 0);
        }

        .book-card {
            padding: 18px;
            border-radius: var(--radius-xl);
            background: #fff;
            border: 1px solid var(--border);
            box-shadow: none;
            transition: transform .28s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .28s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1), background .18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .book-card:hover {
            transform: translate3d(0, -2px, 0);
            box-shadow: 0 16px 34px rgba(25, 28, 29, .05);
            border-color: rgba(0,30,64,.12);
            background: rgba(248,249,250,.98);
        }

        .book-preview {
            height: 220px;
            border-radius: var(--radius-xl);
            padding: 18px;
            display: flex;
            align-items: flex-end;
            background: linear-gradient(180deg, #2d4268 0%, #223758 100%);
            position: relative;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .book-card:nth-child(2) .book-preview {
            background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%);
        }

        .book-card:nth-child(3) .book-preview {
            background: linear-gradient(180deg, #205f43 0%, #134935 100%);
        }

        .book-preview::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 10px;
            background: rgba(0,0,0,.18);
        }

        .book-preview small {
            position: absolute;
            left: 18px;
            top: 18px;
            color: rgba(255,255,255,.58);
            font-size: 11px;
            letter-spacing: .16em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .book-preview h4 {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #f1d08e;
            font-size: 24px;
            line-height: 1;
            letter-spacing: -.8px;
            max-width: 150px;
        }

        .book-card-title {
            margin: 0 0 8px;
            font-size: 20px;
        }

        .book-card p {
            margin: 0 0 16px;
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .book-card .btn {
            width: 100%;
        }

        .locations-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 10px;
        }

        .locations-table th,
        .locations-table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .locations-table th {
            font-size: 12px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 600;
        }

        .locations-table .avail-count {
            font-weight: 700;
            color: var(--success);
        }

        .locations-table .zero-count {
            color: var(--muted);
        }

        .authors-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .author-chip {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--surface-soft);
            border: 1px solid var(--border);
            font-size: 13px;
        }

        .review-notice {
            margin-top: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            background: #fef3c7;
            border: 1px solid rgba(245, 158, 11, .2);
            font-size: 13px;
            color: #92400e;
        }

        .review-notice .reason-badge {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 999px;
            background: #ffedd5;
            border: 1px solid rgba(154, 52, 18, .15);
            font-size: 11px;
            color: #9a3412;
            margin: 0 2px;
        }

        .classification-section {
            margin-top: 16px;
            padding: 16px;
            background: linear-gradient(135deg, rgba(0,30,64,.03), rgba(20,105,109,.05));
            border: 1px solid rgba(0,30,64,.10);
            border-radius: var(--radius-xl);
        }

        .classification-section h4 {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--blue);
            letter-spacing: .02em;
            font-family: 'Newsreader', Georgia, serif;
        }

        .classification-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .classification-chip {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: background-color .2s ease, border-color .2s ease, color .2s ease;
        }

        .classification-chip.specialization {
            background: rgba(42,28,0,.06);
            color: var(--violet);
            border: 1px solid rgba(42,28,0,.12);
        }

        .classification-chip.department {
            background: rgba(0,30,64,.06);
            color: var(--blue);
            border: 1px solid rgba(0,30,64,.12);
        }

        .classification-chip.faculty {
            background: rgba(20,105,109,.08);
            color: var(--cyan);
            border: 1px solid rgba(20,105,109,.14);
        }

        .classification-chip:hover {
            transform: none;
            box-shadow: none;
            background: rgba(243,244,245,.96);
        }

        .loading {
            position: relative;
            text-align: center;
            padding: 2rem 1.5rem;
            font-size: 1.05rem;
            color: var(--muted);
            border-radius: var(--radius-xl);
            border: 1px dashed rgba(195,198,209,.7);
            background: linear-gradient(180deg, rgba(255,255,255,.98), rgba(243,244,245,.94));
            box-shadow: var(--shadow-soft);
            overflow: hidden;
        }

        .loading::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, transparent 0%, rgba(255,255,255,.45) 45%, transparent 100%);
            transform: translateX(-120%);
            animation: loadingSweep 2.8s linear infinite;
            pointer-events: none;
            opacity: .75;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
        @keyframes loadingSweep { to { transform: translateX(120%); } }
        .spinner {
            display: inline-block;
            width: 32px; height: 32px;
            border: 3px solid #e5e7eb;
            border-top-color: var(--blue);
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }

        .error {
            position: relative;
            background: linear-gradient(180deg, rgba(255,248,248,.98), rgba(255,240,240,.95));
            border: 1px solid rgba(186,26,26,.18);
            color: #8a1d1d;
            padding: 1.25rem 1.5rem;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-soft);
            margin: 2rem 0;
            text-align: center;
        }

        .digital-materials-section {
            margin-top: 16px;
        }
        .dm-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            background: rgba(0,30,64,.02);
            border: 1px solid rgba(0,30,64,.10);
            border-radius: var(--radius-xl);
            margin-bottom: 10px;
            transition: transform .22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow .22s cubic-bezier(0.2, 0.8, 0.2, 1), border-color .18s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .dm-card:hover {
            transform: translate3d(0, -1px, 0);
            box-shadow: 0 12px 26px rgba(25, 28, 29, .04);
            border-color: rgba(20,105,109,.16);
        }
        .dm-info {
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 0;
        }
        .dm-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: 20px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--blue), #214c6f);
            color: #fff;
        }
        .dm-label { font-weight: 700; font-size: 15px; margin: 0 0 2px; }
        .dm-meta { font-size: 13px; color: var(--muted, #64748b); }
        .dm-actions { flex-shrink: 0; }
        .dm-locked {
            font-size: 13px;
            color: var(--muted, #64748b);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        @media (max-width: 1120px) {
            .layout,
            .info-grid,
            .cards-section,
            .meta-grid {
                grid-template-columns: 1fr 1fr;
            }

            .layout { grid-template-columns: 1fr; }
            .book-panel { position: static; }
            .meta-grid { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 860px) {
            .nav-links { display: none; }
            .meta-grid,
            .info-grid,
            .cards-section,
            .mini-actions {
                grid-template-columns: 1fr;
            }

            .action-row {
                flex-direction: column;
            }

            .action-row .btn,
            .nav-actions .btn {
                width: 100%;
                min-height: 44px;
            }

            .nav-actions { display: none; }
            .book-cover-wrap { min-height: 360px; }
            .book-mockup {
                width: 220px;
                height: 320px;
            }
            .cover-title { font-size: 28px; }
            .mobile-toggle { display: inline-grid; place-items: center; min-width: 44px; min-height: 44px; }
        }

        @media (max-width: 560px) {
            .container { width: min(100% - 20px, var(--container)); }
            .nav { min-height: 64px; }
            .brand-text { font-size: 13px; }
            .brand-text small { font-size: 10.5px; }
            .title { letter-spacing: -1px; font-size: 24px; }
            .details-card,
            .book-panel,
            .info-card { padding: 18px; }
            .book-cover-wrap { padding: 16px; min-height: 280px; }
            .book-mockup { width: 180px; height: 260px; }
            .cover-title { font-size: 22px; }
            .meta-item { padding: 14px; }
        }
    </style>
</head>
<body class="site-shell">
    @include('partials.navbar', ['activePage' => 'catalog'])

    <main class="page">
        <div class="container">
            <div class="sr-only">{{ ['ru' => 'Просмотр книги', 'kk' => 'Кітапты қарау', 'en' => 'Book view'][$lang] }}</div>
            <div id="loading" class="loading"><div class="spinner"></div><p style="margin:8px 0 0;">{{ ['ru' => 'Загрузка информации о книге...', 'kk' => 'Кітап туралы ақпарат жүктелуде...', 'en' => 'Loading book details...'][$lang] }}</p></div>
            <div id="error" class="error" style="display: none;"></div>
            <div id="content"></div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        const isbn = window.location.pathname.split('/').pop();
        const BOOK_DB_API_ENDPOINT = '/api/v1/book-db/';
        const BOOK_LANG = @json($lang);
        const BOOK_I18N_MAP = {
            ru: {
                notFound: 'Книга не найдена', genericError: 'Ошибка', backToCatalog: 'Вернуться в каталог', untitled: 'Без названия', unknownAuthor: 'Неизвестный автор',
                publisherMissing: 'Издатель не указан', isbnMissing: 'ISBN не указан', yearMissing: 'Год не указан', languageMissing: 'Язык неизвестен',
                unit: 'Подразделение', campus: 'Кампус', servicePoint: 'Пункт выдачи', total: 'Всего', available: 'Доступно', locationsUnavailable: 'Информация о местах хранения недоступна',
                reviewPrefix: '⚠ Данные этого документа проходят проверку:', trainingTracks: '📚 Направления подготовки', showAllBooks: 'Показать все книги', home: 'Главная', catalog: 'Каталог',
                coverTop: 'Каталог', availableNow: '✓ Доступно сейчас', unavailableNow: '✗ Недоступно', invalidIsbn: 'ISBN не валиден', authors: 'Авторы', author: 'Автор', publicationYear: 'Год издания', language: 'Язык',
                availableShort: 'Доступно', copySummary: '{available} из {total}', copyAvailable: 'Экземпляр доступен для выдачи', copyAvailableBody: 'В фонде {available} доступных экземпляров из {total}.',
                allCheckedOut: 'Все экземпляры выданы', allCheckedOutBody: 'Все {total} экземпляров в данный момент выданы.', inStock: 'В наличии', unavailable: 'Недоступно',
                reserve: 'Забронировать книгу', signInToReserve: 'Войдите для бронирования', shortlistAdd: '☆ В подборку', shortlistAdded: '★ В подборке', characteristics: 'Характеристики',
                publisher: 'Издательство', publicationLanguage: 'Язык издания', totalCopies: 'Всего экземпляров', availableNowLabel: 'Доступно сейчас', availabilityByPoint: 'Наличие по пунктам выдачи',
                digitalMaterials: '💻 Электронные материалы', open: 'Открыть', login: 'Войти', checking: '⏳ Проверка...', reservedReady: '✓ Уже забронировано', reserving: '⏳ Бронирование...', noCopies: 'Нет экземпляров', reservationUnavailable: 'Бронирование недоступно',
                reservedState: '✓ Забронировано ({status})', readyForPickup: 'готово к выдаче', waiting: 'ожидание', reserveSuccess: 'Книга успешно забронирована!', validUntil: 'Действует до {date}.', followStatus: 'Следите за статусом в кабинете.',
                reserveFailed: 'Не удалось создать бронирование.', networkError: 'Ошибка сети. Попробуйте ещё раз.'
            },
            kk: {
                notFound: 'Кітап табылмады', genericError: 'Қате', backToCatalog: 'Каталогқа оралу', untitled: 'Атауы жоқ', unknownAuthor: 'Автор белгісіз',
                publisherMissing: 'Баспа көрсетілмеген', isbnMissing: 'ISBN көрсетілмеген', yearMissing: 'Жылы көрсетілмеген', languageMissing: 'Тілі белгісіз',
                unit: 'Бөлім', campus: 'Кампус', servicePoint: 'Берілім нүктесі', total: 'Барлығы', available: 'Қолжетімді', locationsUnavailable: 'Сақталу орындары туралы ақпарат жоқ',
                reviewPrefix: '⚠ Бұл құжаттың деректері тексерілуде:', trainingTracks: '📚 Дайындық бағыттары', showAllBooks: 'Осы бағыттағы барлық кітаптарды көрсету', home: 'Басты бет', catalog: 'Каталог',
                coverTop: 'Каталог', availableNow: '✓ Қазір қолжетімді', unavailableNow: '✗ Қолжетімсіз', invalidIsbn: 'ISBN жарамсыз', authors: 'Авторлар', author: 'Автор', publicationYear: 'Басылым жылы', language: 'Тіл',
                availableShort: 'Қолжетімді', copySummary: '{available} / {total}', copyAvailable: 'Данасы берілуге қолжетімді', copyAvailableBody: 'Қорда {total}-ның {available} данасы қолжетімді.',
                allCheckedOut: 'Барлық даналар берілген', allCheckedOutBody: '{total} дананың барлығы қазір пайдалануда.', inStock: 'Қолда бар', unavailable: 'Қолжетімсіз',
                reserve: 'Кітапты брондау', signInToReserve: 'Брондау үшін кіріңіз', shortlistAdd: '☆ Топтамаға', shortlistAdded: '★ Топтамада', characteristics: 'Сипаттамалар',
                publisher: 'Баспа', publicationLanguage: 'Басылым тілі', totalCopies: 'Жалпы дана', availableNowLabel: 'Қазір қолжетімді', availabilityByPoint: 'Берілім нүктелері бойынша қолжетімділік',
                digitalMaterials: '💻 Электрондық материалдар', open: 'Ашу', login: 'Кіру', checking: '⏳ Тексеру...', reservedReady: '✓ Бұрыннан брондалған', reserving: '⏳ Брондау...', noCopies: 'Дана жоқ', reservationUnavailable: 'Брондау қолжетімсіз',
                reservedState: '✓ Брондалған ({status})', readyForPickup: 'беруге дайын', waiting: 'күту', reserveSuccess: 'Кітап сәтті брондалды!', validUntil: '{date} дейін жарамды.', followStatus: 'Күйін кабинеттен бақылаңыз.',
                reserveFailed: 'Брондауды жасау мүмкін болмады.', networkError: 'Желі қатесі. Қайта көріңіз.'
            },
            en: {
                notFound: 'Book not found', genericError: 'Error', backToCatalog: 'Back to catalog', untitled: 'Untitled', unknownAuthor: 'Unknown author',
                publisherMissing: 'Publisher not specified', isbnMissing: 'ISBN not provided', yearMissing: 'Year not specified', languageMissing: 'Language unknown',
                unit: 'Unit', campus: 'Campus', servicePoint: 'Service point', total: 'Total', available: 'Available', locationsUnavailable: 'Location details are unavailable',
                reviewPrefix: '⚠ This record is currently under review:', trainingTracks: '📚 Academic tracks', showAllBooks: 'Show all books for this track', home: 'Home', catalog: 'Catalog',
                coverTop: 'Catalog', availableNow: '✓ Available now', unavailableNow: '✗ Unavailable', invalidIsbn: 'Invalid ISBN', authors: 'Authors', author: 'Author', publicationYear: 'Publication year', language: 'Language',
                availableShort: 'Available', copySummary: '{available} of {total}', copyAvailable: 'A copy is available for checkout', copyAvailableBody: '{available} of {total} copies are currently available.',
                allCheckedOut: 'All copies are checked out', allCheckedOutBody: 'All {total} copies are currently in use.', inStock: 'In stock', unavailable: 'Unavailable',
                reserve: 'Reserve book', signInToReserve: 'Sign in to reserve', shortlistAdd: '☆ Add to shortlist', shortlistAdded: '★ In shortlist', characteristics: 'Details',
                publisher: 'Publisher', publicationLanguage: 'Publication language', totalCopies: 'Total copies', availableNowLabel: 'Available now', availabilityByPoint: 'Availability by service point',
                digitalMaterials: '💻 Digital materials', open: 'Open', login: 'Sign in', checking: '⏳ Checking...', reservedReady: '✓ Already reserved', reserving: '⏳ Reserving...', noCopies: 'No copies', reservationUnavailable: 'Reservation unavailable',
                reservedState: '✓ Reserved ({status})', readyForPickup: 'ready for pickup', waiting: 'waiting', reserveSuccess: 'The book has been reserved successfully!', validUntil: 'Valid until {date}.', followStatus: 'Track the status in your account.',
                reserveFailed: 'Unable to create the reservation.', networkError: 'Network error. Please try again.'
            }
        };
        const BOOK_I18N = BOOK_I18N_MAP[BOOK_LANG] || BOOK_I18N_MAP.ru;

        function withLang(path) {
            const url = new URL(path, window.location.origin);
            if (BOOK_LANG !== 'ru' && !url.searchParams.has('lang')) {
                url.searchParams.set('lang', BOOK_LANG);
            }
            return `${url.pathname}${url.search}`;
        }

        async function loadBook() {
            const loading = document.getElementById('loading');
            const error = document.getElementById('error');
            const content = document.getElementById('content');

            try {
                const book = await fetchBookWithFallback(isbn);

                if (!book) {
                    throw new Error(BOOK_I18N.notFound);
                }

                renderBook(book);
                loading.style.display = 'none';
            } catch (err) {
                loading.style.display = 'none';
                error.style.display = 'block';
                error.innerHTML = `
                    <strong>${BOOK_I18N.genericError}:</strong> ${err.message}
                    <div style="margin-top: 1rem;">
                        <a href="${withLang('/catalog')}" class="btn btn-ghost">${BOOK_I18N.backToCatalog}</a>
                    </div>
                `;
            }
        }

        async function fetchBookWithFallback(identifier) {
            const encodedIdentifier = encodeURIComponent(identifier);
            const endpoints = [
                `${BOOK_DB_API_ENDPOINT}${encodedIdentifier}`,
            ];

            let lastError = null;

            for (const endpoint of endpoints) {
                try {
                    const response = await fetch(endpoint);

                    if (!response.ok) {
                        if (response.status === 404) {
                            lastError = new Error(BOOK_I18N.notFound);
                            continue;
                        }

                        throw new Error(`API Error: ${response.status}`);
                    }

                    const result = await response.json();
                    if (result?.data) {
                        return result.data;
                    }
                } catch (error) {
                    lastError = error;
                }
            }

            throw lastError || new Error(BOOK_I18N.notFound);
        }

        function normalizeText(value, fallback = '') {
            if (!value) return fallback;
            if (typeof value !== 'string') return fallback;
            return value.trim() || fallback;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderBook(book) {
            const content = document.getElementById('content');
            const title = escapeHtml(normalizeText(book?.title?.display || book?.title?.raw, BOOK_I18N.untitled));
            const author = escapeHtml(normalizeText(book?.primaryAuthor || BOOK_I18N.unknownAuthor));
            const publisher = escapeHtml(normalizeText(book?.publisher?.name || BOOK_I18N.publisherMissing));
            const isbn = escapeHtml(normalizeText(book?.isbn?.raw || BOOK_I18N.isbnMissing));
            const year = escapeHtml(normalizeText(book?.publicationYear || BOOK_I18N.yearMissing));
            const language = escapeHtml(normalizeText(book?.language?.raw || BOOK_I18N.languageMissing));
            const available = book?.copies?.available || 0;
            const total = book?.copies?.total || 0;
            const subtitle = normalizeText(book?.title?.subtitle);
            const authors = Array.isArray(book?.authors) ? book.authors : [];
            const locations = Array.isArray(book?.availability?.locations) ? book.availability.locations : [];
            const needsReview = book?.quality?.needsReview === true;
            const reviewCodes = Array.isArray(book?.quality?.reviewReasonCodes) ? book.quality.reviewReasonCodes : [];
            const classification = Array.isArray(book?.classification) ? book.classification : [];

            const isAvailable = available > 0;

            document.title = `${title} - Digital Library`;

            const authorsHtml = authors.length > 1
                ? `<div class="authors-list">${authors.map(a => `<span class="author-chip">${escapeHtml(a.name || a)}</span>`).join('')}</div>`
                : escapeHtml(author);

            const locationsTableHtml = locations.length
                ? `<table class="locations-table">
                    <thead><tr>
                        <th>${BOOK_I18N.unit}</th>
                        <th>${BOOK_I18N.campus}</th>
                        <th>${BOOK_I18N.servicePoint}</th>
                        <th>${BOOK_I18N.total}</th>
                        <th>${BOOK_I18N.available}</th>
                    </tr></thead>
                    <tbody>${locations.map(loc => {
                        const avail = loc.copies?.available || 0;
                        const tot = loc.copies?.total || 0;
                        return `<tr>
                            <td>${escapeHtml(loc.institutionUnit?.name || '—')}</td>
                            <td>${escapeHtml(loc.campus?.name || '—')}</td>
                            <td>${escapeHtml(loc.servicePoint?.name || '—')}</td>
                            <td>${tot}</td>
                            <td class="${avail > 0 ? 'avail-count' : 'zero-count'}">${avail}</td>
                        </tr>`;
                    }).join('')}</tbody>
                   </table>`
                : `<p style="color:var(--muted);font-size:14px;">${BOOK_I18N.locationsUnavailable}</p>`;

            const reviewHtml = needsReview && reviewCodes.length
                ? `<div class="review-notice">
                    ${BOOK_I18N.reviewPrefix} ${reviewCodes.map(c => `<span class="reason-badge">${escapeHtml(c)}</span>`).join(' ')}
                   </div>`
                : '';

            const classificationHtml = classification.length > 0
                ? `<div class="classification-section">
                    <h4>${BOOK_I18N.trainingTracks}</h4>
                    <div class="classification-chips">
                        ${classification.map(c => {
                            const kind = c.sourceKind || 'department';
                            const url = withLang('/catalog?subject_id=' + encodeURIComponent(c.id) + '&subject_label=' + encodeURIComponent(c.label));
                            return `<a href="${url}" class="classification-chip ${kind}" title="${BOOK_I18N.showAllBooks}: ${escapeHtml(c.label)}">${escapeHtml(c.label)}</a>`;
                        }).join('')}
                    </div>
                   </div>`
                : '';

            content.innerHTML = `
                <div class="breadcrumbs">
                    <span>${BOOK_I18N.home}</span>
                    <span>•</span>
                    <a href="${withLang('/catalog')}">${BOOK_I18N.catalog}</a>
                    <span>•</span>
                    <span>${escapeHtml(title.substring(0, 50))}</span>
                </div>

                <section class="layout">
                    <aside class="card book-panel">
                        <div class="book-cover-wrap">
                            <div class="book-mockup">
                                <div class="cover-badge">${escapeHtml(year)}</div>
                                <div class="cover-top">${BOOK_I18N.coverTop}</div>
                                <h1 class="cover-title">${escapeHtml(title.substring(0, 40))}</h1>
                                <div class="cover-author">${escapeHtml(author.substring(0, 30))}</div>
                            </div>
                        </div>
                        <div class="mini-actions">
                            <div class="mini-action">
                                <strong>${escapeHtml(String(year))}</strong>
                                <span>${BOOK_I18N.publicationYear}</span>
                            </div>
                            <div class="mini-action">
                                <strong>${escapeHtml(language)}</strong>
                                <span>${BOOK_I18N.language}</span>
                            </div>
                            <div class="mini-action">
                                <strong>${isAvailable ? `${available}/${total}` : `0/${total}`}</strong>
                                <span>${BOOK_I18N.availableShort}</span>
                            </div>
                        </div>
                    </aside>

                    <div>
                        <section class="card details-card">
                            <div class="badges">
                                <span class="badge badge-${isAvailable ? 'green' : 'blue'}">${isAvailable ? BOOK_I18N.availableNow : BOOK_I18N.unavailableNow}</span>
                                ${book?.isbn?.isValid === false && isbn !== BOOK_I18N.isbnMissing ? `<span class="badge badge-blue">${BOOK_I18N.invalidIsbn}</span>` : ''}
                            </div>

                            <h2 class="title">${escapeHtml(title)}</h2>
                            ${subtitle ? `<p class="subtitle">${escapeHtml(subtitle)}</p>` : ''}

                            <div class="meta-grid">
                                <div class="meta-item">
                                    <span class="meta-label">${authors.length > 1 ? BOOK_I18N.authors : BOOK_I18N.author}</span>
                                    <span class="meta-value">${authorsHtml}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">${BOOK_I18N.publicationYear}</span>
                                    <span class="meta-value">${escapeHtml(year)}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">${BOOK_I18N.language}</span>
                                    <span class="meta-value">${escapeHtml(language)}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">${BOOK_I18N.availableShort}</span>
                                    <span class="meta-value">${BOOK_I18N.copySummary.replace('{available}', available).replace('{total}', total)}</span>
                                </div>
                            </div>

                            ${reviewHtml}
                            ${classificationHtml}

                            <div id="digital-materials-slot"></div>

                            <div class="status-box ${isAvailable ? '' : 'unavailable'}">
                                <div>
                                    <strong>${isAvailable ? BOOK_I18N.copyAvailable : BOOK_I18N.allCheckedOut}</strong>
                                    <p>${isAvailable ? BOOK_I18N.copyAvailableBody.replace('{available}', available).replace('{total}', total) : BOOK_I18N.allCheckedOutBody.replace('{total}', total)}</p>
                                </div>
                                <div class="status-pill ${isAvailable ? '' : 'unavailable'}">${isAvailable ? BOOK_I18N.inStock : BOOK_I18N.unavailable}</div>
                            </div>

                            <div class="action-row">
                                @if(session('library.user'))
                                <button class="btn btn-primary" id="reserve-btn" onclick="handleReserve()" disabled>${BOOK_I18N.reserve}</button>
                                @else
                                <a href="{{ $lang === 'ru' ? '/login' : '/login?lang=' . $lang }}" class="btn btn-primary" style="text-align:center;">${BOOK_I18N.signInToReserve}</a>
                                @endif
                                <button class="btn btn-ghost" id="book-shortlist-btn" onclick="toggleBookShortlist()" style="border-color:var(--cyan); color:var(--cyan);">${BOOK_I18N.shortlistAdd}</button>
                                <a href="${withLang('/catalog')}" class="btn btn-ghost">${BOOK_I18N.backToCatalog}</a>
                            </div>
                            <div id="reserve-feedback" style="display:none; margin-top:12px; padding:14px 18px; border-radius:8px; font-size:14px;"></div>
                        </section>

                        <section class="info-grid">
                            <div class="card info-card">
                                <h3 class="section-title">${BOOK_I18N.characteristics}</h3>
                                <div class="info-list">
                                    <div class="info-row"><span>ISBN</span><span>${escapeHtml(isbn)}</span></div>
                                    <div class="info-row"><span>${BOOK_I18N.publisher}</span><span>${escapeHtml(publisher)}</span></div>
                                    <div class="info-row"><span>${BOOK_I18N.publicationLanguage}</span><span>${escapeHtml(language)}</span></div>
                                    <div class="info-row"><span>${BOOK_I18N.publicationYear}</span><span>${escapeHtml(year)}</span></div>
                                    <div class="info-row"><span>${BOOK_I18N.totalCopies}</span><span>${total}</span></div>
                                    <div class="info-row"><span>${BOOK_I18N.availableNowLabel}</span><span style="color: ${isAvailable ? 'var(--success)' : '#dc2626'};">${available}</span></div>
                                </div>
                            </div>

                            <div class="card info-card">
                                <h3 class="section-title">${BOOK_I18N.availabilityByPoint}</h3>
                                ${locationsTableHtml}
                            </div>
                        </section>
                    </div>
                </section>
            `;

            loadDigitalMaterials(book.id);
        }

        async function loadDigitalMaterials(documentId) {
            const slot = document.getElementById('digital-materials-slot');
            if (!slot || !documentId) return;

            try {
                const resp = await fetch(`/api/v1/documents/${encodeURIComponent(documentId)}/digital-materials`);
                if (!resp.ok) return;

                const result = await resp.json();
                const materials = result?.data || [];
                if (materials.length === 0) return;

                const fileIcons = { pdf: '📄', epub: '📖', djvu: '📘' };

                slot.innerHTML = `
                    <div class="digital-materials-section">
                        <h4 style="margin:0 0 12px; font-size:16px; font-weight:700;">${BOOK_I18N.digitalMaterials}</h4>
                        ${materials.map(m => {
                            const icon = fileIcons[m.fileType] || '📁';
                            if (m.canAccess) {
                                return `<div class="dm-card">
                                    <div class="dm-info">
                                        <div class="dm-icon">${icon}</div>
                                        <div>
                                            <div class="dm-label">${escapeHtml(m.title)}</div>
                                            <div class="dm-meta">${m.fileType.toUpperCase()} · ${m.fileSize}</div>
                                        </div>
                                    </div>
                                    <div class="dm-actions">
                                        <a href="${escapeHtml(m.viewerUrl)}" class="btn btn-primary" style="padding:8px 18px;font-size:14px;">${BOOK_I18N.open}</a>
                                    </div>
                                </div>`;
                            } else {
                                return `<div class="dm-card" style="opacity:.7;">
                                    <div class="dm-info">
                                        <div class="dm-icon" style="background:#94a3b8;">🔒</div>
                                        <div>
                                            <div class="dm-label">${escapeHtml(m.title)}</div>
                                            <div class="dm-meta">${m.fileType.toUpperCase()} · ${m.fileSize}</div>
                                        </div>
                                    </div>
                                    <div class="dm-locked">${escapeHtml(m.accessDeniedReason)}</div>
                                </div>`;
                            }
                        }).join('')}
                    </div>`;
            } catch (_) {
                // silent — digital materials are supplementary
            }
        }

        @if(session('library.user'))
        document.getElementById('shared-logout-btn')?.addEventListener('click', async () => {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                await fetch('/api/v1/logout', {
                    method: 'POST',
                    headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                });
            } catch (_) {}
            localStorage.removeItem('library.auth.user');
            window.location.href = withLang('/login');
        });
        @endif

        // --- Shortlist integration ---
        const SHORTLIST_API = '/api/v1/shortlist';
        let bookShortlisted = false;
        let currentBookData = null;

        async function checkBookShortlist(identifier) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const res = await fetch(`${SHORTLIST_API}/check`, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ identifiers: [identifier] }),
                });
                if (res.ok) {
                    const json = await res.json();
                    bookShortlisted = !!(json.data && json.data[identifier]);
                    updateShortlistButton();
                }
            } catch (e) { /* silent */ }
        }

        function updateShortlistButton() {
            const btn = document.getElementById('book-shortlist-btn');
            if (!btn) return;
            if (bookShortlisted) {
                btn.innerHTML = BOOK_I18N.shortlistAdded;
                btn.style.background = 'rgba(20,105,109,.08)';
                btn.style.borderColor = 'var(--cyan)';
                btn.style.color = 'var(--cyan)';
            } else {
                btn.innerHTML = BOOK_I18N.shortlistAdd;
                btn.style.background = '';
                btn.style.borderColor = 'var(--cyan)';
                btn.style.color = 'var(--cyan)';
            }
        }

        async function toggleBookShortlist() {
            if (!currentBookData) return;
            const identifier = currentBookData.identifier;
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            if (bookShortlisted) {
                try {
                    const res = await fetch(`${SHORTLIST_API}/${encodeURIComponent(identifier)}`, {
                        method: 'DELETE',
                        headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
                        credentials: 'same-origin',
                    });
                    if (res.ok) {
                        bookShortlisted = false;
                        updateShortlistButton();
                    }
                } catch (e) { console.error(e); }
            } else {
                try {
                    const res = await fetch(SHORTLIST_API, {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(currentBookData),
                    });
                    if (res.ok || res.status === 201 || res.status === 409) {
                        bookShortlisted = true;
                        updateShortlistButton();
                    }
                } catch (e) { console.error(e); }
            }
        }

        // Patch renderBook to capture data and check shortlist
        const _origRenderBook = renderBook;
        renderBook = function(book) {
            _origRenderBook(book);
            const identifier = (book?.isbn?.raw || book?.id || isbn);
            currentBookData = {
                identifier: identifier,
                title: normalizeText(book?.title?.display || book?.title?.raw, BOOK_I18N.untitled),
                author: normalizeText(book?.primaryAuthor),
                publisher: normalizeText(book?.publisher?.name),
                year: normalizeText(book?.publicationYear),
                language: normalizeText(book?.language?.raw),
                isbn: normalizeText(book?.isbn?.raw),
                available: book?.copies?.available || 0,
                total: book?.copies?.total || 0,
            };
            checkBookShortlist(identifier);
            @if(session('library.user'))
            checkExistingReservation(book);
            @endif
        };

        loadBook();

        // --- Reservation integration ---
        @if(session('library.user'))
        let reservationBookId = null;
        let reservationIsbn = null;
        let reservationActive = null;

        function setReserveButtonState(state, label) {
            const btn = document.getElementById('reserve-btn');
            if (!btn) return;
            const states = {
                loading: { disabled: true, opacity: '.6', cursor: 'wait', text: label || BOOK_I18N.checking },
                ready: { disabled: false, opacity: '1', cursor: 'pointer', text: label || BOOK_I18N.reserve },
                reserved: { disabled: true, opacity: '.85', cursor: 'default', text: label || BOOK_I18N.reservedReady },
                submitting: { disabled: true, opacity: '.6', cursor: 'wait', text: label || BOOK_I18N.reserving },
                unavailable: { disabled: true, opacity: '.5', cursor: 'not-allowed', text: label || BOOK_I18N.noCopies },
                no_reservation: { disabled: true, opacity: '.5', cursor: 'not-allowed', text: label || BOOK_I18N.reservationUnavailable },
            };
            const s = states[state] || states.loading;
            btn.disabled = s.disabled;
            btn.style.opacity = s.opacity;
            btn.style.cursor = s.cursor;
            btn.textContent = s.text;
            if (state === 'reserved') {
                btn.style.background = '#065f46';
            } else {
                btn.style.background = '';
            }
        }

        function showReserveFeedback(type, message) {
            const el = document.getElementById('reserve-feedback');
            if (!el) return;
            const colors = {
                success: { bg: '#d1fae5', color: '#065f46', border: '#a7f3d0' },
                error: { bg: '#fee2e2', color: '#991b1b', border: '#fecaca' },
                info: { bg: '#dbeafe', color: '#1e40af', border: '#bfdbfe' },
            };
            const c = colors[type] || colors.info;
            el.style.display = 'block';
            el.style.background = c.bg;
            el.style.color = c.color;
            el.style.border = `1px solid ${c.border}`;
            el.textContent = message;
        }

        async function checkExistingReservation(book) {
            reservationBookId = book?.dbId || null;
            reservationIsbn = book?.isbn?.raw || isbn;
            const btn = document.getElementById('reserve-btn');
            if (!btn) return;

            const checkParam = reservationBookId
                ? `bookId=${encodeURIComponent(reservationBookId)}`
                : (reservationIsbn ? `isbn=${encodeURIComponent(reservationIsbn)}` : null);

            if (!checkParam) {
                setReserveButtonState('no_reservation');
                return;
            }

            setReserveButtonState('loading');
            try {
                const res = await fetch(`/api/v1/account/reservations/check?${checkParam}`, {
                    headers: { Accept: 'application/json' },
                    credentials: 'same-origin',
                });
                if (res.ok) {
                    const json = await res.json();
                    if (json.hasActive) {
                        reservationActive = json.reservation;
                        setReserveButtonState('reserved', BOOK_I18N.reservedState.replace('{status}', json.reservation?.status === 'READY' ? BOOK_I18N.readyForPickup : BOOK_I18N.waiting));
                        return;
                    }
                }
            } catch (e) { /* proceed to ready state */ }
            setReserveButtonState('ready');
        }

        async function handleReserve() {
            if (reservationActive) return;
            setReserveButtonState('submitting');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

            const body = reservationBookId
                ? { bookId: reservationBookId }
                : { isbn: reservationIsbn };

            try {
                const res = await fetch('/api/v1/account/reservations', {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(body),
                });

                const json = await res.json();

                if (res.ok && json.success) {
                    reservationActive = json.reservation;
                    setReserveButtonState('reserved');
                    const expires = json.reservation?.expiresAt
                        ? new Date(json.reservation.expiresAt).toLocaleDateString('ru-RU')
                        : '';
                    showReserveFeedback('success', `${BOOK_I18N.reserveSuccess}${expires ? ` ${BOOK_I18N.validUntil.replace('{date}', expires)}` : ''} ${BOOK_I18N.followStatus}`);
                } else {
                    setReserveButtonState('ready');
                    showReserveFeedback('error', json.message || BOOK_I18N.reserveFailed);
                }
            } catch (e) {
                setReserveButtonState('ready');
                showReserveFeedback('error', BOOK_I18N.networkError);
            }
        }
        @endif
    </script>
</body>
</html>
