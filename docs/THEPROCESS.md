# Sale & Investment Documentation Process

A repeatable methodology for preparing any software project for sale or investment review. This documents how documentation is structured, how costs are calculated, and how the portfolio is presented.

---

## The Philosophy

Every project needs to answer 5 questions for a buyer or investor — regardless of tech stack, market, or business model:

| Question | Document | Audience |
|----------|----------|----------|
| What is this? | **OVERVIEW.md** | Anyone |
| How is it built? | **TECHNOLOGY.md** | Technical buyers, CTOs |
| What does it do? | **PRODUCT.md** | Product people, operators |
| What did it cost? | **COST.md** | Investors, financial buyers |
| Why should I care? | **OPPORTUNITY.md** | Investors, acquirers |

Technical/operational docs still exist — they just move to `Operations/` where they don't clutter the pitch.

---

## Step 1: Scan & Inventory

### What We Do

Scan the entire repo for existing documentation — it's usually scattered:

- `docs/` folder
- `.claude/docs/` (AI-generated during development)
- Root-level markdown files (README, PRODUCT, etc.)
- Other branches (sometimes docs only exist on `main` or a feature branch)
- Config files, comments, and code that contain implicit documentation

### How

```
Check: docs/, .claude/docs/, root *.md files
Check: git branch -a (docs may live on other branches)
Check: git show main:.claude/docs/ (extract if needed)
Result: Full inventory of what documentation already exists
```

### Output

A mental (or written) map of: what content exists, where it lives, and what it covers.

---

## Step 2: Define Target Structure

### The Standard Layout

```
docs/
├── OVERVIEW.md          ← 1-pager: what, where, status
├── TECHNOLOGY.md        ← Stack, architecture (no code snippets)
├── PRODUCT.md           ← Features, pricing, advantages, roadmap
├── COST.md              ← Actual cost vs. agency, git proof
├── OPPORTUNITY.md       ← Revenue projections, ROI, growth levers
├── THEPROCESS.md        ← This document
│
└── Operations/          ← Technical team docs (varies per project)
    ├── ARCHITECTURE.md
    ├── DEPLOYMENT.md
    ├── DEVELOPMENT.md
    ├── CONVENTIONS.md
    └── POST-LAUNCH-CHECKLIST.md
```

### Rules

- **Business docs** (top-level): No code snippets, no terminal commands. Tables, diagrams, comparisons.
- **Operations docs** (subfolder): As technical as needed. These are for the team.
- **Consistency**: Same structure across every project in the portfolio, regardless of stack.

---

## Step 3: Map Content

### Process

For each of the 5 business docs, identify where the source content already exists:

| Target Doc | Typical Sources |
|------------|-----------------|
| OVERVIEW | README.md, PRODUCT.md intro, .env files for environments |
| TECHNOLOGY | ARCHITECTURE.md (trim to high-level), config files |
| PRODUCT | Existing PRODUCT.md, feature lists, landing page copy |
| COST | Generated from git history (see Step 4) |
| OPPORTUNITY | Revenue sections, growth analysis |

### What Gets Combined

- README + environment info → OVERVIEW
- Architecture (trimmed) + stack table → TECHNOLOGY
- Revenue projections + growth levers + investor pitch → OPPORTUNITY

### What Gets Split

- Kitchen-sink docs → PRODUCT (features) + TECHNOLOGY (stack) + OPPORTUNITY (growth)

### What Gets Moved

- All detailed technical docs → `Operations/`
- Audit reports, checklists, setup guides → `Operations/`
- Implementation guides, API docs → `Operations/`

---

## Step 4: Calculate Costs (COST.md)

This is the most data-driven document. Every number comes from verifiable sources.

### Source: Git History

Pull real data — never estimate what you can measure:

```bash
# First and last commit dates
git log --reverse --format="%ai" | head -1    # First commit
git log -1 --format="%ai"                      # Last commit

# Total commits
git rev-list --count HEAD

# Active days (days with at least one commit)
git log --format="%ad" --date=short | sort -u | wc -l

# Lines of code by language
find . -name "*.php" | xargs wc -l
find . -name "*.js" | xargs wc -l
find . -name "*.css" | xargs wc -l

# Total code files
find . -name "*.php" -o -name "*.js" -o -name "*.css" | wc -l
```

### Source: Actual Spend

- AI tools: Subscription cost during build period (~$20-200/month)
- Hosting: Monthly cost if already live
- Domains: Registration cost
- Third-party APIs: Any costs incurred during development

### Agency Comparison

Calculate what a traditional agency would charge for the same output:

| Role | Hourly Rate | How to Estimate Hours |
|------|-------------|----------------------|
| Senior Developer | $125-150/hr | LOC ÷ 50 lines/hr (productive) |
| Frontend Developer | $100-125/hr | Templates/pages × 8-12hr each |
| DevOps | $125-150/hr | CI/CD + hosting setup = 20-40hr |
| Project Management | $100-125/hr | 10-15% of total dev hours |
| QA/Testing | $75-100/hr | 10-15% of total dev hours |
| Discovery/Planning | $150/hr | 40-60hr for most projects |

---

## Step 5: Build Projections (OPPORTUNITY.md)

### Revenue Model

Identify the revenue mechanism and build conservative/moderate/aggressive scenarios:

**For Affiliate platforms:**
- Monthly traffic × CTR × conversion rate × commission = monthly revenue
- Source commission values from affiliate program data

**For Lead Gen platforms:**
- Leads per day × value per lead × 30 days = monthly revenue
- Source lead values from industry data or existing buyer contracts

### ROI Calculation

```
Traditional Build:
  Investment = Agency estimate from COST.md
  Time to launch = Agency timeline
  Time to revenue = Launch + ramp-up (2-4 weeks)
  Break-even = Investment ÷ monthly profit

AI-Assisted Build:
  Investment = Actual cost from COST.md
  Time to launch = Actual timeline from git history
  Break-even = Usually Day 1 (negligible investment)

Value Created:
  Cost savings = Traditional - Actual
  Time savings = Traditional timeline - Actual timeline
  Opportunity cost = Monthly revenue × months saved
```

---

## Step 6: Write the Docs

### Tone & Style Guidelines

| Document | Tone | Length | Key Elements |
|----------|------|--------|--------------|
| OVERVIEW | Factual, concise | 1 page | Status table, what it is, environments |
| TECHNOLOGY | Technical but accessible | 1-2 pages | Stack table, architecture diagram, key decisions |
| PRODUCT | Feature-focused | 2-3 pages | Feature tables, advantages, roadmap |
| COST | Data-driven, verifiable | 2-3 pages | Git data, cost tables, agency comparison |
| OPPORTUNITY | Forward-looking, compelling | 2-3 pages | Revenue scenarios, ROI, growth levers |

### Formatting Rules

- Use tables over paragraphs wherever possible
- ASCII diagrams for architecture flows (no images to maintain)
- No code snippets in business docs
- Bold key numbers and totals
- Include source attribution ("Based on actual git history")

---

## Step 7: Organize & Ship

### Git Operations

```bash
# Stage all docs
git add docs/

# Commit
git commit -m "docs: Complete business + operations documentation"

# Push
git push origin main
```

### Verification

After completion, confirm:
- `ls docs/` shows: OVERVIEW.md, TECHNOLOGY.md, PRODUCT.md, COST.md, OPPORTUNITY.md, THEPROCESS.md, Operations/
- `ls docs/Operations/` shows all technical docs
- No orphaned files in root or old locations

---

## Why This Structure Works

### For Technical Buyers

They open TECHNOLOGY.md → understand the stack in 60 seconds → dive into Operations/ for details.

### For Financial Buyers

They open COST.md → see verifiable git data → compare to agency quotes → understand the value created.

### For Operators

They open PRODUCT.md → understand features → see the roadmap → know what they're running.

### For Investors

They open OPPORTUNITY.md → see revenue projections → understand growth levers → make a decision.

### For Everyone

They open OVERVIEW.md → know what it is, whether it's live, and where to find everything else.

---

*Process developed January 2026. Applied across multiple production platforms.*
