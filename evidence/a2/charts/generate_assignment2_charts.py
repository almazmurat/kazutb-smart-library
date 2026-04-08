from pathlib import Path
import matplotlib.pyplot as plt

OUTPUT_DIR = Path(__file__).resolve().parent
OUTPUT_DIR.mkdir(parents=True, exist_ok=True)

plt.rcParams.update({
    "figure.dpi": 150,
    "axes.titlesize": 14,
    "axes.labelsize": 12,
    "xtick.labelsize": 10,
    "ytick.labelsize": 10,
})

# 1) Coverage by module
modules = [
    "Authentication",
    "Internal Circulation",
    "CRM Integration Boundary",
    "Catalog Search",
    "Internal Review / Triage",
]
coverage = [85, 80, 85, 75, 70]

fig, ax = plt.subplots(figsize=(10, 6))
bars = ax.bar(modules, coverage, color="#4C78A8", edgecolor="black")
ax.set_title("Assignment 2 Coverage by Module")
ax.set_xlabel("Module")
ax.set_ylabel("Coverage (%)")
ax.set_ylim(0, 100)
ax.tick_params(axis="x", rotation=18)
for bar, value in zip(bars, coverage):
    ax.text(bar.get_x() + bar.get_width() / 2, value + 1, f"{value}%", ha="center", va="bottom")
fig.tight_layout()
fig.savefig(OUTPUT_DIR / "coverage-by-module.png", bbox_inches="tight")
plt.close(fig)

# 2) Execution time by run
runs = ["Focused A2 run", "Global Pint", "Critical-path", "Full suite"]
times = [6.87, 1.17, 2.07, 9.95]

fig, ax = plt.subplots(figsize=(9, 6))
bars = ax.bar(runs, times, color="#72B7B2", edgecolor="black")
ax.set_title("Assignment 2 Verification Execution Time by Run")
ax.set_xlabel("Run Type")
ax.set_ylabel("Execution Time (seconds)")
ax.tick_params(axis="x", rotation=15)
for bar, value in zip(bars, times):
    ax.text(bar.get_x() + bar.get_width() / 2, value + 0.12, f"{value:.2f}s", ha="center", va="bottom")
fig.tight_layout()
fig.savefig(OUTPUT_DIR / "execution-time-by-run.png", bbox_inches="tight")
plt.close(fig)

# 3) Run status distribution
statuses = ["Pass", "Warn", "Fail"]
counts = [2, 3, 1]
colors = ["#54A24B", "#ECA82C", "#E45756"]

fig, ax = plt.subplots(figsize=(7, 7))
ax.pie(
    counts,
    labels=statuses,
    autopct="%1.0f",
    startangle=90,
    colors=colors,
    wedgeprops={"edgecolor": "black"},
)
ax.set_title("Assignment 2 Run Status Distribution")
fig.tight_layout()
fig.savefig(OUTPUT_DIR / "run-status-distribution.png", bbox_inches="tight")
plt.close(fig)

print(f"Charts saved to: {OUTPUT_DIR}")
