This project shares one development environment across the host app, core, unicorn, and future vendor packages. Place the instructions content provided by the user in the most appropriate location in the project. Decide the location yourself based on the project structure.

For the list of allowed packages, see `AGENTS.md` or `copilot-instructions.md`.

Before starting, read each package's `instruction.instructions.md`, including the files under `.github/dev/instructions/` and the corresponding files in vendor packages (if they exist). Use the routing rules in `.github/dev/instructions/instruction.instructions.md` to decide where the content belongs:

- Repo-wide mandatory rules go in `instructions/`.
- Reusable workflows go in `skills/`.
- Long background material goes in `docs/`.
- Task templates go in `prompts/`.

Constraints:

- Do not overwrite or revert existing uncommitted changes, such as `.github/dev/copilot-instructions.md`, `.github/dev/instructions/cli.instructions.md`, `composer.json`, `package.json`, and `../../../vite.config.mts`.
- Only modify the packages or directories the content actually applies to. Ask the user before modifying any other vendor package.
- Do not modify the root `AGENTS.md`.
- Add comments only where clarification is needed.

Success criteria:

- The content is placed in the correct location, following that directory's existing naming and format conventions.
- If a new instructions file was added, the index in `.github/dev/instructions/README.md` has been updated.
- The user's content is not lost or rewritten.
- When finished, briefly explain where the content was placed and why.
