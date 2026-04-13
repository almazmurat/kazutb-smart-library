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

        /* Stitch-like detail layout overrides */
        .detail-shell {
            display: grid;
            grid-template-columns: 272px minmax(0, 1fr);
            gap: 28px;
            align-items: start;
            max-width: 1120px;
            margin: 0 auto;
        }

        .detail-left {
            display: grid;
            gap: 12px;
        }

        .detail-cover-card {
            background: #f6f7f8;
            border: 1px solid #d8dde3;
            padding: 14px;
        }

        .detail-cover-art {
            height: 332px;
            border: 1px solid #c4ccd8;
            background: linear-gradient(180deg, #082241 0%, #04152b 100%);
            position: relative;
            padding: 18px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        .detail-cover-art::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(130deg, rgba(255,255,255,.1), rgba(255,255,255,0) 46%);
        }

        .detail-cover-year {
            position: relative;
            z-index: 1;
            align-self: flex-start;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #f5f7fb;
            border: 1px solid rgba(226, 234, 247, .36);
            background: rgba(226, 234, 247, .15);
        }

        .detail-cover-title {
            position: relative;
            z-index: 1;
            margin: 0;
            color: #f4da9f;
            font-family: 'Newsreader', Georgia, serif;
            font-size: 24px;
            line-height: 1.04;
            letter-spacing: -.4px;
            max-width: 192px;
        }

        .detail-cover-author {
            position: relative;
            z-index: 1;
            color: rgba(226, 234, 247, .84);
            font-size: 12px;
            margin-top: 10px;
            font-weight: 600;
        }

        .detail-cover-isbn {
            position: relative;
            z-index: 1;
            color: rgba(226, 234, 247, .75);
            font-size: 10px;
            letter-spacing: .09em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .detail-cover-isbn strong {
            display: block;
            margin-top: 3px;
            color: #fff;
            letter-spacing: 0;
            font-size: 14px;
        }

        .detail-left .btn {
            width: 100%;
            border-radius: 0;
            min-height: 42px;
            font-size: 14px;
        }

        .detail-left .btn-primary {
            background: #0b2a52;
            box-shadow: none;
        }

        .detail-left .btn-ghost {
            border: 1px solid #cfd7e2;
            background: #fff;
            color: #0f5370;
            box-shadow: none;
        }

        .storage-card {
            border: 1px solid #d8dde3;
            background: #fff;
            padding: 12px;
        }

        .storage-card h4 {
            margin: 0 0 8px;
            color: #293344;
            font-size: 11px;
            letter-spacing: .12em;
            text-transform: uppercase;
            font-weight: 800;
        }

        .storage-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 7px 0;
            border-bottom: 1px solid #edf1f5;
            font-size: 12px;
        }

        .storage-item:last-child { border-bottom: 0; }

        .storage-pill {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 6px;
            border-radius: 2px;
            white-space: nowrap;
        }

        .storage-pill.available { background: #d6f2ea; color: #0d766e; }
        .storage-pill.unavailable { background: #fce8e8; color: #a73a4a; }

        .detail-main {
            display: grid;
            gap: 16px;
        }

        .crumb-line {
            font-size: 10px;
            letter-spacing: .08em;
            color: #7f8a98;
            text-transform: uppercase;
            font-weight: 700;
        }

        .detail-title {
            margin: 4px 0 0;
            font-family: 'Newsreader', Georgia, serif;
            font-size: clamp(42px, 5vw, 58px);
            line-height: .95;
            color: #0a2247;
            letter-spacing: -.8px;
            max-width: 900px;
        }

        .detail-subline {
            margin: 0;
            font-size: 32px;
            color: #334155;
            font-style: italic;
            line-height: 1.12;
        }

        .detail-subline .edition {
            font-style: normal;
            color: #16717d;
            margin-left: 8px;
            font-size: 20px;
            font-weight: 500;
        }

        .access-banner {
            position: relative;
            overflow: hidden;
            border: 1px solid #0f2d55;
            background: linear-gradient(135deg, #0c2f57 0%, #0a294d 78%);
            color: #fff;
            padding: 16px 18px;
            display: flex;
            justify-content: space-between;
            gap: 14px;
            align-items: center;
        }

        .access-banner::after {
            content: "";
            position: absolute;
            right: -30px;
            top: -20px;
            width: 130px;
            height: 130px;
            border: 1px solid rgba(197, 214, 238, .22);
            border-radius: 28px;
            transform: rotate(45deg);
        }

        .access-banner h4 {
            margin: 0 0 4px;
            font-family: 'Newsreader', Georgia, serif;
            font-size: 34px;
            letter-spacing: -.5px;
            line-height: .95;
        }

        .access-banner p {
            margin: 0;
            color: rgba(230, 236, 243, .92);
            font-size: 12px;
            max-width: 430px;
        }

        .access-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .access-actions .btn {
            min-height: 40px;
            border-radius: 0;
            padding: 0 14px;
            font-size: 11px;
            letter-spacing: .07em;
            text-transform: uppercase;
            font-weight: 800;
        }

        .access-actions .btn-primary {
            background: #ffffff;
            color: #0b2a52;
        }

        .access-actions .btn-secondary {
            background: transparent;
            border: 1px solid rgba(220, 228, 240, .48);
            color: #fff;
            box-shadow: none;
        }

        .dual-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 1fr;
            gap: 24px;
            padding-top: 4px;
        }

        .dual-grid--single {
            grid-template-columns: 1fr;
        }

        .section-head {
            margin: 0 0 12px;
            font-size: 12px;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #334155;
            font-weight: 800;
            border-bottom: 1px solid #d8dde3;
            padding-bottom: 8px;
        }

        .desc-text {
            color: #4b5563;
            line-height: 1.65;
            font-size: 16px;
            margin: 0;
            max-width: 560px;
        }

        .meta-list {
            display: grid;
            gap: 0;
        }

        .meta-line {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 8px 0;
            border-bottom: 1px solid #e7ebf0;
            font-size: 13px;
        }

        .meta-line span:first-child {
            color: #64748b;
            font-size: 10px;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 800;
            flex: 0 0 auto;
        }

        .meta-line span:last-child {
            color: #111827;
            font-weight: 700;
            text-align: right;
        }

        .licensed {
            margin-top: 22px;
        }

        .licensed-items {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .licensed-chip {
            border: 1px solid #d8dde3;
            background: #f8fafc;
            padding: 10px 11px;
            font-size: 11px;
            color: #1f395d;
            font-weight: 700;
        }

        .similar-wrap {
            margin-top: 10px;
        }

        .similar-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .similar-head h3 {
            margin: 0;
            font-family: 'Newsreader', Georgia, serif;
            color: #0a2247;
            font-size: 46px;
            letter-spacing: -.6px;
        }

        .similar-head a {
            color: #0f766e;
            font-size: 13px;
            font-weight: 700;
        }

        .similar-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
        }

        .similar-card {
            border: 1px solid #d8dde3;
            background: #fff;
            overflow: hidden;
        }

        .similar-image {
            height: 158px;
            background: linear-gradient(180deg, #244166, #10233d);
            position: relative;
        }

        .similar-image::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,.15), rgba(255,255,255,0) 45%);
        }

        .similar-card:nth-child(2) .similar-image { background: linear-gradient(180deg, #75522e, #3f2816); }
        .similar-card:nth-child(3) .similar-image { background: linear-gradient(180deg, #5b6774, #313a46); }

        .similar-body {
            padding: 10px;
        }

        .similar-title {
            margin: 0;
            font-size: 34px;
            line-height: .95;
            color: #0a2247;
            font-family: 'Newsreader', Georgia, serif;
            letter-spacing: -.4px;
        }

        .similar-meta {
            margin-top: 5px;
            color: #7b8795;
            font-size: 10px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        /* Catalog card parity for detail/similar sections */
        .catalog-book-card {
            position: relative;
            display: flex;
            flex-direction: column;
            background: linear-gradient(180deg, rgba(255,255,255,.99), rgba(245,247,248,.96));
            border: 1px solid rgba(195,198,209,.7);
            box-shadow: 0 10px 24px rgba(25,28,29,.03);
            padding: 12px;
            overflow: hidden;
        }

        .catalog-book-stage {
            position: relative;
            height: 300px;
            margin-bottom: 10px;
        }

        .catalog-book-body {
            position: absolute;
            inset: 0;
            z-index: 0;
            border-radius: 2px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(180deg, rgba(255,255,255,.96), rgba(250,250,250,.94));
            border: 1px solid rgba(195,198,209,.3);
        }

        .catalog-book-body-row {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 8px;
            padding-top: 6px;
            border-top: 1px solid rgba(124, 110, 84, .14);
            font-size: 10px;
        }

        .catalog-book-body-row span {
            color: #7c6e54;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .catalog-book-body-row strong {
            color: #403623;
            font-size: 10px;
            text-align: right;
            max-width: 58%;
            word-break: break-word;
        }

        .catalog-book-cover {
            position: absolute;
            inset: 0;
            z-index: 2;
            border-radius: 2px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.06), 0 12px 24px rgba(25,28,29,.1);
            isolation: isolate;
        }

        .catalog-book-cover::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.04), transparent 42%, rgba(0,0,0,.06) 100%);
            pointer-events: none;
        }

        .catalog-cover-top {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .catalog-cover-year {
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,.09);
            color: rgba(255,255,255,.88);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .catalog-cover-code {
            padding: 5px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,.09);
            color: rgba(255,255,255,.88);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .catalog-cover-kicker {
            color: rgba(255,255,255,.64);
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 700;
        }

        .catalog-cover-title {
            margin: 6px 0 0;
            color: #f2d79b;
            font-family: 'Newsreader', Georgia, serif;
            font-size: clamp(30px, 3.1vw, 44px);
            line-height: .92;
            letter-spacing: -.8px;
            max-width: 100%;
            overflow-wrap: anywhere;
            word-break: break-word;
            hyphens: auto;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .catalog-cover-subline {
            margin-top: 6px;
            color: rgba(255,255,255,.84);
            font-size: 12px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .catalog-cover-isbn {
            color: rgba(255,255,255,.68);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .catalog-cover-isbn strong {
            display: block;
            margin-top: 3px;
            color: #fff;
            letter-spacing: .02em;
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
            overflow-wrap: anywhere;
        }

        .catalog-meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 8px;
        }

        .catalog-tag {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 2px;
            font-size: 10px;
            font-weight: 800;
            color: #1f3552;
            background: #eef2f7;
            border: 1px solid rgba(195,198,209,.7);
        }

        .catalog-tag.green {
            color: #0f766e;
            background: #dff5ee;
        }

        .catalog-copy h3 {
            margin: 0 0 8px;
            font-size: clamp(24px, 2.6vw, 36px);
            font-family: 'Newsreader', Georgia, serif;
            line-height: .95;
            color: #0b2a55;
            overflow-wrap: anywhere;
        }

        .catalog-copy p {
            margin: 0;
            color: #5a6673;
            line-height: 1.4;
            font-size: 12px;
        }

        .catalog-tone-navy { background: linear-gradient(180deg, #2d4268 0%, #223758 100%); }
        .catalog-tone-wine { background: linear-gradient(180deg, #8f1f1f 0%, #6d1111 100%); }
        .catalog-tone-forest { background: linear-gradient(180deg, #205f43 0%, #134935 100%); }

        .catalog-book-card--mini .catalog-book-stage {
            height: 200px;
        }

        .catalog-book-card--mini .catalog-cover-title {
            font-size: 20px;
            -webkit-line-clamp: 2;
        }

        .catalog-book-card--mini .catalog-cover-subline {
            font-size: 11px;
        }

        .catalog-book-card--mini .catalog-cover-isbn strong {
            font-size: 16px;
        }

        .catalog-book-card--mini .catalog-copy h3 {
            font-size: 16px;
            line-height: 1.2;
        }

        .desc-text strong {
            color: #0b2a55;
            font-weight: 700;
        }

        .meta-aux {
            margin-top: 12px;
            display: grid;
            gap: 8px;
        }

        .meta-pill {
            display: inline-flex;
            align-items: center;
            padding: 4px 9px;
            border-radius: 999px;
            background: rgba(11,42,85,.06);
            color: #0b2a55;
            font-size: 11px;
            font-weight: 700;
            margin-right: 6px;
            margin-bottom: 6px;
        }

        @media (max-width: 1120px) {
            .detail-shell,
            .dual-grid {
                grid-template-columns: 1fr;
            }

            .similar-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

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
            .access-banner {
                flex-direction: column;
                align-items: flex-start;
            }

            .access-actions {
                width: 100%;
            }

            .access-actions .btn {
                flex: 1 1 auto;
            }

            .similar-grid {
                grid-template-columns: 1fr;
            }

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
                reservedState: '✓ Забронировано ({status})', readyForPickup: 'готово к выдаче', waiting: 'ожидание', reserveSuccess: 'Книга успешно забронирована!', validUntil: 'Действует до {date}.', followStatus: 'Следите за статусом в кабинете.', description: 'Описание', metadata: 'Метаданные', licensedResources: 'Лицензированные ссылки и ресурсы', similarResources: 'Похожие академические ресурсы', browseMore: 'Смотреть ещё', readOnline: 'Читать онлайн', requestAccess: 'Запросить доступ', statusInStorage: 'Статус в фонде', allCollections: 'Основная коллекция',
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
                reservedState: '✓ Брондалған ({status})', readyForPickup: 'беруге дайын', waiting: 'күту', reserveSuccess: 'Кітап сәтті брондалды!', validUntil: '{date} дейін жарамды.', followStatus: 'Күйін кабинеттен бақылаңыз.', description: 'Сипаттама', metadata: 'Метадеректер', licensedResources: 'Лицензиялық сілтемелер мен ресурстар', similarResources: 'Ұқсас академиялық ресурстар', browseMore: 'Тағы көру', readOnline: 'Онлайн оқу', requestAccess: 'Қол жеткізуді сұрау', statusInStorage: 'Қордағы мәртебе', allCollections: 'Негізгі қор',
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
                reservedState: '✓ Reserved ({status})', readyForPickup: 'ready for pickup', waiting: 'waiting', reserveSuccess: 'The book has been reserved successfully!', validUntil: 'Valid until {date}.', followStatus: 'Track the status in your account.', description: 'Description', metadata: 'Metadata', licensedResources: 'Licensed references & resources', similarResources: 'Similar Academic Resources', browseMore: 'Browse More', readOnline: 'Read online', requestAccess: 'Request access', statusInStorage: 'Status in Storage', allCollections: 'Main Collection',
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
            const authors = Array.isArray(book?.authors) ? book.authors : [];
            const locations = Array.isArray(book?.availability?.locations) ? book.availability.locations : [];

            const isAvailable = available > 0;
            const servicePointsText = locations.length > 0
                ? locations.slice(0, 4).map(loc => loc.servicePoint?.name || loc.campus?.name || '').filter(Boolean).join(' · ')
                : BOOK_I18N.locationsUnavailable;

            document.title = `${title} - Digital Library`;

            const authorsText = authors.length > 0
                ? escapeHtml(authors.map((a) => a?.name || a).filter(Boolean).join(', '))
                : escapeHtml(author);

            const storageItemsHtml = locations.length > 0
                ? locations.slice(0, 3).map(loc => {
                    const avail = Number(loc.copies?.available || 0);
                    const totalLoc = Number(loc.copies?.total || 0);
                    return `<div class="storage-item">
                        <span>${escapeHtml(loc.servicePoint?.name || loc.campus?.name || '—')}</span>
                        <span class="storage-pill ${avail > 0 ? 'available' : 'unavailable'}">${avail > 0 ? `${BOOK_I18N.available} (${avail})` : `${BOOK_I18N.unavailable} (${totalLoc})`}</span>
                    </div>`;
                }).join('')
                : `<div class="storage-item"><span>${BOOK_I18N.locationsUnavailable}</span><span class="storage-pill unavailable">0</span></div>`;

            const similarCards = [
                { title: title.split(' ').slice(0, 3).join(' ') || title, meta: escapeHtml(author.substring(0, 26)), tone: 'catalog-tone-navy' },
                { title: escapeHtml(publisher.substring(0, 26)) || 'KazTBU Archives', meta: 'UNIVERSITY ARCHIVES', tone: 'catalog-tone-wine' },
                { title: `${escapeHtml(language)} · ${escapeHtml(year)}`, meta: escapeHtml(isbn.substring(0, 18)), tone: 'catalog-tone-forest' }
            ];

            content.innerHTML = `
                <section class="detail-shell">
                    <aside class="detail-left">
                        <article class="catalog-book-card">
                            <div class="catalog-book-stage">
                                <div class="catalog-book-body">
                                    <div style="display:grid; gap:8px;">
                                        <div class="catalog-book-body-row"><span>${BOOK_I18N.publisher}</span><strong>${escapeHtml(publisher.substring(0, 22))}</strong></div>
                                        <div class="catalog-book-body-row"><span>${BOOK_I18N.language}</span><strong>${escapeHtml(language)}</strong></div>
                                        <div class="catalog-book-body-row"><span>${BOOK_I18N.total}</span><strong>${total}</strong></div>
                                    </div>
                                </div>
                                <div class="catalog-book-cover catalog-tone-navy">
                                    <div class="catalog-cover-top">
                                        <span class="catalog-cover-year">${escapeHtml(year)}</span>
                                        <span class="catalog-cover-code">УДК: ${escapeHtml(book?.udc?.raw || '—')}</span>
                                    </div>
                                    <div>
                                        <div class="catalog-cover-kicker">${escapeHtml(publisher.substring(0, 20))}</div>
                                        <h2 class="catalog-cover-title">${escapeHtml(title.substring(0, 28))}</h2>
                                        <div class="catalog-cover-subline">${escapeHtml(author.substring(0, 26))}</div>
                                    </div>
                                    <div class="catalog-cover-isbn">ISBN<strong>${escapeHtml(isbn.substring(0, 18))}</strong></div>
                                </div>
                            </div>
                            <div class="catalog-meta-row">
                                <span class="catalog-tag">${escapeHtml(year)}</span>
                                <span class="catalog-tag ${isAvailable ? 'green' : ''}">${isAvailable ? `${available} ${BOOK_I18N.availableShort}` : BOOK_I18N.unavailable}</span>
                            </div>
                            <div class="catalog-copy">
                                <h3>${escapeHtml(title)}</h3>
                                <p>${escapeHtml(publisher)}</p>
                            </div>
                        </article>

                        @if(session('library.user'))
                        <button class="btn btn-primary" id="reserve-btn" onclick="handleReserve()" disabled>${BOOK_I18N.reserve}</button>
                        @else
                        <a href="{{ $lang === 'ru' ? '/login' : '/login?lang=' . $lang }}" class="btn btn-primary" style="text-align:center;">${BOOK_I18N.signInToReserve}</a>
                        @endif
                        <button class="btn btn-ghost" id="book-shortlist-btn" onclick="toggleBookShortlist()">${BOOK_I18N.shortlistAdd}</button>

                        <div class="storage-card">
                            <h4>${BOOK_I18N.statusInStorage}</h4>
                            ${storageItemsHtml}
                        </div>
                        <div id="reserve-feedback" style="display:none; margin-top:4px; padding:12px 14px; border-radius:2px; font-size:13px;"></div>
                    </aside>

                    <div class="detail-main">
                        <div class="crumb-line">${BOOK_I18N.catalog} › ENGINEERING › KAZUTB SPECIALCOLLECTION</div>
                        <h1 class="detail-title">${escapeHtml(title)}</h1>
                        <p class="detail-subline">by ${escapeHtml(author)} <span class="edition">${escapeHtml(year)} Edition</span></p>

                        <div class="access-banner">
                            <div>
                                <h4>Digital Library Materials</h4>
                                <p>${isAvailable ? BOOK_I18N.copyAvailableBody.replace('{available}', available).replace('{total}', total) : BOOK_I18N.allCheckedOutBody.replace('{total}', total)}</p>
                            </div>
                            <div class="access-actions" id="digital-materials-slot">
                                <button class="btn btn-primary" type="button">${BOOK_I18N.readOnline}</button>
                                <button class="btn btn-secondary" type="button">${BOOK_I18N.requestAccess}</button>
                            </div>
                        </div>

                        <div class="dual-grid dual-grid--single">
                            <div>
                                <h3 class="section-head">${BOOK_I18N.metadata}</h3>
                                <div class="meta-list">
                                    <div class="meta-line"><span>ISBN-13</span><span>${escapeHtml(isbn)}</span></div>
                                    <div class="meta-line"><span>UDC</span><span>${escapeHtml(book?.udc?.raw || '—')}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.publicationYear}</span><span>${escapeHtml(year)}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.language}</span><span>${escapeHtml(language)}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.publisher}</span><span>${escapeHtml(publisher)}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.author}</span><span>${authorsText}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.totalCopies}</span><span>${total}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.availableNowLabel}</span><span>${available}</span></div>
                                    <div class="meta-line"><span>${BOOK_I18N.availabilityByPoint}</span><span>${escapeHtml(servicePointsText)}</span></div>
                                    <div class="meta-line"><span>ID</span><span>${escapeHtml(String(book?.id || '—'))}</span></div>
                                </div>
                            </div>
                        </div>

                        <div class="similar-wrap">
                            <div class="similar-head">
                                <h3>${BOOK_I18N.similarResources}</h3>
                                <a href="${withLang('/catalog')}">${BOOK_I18N.browseMore} →</a>
                            </div>
                            <div class="similar-grid">
                                ${similarCards.map((item) => `
                                    <article class="catalog-book-card catalog-book-card--mini">
                                        <div class="catalog-book-stage">
                                            <div class="catalog-book-cover ${item.tone}">
                                                <div class="catalog-cover-top">
                                                    <span class="catalog-cover-year">${escapeHtml(year)}</span>
                                                </div>
                                                <div>
                                                    <h4 class="catalog-cover-title">${item.title}</h4>
                                                    <div class="catalog-cover-subline">${item.meta}</div>
                                                </div>
                                                <div class="catalog-cover-isbn">ISBN<strong>${escapeHtml(isbn.substring(0, 12))}</strong></div>
                                            </div>
                                        </div>
                                        <div class="catalog-copy">
                                            <h3>${item.title}</h3>
                                            <p>${item.meta}</p>
                                        </div>
                                    </article>
                                `).join('')}
                            </div>
                        </div>
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

                const readable = materials.find((m) => m.canAccess);
                const restricted = materials.find((m) => !m.canAccess);

                slot.innerHTML = `
                    ${readable
                        ? `<a href="${escapeHtml(readable.viewerUrl)}" class="btn btn-primary">${BOOK_I18N.readOnline}</a>`
                        : `<button class="btn btn-primary" type="button" disabled>${BOOK_I18N.readOnline}</button>`}
                    ${restricted
                        ? `<button class="btn btn-secondary" type="button" title="${escapeHtml(restricted.accessDeniedReason || '')}">${BOOK_I18N.requestAccess}</button>`
                        : `<button class="btn btn-secondary" type="button">${BOOK_I18N.requestAccess}</button>`}
                `;
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
