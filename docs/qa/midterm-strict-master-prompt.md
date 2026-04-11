# STRICT MASTER PROMPT — Midterm QA Implementation & Empirical Analysis

Use this prompt when the goal is **full midterm delivery**, not a shallow QA-note refresh.

## Non-negotiable rule
Do **not** mark the work complete unless all of the following are true:
- the repository contains a professional midterm QA package;
- a technical report draft exists with **Abstract, Introduction, Literature Review, Methodology, Results, and Discussion**;
- empirical evidence tables exist for failed tests, flaky analysis, coverage gaps, and unexpected behavior;
- unit + integration + E2E depth is mapped explicitly;
- visuals are referenced in the report, not just generated in a folder;
- CI/CD evidence and quality gates are both shown and critically evaluated;
- any unresolved item is marked `PARTIAL` or `BLOCKER`, never hidden.

## Required execution order
1. audit the repo against the brief;
2. clean wording and rename unprofessional surfaces;
3. re-evaluate risk with real evidence;
4. expand the automation suite where gaps are found;
5. verify locally and, when applicable, remotely;
6. build the article-style report draft;
7. provide a requirement-by-requirement completion matrix;
8. prove TODO closure with evidence.

## Required output structure
1. **Audit Findings**
2. **Changes Made**
3. **New Tests Added**
4. **Evidence Extracted**
5. **Updated Risk Analysis**
6. **Coverage / Flaky / Failure Analysis**
7. **CI/CD and Quality Gates**
8. **Technical Report Draft Status**
9. **Repository Wording Cleanup**
10. **TODO Closure**
11. **Final Midterm Compliance Matrix**
12. **Remaining Blockers or Limitations**

## Honesty rule
- If a row is not complete, mark it `PARTIAL`.
- If something cannot be delivered with current repo truth or evidence, mark it `BLOCKER` and explain why.
- Never say “done” or “midterm-ready” without evidence links, test results, and the completion matrix.
