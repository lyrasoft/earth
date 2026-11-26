# Prompt: initialize repository guidance

Use this prompt when creating basic AI and contributor guidance for a repository.

Inspect the repository and confirm its actual purpose, structure, and documentation patterns. Create or update `AGENTS.md` and relevant repository guidance so future contributors can quickly understand the project.

- Create or adjust `.github/copilot-instructions.md`, add a link to `../main.instructions.md`, and provide a brief description of the project.
- The root `AGENTS.md` should also link to `../main.instructions.md` and provide a brief description of the project.
- `../instructions/architecture.instructions.md` describes the project structure and is empty by default. Read and analyze the project's structure, then explain it in this file.
  - If the project is large, put the detailed structure description in `docs/architecture.md`. This file need not be a concise bullet list; it can explain the directory structure, main modules, core features, and dependencies in detail.
- Based on the project's actual needs, create or update other guidance files in the `instructions` directory and link to them from `../main.instructions.md`.
