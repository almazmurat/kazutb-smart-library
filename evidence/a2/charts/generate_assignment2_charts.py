from __future__ import annotations

import json
from html import escape
from pathlib import Path

OUTPUT_DIR = Path(__file__).resolve().parent
METRICS_FILE = OUTPUT_DIR.parent / "assignment2-metrics.json"
OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

with METRICS_FILE.open("r", encoding="utf-8") as fh:
    metrics = json.load(fh)


def write_bar_chart(filename: str, title: str, labels: list[str], values: list[float], value_suffix: str = "") -> None:
    width = 1000
    height = 110 + len(labels) * 88
    margin_left = 260
    margin_right = 80
    bar_height = 36
    max_value = max(values) if values else 1
    usable_width = width - margin_left - margin_right

    rows: list[str] = [
        f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="{height}" viewBox="0 0 {width} {height}">',
        '<rect width="100%" height="100%" fill="#ffffff"/>',
        f'<text x="40" y="42" font-size="28" font-family="Arial, sans-serif" font-weight="bold">{escape(title)}</text>',
    ]

    for index, (label, value) in enumerate(zip(labels, values)):
        y = 90 + index * 88
        bar_width = 0 if max_value == 0 else int((value / max_value) * usable_width)
        rows.append(f'<text x="40" y="{y + 24}" font-size="18" font-family="Arial, sans-serif">{escape(label)}</text>')
        rows.append(f'<rect x="{margin_left}" y="{y}" width="{usable_width}" height="{bar_height}" fill="#eef2ff" rx="6"/>')
        rows.append(f'<rect x="{margin_left}" y="{y}" width="{bar_width}" height="{bar_height}" fill="#4C78A8" rx="6"/>')
        rows.append(f'<text x="{margin_left + bar_width + 10}" y="{y + 24}" font-size="18" font-family="Arial, sans-serif">{value:g}{escape(value_suffix)}</text>')

    rows.append('</svg>')
    (OUTPUT_DIR / filename).write_text('\n'.join(rows), encoding='utf-8')


risk_labels = [entry["module"] for entry in metrics["risk_module_checks"]]
risk_values = [entry["checks"] for entry in metrics["risk_module_checks"]]
write_bar_chart(
    "coverage-by-module.svg",
    "Assignment 2 Automated Checks by Risk Module",
    risk_labels,
    risk_values,
)

execution_labels = ["PHPUnit critical path", "Vite build", "Playwright smoke"]
execution_values = [
    metrics["local_quality_gate"]["phpunit_duration_seconds"],
    metrics["local_quality_gate"]["vite_build_duration_seconds"],
    metrics["playwright_smoke"]["duration_seconds"],
]
write_bar_chart(
    "execution-time-by-run.svg",
    "Assignment 2 Verified Execution Time by Step",
    execution_labels,
    execution_values,
    value_suffix="s",
)

severity_counts: dict[str, int] = {}
for defect in metrics["audit_defects_resolved"]:
    severity = defect["severity"].capitalize()
    severity_counts[severity] = severity_counts.get(severity, 0) + 1

write_bar_chart(
    "run-status-distribution.svg",
    "Resolved Audit Defects by Severity",
    list(severity_counts.keys()),
    list(severity_counts.values()),
)

print(f"Charts saved to: {OUTPUT_DIR}")
