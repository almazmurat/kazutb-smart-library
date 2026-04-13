import argparse
import glob
import json
import os
import re
from collections import Counter
from datetime import datetime
from pathlib import Path


CATEGORY_RULES = {
    "01-project-truth": {
        "keywords": ["kaztbu", "project truth", "hub", "identity", "terminology", "naming"],
        "link": "[[01-project-truth]]",
        "tag": "project-truth",
    },
    "02-architecture": {
        "keywords": ["architecture", "laravel", "docker", "shell", "frontend", "backend", "deploy"],
        "link": "[[02-architecture]]",
        "tag": "architecture",
    },
    "03-domain": {
        "keywords": ["catalog", "book", "reader", "domain", "discover", "workflow"],
        "link": "[[03-domain]]",
        "tag": "domain",
    },
    "04-crm-auth-integration": {
        "keywords": ["crm", "auth", "ldap", "integration", "token", "session", "api/login"],
        "link": "[[04-crm-auth-integration]]",
        "tag": "crm-auth",
    },
    "05-data-quality-stewardship": {
        "keywords": ["database", "postgres", "schema", "migration", "backup", "quality"],
        "link": "[[05-data-quality-stewardship]]",
        "tag": "data-quality",
    },
    "06-decisions": {
        "keywords": ["decision", "trade-off", "chosen", "rule", "policy", "fix"],
        "link": "[[06-decisions]]",
        "tag": "decisions",
    },
    "12-reference": {
        "keywords": ["reference", "tutorial", "prompt", "checklist", "how to", "guide"],
        "link": "[[12-reference]]",
        "tag": "reference",
    },
}

DECISION_HINTS = (
    "problem",
    "decision",
    "fix",
    "because",
    "therefore",
    "trade",
    "risk",
    "hurdle",
    "schema",
    "auth",
    "integration",
)


def extract_text_from_response_part(part):
    if isinstance(part, dict):
        val = part.get("value", "")
        if isinstance(val, list):
            return "\n".join(str(item) for item in val)
        return str(val)
    if isinstance(part, str):
        return part
    return ""


def convert_json_to_md(filename):
    path = Path(filename)
    if not path.exists():
        return None

    try:
        payload = json.loads(path.read_text(encoding="utf-8"))
    except Exception as exc:
        print(f"ERROR reading {filename}: {exc}")
        return None

    md_path = path.with_suffix(".md")
    lines = [
        "---",
        "type: ai-chat",
        "project: [[Digital Library]]",
        "tags: #ai-memory #smart-node",
        "---",
        "",
        f"# Chat Session: {path.stem}",
        "",
    ]

    for req in payload.get("requests", []):
        prompt = req.get("message", {}).get("text", "No prompt")
        lines.append("### User")
        lines.append(str(prompt))
        lines.append("")
        lines.append("### Copilot")

        for part in req.get("response", []):
            lines.append(extract_text_from_response_part(part))

        lines.append("")
        lines.append("---")
        lines.append("")

    md_path.write_text("\n".join(lines).strip() + "\n", encoding="utf-8")
    return str(md_path)


def normalize_whitespace(text):
    return re.sub(r"\s+", " ", text).strip()


def infer_categories_and_tags(text):
    lowered = text.lower()
    scores = []
    tags = []

    for category, spec in CATEGORY_RULES.items():
        score = sum(1 for kw in spec["keywords"] if kw in lowered)
        if score > 0:
            scores.append((score, category))
            tags.append(spec["tag"])

    scores.sort(reverse=True)
    links = [CATEGORY_RULES[cat]["link"] for _, cat in scores[:5]]

    if not links:
        links = ["[[01-project-truth]]"]

    tags = list(dict.fromkeys(tags))
    if "chat-memory" not in tags:
        tags.insert(0, "chat-memory")

    return links, tags[:6]


def extract_durable_items(text, max_items=5):
    lines = [normalize_whitespace(line) for line in text.splitlines()]
    lines = [line for line in lines if len(line) >= 40]

    selected = []
    for line in lines:
        lowered = line.lower()
        if any(hint in lowered for hint in DECISION_HINTS):
            if line not in selected:
                selected.append(line)
        if len(selected) >= max_items:
            break

    if not selected:
        selected = lines[:max_items]

    return selected[:max_items]


def build_fragment(chat_md_path, out_dir):
    chat_name = Path(chat_md_path).name
    content = Path(chat_md_path).read_text(encoding="utf-8", errors="ignore")

    links, tags = infer_categories_and_tags(content)
    durable_items = extract_durable_items(content)
    status = "Verified" if "pass" in content.lower() or "verified" in content.lower() else "In progress"
    now = datetime.now().strftime("%Y-%m-%d")

    inferred_types = []
    for item in durable_items:
        low = item.lower()
        if "decision" in low or "chosen" in low or "policy" in low:
            inferred_types.append("Decision")
        elif "fix" in low or "bug" in low or "error" in low:
            inferred_types.append("Bug Fix")
        elif "integration" in low or "auth" in low or "api" in low:
            inferred_types.append("Integration Contract")
        elif "pattern" in low or "workflow" in low or "template" in low:
            inferred_types.append("Pattern")
        else:
            inferred_types.append("Reference")

    fragment_lines = [
        "---",
        "type: memory-fragment",
        "project: [[Digital Library]]",
        f"source_chat: [[{chat_name}]]",
        f"date: {now}",
        "tags:",
    ]
    fragment_lines.extend([f"  - {tag}" for tag in tags])
    fragment_lines.append("links:")
    fragment_lines.extend([f"  - \"{link}\"" for link in links])
    fragment_lines.append("---")
    fragment_lines.append("")
    fragment_lines.append("# Memory Fragment")
    fragment_lines.append("")
    fragment_lines.append("## Session")
    fragment_lines.append(f"- Date: {now}")
    fragment_lines.append(f"- Source chat: [[{chat_name}]]")
    fragment_lines.append(f"- Main topic: {Path(chat_md_path).stem}")
    fragment_lines.append(f"- Status: {status}")
    fragment_lines.append("")
    fragment_lines.append("## Durable Knowledge Extracted")

    for idx, item in enumerate(durable_items, start=1):
        item_type = inferred_types[idx - 1]
        target = links[(idx - 1) % len(links)]
        fragment_lines.append(f"{idx}. Title: Extract {idx}")
        fragment_lines.append(f"Type: {item_type}")
        fragment_lines.append(f"Problem: {item[:180]}")
        fragment_lines.append("Decision Logic: Captured from conversation evidence.")
        fragment_lines.append("Result: Promoted to durable memory fragment.")
        fragment_lines.append(f"Suggested vault target: {target}")
        fragment_lines.append("")

    fragment_lines.append("## Naming Or Terminology To Remember")
    if "казтбу" in content.lower():
        fragment_lines.append("- Use КазТБУ as the default institutional naming in project context.")
    else:
        fragment_lines.append("- No new terminology rule extracted in this session.")
    fragment_lines.append("")

    fragment_lines.append("## Promotion Recommendations")
    fragment_lines.append("- Promote only repeated patterns and decisions to permanent vault notes.")
    fragment_lines.append("- Keep raw chat as evidence link, not as primary knowledge store.")
    fragment_lines.append("")

    out_path = Path(out_dir) / f"{Path(chat_md_path).stem}-fragment.md"
    out_path.parent.mkdir(parents=True, exist_ok=True)
    out_path.write_text("\n".join(fragment_lines).strip() + "\n", encoding="utf-8")
    return out_path


def build_min_hub(chat_files, fragment_paths, out_dir):
    link_counter = Counter()
    for fp in fragment_paths:
        text = Path(fp).read_text(encoding="utf-8", errors="ignore")
        for match in re.findall(r'\[\[(.*?)\]\]', text):
            if match in CATEGORY_RULES:
                link_counter[match] += 1

    lines = [
        "# KazTBU Digital Library - Central Hub (Dynamic)",
        "",
        "This hub is generated automatically from chat memory fragments.",
        "It keeps only active knowledge paths, not full transcript summaries.",
        "",
        "## Active Architecture Links",
    ]

    if link_counter:
        for category, count in link_counter.most_common(6):
            lines.append(f"- {CATEGORY_RULES[category]['link']} - evidence: {count}")
    else:
        lines.append("- [[01-project-truth]] - evidence: 0")

    lines.append("")
    lines.append("## Source Chats")
    for chat in sorted(chat_files):
        lines.append(f"- [[{Path(chat).name}]]")

    lines.append("")
    lines.append("## Generated Fragments")
    for frag in sorted(fragment_paths):
        lines.append(f"- [[{Path(frag).name}]]")

    hub_path = Path(out_dir) / "CENTRAL_HUB_MIN.md"
    hub_path.write_text("\n".join(lines).strip() + "\n", encoding="utf-8")
    return hub_path


def copy_latest_fragment(fragment_paths, out_dir):
    if not fragment_paths:
        return None
    latest = max(fragment_paths, key=lambda p: Path(p).stat().st_mtime)
    latest_text = Path(latest).read_text(encoding="utf-8", errors="ignore")
    latest_path = Path(out_dir) / "LATEST_FRAGMENT.md"
    latest_path.write_text(latest_text, encoding="utf-8")
    return latest_path


def main():
    parser = argparse.ArgumentParser(description="Obsidian memory sync and fragment generation")
    parser.add_argument("--fragments-only", action="store_true", help="Skip JSON->MD conversion and generate fragments only")
    parser.add_argument(
        "--out-dir",
        default="artifacts/obsidian/memory-fragments",
        help="Output directory for generated memory artifacts",
    )
    args = parser.parse_args()

    converted = []
    if not args.fragments_only:
        files_to_check = ["chat.json"] + [f"chat{i}.json" for i in range(1, 20)]
        for filename in files_to_check:
            md_file = convert_json_to_md(filename)
            if md_file:
                converted.append(md_file)
                print(f"CONVERTED: {filename} -> {md_file}")

    chat_md_files = sorted(glob.glob("chat*.md"))
    if not chat_md_files:
        print("No chat*.md files found. Nothing to build.")
        return

    out_dir = Path(args.out_dir)
    out_dir.mkdir(parents=True, exist_ok=True)

    fragment_paths = []
    for chat_file in chat_md_files:
        path = build_fragment(chat_file, out_dir)
        fragment_paths.append(str(path))
        print(f"FRAGMENT: {path}")

    hub_path = build_min_hub(chat_md_files, fragment_paths, out_dir)
    latest = copy_latest_fragment(fragment_paths, out_dir)

    print("\nDONE")
    print(f"Fragments: {len(fragment_paths)}")
    print(f"Hub: {hub_path}")
    if latest:
        print(f"Latest: {latest}")


if __name__ == "__main__":
    main()