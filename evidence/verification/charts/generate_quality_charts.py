#!/usr/bin/env python3
import json
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DATA = ROOT / 'quality-metrics.json'
OUT = ROOT / 'charts'
OUT.mkdir(parents=True, exist_ok=True)

with DATA.open('r', encoding='utf-8') as fh:
    metrics = json.load(fh)


def esc(text: str) -> str:
    return (text.replace('&', '&amp;')
                .replace('<', '&lt;')
                .replace('>', '&gt;'))


def write_svg(path: Path, title: str, subtitle: str, labels, values, color='#2563eb', value_suffix=''):
    width = 960
    height = 520
    margin_left = 220
    margin_right = 40
    margin_top = 90
    margin_bottom = 70
    chart_width = width - margin_left - margin_right
    bar_gap = 14
    bar_height = max(24, int((height - margin_top - margin_bottom - (len(labels) - 1) * bar_gap) / max(len(labels), 1)))
    max_value = max(values) if values else 1

    parts = [
        f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="{height}" viewBox="0 0 {width} {height}">',
        '<rect width="100%" height="100%" fill="#ffffff"/>',
        f'<text x="32" y="40" font-family="Arial, Helvetica, sans-serif" font-size="26" font-weight="700" fill="#111827">{esc(title)}</text>',
        f'<text x="32" y="66" font-family="Arial, Helvetica, sans-serif" font-size="14" fill="#4b5563">{esc(subtitle)}</text>',
        f'<line x1="{margin_left}" y1="{height - margin_bottom}" x2="{width - margin_right}" y2="{height - margin_bottom}" stroke="#d1d5db" stroke-width="1"/>'
    ]

    for step in range(0, 6):
        x = margin_left + chart_width * (step / 5)
        value = max_value * (step / 5)
        parts.append(f'<line x1="{x:.1f}" y1="{margin_top}" x2="{x:.1f}" y2="{height - margin_bottom}" stroke="#f3f4f6" stroke-width="1"/>')
        parts.append(f'<text x="{x:.1f}" y="{height - margin_bottom + 22}" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="12" fill="#6b7280">{value:.0f}{esc(value_suffix)}</text>')

    for i, (label, value) in enumerate(zip(labels, values)):
        y = margin_top + i * (bar_height + bar_gap)
        bar_width = 0 if max_value == 0 else chart_width * (value / max_value)
        parts.append(f'<text x="{margin_left - 12}" y="{y + bar_height / 2 + 4:.1f}" text-anchor="end" font-family="Arial, Helvetica, sans-serif" font-size="13" fill="#111827">{esc(label)}</text>')
        parts.append(f'<rect x="{margin_left}" y="{y}" width="{bar_width:.1f}" height="{bar_height}" rx="6" fill="{color}" opacity="0.9"/>')
        parts.append(f'<text x="{margin_left + bar_width + 8:.1f}" y="{y + bar_height / 2 + 4:.1f}" font-family="Arial, Helvetica, sans-serif" font-size="13" fill="#111827">{value}{esc(value_suffix)}</text>')

    parts.append('</svg>')
    path.write_text('\n'.join(parts), encoding='utf-8')

risk = metrics['risk_automation']
write_svg(
    OUT / 'coverage-by-module.svg',
    'Critical-path coverage by module',
    f"Verification bundle {metrics['verification_run_id']} — counts derived from real repo tests",
    [item['area'] for item in risk],
    [item['checks'] for item in risk],
    color='#0f766e'
)

write_svg(
    OUT / 'execution-time-by-run.svg',
    'Execution time by verification step',
    f"Backend + browser timings from {metrics['verification_run_id']}",
    ['composer qa:ci', 'npm run test:e2e', 'vite build'],
    [metrics['commands']['composer qa:ci']['duration_seconds'], metrics['commands']['npm run test:e2e']['duration_seconds'], metrics['commands']['composer qa:ci']['frontend_build_seconds']],
    color='#7c3aed',
    value_suffix='s'
)

write_svg(
    OUT / 'run-status-distribution.svg',
    'Verification status distribution',
    'Passed checks and resolved blocker summary for the current defended scope',
    ['Backend checks passed', 'Browser checks passed', 'Open blockers'],
    [metrics['commands']['composer qa:ci']['tests'], metrics['commands']['npm run test:e2e']['tests'], 0],
    color='#dc2626'
)
